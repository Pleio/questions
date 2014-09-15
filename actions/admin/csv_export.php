<?php
/**
 * Export CSV admin action
 *
 * @package ElggQuestions
 */

$phases = questions_get_phases_array();

// create a temp file
$fh = tmpfile();

$headers = array();
$headers[] = "question_title";
$headers[] = "question_description";
$headers[] = "question_time_created";
$headers[] = "number_answers";
$headers[] = "number_intanswers";

foreach ($phases as $phase) {
  $headers[] = $phase;
}

// headers
fwrite($fh, "\"" . implode("\";\"", $headers) . "\"" . PHP_EOL);


$options = array(
  'type' => 'object',
  'subtype' => QUESTION_OBJECT,
  'limit' => false
);

$questions = elgg_get_entities($options);

foreach ($questions as $question) {
  $values = array();
  
  $values[] = utf8_decode($question->title);
  $values[] = utf8_decode($question->description);
  $values[] = $question->time_created;
  $values[] = count($question->getAnswers());

  $intAnswers = $question->getIntAnswers();
  $values[] = count($answers);

  $totalPhaseTime = array();
  foreach ($intAnswers as $intAnswer) {
    $totalPhaseTime[$intAnswer->phase_guid] += $intAnswer->timeSpent;
  }

  foreach (array_keys($phases) as $phaseGuid) {
    $values[] = $totalPhaseTime[$phaseGuid];
  }

  fwrite($fh, "\"" . implode("\";\"", $values) . "\"" . PHP_EOL);
}

// read the csv in to a var before output
$contents = "";
rewind($fh);
while (!feof($fh)) {
  $contents .= fread($fh, 2048);
}

// cleanup the temp file
fclose($fh);

// output the csv
header("Content-Type: text/csv; charset=ISO-8859-1");
header("Content-Disposition: attachment; filename=\"export.csv\"");
header("Content-Length: " . strlen($contents));

echo $contents;
exit();