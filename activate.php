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

// create workflow site access collection and register it to the site
$site = elgg_get_site_entity();
if (!$site->getPrivateSetting('workflowACL')) {
  $aclGuid = create_access_collection("Workflow " . $site->name, $site->guid);
  $site->setPrivateSetting('workflowACL', $aclGuid);
}

// Migrate old "correct marks" to new marks with entity relations
$options = array(
  'subtypes' => ANSWER_OBJECT,
  'metadata_names' => array('correct_answer'), 
  'limit' => 0
);

$answers = elgg_get_entities_from_metadata($options);
foreach ($answers as $answer) {
  $question = $answer->getContainerEntity();

  unset($answer->correct_answer);
  $answer->save();

  add_entity_relationship($question->guid, "correctAnswer", $answer->guid);
}