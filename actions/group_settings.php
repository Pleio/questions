<?php

$group_guid = (int) get_input("group_guid");
$solution_time = (int) get_input("solution_time");

$forward_url = REFERER;

if (!empty($group_guid)) {
	$group = get_entity($group_guid);
	
	if (!empty($group) && elgg_instanceof($group, "group")) {
		if ($group->canEdit()) {
			// save the setting
			$group->setPrivateSetting("questions_solution_time", $solution_time);
			
			system_message(elgg_echo("questions:action:group_settings:success"));
			$forward_url = $group->getURL();
			
		} else {
			register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
		}
	} else {
		register_error(elgg_echo("ClassException:ClassnameNotClass", array($group_guid, elgg_echo("group"))));
	}
} else {
	register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

forward($forward_url);
