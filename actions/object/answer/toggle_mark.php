<?php
/**
 * This action marks an answer as the correct answer for a question.
 *
 */

$guid = (int) get_input("guid");

if (!empty($guid)) {
	$entity = get_entity($guid);
	
	if (!empty($entity) && elgg_instanceof($entity, "object", "answer")) {
		// are you allowed to mark answers as correct
		if (questions_can_mark_answer($entity)) {
			$question = $entity->getContainerEntity();
			$answer = $question->getMarkedAnswer();
			
			if (empty($answer)) {
				// no answer yet, so mark this one
				$entity->markAsCorrect();
				
				system_message(elgg_echo("questions:action:answer:toggle_mark:success:mark"));
			} elseif ($answer->getGUID() == $entity->getGUID()) {
				// the marked answer is this answer, so unmark
				$entity->undoMarkAsCorrect();
				
				system_message(elgg_echo("questions:action:answer:toggle_mark:success:unmark"));
			} else {
				register_error(elgg_echo("questions:action:answer:toggle_mark:error:duplicate"));
			}
		} else {
			register_error(elgg_echo("questions:action:answer:toggle_mark:error:not_allowed"));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
	}
} else {
	register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

forward(REFERER);