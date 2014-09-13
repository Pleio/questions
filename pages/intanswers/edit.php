<?php
/**
 * Edit internal (workflow) answer page
 *
 * @package ElggQuestions
 */

$intanswer_guid = get_input('guid');
$intanswer = get_entity($intanswer_guid);

if (!elgg_instanceof($intanswer, 'object', 'intanswer') || !$intanswer->canEdit()) {
  register_error(elgg_echo('questions:intanswer:unknown'));
  forward(REFERRER);
}

$question = $intanswer->getContainerEntity();

$title = elgg_echo("questions:intanswer:edit");

elgg_push_breadcrumb($question->title, $question->getWorkflowURL());
elgg_push_breadcrumb($title);

$content = elgg_view_form('object/intanswer/edit', array(), array('entity' => $intanswer));

$body = elgg_view_layout('content', array(
  'title' => $title,
  'content' => $content,
  'filter' => ''
));

echo elgg_view_page($title, $body);
