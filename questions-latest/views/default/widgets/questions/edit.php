<?php
/**
 * Questions widget settings
 *
 * @package Questions
 */

$widget = $vars['entity'];

$limit = (int) $widget->limit;
if ($limit < 1) {
	$limit = 5;
}

if ($widget->context == "dashboard") {
	$content_type_options = array("mine" => elgg_echo("mine"), "all" => elgg_echo("all"));
	if (questions_is_expert()) {
		$content_type_options["todo"] = elgg_echo("questions:todo");
	}
	
	echo "<div>";
	echo elgg_echo("widget:questions:content_type");
	echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[content_type]", "value" => $widget->content_type, "options_values" => $content_type_options));
	echo "</div>";
}

echo "<div>";
echo elgg_echo("widget:numbertodisplay");
echo elgg_view('input/text', array('name' => 'params[limit]', 'value' => $limit));
echo "</div>";