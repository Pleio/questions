<?php

$group = elgg_extract("entity", $vars);
if (!empty($group) && elgg_instanceof($group, "group") && $group->canEdit() && ($group->questions_enable == "yes")) {
	$solution_time = questions_get_solution_time($group);
	
	$content = "<div>";
	$content .= elgg_echo("questions:settings:general:solution_time");
	$content .= elgg_view("input/dropdown", array("name" => "solution_time", "value" => $solution_time, "options" => range(0, 30), "class" => "mls"));
	$content .= "<div class='elgg-subtext'>" . elgg_echo("questions:group_settings:solution_time:description") . "</div>";
	$content .= "</div>";
	
	$content .= "<div class='elgg-foot'>";
	$content .= elgg_view("input/hidden", array("name" => "group_guid", "value" => $group->getGUID()));
	$content .= elgg_view("input/submit", array("value" => elgg_echo("save")));
	$content .= "</div>";
	
	$form = elgg_view("input/form", array("body" => $content, "action" => "action/questions/group_settings"));
	
	echo elgg_view_module("info", elgg_echo("questions:group_settings:title"), $form);
}
