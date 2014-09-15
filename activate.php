<?php
/**
 * This file is executed when the plugin is enabled
 */

// register our own classes
if (!update_subtype("object", 'question', 'ElggQuestion')) {
	add_subtype("object", 'question', 'ElggQuestion');
}

if (!update_subtype("object", 'answer', 'ElggAnswer')) {
	add_subtype("object", 'answer', 'ElggAnswer');
}

if (!update_subtype("object", 'intanswer', 'ElggIntAnswer')) {
  add_subtype("object", 'intanswer', 'ElggIntAnswer');
}

if (!update_subtype("object", 'questions_workflow_phase', 'QuestionsWorkflowPhase')) {
  add_subtype("object", 'questions_workflow_phase', 'QuestionsWorkflowPhase');
}

// Migrate old "correct marks" to new marks with entity relations
$options = array(
  'types' => 'object',
  'subtypes' => ANSWER_OBJECT,
  'limit' => false
);

$answers = elgg_get_entities($options);
foreach ($answers as $answer) {
  if($answer->correct_answer) {
    $question = $answer->getContainerEntity();
    unset($answer->correct_answer);
    $answer->save();

    add_entity_relationship($question->guid, "correctAnswer", $answer->guid);
  }
}