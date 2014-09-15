<?php
/**
 * Helper functions
 *
 * @package Questions
 *
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
 * This function checks if the questions workflow is enabled in the plugin settings and for the given container
 *
 * @return bool true is enabled, false otherwise
 */
function questions_workflow_enabled(ElggEntity $container = null) {
	if ($container && $container instanceof ElggGroup) {
		return $container->getPrivateSetting('questions_workflow_enabled') == "yes";
	}

	return elgg_get_plugin_setting("workflow_enabled", "questions") == "yes";
}

/**
 * Return a list of active phases
 *
 */
function questions_get_phases($group_id = 0) {
	static $phases;

	if (!isset($phases)) {	
		$phases = array();

		if (!$group_id) {
			$group_id = elgg_get_site_entity()->guid;
		}

	  $options = array(
	      "type" => "object",
	      "subtype" => QUESTIONS_WORKFLOW_PHASE,
	      "limit" => false,
	      "order_by_metadata" => array(array('name' => 'order', 'direction' => "asc", 'as' => "integer")),
	      "container_guid" => $group_id,
	      "pagination" => false,
	      "full_view" => false
	    );

			$phases = elgg_get_entities_from_metadata($options);

			$result = array();
			foreach ($phases as $phase) {
				$result[$phase->guid] = $phase;
			}
			
			return $result;
	}
}

function questions_get_phases_array($group_id = 0) {
	static $result;

	if (!isset($result)) {	
		$result = array();
		$phases = questions_get_phases($group_id);

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
 * @return bool true if the user is an expert, false otherwise
 */
function questions_expert_gatekeeper(ElggEntity $container = null) {
	if ($container == null) {
		$container = elgg_get_site_entity();
	}

	if (!questions_is_expert($container, elgg_get_logged_in_user_entity())) {
		register_error(elgg_echo('questions:workflow:noaccess'));
		forward();
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
 * Get the default defined personal access setting.
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
function questions_time_diff(int $beginTS, int $endTS) {

	$settingBeginHour = elgg_get_plugin_setting("workflow_workingtimes_begin_hour", "questions");
	$settingBeginMinute = elgg_get_plugin_setting("workflow_workingtimes_begin_minute", "questions");
	$settingEndHour = elgg_get_plugin_setting("workflow_workingtimes_end_hour", "questions");
	$settingEndMinute = elgg_get_plugin_setting("workflow_workingtimes_end_minute", "questions");
	$workingDays = unserialize(elgg_get_plugin_setting("workflow_workingtimes_days", "questions"));

	if ($beginTS == 0 | $endTS == 0 | $beginTS >= $endTS) {
		return 0;
	}

	if (isset($settingBeginHour) && isset($settingBeginMinute)) {
		$workBeginTime = new DateTime($settingBeginHour . ":" . $settingBeginMinute);
	} else {
		$workBeginTime = new DateTime("09:00");
	}

	if (isset($settingEndHour) && isset($settingEndMinute)) {
		$workEndTime = new DateTime($settingEndHour . ":". $settingEndMinute);
	} else {
		$workEndTime = new DateTime("17:00");
	}

	if (!is_array($workingDays)) {
		$workingDays = array(1,2,3,4,5);
	}

	$begin = new DateTime();
	$begin->setTimeStamp($beginTS);
	$end = new DateTime();
	$end->setTimeStamp($endTS);

	// on the same day
	if ($begin->format("Ymd") == $end->format("Ymd")) {
		// return zero if this day is not a working day
		if (!in_array($begin->format("N"), $workingDays)) {
			return 0;
		}

		// format as times
		$beginTime = new DateTime($begin->format("H:i:s"));
		$endTime = new DateTime($end->format("H:i:s"));

		if ($beginTime < $workBeginTime) {
			$lowerBound = $workBeginTime;
		} else {
			if ($beginTime > $workEndTime) {
				$lowerBound = $workEndTime;
			} else {
				$lowerBound = $beginTime;
			}
		}

		if ($endTime > $workEndTime) {
			$upperBound = $workEndTime;
		} else {
			if ($endTime < $workBeginTime) {
				$upperBound = $workBeginTime;
			} else {
				$upperBound = $endTime;
			}
		}

		$diff = $lowerBound->diff($upperBound);
		return (($diff->h*3600) + ($diff->i*60) + ($diff->s));
	} else {
		// not on the same day
		$totalTime = 0;

		// calculate the working time on the first day
		if (in_array($begin->format('N'), $workingDays)) {
			$beginTime = new DateTime($begin->format("H:i:s"));

			if ($beginTime < $workBeginTime) {
				$lowerBound = $workBeginTime;
			} elseif ($beginTime > $workBeginTime && $beginTime < $workEndTime) {
				$lowerBound = $beginTime;
			} else {
				$lowerBound = $workEndTime;
			}

			$upperBound = $workEndTime;

			$diff = $lowerBound->diff($upperBound);
			$totalTime = $totalTime + ($diff->h*3600) + ($diff->i*60) + ($diff->s);
		}

		// calculate the working time on the last day
		if (in_array($end->format('N'), $workingDays)) {
			$endTime = new DateTime($end->format("H:i:s"));
			
			$lowerBound = $workBeginTime;

			if ($endTime < $workBeginTime) {
				$upperBound = $workBeginTime;
			} elseif ($endTime > $workBeginTime && $endTime < $workEndTime) {
				$upperBound = $endTime;
			} else {
				$upperBound = $workEndTime;
			}

			$diff = $lowerBound->diff($upperBound);
			$totalTime = $totalTime + ($diff->h*3600) + ($diff->i*60) + ($diff->s);
		}

		// set to beginning of the next day
		$begin->modify('midnight +1 day');	

		// set to last midnight
		$end->modify('midnight');
		
		$diff = $workBeginTime->diff($workEndTime);
		$secondsPerDay = ($diff->h*3600) + ($diff->i*60) + ($diff->s);
		
		// calculate the workingtime on the days inbetween
		$period = new DatePeriod($begin, new DateInterval('P1D'), $end);		
		foreach ($period as $day) {
			if (in_array($day->format('N'), $workingDays)) {
				$totalTime = $totalTime + $secondsPerDay;
			}
		}

		return $totalTime;
	}
}

/**
 * Notify the experts that a new question was asked
 *
 * @param ElggQuestion $entity the question to notify about
 * @param bool $moving is this question being moved
 *
 * @return void
 */
function questions_notify_experts(ElggQuestion $entity, $moving = false) {
	
	// only if experts enabled
	if (questions_experts_enabled() && elgg_get_plugin_setting("experts_notify", "questions") == "yes") {
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

	if (questions_workflow_enabled()) {
		return 0;
	}
	
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

/**
 * Retrieve the workflow access collection controlling access
 * of the workflow entities.
 * 
 * @param int $group_id the group id of the workflow or 0 for site.
 *
 * @return int $ac_id the access id
 */
function questions_get_workflow_access_collection($group_id = 0) {

	if (!$group_id) {
		$entity = elgg_get_site_entity();
	} else {
		$entity = get_entity($group_id);
		if (!$entity instanceof ElggGroup) {
			throw new Exception("Given entity is not a group.");
		}
	}

	$aclGuid = $entity->getPrivateSetting('workflow_acl');

	if (!$aclGuid) {
		$aclGuid = create_access_collection("Workflow " . $entity->name, $entity->guid);
		$entity->setPrivateSetting('workflow_acl', $aclGuid);
	}

	return $aclGuid;
}

/**
 * Retrieve the workflow access collection controlling access
 * of the workflow entities.
 * 
 * @param int $group_id the group id of the workflow
 *
 * @return int $ac_id the access id
 */
function questions_get_site_email() {
  $site = elgg_get_site_entity();
  if(!empty($site->email)){
    if(!empty($site->name)){
      $site_from = $site->name . " <" . $site->email . ">";
    } else {
      $site_from = $site->email;
    }
  } else {
    // no site email, so make one up
    if(!empty($site->name)){
      $site_from = $site->name . " <noreply@" . get_site_domain($site->getGUID()) . ">";
    } else {
      $site_from = "noreply@" . get_site_domain($site->getGUID());
    }
  }
  return $site_from;	
}

/**
 * Send a workflow notification for a question to the phase owner
 * 
 * @param ElggQuestion $question the question the notification is about
 * @param QuesionsWorkflowPhase $phase the phase of the workflow
 *
 * @return bool
 */
function questions_send_workflow_notification(ElggQuestion $question, QuestionsWorkflowPhase $phase) {
	return elgg_send_email(
		questions_get_site_email(), 
   	$phase->email, 
		elgg_echo("questions:workflow:email:subject", array($question->title)),
		elgg_echo("questions:workflow:email:body", array($question->getWorkflowURL()))
	); 
}

/**
 * Return a formated timespan
 * 
 * @param int $timespan in seconds
 *
 * @return string $answer
 */
function questions_get_friendly_timespan($timespan) {
	$minute = 60;
	$hour = $minute*60;
		

	if ($timespan < $minute) {
		if ($timespan == 1) {
			return elgg_echo("friendlytimespan:seconds:singular", array($timespan));
		} else {
			return elgg_echo("friendlytimespan:seconds", array($timespan));
		}
	} elseif ($timespan < $hour) {
		$timespan = round($timespan/$minute);

		if ($timespan > 1) {
			return elgg_echo("friendlytimespan:minutes", array($timespan));
		} else {
			return elgg_echo("friendlytimespan:minutes:singular", array($timespan));
		}
	} else {
		$timespan = round($timespan/$hour);
		if ($timespan == 0) {
			$timespan = 1;
		}

		if ($timespan > 1) {
			return elgg_echo("friendlytimespan:hours", array($timespan));
		} else {
			return elgg_echo("friendlytimespan:hours:singular", array($timespan));
		}
	}
}