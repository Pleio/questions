<?php
/**
 * Questions plugin settings view
 *
 * @package ElggQuestions
 */

$plugin = elgg_extract("entity", $vars);

$noyes_options = array(
	"no" => elgg_echo("option:no"),
	"yes" => elgg_echo("option:yes")
);

$personal_access_options = array(
	"user_defined" => elgg_echo("questions:settings:access:options:user"),
	ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
	ACCESS_PUBLIC => elgg_echo("PUBLIC")
);

$group_access_options = array(
	"user_defined" => elgg_echo("questions:settings:access:options:user"),
	"group_acl" => elgg_echo("questions:settings:access:options:group"),
	ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
	ACCESS_PUBLIC => elgg_echo("PUBLIC")
);


function format_times($i) {
  return str_pad($i, 2, "0", STR_PAD_LEFT);
}

$hours = range(0,23);
$minutes = range(0,59);

$hours = array_map('format_times', $hours);
$minutes = array_map('format_times', $minutes);

$days_of_the_week_options = array(
  elgg_echo("Monday") => 1,
  elgg_echo("Tuesday") => 2,
  elgg_echo("Wednesday") => 3,
  elgg_echo("Thursday") => 4,
  elgg_echo("Friday") => 5,
  elgg_echo("Saturday") => 6,
  elgg_echo("Sunday") => 7
);

if ($plugin->workflow_workingtimes_days) {
  $workflow_workingtimes_days = unserialize($plugin->workflow_workingtimes_days);
} else {
  $workflow_workingtimes_days = array(1,2,3,4,5);
}

if (!isset($plugin->workflow_workingtimes_begin_hour)) {
  $plugin->workflow_workingtimes_begin_hour = 9;
}
if (!isset($plugin->workflow_workingtimes_end_hour)) {
 $plugin->workflow_workingtimes_end_hour = 17; 
}

// general settings
$general_settings = "<div>";
$general_settings .= elgg_echo("questions:settings:general:close");
$general_settings .= elgg_view("input/dropdown", array("name" => "params[close_on_marked_answer]", "value" => $plugin->close_on_marked_answer, "options_values" => $noyes_options, "class" => "mls"));
$general_settings .= "<div class='elgg-subtext'>" . elgg_echo("questions:settings:general:close:description") . "</div>";
$general_settings .= "</div>";

$general_settings .= "<div>";
$general_settings .= elgg_echo("questions:settings:general:solution_time");
$general_settings .= elgg_view("input/dropdown", array("name" => "params[site_solution_time]", "value" => $plugin->site_solution_time, "options" => range(0, 30), "class" => "mls"));
$general_settings .= "<div class='elgg-subtext'>" . elgg_echo("questions:settings:general:solution_time:description") . "</div>";
$general_settings .= "</div>";

$general_settings .= "<div>";
$general_settings .= elgg_echo("questions:settings:general:limit_to_groups");
$general_settings .= elgg_view("input/dropdown", array("name" => "params[limit_to_groups]", "value" => $plugin->limit_to_groups, "options_values" => $noyes_options, "class" => "mls"));
$general_settings .= "<div class='elgg-subtext'>" . elgg_echo("questions:settings:general:limit_to_groups:description") . "</div>";
$general_settings .= "</div>";

echo elgg_view_module("inline", elgg_echo("questions:settings:general:title"), $general_settings);

// adding expert roles
$expert_settings = "<div>";
$expert_settings .= elgg_echo("questions:settings:experts:enable");
$expert_settings .= elgg_view("input/dropdown", array("name" => "params[experts_enabled]", "value" => $plugin->experts_enabled, "options_values" => $noyes_options, "class" => "mls"));
$expert_settings .= "<div class='elgg-subtext'>" . elgg_echo("questions:settings:experts:enable:description") . "</siv>";
$expert_settings .= "</div>";

$expert_settings .= "<div>";
$expert_settings .= elgg_echo("questions:settings:experts:answer");
$expert_settings .= elgg_view("input/dropdown", array("name" => "params[experts_answer]", "value" => $plugin->experts_answer, "options_values" => $noyes_options, "class" => "mls"));
$expert_settings .= "</div>";

$expert_settings .= "<div>";
$expert_settings .= elgg_echo("questions:settings:experts:mark");
$expert_settings .= elgg_view("input/dropdown", array("name" => "params[experts_mark]", "value" => $plugin->experts_mark, "options_values" => $noyes_options, "class" => "mls"));
$expert_settings .= "</div>";

$expert_settings .= "<div>";
$expert_settings .= elgg_echo("questions:settings:experts:notify");
$expert_settings .= elgg_view("input/dropdown", array("name" => "params[experts_notify]", "value" => $plugin->experts_edit, "options_values" => $noyes_options, "class" => "mls"));
$expert_settings .= "</div>";

echo elgg_view_module("inline", elgg_echo("questions:settings:experts:title"), $expert_settings);

// workflow settings
$expert_settings = "<div>";
$expert_settings .= elgg_echo("questions:settings:workflow:enable");
$expert_settings .= elgg_view("input/dropdown", array("name" => "params[workflow_enabled]", "value" => $plugin->workflow_enabled, "options_values" => $noyes_options, "class" => "mls"));
$expert_settings .= "<div class='elgg-subtext'>" . elgg_echo("questions:settings:workflow:enable:description") . "</div>";
$expert_settings .= "</div>";

$expert_settings .= "<div>";
$expert_settings .= elgg_echo("questions:settings:workflow:phases");
$expert_settings .= "<div class='elgg-subtext'>" . elgg_echo("questions:settings:workflow:phases:description") . "</div>";
$expert_settings .= elgg_view("questions/admin/workflow_phase/list");
$expert_settings .= "</div>";

$expert_settings .= "<div>";
$expert_settings .= elgg_echo("questions:settings:workflow:workingtimes");
$expert_settings .= "<div class='elgg-subtext'>" . elgg_echo("questions:settings:workflow:workingtimes:description") . "</div>";
$expert_settings .= elgg_view('input/checkboxes', array("name" => "params[workflow_workingtimes_days]", "options" => $days_of_the_week_options, "value"=>$workflow_workingtimes_days));

$expert_settings .= "<div>" . elgg_echo("questions:settings:workflow:workingtimes:from") . "</div>";
$expert_settings .= elgg_view('input/dropdown', array("name" => "params[workflow_workingtimes_begin_hour]", "options" => $hours, "value"=>$plugin->workflow_workingtimes_begin_hour));
$expert_settings .= " : ";
$expert_settings .= elgg_view('input/dropdown', array("name" => "params[workflow_workingtimes_begin_minute]", "options" => $minutes, "value"=>$plugin->workflow_workingtimes_begin_minute));

$expert_settings .= "<div>" . elgg_echo("questions:settings:workflow:workingtimes:to") . "</div>";
$expert_settings .= elgg_view('input/dropdown', array("name" => "params[workflow_workingtimes_end_hour]", "options" => $hours, "value"=>$plugin->workflow_workingtimes_end_hour));
$expert_settings .= " : ";
$expert_settings .= elgg_view('input/dropdown', array("name" => "params[workflow_workingtimes_end_minute]", "options" => $minutes, "value"=>$plugin->workflow_workingtimes_end_minute));
$expert_settings .= "</div>";

echo elgg_view_module("inline", elgg_echo("questions:settings:workflow:title"), $expert_settings);

// access options
$access_settings = "<div>";
$access_settings .= elgg_echo("questions:settings:access:personal");
$access_settings .= elgg_view("input/access", array("name" => "params[access_personal]", "value" => $plugin->access_personal, "options_values" => $personal_access_options, "class" => "mls"));
$access_settings .= "</div>";

$access_settings .= "<div>";
$access_settings .= elgg_echo("questions:settings:access:group");
$access_settings .= elgg_view("input/access", array("name" => "params[access_group]", "value" => $plugin->access_group, "options_values" => $group_access_options, "class" => "mls"));
$access_settings .= "</div>";

echo elgg_view_module("inline", elgg_echo("questions:settings:access:title"), $access_settings);
