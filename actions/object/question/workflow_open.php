<?php
/**
 * Open workflow for a question.
 * 
 * @package Questions
 *
 */

$guid = (int) get_input('guid');
$question = get_entity($guid);

if (!$question instanceof ElggQuestion) {
  register_error(elgg_echo("ClassException:ClassnameNotClass", array($guid, elgg_echo("item:object:question"))));
  forward(REFERER);
}

if (!$question->canEdit()) {
  register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
  forward(REFERER);
}

if ($question->setWorkflowOpen()) {
  system_message(elgg_echo("questions:workflow:open:success"));
} else {
  system_message(elgg_echo("questions:workflow:open:error"));
}

$forward_url = $question->getWorkflowURL();
forward($forward_url);