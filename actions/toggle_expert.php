<?php
/**
 * This action toggles the expert role for a user on or off
 */

$user_guid = (int) get_input("user_guid");
$page_owner_guid = (int) get_input("guid");
$access_collection_guid = questions_get_workflow_access_collection($page_owner_guid);

if (!$access_collection_guid) {
	register_error(elgg_echo("questions:workflow:noacl"));
	forward(REFERER);
}


if (!empty($user_guid) && !empty($page_owner_guid)) {
	$user = get_user($user_guid);
	$page_owner = get_entity($page_owner_guid);
	
	if (!empty($user) && !empty($page_owner) && (elgg_instanceof($page_owner, "site") || elgg_instanceof($page_owner, "group")) && $page_owner->canEdit()) {
		// check if the user is an expert
		if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $page_owner->getGUID())) {
			// yes, so remove
			remove_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $page_owner->getGUID());
			
			// @todo: only when workflow is enabled
			remove_user_from_access_collection($user_guid, $access_collection_guid);

			system_message(elgg_echo("questions:action:toggle_expert:success:remove", array($user->name, $page_owner->name)));
		} else {
			// no, so add
			add_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $page_owner->getGUID());
			
			// @todo: only when workflow is enabled
			add_user_to_access_collection($user_guid, $access_collection_guid);

			system_message(elgg_echo("questions:action:toggle_expert:success:make", array($user->name, $page_owner->name)));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($page_owner_guid)));
	}
} else {
	register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

forward(REFERER);