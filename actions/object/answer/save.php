<?php
/**
 * Save answer object
 *
 * @package Questions
 *
 */

elgg_make_sticky_form('answer');

$guid = (int) get_input('guid');

$answer = new ElggAnswer($guid);

$adding = !$answer->guid;
$editing = !$adding;

if ($editing && !$answer->canEdit()) {
	register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
	forward(REFERER);
}

$container_guid = (int) get_input('container_guid');
$description = get_input('description');

if (empty($container_guid) || empty($description)) {
	register_error(elgg_echo("questions:action:answer:save:error:body", array($container_guid, $description)));
	forward(REFERER);
}

if ($adding && !can_write_to_container(0, $container_guid, 'object', 'answer')) {
	register_error(elgg_echo("questions:action:answer:save:error:container"));
	forward(REFERER);
}

$question = get_entity($container_guid);

if (empty($question) || !elgg_instanceof($question, "object", "question")){
	register_error(elgg_echo("ClassException:ClassnameNotClass", array($container_guid, elgg_echo("item:object:question"))));
	forward(REFERER);
}

if ($question->getStatus() != "open") {
	elgg_clear_sticky_form('answer');
	
	register_error(elgg_echo("questions:action:answer:save:error:question_closed"));
	forward(REFERER);
}

$answer->description = $description;
$answer->access_id = $question->access_id;
$answer->container_guid = $container_guid;

try {
	$answer->save();
	
	if ($adding) {
		add_to_river("river/object/answer/create", "create", elgg_get_logged_in_user_guid(), $answer->guid, $answer->access_id);
	}
} catch (Exception $e) {
	register_error(elgg_echo("questions:action:answer:save:error:save"));
	register_error($e->getMessage());
}

elgg_clear_sticky_form('answer');

forward(get_input('forward', $answer->getURL()));
