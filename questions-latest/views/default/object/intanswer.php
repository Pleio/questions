<?php
/**
 * Workflow internal (workflow) answer object
 *
 * @package ElggQuestions
 */

$intanswer = $vars['entity'];

$image = elgg_view_entity_icon(get_entity($intanswer->owner_guid), 'small');

// create subtitle
$owner = $intanswer->getOwnerEntity();
$owner_link = elgg_view("output/url", array("text" => $owner->name, "href" => $owner->getURL(), "is_trusted" => true));

$friendly_time = elgg_view_friendly_time($intanswer->time_created);
$subtitle = $owner_link . " " . $friendly_time;

if (isset($intanswer->phase_guid)) {
  $subtitle .= ", " . $intanswer->getPhase()->name;
}

if (isset($intanswer->email)) {
  $subtitle .= ", " . elgg_echo("questions:workflow:email:notification:sent");
}

if (isset($intanswer->timeSpent)) {
  $subtitle .= ", " . questions_get_friendly_timespan($intanswer->timeSpent);
}

if (isset($intanswer->answerGuid)) {
  $subtitle .= ", " . elgg_echo("questions:workflow:publishedFront");
}

if (isset($intanswer->workflowCloseCycle)) {
  $subtitle .= ", " . elgg_echo("questions:workflow:closed");
}

// build entity menu
$entity_menu = elgg_view_menu('entity', array(
  'entity' => $vars['entity'],
  'handler' => 'intanswers',
  'sort_by' => 'priority',
  'class' => 'elgg-menu-hz'
));

$body = elgg_view('output/longtext', array('value' => $intanswer->description));

// add comments
$comment_count = $intanswer->countComments();
if ($comment_count) {
  $comment_options = array(
    'guid' => $intanswer->getGUID(),
    'annotation_name' => 'generic_comment',
    'limit' => false
  );
  
  $comments = elgg_get_annotations($comment_options);
  
  $body .= "<span class='elgg-river-comments-tab'>" . elgg_echo('comments') . "</span>";
  $body .= elgg_view_annotation_list($comments, array('list_class' => 'elgg-river-comments'));
}

// show a comment form like in the river
$body_vars = array(
  'entity' => $intanswer,
  'inline' => true
);
$body .= "<div class='elgg-river-item hidden' id='comments-add-" . $intanswer->getGUID() . "'>";
$body .= elgg_view_form('comments/add', array(), $body_vars);
$body .= "</div>";

// build content
$params = array(
  'entity' => $intanswer,
  'metadata' => $entity_menu,
  'subtitle' => $subtitle,
  'content' => $body
);

$summary = elgg_view('page/components/summary', $params);

echo elgg_view_image_block($image, $summary);
