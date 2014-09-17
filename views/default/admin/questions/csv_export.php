<?php
/**
 * Export CSV page
 *
 * @package ElggQuestions
 */

$title = elgg_echo('admin:questions:csv_export');

$description = '<p>' . elgg_echo('questions:workflow:csv_export:description') .'</p>';
$description .= elgg_view_form('questions/admin/csv_export');

echo elgg_view_module('inline', $title, $description, array('class' => 'elgg-form-settings'));
