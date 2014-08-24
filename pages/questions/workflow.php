<?php
/**
 * Elgg questions plugin everyone page
 *
 * @package ElggQuestions
 */

gatekeeper();
questions_expert_gatekeeper();

if (empty($page_owner)) {
  $page_owner = elgg_get_logged_in_user_entity();
}

// set page owner and add breadcrumb
elgg_set_page_owner_guid($page_owner->getGUID());
elgg_push_breadcrumb(elgg_echo("questions:workflow"));

// prepare options
$dbprefix = elgg_get_config("dbprefix");
$correct_answer_id = add_metastring("correct_answer");

$content = elgg_list_entities(array(
  'type' => 'object',
  'subtype' => 'question',
  'full_view' => false,
  'list_type_toggle' => false,
)); 

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
