<?php
/**
 * Claim a question (by e-mail link).
 * 
 * @package Questions
 *
 */

$guid = (int) get_input('guid');
$question = get_entity($guid);

if (!elgg_is_logged_in()) {
	$_SESSION['last_forward_from'] = current_page_url();
	forward('/login');
}

if (!$question instanceof ElggQuestion) {
  register_error(elgg_echo("ClassException:ClassnameNotClass", array($guid, elgg_echo("item:object:question"))));
  forward(REFERER);
}

if (!$question->canEdit()) {
  register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
  forward(REFERER);
}

if (!$question->workflowManager) {
	$question->workflowManager = elgg_get_logged_in_user_guid();
	$question->save();
	system_message(elgg_echo("questions:workflow:claim:success"));
} else {
	system_error(elgg_echo("questions:workflow:claim:failed"));
}

forward($question->getWorkflowURL());