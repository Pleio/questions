<?php 
$guid = get_input("guid");

if(!empty($guid)){
  $entity = get_entity($guid);
  
  if($entity instanceof QuestionsWorkflowPhase){
    if($entity->delete()){
      echo "true";
      system_message(elgg_echo("questions:workflow:action:phases:delete:success"));
    } else {
      register_error(elgg_echo("questions:workflow:action:phases:save:error"));
    }
  } else {
    register_error(elgg_echo("questions:workflow:action:phases:save:error:type"));
  }
} else {
  register_error(elgg_echo("questions:workflow:action:phases:save:error:guid"));
}

forward(REFERER);