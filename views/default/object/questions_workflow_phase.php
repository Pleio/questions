<?php
/**
 * Question workflow phase object view
 *
 * @package ElggQuestions
 */

$handle = "<div onclick='$(\"#" . $vars['entity']->guid . "\").toggle();' class='questions_workflow_phase_handle'></div>";

$title = "<div class='questions_workflow_phase_title'>";
$title .= "<span class='elgg-icon elgg-icon-drag-arrow'></span>";
$title .= "<b>" . $vars['entity']->name . "</b>";
$title .= "<a href='" . $vars["url"] . "questions/admin/forms/phase_field/" . $vars['entity']->guid  . "' class='questions-popup'><span class='elgg-icon elgg-icon-settings-alt' title='" . elgg_echo("edit") . "'></span></a>";
$title .= "<span class='elgg-icon elgg-icon-delete' title='" . elgg_echo("delete") . "' onclick='questions_workflow_phase_remove(" . $vars['entity']->guid . ");'></span>";
$title .= "</div>";
    
$info = $handle . $title;

echo "<div id='questions_workflow_phase_" . $vars['entity']->guid . "' class='custom_field' rel='" . $vars['entity']->category_guid . "'>"  . $info . "</div>";