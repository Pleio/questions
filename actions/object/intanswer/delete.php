<?php
/**
 * Delete internal (workflow) answer
 *
 * @package Questions
 *
 */

$guid = (int) get_input('guid');
$answer = get_entity($guid);

if (!$answer instanceof ElggIntAnswer) {
  register_error(elgg_echo("ClassException:ClassnameNotClass", array($guid, elgg_echo("item:object:intanswer"))));
  forward(REFERER);
}

if (!$answer->canEdit()) {
  register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
  forward(REFERER);
}

$question = $answer->getContainerEntity();
$answer->delete();

forward(get_input('forward', $question->getWorkflowURL()));
