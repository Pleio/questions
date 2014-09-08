<?php

elgg_make_sticky_form("question");

$guid = (int) get_input("guid");

$title = get_input("title");
$description = get_input("description");
$tags = string_to_tag_array(get_input("tags", ""));
$access_id = (int) get_input("access_id");

$forward_url = REFERER;

if (!empty($guid)) {
	$entity = get_entity($guid);
	
	if (!empty($entity) && elgg_instanceof($entity, "object", "question")) {
		$container = $entity->getContainerEntity();
		
		if ($entity->canEdit() && questions_can_move_to_discussions($container)) {
			
			// save the latest changes
			$entity->title = $title;
			$entity->description = $description;
			$entity->tags = $tags;
			$entity->access_id = $access_id;
			
			$entity->save();
			
			// create new discussion
			$topic = new ElggObject();
			$topic->subtype = "groupforumtopic";
			$topic->container_guid = $entity->getContainerGUID();
			$topic->access_id = $entity->access_id;
			
			$topic->title = $entity->title;
			$topic->description = $entity->description;
			$topic->tags = $entity->tags;
			$topic->status = "open";
			
			if ($topic->save()) {
				// cleanup sticky form
				elgg_clear_sticky_form("question");
				
				// make sure we can copy all annotations
				$ia = elgg_set_ignore_access(true);
				
				$annotation_options = array(
					"guid" => $entity->getGUID(),
					"annotation_names" => "generic_comment",
					"limit" => false
				);
				$annotations = elgg_get_annotations($annotation_options);
				if (!empty($annotations)) {
					// copy all comments on the question to topic replies
					foreach ($annotations as $annotation) {
						$new_annotation = create_annotation($topic->getGUID(), "group_topic_post", $annotation->value, null, $annotation->owner_guid, $annotation->access_id);
						if (!empty($new_annotation)) {
							questions_backdate_annotation($new_annotation->id, $annotation->time_created);
						}
					}
				}
				
				$answer_options = array(
					"type" => "object",
					"subtype" => "answer",
					"container_guid" => $entity->getGUID(),
					"limit" => false
				);
				$answers = elgg_get_entities($answer_options);
				if (!empty($answers)) {
					// copy all answers on the question to topic replies
					foreach ($answers as $answer) {
						$new_annotation = create_annotation($topic->getGUID(), "group_topic_post", $answer->description, null, $answer->getOwnerGUID(), $answer->access_id);
						if (!empty($new_annotation)) {
							questions_backdate_annotation($new_annotation->id, $answer->time_created);
						}
						
						// copy all comments on the answer to topic replies
						$annotation_options["guid"] = $answer->getGUID();
						$annotations = elgg_get_annotations($annotation_options);
						if (!empty($annotations)) {
							foreach ($annotations as $annotation) {
								$new_annotation = create_annotation($topic->getGUID(), "group_topic_post", $annotation->value, null, $annotation->owner_guid, $annotation->access_id);
								if (!empty($new_annotation)) {
									questions_backdate_annotation($new_annotation->id, $annotation->time_created);
								}
							}
						}
					}
				}
				
				// last changes to the topic
				// backdate the discussion
				$topic->time_created = $entity->time_created;
				// set correct owner of the topic
				$topic->owner_guid = $entity->getOwnerGUID();
				$topic->save();
				
				// cleaup the old question
				$entity->delete();
				
				// restore access
				elgg_set_ignore_access($ia);
				
				// set correct forward url
				$forward_url = "questions/todo/" . $entity->getContainerGUID();
				system_message(elgg_echo("questions:action:question:move_to_discussions:success"));
			} else {
				register_error(elgg_echo("questions:action:question:move_to_discussions:error:topic"));
			}
		} else {
			register_error(elgg_echo("questions:action:question:move_to_discussions:error:move"));
		}
	} else {
		register_error(elgg_echo("ClassException:ClassnameNotClass", array($guid, elgg_echo("item:object:question"))));
	}
} else {
	register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

forward($forward_url);