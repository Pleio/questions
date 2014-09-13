<?php
/**
 * Event handlers
 *
 * @package Questions
 *
 */

/**
 * When an expert leaves the group, remove the expert role
 *
 * @param string $event the 'leave' event
 * @param string $type for the 'group' type
 * @param array $params the provided params
 *
 * @return void
 */
function questions_leave_group_handler($event, $type, $params) {
	
	if (!empty($params) && is_array($params)) {
		$user = elgg_extract("user", $params);
		$group = elgg_extract("group", $params);
		
		if (!empty($user) && elgg_instanceof($user, "user") && !empty($group) && elgg_instanceof($group, "group")) {
			// is the user an expert in this group
			if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $group->getGUID())) {
				// remove the expert role
				remove_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $group->getGUID());
			}
		}
	}
}

/**
 * When an expert leaves the site, remove the expert role
 *
 * @param string $event the 'delete' event
 * @param string $type for the 'member_of_site' type
 * @param ElggRelationship $relationship the provided params
 *
 * @return void
 */
function questions_leave_site_handler($event, $type, $relationship) {
	
	if (!empty($relationship) && ($relationship instanceof ElggRelationship)) {
		$user = get_user($relationship->guid_one);
		$site = elgg_get_site_entity($relationship->guid_two);
		
		if (!empty($user) && !empty($site)) {
			// is the user an expert in this site
			if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $site->getGUID())) {
				// remove the expert role
				remove_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $site->getGUID());
			}
		}
	}
}
