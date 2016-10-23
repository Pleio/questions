<?php
/**
 * Edit internal (workflow) answer form
 *
 * @package ElggQuestions
 */

$intanswer = $vars['entity'];
$phases = questions_get_phases_array();
$worktimes = array(
  0 => '----',
  6*60 => elgg_echo('friendlytimespan:minutes', array(6)),
  12*60 => elgg_echo('friendlytimespan:minutes', array(12)),
  18*60 => elgg_echo('friendlytimespan:minutes', array(18)),
  24*60 => elgg_echo('friendlytimespan:minutes', array(24)),
  30*60 => elgg_echo('friendlytimespan:minutes', array(30)),
  36*60 => elgg_echo('friendlytimespan:minutes', array(36)),
  42*60 => elgg_echo('friendlytimespan:minutes', array(42)),
  48*60 => elgg_echo('friendlytimespan:minutes', array(48)),
  54*60 => elgg_echo('friendlytimespan:minutes', array(54)),
  60*60 => elgg_echo('friendlytimespan:minutes', array(60)),
  90*60 => elgg_echo('friendlytimespan:hours', array("1,5")),
  120*60 => elgg_echo('friendlytimespan:hours', array("2")),
  150*60 => elgg_echo('friendlytimespan:hours', array("2,5")),
  180*60 => elgg_echo('friendlytimespan:hours', array("3"))
);


$description = array(
  'name' => 'description',
  'id' => 'answer_description',
  'value' => elgg_get_sticky_value('intanswer', 'description', $intanswer->description),
);

echo elgg_view('input/longtext', $description);

if (!isset($intanswer->guid)) {
  $timeworked = array(
    'name' => 'timeworked',
    'id' => 'answer_timeworked',
    'options_values' => $worktimes
  );

  echo elgg_echo('questions:workflow:timeworked');
  echo elgg_view('input/legacy_dropdown', $timeworked);

  $phase = array(
    'name' => 'phase_guid',
    'id' => 'answer_phase',
    'options_values' => $phases,
    'value' => $vars['current_phase_guid']
  );

  echo elgg_echo('questions:workflow:changephaseto');
  echo elgg_view('input/legacy_dropdown', $phase);
}

if ($vars['current_phase_guid'] == end(array_keys($phases))) {
  $display = 'block';
} else {
  $display = 'none';
}
echo '<div id="answer_frontend" style="display:' . $display . ';">';
echo elgg_view('input/legacy_checkbox', array('id'=>'answer_frontend_check', 'name'=>'answer_frontend', 'value'=>'1'));
echo elgg_echo('questions:workflow:answer:frontend');
echo '</div>';

echo '<div class="mts">';
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $intanswer->container_guid));
echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $intanswer->guid));
echo elgg_view('input/submit', array('value' => elgg_echo('submit')));
echo '</div>';
