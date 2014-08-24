<?php
/**
 * Edit answer page
 *
 * @package ElggQuestions
 */

$answer_guid = get_input('guid');
$answer = get_entity($answer_guid);

if (!elgg_instanceof($answer, 'object', 'answer') || !$answer->canEdit()) {
	register_error(elgg_echo('questions:answer:unknown'));
	forward(REFERRER);
}

$question = $answer->getContainerEntity();

$title = elgg_echo("questions:answer:edit");

elgg_push_breadcrumb($question->title, $question->getURL());
elgg_push_breadcrumb($title);

$content = elgg_view_form('object/answer/edit', array(), array('entity' => $answer));

$body = elgg_view_layout('content', array(
	'title' => $title,
	'content' => $content,
	'filter' => ''
));

echo elgg_view_page($title, $body);
