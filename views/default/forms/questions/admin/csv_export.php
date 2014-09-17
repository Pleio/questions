<?php
/**
 * Export CSV form
 *
 * @package ElggQuestions
 */

$units = array(
  "hours" => elgg_echo("questions:workflow:csv_export:hours"),
  "minutes" => elgg_echo("questions:workflow:csv_export:minutes"),
  "seconds" => elgg_echo("questions:workflow:csv_export:seconds")
);

echo "<p>";
echo elgg_echo("questions:workflow:csv_export:timeunit") . ": ";

echo elgg_view("input/dropdown", array(
  "name" => "unit", 
  "options_values" => $units
));

echo "</p>";

echo elgg_view("input/submit", array(
  'text' => elgg_echo('questions:workflow:csv_export:download')
));