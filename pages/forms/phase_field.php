<?php
/**
 * Display workflow phase field
 *
 * @package ElggQuestions
 */

$vars = array();

if($guid = get_input("guid")){
  if($entity = get_entity($guid)){
    if($entity instanceof QuestionsWorkflowPhase){
      $vars["entity"] = $entity;
    }
  }
}


echo elgg_view("forms/questions/admin/workflow_phase", $vars);