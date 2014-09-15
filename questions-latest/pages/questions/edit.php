<?php
/**
 * Edit question page
 *
 * @package ElggQuestions
 */

$question_guid = (int) get_input("guid");
$question = get_entity($question_guid);

if (!elgg_instanceof($question, "object", "question") || !$question->canEdit()) {
	register_error(elgg_echo("questions:unknown"));
	forward(REFERRER);
}

elgg_push_breadcrumb($question->title, $question->getURL());
elgg_push_breadcrumb(elgg_echo("edit"));

$form_vars = array();
if (questions_limited_to_groups()) {
	$form_vars["class"] = "questions-validate-container";
}

$body_vars = array(
	"entity" => $question,
);

$content = elgg_view_form("object/question/save", $form_vars, $body_vars);

$body = elgg_view_layout("content", array(
	"title" => elgg_echo("edit"),
	"content" => $content,
	"filter" => ""
));

echo elgg_view_page(elgg_echo("edit"), $body);
