<?php

$intanswer = $vars['entity'];

$description = array(
  'name' => 'description',
  'id' => 'answer_description',
  'value' => elgg_get_sticky_value('intanswer', 'description', $intanswer->description),
);

echo elgg_view('input/longtext', $description);

if (!isset($intanswer->guid)) {
  $phases = questions_get_phases();
  unset($phases[key($phases)]);

  $phase = array(
    'name' => 'phase_guid',
    'id' => 'answer_phase',
    'options_values' => $phases,
    'value' => $vars['current_phase_guid']
  );

  echo elgg_echo('questions:workflow:phase');
  echo elgg_view('input/dropdown', $phase);
}

echo '<div class="mts">';
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $intanswer->container_guid));
echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $intanswer->guid));
echo elgg_view('input/submit', array('value' => elgg_echo('submit')));
echo '</div>';
