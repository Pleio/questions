<?php  

$site_guid = elgg_get_site_entity()->getGUID();
$guid = get_input("guid");
$name = get_input("name");
$due = get_input("due");

if(!empty($guid)){
  $entity = get_entity($guid);
  if (!($entity instanceof QuestionsWorkflowPhase)) {
    $entity = null;
  }
} else {
  $entity = new QuestionsWorkflowPhase();
  $entity->owner_guid = $site_guid;
  $entity->container_guid = $site_guid;
  $entity->save();
  $add = true;
} 
 
if(!is_null($entity)){
  $entity->name = $name;
  $entity->due = $due;

  // add correct order
  if($add){
    $options = array(
      "type" => "object",
      "subtype" => QUESTIONS_WORKFLOW_PHASE,
      "count" => true,
      "owner_guid" => $site_guid
    );    

    $count = elgg_get_entities($options);
    $entity->order = $count;
  }

  if ($entity->save()) {
    system_message(elgg_echo("questions:workflow:action:phases:save:success"));
  } else {
    register_error(elgg_echo("questions:workflow:action:phases:save:error"));
  }
} else {
  register_error(elgg_echo("questions:workflow:action:phases:save:error"));
}

forward(REFERER);