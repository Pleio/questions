<?php
/**
 * Hooks
 *
 * @package Questions
 *
 */


function questions_notify_subject_handler($hook, $type, $items, $params) {
	$answer = $params['annotation'];
	$question = $answer->getContainerEntity();

	return elgg_echo("questions:notify:newresponse:subject", array($question->title));
}


function questions_notify_message_handler($hook, $type, $items, $params) {
	$answer = $params['annotation'];
	$question = $answer->getContainerEntity();

	return elgg_echo("questions:notify:newresponse:message", array($question->title, $question->getURL()));
}


function questions_owner_block_menu_handler($hook, $type, $items, $params) {
	$entity = $params['entity'];

	if ($entity instanceof ElggGroup && $entity->questions_enable == 'yes') {
		$items[] = ElggMenuItem::factory(array(
				'name' => 'questions',
				'href' => "/questions/group/$entity->guid/all",
				'text' => elgg_echo('questions:group'),
		));
	} elseif ($entity instanceof ElggUser) {
		$items[] = ElggMenuItem::factory(array(
				'name' => 'questions',
				'href' => "/questions/owner/$entity->username",
				'text' => elgg_echo('questions'),
		));
	}

	return $items;
}

function questions_entity_menu_handler($hook, $type, $items, $params) {

	if (!empty($params) && is_array($params)) {
		$entity = elgg_extract("entity", $params);

		if (!empty($entity) && (elgg_instanceof($entity, "object", "question") || elgg_instanceof($entity, "object", "answer"))) {
			if ($entity->canComment()) {
				if (elgg_extract("full_view", $params, false) || elgg_instanceof($entity, "object", "answer")) {
					$items[] = ElggMenuItem::factory(array(
							"name" => "comment",
							"rel" => "toggle",
							"link_class" => "elgg-toggler",
							"href" => "#comments-add-$entity->guid",
							"text" => elgg_view_icon("speech-bubble"),
							"priority" => 600,
					));
				}
			}

			if (elgg_instanceof($entity, "object", "answer") && questions_can_mark_answer($entity)) {
				$question = $entity->getContainerEntity();
				$answer = $question->getCorrectAnswer();

				if (empty($answer)) {
					$items[] = ElggMenuItem::factory(array(
						"name" => "questions_mark",
						"text" => elgg_echo("questions:menu:entity:answer:mark"),
						"href" => "action/answers/toggle_mark?guid=" . $entity->getGUID(),
						"is_action" => true
					));
				} elseif ($entity->getGUID() == $answer->getGUID()) {
					// there is an anwser and it's this entity
					$items[] = ElggMenuItem::factory(array(
						"name" => "questions_mark",
						"text" => elgg_echo("questions:menu:entity:answer:unmark"),
						"href" => "action/answers/toggle_mark?guid=" . $entity->getGUID(),
						"is_action" => true
					));
				}
			}
		}
	}

	return $items;
}

/**
 * Change the filter menu for the questions context
 *
 * @param string $hook the 'register' hook
 * @param string $type the 'menu:filter' type
 * @param array $items the current menu items
 * @param array $params the supplied parameters
 */
function questions_filter_menu_handler($hook, $type, $items, $params) {

	if (!empty($items) && is_array($items) && elgg_in_context("questions")) {
		$page_owner = elgg_get_page_owner_entity();

		// change some menu items
		foreach ($items as $key => $item) {
			// remove friends
			if ($item->getName() == "friend") {
				unset($items[$key]);
			}

			// in group context
			if (elgg_instanceof($page_owner, "group")) {
				// remove mine
				if ($item->getName() == "mine") {
					unset($items[$key]);
				}

				// highlight all
				if ($item->getName() == "all") {
					$item->setHref("questions/group/" . $page_owner->getGUID() . "/all");

					$current_page = current_page_url();
					if (stristr($current_page, "questions/group/" . $page_owner->getGUID() . "/all")) {
						$item->setSelected(true);
					}
				}
			}
		}

		if (elgg_instanceof($page_owner, "group")) {
			$expertContainer = $page_owner;
		} else {
			$expertContainer = null;
		}
		
		if (questions_is_expert($expertContainer) && !questions_workflow_enabled($expertContainer)) {
			if (elgg_instanceof($page_owner, "group")) {
				$items[] = ElggMenuItem::factory(array(
					"name" => "todo_group",
					"text" => elgg_echo("questions:menu:filter:todo_group"),
					"href" => "questions/todo/" . $page_owner->getGUID(),
					"priority" => 710
				));
			} else {
				$items[] = ElggMenuItem::factory(array(
					"name" => "todo",
					"text" => elgg_echo("questions:menu:filter:todo"),
					"href" => "questions/todo",
					"priority" => 700
				));
			}
		}

		if (questions_is_expert($expertContainer) && questions_workflow_enabled($expertContainer)) {
			if (elgg_instanceof($page_owner, "group")) {
				$url = "questions/group/" . $page_owner->getGUID() . "/workflow";
			} else {
				$url = "questions/workflow";
			}

			$items[] = ElggMenuItem::factory(array(
				"name" => "workflow",
				"text" => elgg_echo("questions:menu:workflow"),
				"href" => $url,
				"priority" => 705
			));
		}

		if ((elgg_is_admin_logged_in() | questions_is_expert($expertContainer)) && questions_experts_enabled()) {
			$experts_href = "questions/experts";
			if (elgg_instanceof($page_owner, "group")) {
				$experts_href .= "/" . elgg_get_page_owner_guid();
			}

			$items[] = ElggMenuItem::factory(array(
				"name" => "experts",
				"text" => elgg_echo("questions:menu:filter:experts"),
				"href" => $experts_href,
				"priority" => 800
			));
		}
	}

	return $items;
}

function questions_user_hover_menu_handler($hook, $type, $returnvalue, $params) {
	$result = $returnvalue;

	// are experts enabled
	if (questions_experts_enabled()) {
		if (!empty($params) && is_array($params)) {
			// get the user for this menu
			$user = elgg_extract("entity", $params);

			if (!empty($user) && elgg_instanceof($user, "user")) {
				// get page owner
				$page_owner = elgg_get_page_owner_entity();
				if (!elgg_instanceof($page_owner, "group")) {
					$page_owner = elgg_get_site_entity();
				}

				// can the current person edit the page owner, to assign the role
				// and is the current user not the owner of this page owner
				if ($page_owner->canEdit()) {
					$text = elgg_echo("questions:menu:user_hover:make_expert");
					$confirm_text = elgg_echo("questions:menu:user_hover:make_expert:confirm", array($page_owner->name));

					if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $page_owner->getGUID())) {
						$text = elgg_echo("questions:menu:user_hover:remove_expert");
						$confirm_text = elgg_echo("questions:menu:user_hover:remove_expert:confirm", array($page_owner->name));
					}

					$result[] = ElggMenuItem::factory(array(
						"name" => "questions_expert",
						"text" => $text,
						"href" => "action/questions/toggle_expert?user_guid=" . $user->getGUID() . "&guid=" . $page_owner->getGUID(),
						"confirm" => $confirm_text
					));
				}
			}
		}
	}

	return $result;
}

function questions_container_permissions_handler($hook, $type, $returnvalue, $params) {
	$result = $returnvalue;
	static $experts_only;

	if (!$result && !empty($params) && is_array($params)) {
		$question = elgg_extract("container", $params);
		$user = elgg_extract("user", $params);
		$subtype = elgg_extract("subtype", $params);

		if (($subtype == "answer") && !empty($user) && elgg_instanceof($question, "object", "question")) {
			// check expert setting
			if (!isset($experts_only)) {
				$experts_only = false;

				$setting = elgg_get_plugin_setting("experts_answer", "questions");
				if ($setting == "yes") {
					$experts_only = true;
				}
			}

			// get the container of the question
			$container = $question->getContainerEntity();

			if (!$experts_only) {
				if (elgg_instanceof($container, "user")) {
					$result = true;
				} elseif (elgg_instanceof($container, "group")) {
					// if the user can ask a question in the group, he should be able to answer one too
					$result = $container->canWriteToContainer($user->getGUID(), "object", "question");
				}
			} else {
				$result = questions_is_expert($container, $user);
			}
		}
	}

	return $result;
}

function questions_permissions_handler($hook, $type, $returnvalue, $params) {
	$result = $returnvalue;

	$entity = elgg_extract("entity", $params);
	$user = elgg_extract("user", $params);

	if (!empty($params) && is_array($params)) {
		if (elgg_instanceof($entity, "object", "answer") | elgg_instanceof($entity, "object", "intanswer")) {
			$entity = elgg_extract("entity", $params);
			$user = elgg_extract("user", $params);

			// reset rights inherited from container
			if ($entity->getOwnerGUID() != $user->getGUID()) {
				$result = false;
			}			
		} elseif (elgg_instanceof($entity, "object", "question")) {
			// disable access to owner for closed questions
			if (elgg_instanceof($entity, "object", "question")) {
				if ($entity->getStatus() == "closed" && $user->getGUID()==$entity->getOwnerGUID()) {
					$result = false;
				}
			}

			// enable access for expert
			$container = $entity->getContainerEntity();
			if (questions_experts_enabled() && questions_is_expert($container, $user)) {
				$result = true;
			}			
		}
	}

	return $result;
}

function questions_widget_url_handler($hook, $type, $returnvalue, $params) {
	$result = $returnvalue;

	if (!$result && !empty($params) && is_array($params)) {
		$widget = elgg_extract("entity", $params);

		if (!empty($widget) && elgg_instanceof($widget, "object", "widget")) {
			// only handle questions widget
			if ($widget->handler == "questions") {
				// who owns the widget
				$owner = $widget->getOwnerEntity();

				if (elgg_instanceof($owner, "user")) {
					// user
					$result = "questions/owner/" . $owner->username;
					if ($widget->context == "dashboard") {
						switch($widget->content_type) {
							case "all":
								$result = "questions/all";
								break;
							case "todo":
								if (questions_is_expert()) {
									$result = "questions/todo";
									break;
								}
							case "mine":
							default:
								$result = "questions/owner/" . $owner->username;
						}
					}
				} elseif (elgg_instanceof($owner, "group")) {
					// group
					$result = "questions/group/" . $owner->getGUID() . "/all";
				} elseif (elgg_instanceof($owner, "site")) {
					// site
					$result = "questions/all";
				}
			}
		}
	}

	return $result;
}

/**
 * A plugin hook for the CRON, so we can send out notifications to the experts about there workload
 *
 * @param string $hook the 'cron' hook
 * @param string $type the 'daily' interval
 * @param unknown_type $returnvalue default return value
 * @param array $params supplied params
 *
 * @return void
 */
function questions_daily_cron_handler($hook, $type, $returnvalue, $params) {

	// are experts enabled
	if (questions_experts_enabled()) {

		// validate input
		if (!empty($params) && is_array($params)) {
			$time = elgg_extract("time", $params, time());
			$dbprefix = elgg_get_config("dbprefix");
			$site = elgg_get_site_entity();

			// get all experts
			$expert_options = array(
				"type" => "user",
				"site_guids" => false,
				"limit" => false,
				"joins" => array("JOIN " . $dbprefix . "entity_relationships re2 ON e.guid = re2.guid_one"),
				"wheres" => array("(re2.guid_two = " . $site->getGUID() . " AND re2.relationship = 'member_of_site')"),
				"relationship" => QUESTIONS_EXPERT_ROLE,
				"inverse_relationship" => true
			);
			$experts = elgg_get_entities_from_relationship($expert_options);

			if (!empty($experts)) {
				// sending could take a while
				set_time_limit(0);

				$status_id = add_metastring("status");
				$closed_id = add_metastring("closed");

				$status_where = "NOT EXISTS (
					SELECT 1
					FROM " . $dbprefix . "metadata md
					WHERE md.entity_guid = e.guid
					AND md.name_id = " . $status_id . "
					AND md.value_id = " . $closed_id . ")";

				$question_options = array(
					"type" => "object",
					"subtype" => "question",
					"limit" => 3,
				);

				// loop through all experts
				foreach ($experts as $expert) {
					// fake a logged in user
					$backup_user = elgg_extract("user", $_SESSION);
					$_SESSION["user"] = $expert;

					$subject = elgg_echo("questions:daily:notification:subject", array(), get_current_language());
					$message = "";

					$container_where = array();
					if (check_entity_relationship($expert->getGUID(), QUESTIONS_EXPERT_ROLE, $site->getGUID())) {
						$container_where[] = "(e.container_guid NOT IN (
							SELECT ge.guid
							FROM " . $dbprefix . "entities ge
							WHERE ge.type = 'group'
							AND ge.site_guid = " . $site->getGUID() . "
							AND ge.enabled = 'yes'
						))";
					}

					$group_options = array(
						"type" => "group",
						"limit" => false,
						"relationship" => QUESTIONS_EXPERT_ROLE,
						"relationship_guid" => $expert->getGUID(),
						"callback" => "questions_row_to_guid"
					);
					$groups = elgg_get_entities_from_relationship($group_options);
					if (!empty($groups)) {
						$container_where[] = "(e.container_guid IN (" . implode(",", $groups) . "))";
					}

					if (empty($container_where)) {
						// no groups or site? then skip to next expert
						continue;
					}
					$container_where = "(" . implode(" OR ", $container_where) . ")";

					// get overdue questions
					// eg: solution_time < $time && status != closed
					$question_options["metadata_name_value_pairs"] = array(
						"name" => "solution_time",
						"value" => $time,
						"operand" => "<",
					);
					$question_options["wheres"] = array(
						$status_where,
						$container_where
					);
					$question_options["order_by_metadata"] = array(
						"name" => "solution_time",
						"direction" => "ASC",
						"as" => "integer"
					);
					$questions = elgg_get_entities_from_metadata($question_options);
					if (!empty($questions)) {
						$message .= elgg_echo("questions:daily:notification:message:overdue", array(), get_current_language()) . PHP_EOL;

						foreach ($questions as $question) {
							$message .= " - " . $question->title . " (" . $question->getURL() . ")" . PHP_EOL;
						}

						$message .= elgg_echo("questions:daily:notification:message:more", array(), get_current_language());
						$message .= " " . $site->url . "questions/todo" . PHP_EOL . PHP_EOL;
					}

					// get due questions
					// eg: solution_time >= $time && solution_time < ($time + 1 day) && status != closed
					$question_options["metadata_name_value_pairs"] = array(
						array(
							"name" => "solution_time",
							"value" => $time,
							"operand" => ">=",
						),
						array(
							"name" => "solution_time",
							"value" => $time + (24 * 60 * 60),
							"operand" => "<",
						),
					);

					$questions = elgg_get_entities_from_metadata($question_options);
					if (!empty($questions)) {
						$message .= elgg_echo("questions:daily:notification:message:due", array(), get_current_language()) . PHP_EOL;

						foreach ($questions as $question) {
							$message .= " - " . $question->title . " (" . $question->getURL() . ")" . PHP_EOL;
						}

						$message .= elgg_echo("questions:daily:notification:message:more", array(), get_current_language());
						$message .= " " . $site->url . "questions/todo" . PHP_EOL . PHP_EOL;
					}

					// get new questions
					// eg: time_created >= ($time - 1 day)
					unset($question_options["metadata_name_value_pairs"]);
					unset($question_options["order_by_metadata"]);
					$question_options["wheres"] = array(
						$container_where,
						"(e.time_created > " . ($time - (24 * 60 *60)) . ")"
					);
					$questions = elgg_get_entities_from_metadata($question_options);
					if (!empty($questions)) {
						$message .= elgg_echo("questions:daily:notification:message:new", array(), get_current_language()) . PHP_EOL;

						foreach ($questions as $question) {
							$message .= " - " . $question->title . " (" . $question->getURL() . ")" . PHP_EOL;
						}

						$message .= elgg_echo("questions:daily:notification:message:more", array(), get_current_language());
						$message .= " " . $site->url . "questions/all" . PHP_EOL . PHP_EOL;
					}

					// is there content in the message
					if (!empty($message)) {
						// force to email
						notify_user($expert->getGUID(), $site->getGUID(), $subject, $message, null, "email");
					}

					// restore user
					$_SESSION["user"] = $backup_user;
				}
			}
		}
	}
}