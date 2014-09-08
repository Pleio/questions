<?php
$title = $question->title;
elgg_push_breadcrumb($title);

if ($question->isWorkflowOpen()) {
  $title .= " | " . $question->getCurrentWorkflowPhase()->name;
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
  'pagination' => false
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
  if ($question->isWorkflowOpen()) {
    $add_form = elgg_view_form('object/intanswer/add', array(), array(
        'container_guid' => $question->guid, 
        'current_phase_guid' => $question->currentPhase
    ));
    $content .= elgg_view_module('info', elgg_echo('questions:workflow:addyours'), $add_form);
  } else {
    $open_workflow = elgg_view("questions/workflow/open", array('question'=>$question));
    $content .= elgg_view_module('info', elgg_echo('questions:workflow:open:title'), $open_workflow);
  }
}