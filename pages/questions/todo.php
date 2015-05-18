<?php
/**
 * Elgg questions todo page
 *
 * @package ElggQuestions
 */

gatekeeper();

if (!questions_is_expert()) {
  forward("questions/all");
}

// check for a group filter
$group_guid = (int) get_input("group_guid");
if (!empty($group_guid)) {
  $group = get_entity($group_guid);
  if (!empty($group) && elgg_instanceof($group, "group")) {
    // make sure the user is an expert of this group
    if (!questions_is_expert($group)) {
      forward("questions/all");
    }
    $page_owner = $group;
    elgg_push_breadcrumb($group->name, "questions/group/" . $group->getGUID() . "/all");
  }
}

if (empty($page_owner)) {
  $page_owner = elgg_get_logged_in_user_entity();
}

// set page owner and add breadcrumb
elgg_set_page_owner_guid($page_owner->getGUID());
elgg_push_breadcrumb(elgg_echo("questions:todo"));

// add title button
elgg_register_title_button();

// prepare options
$dbprefix = elgg_get_config("dbprefix");

$options = array(
  "type" => "object",
  "subtype" => "question",
  "wheres" => array("NOT EXISTS (
    SELECT 1
    FROM " . $dbprefix . "entities e2
    JOIN " . $dbprefix . "entity_relationships rel ON e2.guid = rel.guid_one
    WHERE e2.container_guid = e.guid
    AND rel.relationship = 'correctAnswer')"),
  "full_view" => false,
  "list_type_toggle" => false,
  "order_by_metadata" => array("name" => "solution_time")
);

if (elgg_instanceof($page_owner, "group")) {
  $options["container_guid"] = $page_owner->getGUID();
} else {
  $site = elgg_get_site_entity();
  $user = elgg_get_logged_in_user_entity();
  $container_where = array();

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

  if (count($groups) > 0) {
    $container_where[] = "(e.container_guid IN (" . implode(",", $groups) . "))";
  }

  $options["wheres"][] = $container_where;
}

$content = elgg_list_entities_from_metadata($options);

if (!$content) {
  $content = elgg_echo("questions:todo:none");
}

$title = elgg_echo("questions:todo");

$body = elgg_view_layout("content", array(
  "title" => $title,
  "content" => $content,
  "filter_context" => ""
));

echo elgg_view_page($title, $body);