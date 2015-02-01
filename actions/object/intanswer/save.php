<?php
/**
 * Save internal (workflow) answer
 *
 * @package Questions
 *
 */

elgg_make_sticky_form('intanswer');

$guid = (int) get_input('guid');
$phase_guid = (int) get_input('phase_guid');
$answer_frontend = (int) get_input('answer_frontend');
$container_guid = (int) get_input('container_guid');
$description = get_input('description');
$timeworked = get_input('timeworked');

$intanswer = new ElggIntAnswer($guid);

$adding = !$intanswer->guid;
$editing = !$adding;

if ($editing && !$intanswer->canEdit()) {
  register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
  forward(REFERER);
}

if (empty($container_guid) || empty($description)) {
  register_error(elgg_echo("questions:action:answer:save:error:body"));
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

if ($adding && !$phase_guid) {
  register_error(elgg_echo("questions:action:intanswer:save:nophase")); 
  forward(REFERER);
}

if ($adding && (!$timeworked | !is_numeric($timeworked))) {
  register_error(elgg_echo("questions:action:intanswer:save:notimeworked")); 
  forward(REFERER);
}

if (get_entity($question->container_guid) instanceof ElggGroup) {
  $group_guid = $question->container_guid;
} else {
  $group_guid = 0;
}

$access_collection_guid = questions_get_workflow_access_collection();
if (!$access_collection_guid) {
  register_error(elgg_echo("questions:workflow:noacl"));
  forward(REFERER);
}

$intanswer->access_id = $access_collection_guid;
$intanswer->description = $description;
$intanswer->container_guid = $container_guid;


if ($adding) {
  $intanswer->phase_guid = $question->getCurrentWorkflowPhase()->guid;
  $intanswer->timeWorked = $timeworked;
  $intanswer->save();
  
  $question->changeWorkflowPhase($phase_guid);
  $question->workflow_lastaction = time();
}

try {
  if ($answer_frontend == 1) {
    $intanswer->answerGuid = true;
    $question->workflowLastView = time();
  }

  $intanswer->save();
  $question->save();

} catch (Exception $e) {
  register_error(elgg_echo("questions:action:answer:save:error:save"));
  register_error($e->getMessage());
}

elgg_clear_sticky_form('intanswer');

forward(get_input('forward', $intanswer->getURL()));
