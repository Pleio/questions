<?php

$intanswer = $vars['entity'];
$phases = questions_get_phases_array();

$description = array(
  'name' => 'description',
  'id' => 'answer_description',
  'value' => elgg_get_sticky_value('intanswer', 'description', $intanswer->description),
);

echo elgg_view('input/longtext', $description);

if (!isset($intanswer->guid)) {
  $phase = array(
    'name' => 'phase_guid',
    'id' => 'answer_phase',
    'options_values' => $phases,
    'value' => $vars['current_phase_guid']
  );

  echo elgg_echo('questions:workflow:changephaseto');
  echo elgg_view('input/dropdown', $phase);
}

if ($vars['current_phase_guid'] == end(array_keys($phases))) {
  $display = 'block';
} else {
  $display = 'none';
}
echo '<div id="answer_frontend" style="display:' . $display . ';">';
echo elgg_view('input/checkbox', array('id'=>'answer_frontend_check', 'name'=>'answer_frontend', 'value'=>'1'));
echo elgg_echo('questions:workflow:answer:frontend');
echo '</div>';

echo '<div class="mts">';
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $intanswer->container_guid));
echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $intanswer->guid));
echo elgg_view('input/submit', array('value' => elgg_echo('submit')));
echo '</div>';
