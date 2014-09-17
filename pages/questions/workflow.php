<?php
/**
 * Workflow overview page
 *
 * @package ElggQuestions
 */

gatekeeper();
questions_expert_gatekeeper();

if (get_input('group_guid')) {
  elgg_set_page_owner_guid(get_input('group_guid'));
} else {
  elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
}

// set breadcrumb
elgg_push_breadcrumb(elgg_echo("questions:workflow"));

// prepare options
$dbprefix = elgg_get_config("dbprefix");
$correct_answer_id = add_metastring("correct_answer");

$settings = array(
  'type' => 'object',
  'subtype' => 'question',
  'full_view' => false,
  'list_type_toggle' => false,
  'workflow' => true
); 

if (get_input('group_guid')) {
  $settings['container_guid'] = get_input('group_guid');
}

$content = elgg_view('questions/workflow/all');
$content .= elgg_list_entities($settings);

if (!$content) {
  $content = elgg_echo('questions:none');
}

$title = elgg_echo("questions:workflow");

$body = elgg_view_layout("content", array(
  "title" => $title,
  "content" => $content,
  "filter_context" => ""
));

echo elgg_view_page($title, $body);
