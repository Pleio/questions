<?php
/**
 * Elgg questions plugin everyone page
 *
 * @package ElggQuestions
 */

elgg_register_title_button();

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'question',
	'full_view' => false,
	'list_type_toggle' => false,
));

if (!$content) {
	$content = elgg_echo('questions:none');
}

$title = elgg_echo('questions:updated');

$body = elgg_view_layout('content', array(
	'title' => $title,
	'content' => $content,
	'filter_context' => 'updated'
));

echo elgg_view_page($title, $body);
