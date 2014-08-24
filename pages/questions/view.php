<?php
/**
 * View a question
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
$crumbs_title = $page_owner->name;

if (get_input('workflow') == true) {
  elgg_push_breadcrumb(elgg_echo("questions:workflow"), "questions/workflow");
}

if (elgg_instanceof($page_owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "questions/group/$page_owner->guid");
}

if (get_input('workflow') == true) {
  include("view_workflow.php");
} else {
  include("view_answers.php");
}

$body = elgg_view_layout('content', array(
  'title' => $title_icon . $title,
  'content' => $content,
  'filter' => '',
));

echo elgg_view_page($title, $body);
