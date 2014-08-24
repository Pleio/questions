<?php
/**
 * All helper functions for the questions plugin can be found in this file.
 */

/**
 * This function checks if expert roles are enabled in the plugin settings
 *
 * @return bool true is enabled, false otherwise
 */
function questions_experts_enabled() {
	static $result;
	
	if (!isset($result)) {
		$result = false;
		
		$setting = elgg_get_plugin_setting("experts_enabled", "questions");
		if ($setting == "yes") {
			$result = true;
		}
	}
	
	return $result;
}

/**
 * This function checks if the questions workflow is enabled in the plugin settings
 *
 * @return bool true is enabled, false otherwise
 */
function questions_workflow_enabled() {
	static $result;

	if (!isset($result)) {
		$result = false;

		$setting = elgg_get_plugin_setting("workflow_enabled", "questions");
		if ($setting == "yes") {
			$result = true;
		}
	}
	
	return $result;	
}

/**
 * Return a list of active phases
 *
 * @return bool true is enabled, false otherwise
 */
function questions_get_phases(ElggEntity $container = null) {
	static $result;

	if (!isset($result)) {	
		$result = [];
		if (!isset($container)) {
			$container = elgg_get_site_entity();
		}

  $options = array(
      "type" => "object",
      "subtype" => QUESTIONS_WORKFLOW_PHASE,
      "limit" => false,
      "order_by_metadata" => array(array('name' => 'order', 'direction' => "asc", 'as' => "integer")),
      "owner_guid" => elgg_get_site_entity()->getGUID(),
      "pagination" => false,
      "full_view" => false
    );

		$phases = elgg_get_entities_from_metadata($options);
		foreach($phases as $phase) {
			$result[$phase->guid] = $phase->name;
		}
	}

	return $result;
}


/**
 * Check if a user is an expert
 *
 * @param ElggEntity $container the container where a question was asked, leave empty for any relationship
 * @param ElggUser $user the user to check (defaults to current user)
 *
 * @return bool true if the user is an expert, false otherwise
 */
function questions_is_expert(ElggEntity $container = null, ElggUser $user = null) {
	$result = false;
	
	// make sure we have a user
	if (empty($user) || !elgg_instanceof($user, "user")) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if (empty($user)) {
		return false;
	}
	
	if (elgg_instanceof($container)) {
		if (elgg_instanceof($container, "user")) {
			$container = elgg_get_site_entity();
		}
		
		if (elgg_instanceof($container, "site") || elgg_instanceof($container, "group")) {
			if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $container->getGUID())) {
				// user has the expert role
				$result = true;
			}
		}
	} else {
		$expert_options = array(
			"count" => true,
			"relationship" => QUESTIONS_EXPERT_ROLE,
			"relationship_guid" => $user->getGUID()
		);
		
		if (elgg_get_entities_from_relationship($expert_options)) {
			// check if user has any expert relationship with entity on this site
			$result = true;
		}
		
		if (!$result) {
			// added specific check for Subsite Manager plugin where site has no current site entity set as entity_guid
			if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, elgg_get_site_entity()->getGUID())) {
				// user has the expert role for this site
				$result = true;
			}
		}
	}
		
	return $result;
}


/**
 * Build a wall to block non-experts. Forward non-experts off the page.
 *
 * @param ElggEntity $container the container where a question was asked, leave empty for any relationship
 *
 * @return bool true if the user is an expert, false otherwise
 */
function questions_expert_gatekeeper() {
	if (!questions_is_expert(elgg_get_site_entity(), null)) {
		forward('', 'admin');
	}
}

/**
 * Check if the user can mark this answer as the correct one
 *
 * @param ElggAnswer $entity the answer to check
 * @param ElggUser $user the use who is wants to do the action (defaults to current user)
 *
 * @return bool true if the user is allowed to mark, false otherwise
 */
function questions_can_mark_answer(ElggAnswer $entity, ElggUser $user = null) {
	$result = false;
	static $experts_only;
	
	// check if we have a user
	if (empty($user) || !elgg_instanceof($user, "user")) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if (!empty($user) && !empty($entity) && elgg_instanceof($entity, "object", "answer")) {
		$container = $entity->getContainerEntity();
		
		// are experts enabled
		if (!questions_experts_enabled()) {
			// no, so only question owner can mark
			if ($user->getGUID() == $container->getOwnerGUID()) {
				$result = true;
			}
		} else {
			// get plugin setting for who can mark the answer
			if (!isset($experts_only)) {
				$experts_only = false;
				
				$setting = elgg_get_plugin_setting("experts_mark", "questions");
				if ($setting == "yes") {
					$experts_only = true;
				}
			}
			
			// are only experts allowed to mark
			if (!$experts_only) {
				// no, so the owner of a question can also mark
				if ($user->getGUID() == $container->getOwnerGUID()) {
					$result = true;
				}
			}
			
			// is the user an expert
			if (!$result && questions_is_expert($container->getContainerEntity(), $user)) {
				$result = true;
			}
		}
	}
	
	return $result;
}

/**
 * Make sure the provided access_id is valid for this container
 *
 * @param int $access_id the current access_id
 * @param int $container_guid the container where the entity will be placed
 *
 * @return int the correct access_id for this container
 */
function questions_validate_access_id($access_id, $container_guid) {
	
	if (!$access_id == ACCESS_DEFAULT) {
		$access_id = get_default_access();
	}
	
	if (!empty($container_guid)) {
		$container = get_entity($container_guid);
		
		if (!empty($container)) {
			if (elgg_instanceof($container, "user")) {
				// is a default level defined in the plugin settings
				$personal_access_id = questions_get_personal_access_level();
				if ($personal_access_id !== false) {
					$access_id = $personal_access_id;
				} else {
					// make sure access_id is not a group acl
					$acl = get_access_collection($access_id);
					
					if (!empty($acl) && ($acl->owner_guid != $container->getGUID())) {
						// this acl is a group acl, so set to something else
						$access_id = ACCESS_LOGGED_IN;
					}
				}
			} elseif (elgg_instanceof($container, "group")) {
				// is a default level defined in the plugin settings
				$group_access_id = questions_get_group_access_level($container);
				if ($group_access_id !== false) {
					$access_id = $group_access_id;
				} else {
					// friends access not allowed in groups
					if ($access_id == ACCESS_FRIENDS) {
						// so set it to group access
						$access_id = $container->group_acl;
					}
					
					// check if access is an acl
					$acl = get_access_collection($access_id);
					
					if (!empty($acl) && ($acl->owner_guid != $container->getGUID())) {
						// this acl is an acl, make sure it's the group acl
						$access_id = $container->group_acl;
					}
				}
			}
		}
	}
	
	return $access_id;
}

/**
 * Get the default defined peronal access setting.
 *
 * @return bool|int the access_id or false if up to the user
 */
function questions_get_personal_access_level() {
	static $result;
	
	if (!isset($result)) {
		$result = false;
		
		$setting = elgg_get_plugin_setting("access_personal", "questions");
		if (!empty($setting) && ($setting != "user_defined")) {
			$result = (int) $setting;
		}
	}
	
	return $result;
}

/**
 * Get the default defined group access setting.
 *
 * @param ElggGroup $group the group if the setting is group_acl
 *
 * @return bool|int the access_id or false if up to the user
 */
function questions_get_group_access_level(ElggGroup $group) {
	static $plugin_setting;
	$result = false;
	
	if (!isset($plugin_setting)) {
		$plugin_setting = false;
		
		$setting = elgg_get_plugin_setting("access_group", "questions");
		if (!empty($setting) && ($setting != "user_defined")) {
			$plugin_setting = $setting;
		}
	}
	
	if ($plugin_setting) {
		if ($plugin_setting == "group_acl") {
			$result = $group->group_acl;
		} else {
			$result = (int) $plugin_setting;
		}
	}
	
	return $result;
}

/**
 * This function checks of the plugin setting to close a question on a marked answer is set
 *
 * @return bool true is we need to close, false otherwise
 */
function questions_close_on_marked_answer() {
	static $result;
	
	if (!isset($result)) {
		$result = false;
		
		$setting = elgg_get_plugin_setting("close_on_marked_answer", "questions");
		if ($setting == "yes") {
			$result = true;
		}
	}
	
	return $result;
}

/**
 * Calculate the difference between two timestamps and take into account only working hours.
 *
 * @param int $timestamp1 ELGG timestamp 1
 * @param int $timestamp2 ELGG timestamp 2
 * 
 * @return int difference between timestamps (hours)
 */
function questions_time_diff($timestamp1, $timestamp2, $working_days = array(1,2,3,4,5), $start_time = array(8,30), $end_time = array(17,0)) {
	$dt1 = new DateTime(); $dt2 = new DateTime();
	$dt1->setTimestamp($timestamp1); $dt2->setTimestamp($timestamp2);

	// Calculate whole days diff
	$beginTime = new DateTime("08:30");
	$endTime = new DateTime("17:00");

	// Calculate hours per day
	$hoursPerDay = $endTime->diff($beginTime);
	$hoursPerDay = ($hoursPerDay->format('%h') + $hoursPerDay->format('%i')/60);

	// Calculate total days difference
	$daysDifference = $dt2->diff($dt1);
	return (($hoursPerDay * $daysDifference->format('%a')) + $daysDifference->format('%h'));
}

/**
 * Notify the experts that a new question was asked
 *
 * @param ElggQuestion $entity the question to notify about
 * @param bool $moving is this qquestion being moved
 *
 * @return void
 */
function questions_notify_experts(ElggQuestion $entity, $moving = false) {
	
	// only if experts enabled
	if (questions_experts_enabled()) {
		// validate input
		if (!empty($entity) && elgg_instanceof($entity, "object", "question")) {
			$experts = array();
			$container = $entity->getContainerEntity();
			if (!elgg_instanceof($container, "group")) {
				$container = elgg_get_site_entity();
			}
			
			// get experts
			$options = array(
				"type" => "user",
				"site_guids" => false,
				"limit" => false,
				"relationship" => QUESTIONS_EXPERT_ROLE,
				"relationship_guid" => $container->getGUID(),
				"inverse_relationship" => true,
			);
			if ($users = elgg_get_entities_from_relationship($options)) {
				$experts = $users;
			}
			
			// trigger a hook so others can extend the list
			$params = array(
				"entity" => $entity,
				"experts" => $experts,
				"moving" => $moving
			);
			$experts = elgg_trigger_plugin_hook("notify_experts", "questions", $params, $experts);
			
			if (!empty($experts) && is_array($experts)) {
				$subject_key = "questions:notify_experts:create:subject";
				$message_key = "questions:notify_experts:create:message";
				if ($moving) {
					$subject_key = "questions:notify_experts:moving:subject";
					$message_key = "questions:notify_experts:moving:message";
				}
				
				$subject = elgg_echo($subject_key);
				
				foreach ($experts as $expert) {
					$message = elgg_echo($message_key, array(
						$expert->name,
						$entity->title,
						$entity->getURL()
					));
					
					notify_user($expert->getGUID(), $entity->getOwnerGUID(), $subject, $message, null, "email");
				}
			}
		}
	}
}

/**
 * Return the number of days it should take to solve a question.
 *
 * @param ElggEntity $container if a group is provided, first the setting of the group is checked, then the default setting of the site
 *
 * @return int the number of days it should take to solve the question. 0 for infinite
 */
function questions_get_solution_time(ElggEntity $container) {
	$result = 0;
	
	// get site setting
	$result = (int) elgg_get_plugin_setting("site_solution_time", "questions");
	
	// check is group
	if (elgg_instanceof($container, "group")) {
		// get group setting
		$group_setting = $container->getPrivateSetting("questions_solution_time");
		if (($group_setting !== false) && ($group_setting !== null)) {
			// we have a valid group setting
			$result = (int) $group_setting;
		}
		
	}
	
	return $result;
}

/**
 * Check the plugin setting if questions are limited to groups.
 *
 * @return bool true if limited
 */
function questions_limited_to_groups() {
	static $result;
	
	if (!isset($result)) {
		$result = false;
		
		$setting = elgg_get_plugin_setting("limit_to_groups", "questions");
		if ($setting == "yes") {
			$result = true;
		}
	}
	
	return $result;
}

/**
 * Return the GUID from a database row.
 *
 * @param stdObject $row the database row
 *
 * @return int the GUID
 */
function questions_row_to_guid($row) {
	return (int) $row->guid;
}

/**
 * Checks if a question can be moved to the discussion in the container.
 *
 * @param ElggEntity $container the container where the question should become a discussion
 * @param ElggUser $user the user trying to move the question, defaults to current user
 *
 * @return bool true is can move, false otherwise
 */
function questions_can_move_to_discussions(ElggEntity $container, ElggUser $user = null) {
	$result = false;
	
	// make sure we have a user
	if (empty($user) || !elgg_instanceof($user, "user")) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	// only if container is a group
	if (!empty($container) && elgg_instanceof($container, "group") && !empty($user)) {
		// only experts can move
		if (questions_is_expert($container, $user)) {
			// are discussions enabled
			if ($container->forum_enable != "no") {
				$result = true;
			}
		}
	}
	
	return $result;
}

/**
 * Backdate an annotation, since this can't be done by Elgg core functions
 *
 * @param int $annotation_id the annotation to update
 * @param int $time_created the new time_created
 *
 * @access private
 *
 * @return bool true if the update succeeded
 */
function questions_backdate_annotation($annotation_id, $time_created) {
	$result = false;
	
	$annotation_id = sanitise_int($annotation_id, false);
	$time_created = sanitise_int($time_created);
	
	if (!empty($annotation_id)) {
		$query = "UPDATE " . elgg_get_config("dbprefix") . "annotations
				SET time_created = " . $time_created . "
				WHERE id = " . $annotation_id;
		
		$result = (bool) update_data($query);
	}
	
	return $result;
}
