<?php
/**
 * List most recent questions on group profile page
 *
 * @package Questions
 */

$group = elgg_get_page_owner_entity();

if ($group->questions_enable != "yes") {
	return true;
}

$all_link = elgg_view("output/url", array(
	"href" => "questions/group/" . $group->getGUID() . "/all",
	"text" => elgg_echo("link:view:all"),
	"is_trusted" => true
));

elgg_push_context("widgets");
$options = array(
	"type" => "object",
	"subtype" => "question",
	"container_guid" => elgg_get_page_owner_guid(),
	"limit" => 6,
	"full_view" => false,
	"pagination" => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = elgg_view("output/longtext", array("value" => elgg_echo("questions:none")));
}

$new_link = "";
if ($group->canWriteToContainer(0, "object", "question")) {
	$new_link = elgg_view("output/url", array(
		"href" => "questions/add/$group->guid",
		"text" => elgg_echo("questions:add"),
		"is_trusted" => true
	));
}

echo elgg_view("groups/profile/module", array(
	"title" => elgg_echo("questions:group"),
	"content" => $content,
	"all_link" => $all_link,
	"add_link" => $new_link,
));
