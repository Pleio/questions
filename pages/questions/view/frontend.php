<?php
/**
 * View a question, all answers
 *
 * @package ElggQuestions
 */

$title = $question->title;
elgg_push_breadcrumb($title);

// build page elements
$title_icon = "";

// switch to go from frontend to backend
if (questions_workflow_enabled() && questions_is_expert($page_owner)) {
  $overview = elgg_view('questions/overview', array('question'=>$question));
} else {
  $overview = "";
}

$content = elgg_view_entity($question, array('full_view' => true));

$answers = "";

// add the rest of the answers
$options = array(
  'type' => 'object',
  'subtype' => 'answer',
  'container_guid' => $question->guid,
  'count' => true,
  'limit' => false,
  'pagination' => false,
  'order_by' => 'e.time_created'
);

if (elgg_is_active_plugin("likes")) {
  // order answers based on likes
  $dbprefix = elgg_get_config("dbprefix");
  $likes_id = add_metastring("likes");
  
  $options["selects"] = array(
    "(SELECT count(a.name_id) AS likes_count
    FROM " . $dbprefix . "annotations a
    WHERE a.entity_guid = e.guid
    AND a.name_id = " . $likes_id . ") AS likes_count");
}

$answers = elgg_list_entities($options);
$count = elgg_get_entities($options);

$content .= elgg_view_module('info', "$count " . elgg_echo('answers'), $answers, array("class" => "mtm"));

// add answer form
if (($question->getStatus() == "open") && $question->canWriteToContainer(0, 'object', 'answer')) {
  
  $add_form = elgg_view_form('object/answer/add', array(), array('container_guid' => $question->guid));
  
  $content .= elgg_view_module('info', elgg_echo('answers:addyours'), $add_form);
} elseif ($question->getStatus() == "closed") {
  // add an icon to show this question is closed
  $title_icon = elgg_view_icon("lock-closed");
}