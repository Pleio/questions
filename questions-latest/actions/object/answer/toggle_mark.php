<?php
/**
 * Mark answer as correct
 *
 * @package Questions
 *
 */

$guid = (int) get_input("guid");

if (!empty($guid)) {
	$answer = get_entity($guid);
	
	if (!empty($answer) && elgg_instanceof($answer, "object", "answer")) {
		if (questions_can_mark_answer($answer)) {
			$question = $answer->getQuestion();
			$correctAnswer = $question->getCorrectAnswer();
			
		if (!$correctAnswer) {
				$answer->markCorrect();
				system_message(elgg_echo("questions:action:answer:toggle_mark:success:mark"));
			} elseif ($answer == $correctAnswer) {
				$answer->unmarkCorrect();
				system_message(elgg_echo("questions:action:answer:toggle_mark:success:unmark"));
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