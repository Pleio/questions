<?php

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

$question->workflowLastView = time();

if ($question->save()) {
  system_message(elgg_echo("questions:workflow:keep:closed:success"));
} else {
  system_message(elgg_echo("questions:workflow:keep:closed:error"));
}

$forward_url = $question->getWorkflowURL();
forward($forward_url);