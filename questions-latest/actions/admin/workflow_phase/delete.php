<?php 
/**
 * Delete workflow phase
 *
 * @package Questions
 *
 */

$guid = get_input("guid");

if(!empty($guid)){
  $entity = get_entity($guid);
  
  if($entity instanceof QuestionsWorkflowPhase && $entity->canEdit()){
    if($entity->disable()){
      system_message(elgg_echo("questions:workflow:action:phases:delete:success"));
    } else {
      register_error(elgg_echo("questions:workflow:action:phases:delete:error"));
    }
  } else {
    register_error(elgg_echo("questions:workflow:action:phases:delete:error:type"));
  }
} else {
  register_error(elgg_echo("questions:workflow:action:phases:delete:error:guid"));
}

forward(REFERER);