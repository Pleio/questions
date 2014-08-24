<?php 
$ordering = get_input("elgg-object");

if(!empty($ordering) && is_array($ordering)){
  foreach($ordering as $order => $guid){
    if($entity = get_entity($guid)){
      if($entity instanceof QuestionsWorkflowPhase){     
        $entity->order = $order + 1;
        
        // trigger memcache update
        $entity->save();
      }
    }
  } 
}

exit();