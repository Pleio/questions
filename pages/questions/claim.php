<?php
/**
 * Claim a question page
 *
 * @package ElggQuestions
 */

$guid = (int) get_input('guid');
$question = get_entity($guid);

// make sure we have a question
if (empty($question) || !elgg_instanceof($question, "object", "question")) {
	register_error(elgg_echo("ClassException:ClassnameNotClass", array($guid, elgg_echo("item:object:question"))));
	forward(REFERER);
}

// set page owner
elgg_set_page_owner_guid($question->getContainerGUID());
$page_owner = $question->getContainerEntity();

// set breadcrumb
elgg_push_breadcrumb(elgg_echo("questions:workflow"), "questions/workflow");

if (elgg_instanceof($page_owner, 'group')) {
  $base_url = "questions/group/$page_owner->guid/workflow";
  elgg_push_breadcrumb($page_owner->name, $url);
}

elgg_push_breadcrumb($question->title);

if ($question->workflowManager) {
	system_message(elgg_echo("questions:workflow:alreadyclaimed"));
	forward($question->getWorkflowURL());
}


$content = elgg_view_form('object/question/claim', null, array(
  'question' => $question
));

$body = elgg_view_layout('content', array(
  'title' => $title_icon . $title,
  'content' => $content,
  'filter' => '',
));

echo elgg_view_page($title, $body);
