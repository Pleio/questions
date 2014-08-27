<?php
$title = $question->title;
elgg_push_breadcrumb($title);

if (isset($question->current_phase_guid)) {
  $title .= " | " . get_entity($question->current_phase_guid)->name;
}

// build page elements
$title_icon = "";
$content = elgg_view_entity($question, array('full_view' => true));


$intanswers = "";

// add the rest of the answers
$options = array(
  'type' => 'object',
  'subtype' => 'intanswer',
  'container_guid' => $question->guid,
  'count' => true,
  'limit' => false,
  'order_by' => 'time_created',
);

$intanswers .= elgg_list_entities($options);

$count = elgg_get_entities($options);
if ($marked_answer) {
  $count++;
}

// show all internal answers
if ($count > 0) {
  $content .= elgg_view_module('info', "$count " . elgg_echo('questions:workflow:intanswers'), $intanswers, array("class" => "mtm"));
}

// add form to open internal question or answer form
if ($question->canWriteToContainer(0, 'object', 'answer')) {
  if ($question->current_phase_guid) {
    $add_form = elgg_view_form('object/intanswer/add', array(), array(
        'container_guid' => $question->guid, 
        'current_phase_guid' => $question->current_phase_guid
    ));
    $content .= elgg_view_module('info', elgg_echo('questions:workflow:addyours'), $add_form);
  } else {
    $open_workflow = elgg_view_form('object/question/workflow_open', array(), array(
        'id'=>'workflow_add_intanswer',
        'question_guid' => $question->guid
    ));
    $content .= elgg_view_module('info', elgg_echo('questions:workflow:open:title'), $open_workflow);
  }
}