<?php

elgg_make_sticky_form('intanswer');

$guid = (int) get_input('guid');
$phase_guid = (int) get_input('phase_guid');

$intanswer = new ElggIntAnswer($guid);
$adding = !$intanswer->guid;
$editing = !$adding;

if ($editing && !$intanswer->canEdit()) {
  register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
  forward(REFERER);
}

$container_guid = (int) get_input('container_guid');
$description = get_input('description');

if (empty($container_guid) || empty($description)) {
  register_error(elgg_echo("questions:action:answer:save:error:body", array($container_guid, $description)));
  forward(REFERER);
}

if ($adding && !can_write_to_container(0, $container_guid, 'object', 'intanswer')) {
  register_error(elgg_echo("questions:action:answer:save:error:container"));
  forward(REFERER);
}

$question = get_entity($container_guid);

if (empty($question) || !elgg_instanceof($question, "object", "question")){
  register_error(elgg_echo("ClassException:ClassnameNotClass", array($container_guid, elgg_echo("item:object:question"))));
  forward(REFERER);
}

$intanswer->description = $description;

if ($adding && isset($phase_guid)) {
  $phases = questions_get_phases($container_guid);

  // Place intanswer at first phase if no current phase is selected
  if (!isset($question->current_phase_guid)) {
    $intanswer->phase_guid = key($phases);
  } else {
    $intanswer->phase_guid = $question->current_phase_guid;
  }

  // Save total answer time when the question is closed and remove current phase flag
  $last_phase_guid =  key( array_slice( $phases, -1, 1, TRUE ) );
  if (($phase_guid == $last_phase_guid) && isset($question->current_phase_guid)) {
    if (isset($question->total_answer_time)) {
      array_push($question->total_answer_time, $question->getWorkflowTotalTime());
    } else {
      $question->total_answer_time = array($question->getWorkflowTotalTime());
    }

    unset($question->current_phase_guid);
  }
  
  // Set phase of question to the changed GUID
  $question->current_phase_guid = $phase_guid;
}

$intanswer->access_id = $question->access_id;
$intanswer->container_guid = $container_guid;

try {
  $intanswer->save();
  $question->save();
  
  if ($adding) {
    add_to_river("river/object/answer/create", "create", elgg_get_logged_in_user_guid(), $intanswer->guid, $intanswer->access_id);
  }
} catch (Exception $e) {
  register_error(elgg_echo("questions:action:answer:save:error:save"));
  register_error($e->getMessage());
}

elgg_clear_sticky_form('intanswer');

forward(get_input('forward', $intanswer->getURL()));
