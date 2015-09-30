<?php
/**
 * Workflow overview page
 *
 * @package ElggQuestions
 */

if (get_input('group_guid')) {
  elgg_set_page_owner_guid(get_input('group_guid'));
} else {
  elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
}

// set breadcrumb
elgg_push_breadcrumb(elgg_echo("questions:workflow"), "questions/workflow");

elgg_push_context("workflow");
elgg_push_context("questions");

$page_owner = elgg_get_page_owner_entity();
if (elgg_instanceof($page_owner, 'group')) {
  elgg_push_breadcrumb($page_owner->name);
}


// prepare options
$dbprefix = elgg_get_config("dbprefix");
$correct_answer_id = add_metastring("correct_answer");

$settings = array(
  'type' => 'object',
  'subtype' => 'question',
  'full_view' => false,
  'list_type_toggle' => false,
  'workflow' => true,
  'class' => 'questions-workflow-list',
  'order_by' => 'e.time_created desc'
);

$metastring_id = get_metastring_id('workflow_lastaction');
if ($metastring_id) {
    $settings['joins'] = array(
    "left join {$dbprefix}metadata md ON e.guid = md.entity_guid AND md.name_id = {$metastring_id}",
    "left join {$dbprefix}metastrings ms ON md.value_id = ms.id",
    );
    $settings['order_by'] = 'ABS(ms.string) desc, e.time_created desc';
}

$currentPhase_id = get_metastring_id('currentPhase');
$lastview_id = get_metastring_id('workflowLastView');

if (get_input('status')) {
    if (get_input('status') == 'open' && $currentPhase_id) {
        $settings['joins'][] = "left join {$dbprefix}metadata md2 ON e.guid = md2.entity_guid AND md2.name_id = {$currentPhase_id}";
        $settings['joins'][] = "left join {$dbprefix}metastrings ms2 ON md2.value_id = ms2.id";

        $settings['wheres'] = 'ms2.string IS NOT NULL';
    } elseif (get_input('status') == 'closed_check' && $lastview_id) {
        $settings['joins'][] = "left join {$dbprefix}metadata md2 ON e.guid = md2.entity_guid AND md2.name_id = {$lastview_id}";
        $settings['joins'][] = "left join {$dbprefix}metastrings ms2 ON md2.value_id = ms2.id";

        $settings['wheres'] = array();
        $settings['wheres'][] = 'ms2.string < e.last_action';
    } elseif (get_input('status') == 'closed_no_check' && $lastview_id) {
        $settings['joins'][] = "left join {$dbprefix}metadata md2 ON e.guid = md2.entity_guid AND md2.name_id = {$lastview_id}";
        $settings['joins'][] = "left join {$dbprefix}metastrings ms2 ON md2.value_id = ms2.id";

        $settings['wheres'] = array();
        $settings['wheres'][] = 'ms2.string >= e.last_action';
    }
}

if (get_input('group_guid')) {
  $settings['container_guid'] = get_input('group_guid');
}

$content = elgg_view('questions/workflow/all');
$content .= "<div class=\"question-workflow-list\">" . elgg_list_entities($settings) . "</div>";

if (!$content) {
  $content = elgg_echo('questions:none');
}

$title = elgg_echo("questions:workflow");

$body = elgg_view_layout("content", array(
  "title" => $title,
  "content" => $content,
  "filter_context" => ""
));

echo elgg_view_page($title, $body);