<?php
/**
 * Elgg questions plugin owner page
 *
 * @package Questions
 */

$page_owner = elgg_get_page_owner_entity();
elgg_push_breadcrumb($page_owner->name);
elgg_register_title_button();
$title = elgg_echo('questions:owner', array($page_owner->name));

$options = array(
	'type' => 'object',
	'subtype' => 'question',
	'full_view' => false,
	'list_type_toggle' => false
);
if (elgg_instanceof($page_owner, "group")) {
	// groups are containers
	$options['container_guid'] = $page_owner->getGUID();
} else {
	// users list all owned questions
	$options['owner_guid'] = $page_owner->getGUID();
}

$content = elgg_list_entities($options);

if (!$content) {
	$content = elgg_echo('questions:none');
}

$vars = array(
	'title' => $title,
	'content' => $content,
	'filter_context' => 'mine'
);

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);
