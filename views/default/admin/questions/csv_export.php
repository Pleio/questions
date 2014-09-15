<?php
/**
 * Export CSV page
 *
 * @package ElggQuestions
 */

$title = elgg_echo('questions:csv_export');
$description = '<p>' . elgg_echo('questions:csv_export:description') .'</p>';
$params = array(
  'text' => elgg_echo('questions:csv_export:download'),
  'href' => 'action/questions/csv_export',
  'class' => 'elgg-button elgg-button-submit',
  'is_action' => true,
  'is_trusted' => true,
);
$description .= '<p>' . elgg_view('output/url', $params) . '</p>';

echo elgg_view_module('inline', $title, $description, array('class' => 'elgg-form-settings'));
