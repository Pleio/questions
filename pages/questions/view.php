<?php
/**
 * View a question page
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

if (elgg_instanceof($page_owner, 'group')) {
  $base_url = "questions/group/$page_owner->guid/all";
  elgg_push_breadcrumb($crumbs_title, $base_url);
} else {
  $base_url = "questions";
}

if ($workflow == true) {
  elgg_push_breadcrumb(elgg_echo("questions:workflow"), $base_url . "/workflow");
}

if ($workflow == true) {
  include("view/workflow.php");
} else {
  include("view/frontend.php");
}

$content = $overview . $content;

$body = elgg_view_layout('content', array(
  'title' => $title_icon . $title,
  'content' => $content,
  'filter' => '',
));

echo elgg_view_page($title, $body);
