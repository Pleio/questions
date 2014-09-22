<?php
/**
 * Export CSV admin action
 *
 * @package ElggQuestions
 */

access_show_hidden_entities(true);
$phases = questions_get_phases_array();
access_show_hidden_entities(false);


if (get_input('unit') == "hours") {
  $timeUnit = 3600;
} elseif (get_input('unit') == "minutes") {
  $timeUnit = 60;
} else {
  $timeUnit = 1;
}

function round_timeunit($input, $timeUnit) {
  return number_format($input / $timeUnit, 2, ",", "");
}

// create a temp file
$fh = tmpfile();

$headers = array();
$headers[] = "question_title";
$headers[] = "question_url";
$headers[] = "question_creator";
$headers[] = "question_time_created";
$headers[] = "workflow_status";
$headers[] = "number_answers";
$headers[] = "number_intanswers";
$headers[] = "cycle_number";

foreach ($phases as $phase) {
  $headers[] = $phase . " cycletime";
  $headers[] = $phase . " worktime";
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
  $values[] = utf8_decode($question->getURL());
  $values[] = utf8_decode($question->getOwnerEntity()->name);
  $values[] = date("d-m-Y H:i:s", $question->time_created);
  $values[] = $question->isWorkflowOpen() ? "open" : "closed";
  $values[] = count($question->getAnswers());

  $intAnswers = array_reverse($question->getIntAnswers());
  $values[] = count($intAnswers);

  
  $totalPhaseTimes = array();
  $totalPhaseWorkTimes = array();
  $cycles = array();
  $workCycles = array();

  // sum up all time realisations for the cycles
  foreach ($intAnswers as $intAnswer) {
    $totalPhaseTimes[$intAnswer->phase_guid] += $intAnswer->timeSpent;
    $totalPhaseWorkTimes[$intAnswer->phase_guid] += $intAnswer->timeWorked;

    if (isset($intAnswer->workflowCloseCycle)) {
      $cycles[] = $totalPhaseTimes;
      $workCycles[] = $totalPhaseWorkTimes;

      $totalPhaseTimes = array();
      $totalPhaseWorkTimes = array();
    }
  }
  
  // determine if to append the last row
  if (count($totalPhaseTimes) > 0) {
    $cycles[] = $totalPhaseTimes;
    $workCycles[] = $totalPhaseWorkTimes;
  }

  // generate a row in csv for each cycle
  $i = 1;
  foreach ($cycles as $key => $cycle) {
    $currentCycleTimes = array();

    foreach (array_keys($phases) as $phaseGuid) {
      $currentCycleTimes[] = round_timeunit($cycle[$phaseGuid], $timeUnit);
      $currentCycleTimes[] = round_timeunit($workCycles[$key][$phaseGuid], $timeUnit);
    }
    
    $cycleValues = array();
    $cycleValues = $values;
    $cycleValues[] = $i;
    $cycleValues = array_merge($cycleValues, $currentCycleTimes);

    fwrite($fh, "\"" . implode("\";\"", $cycleValues) . "\"" . PHP_EOL);
    $i++;
  }
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