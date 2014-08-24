<?php
/**
 *	Questions widget content
 **/

$widget = $vars["entity"];

$limit = (int) $widget->limit;
if ($limit < 1) {
	$limit = 5;
}

$options = array(
	"type" => "object",
	"subtype" => "question",
	"limit" => $limit,
	"full_view" => false,
	"pagination" => false
);

$getter = "elgg_get_entities";

switch ($widget->context) {
	case "profile":
		$options["owner_guid"] = $widget->getOwnerGUID();
		break;
	case "dashboard":
		
		$type = $widget->content_type;
		if (($type == "todo") && !questions_is_expert()) {
			$type = "mine";
		}
		
		// user shows owned
		switch($type) {
			case "todo":
				$getter = "elgg_get_entities_from_metadata";
				
				// prepare options
				$dbprefix = elgg_get_config("dbprefix");
				$correct_answer_id = add_metastring("correct_answer");
				$site = elgg_get_site_entity();
				$user = elgg_get_logged_in_user_entity();
				
				$container_where = array();
								
				$options["wheres"] = array("NOT EXISTS (
							SELECT 1
							FROM " . $dbprefix . "entities e2
							JOIN " . $dbprefix . "metadata md ON e2.guid = md.entity_guid
							WHERE e2.container_guid = e.guid
							AND md.name_id = " . $correct_answer_id . ")");
				$options["order_by_metadata"] = array("name" => "solution_time");
				
				if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $site->getGUID())) {
					$container_where[] = "(e.container_guid NOT IN (
						SELECT ge.guid
						FROM " . $dbprefix . "entities ge
						WHERE ge.type = 'group'
						AND ge.site_guid = " . $site->getGUID() . "
						AND ge.enabled = 'yes'
					))";
				}
				
				$group_options = array(
						"type" => "group",
						"limit" => false,
						"relationship" => QUESTIONS_EXPERT_ROLE,
						"relationship_guid" => $user->getGUID(),
						"callback" => "questions_row_to_guid"
				);
				
				$groups = elgg_get_entities_from_relationship($group_options);
				if (!empty($groups)) {
					$container_where[] = "(e.container_guid IN (" . implode(",", $groups) . "))";
				}
				
				$container_where = "(" . implode(" OR ", $container_where) . ")";
				
				$options["wheres"][] = $container_where;
								
				break;
			case "all":
				// just get all questions
				break;
			case "mine":
			default:
				$options["owner_guid"] = $widget->getOwnerGUID();
		}
		
		break;
	case "groups":
		// only in this container
		$options["container_guid"] = $widget->getOwnerGUID();
		break;
}

$content = elgg_list_entities($options, $getter);
if (empty($content)) {
	$content = elgg_view("output/longtext", array("value" => elgg_echo("questions:none")));
}

echo $content;
