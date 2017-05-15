<?php
$page_owner = elgg_get_page_owner_entity();

if (!$page_owner) {
    register_error(elgg_echo('profile:notfound'));
    forward('/questions/all');
}

elgg_push_breadcrumb($page_owner->name);
elgg_register_title_button();

elgg_push_context("questions");

$title = elgg_echo('answers:owner', array($page_owner->name));

$options = array(
    'type' => 'object',
    'subtype' => 'question',
    'full_view' => false,
    'list_type_toggle' => false,
    'order_by' => "e.last_action DESC"
);

$answer_id = get_subtype_id("object", "answer");
if ($answer_id) {
    $options["joins"] = array("JOIN elgg_entities answers ON answers.container_guid = e.guid");
    $options["wheres"] = array(
    "answers.subtype = {$answer_id}",
    "answers.owner_guid = {$page_owner->guid}"
    );
}

$content = elgg_list_entities($options);

$vars = array(
    'title' => $title,
    'content' => $content,
    'filter' => ''
);

elgg_unregister_menu_item('title', 'add');

$body = elgg_view_layout('content', $vars);
echo elgg_view_page($title, $body);

elgg_pop_context();