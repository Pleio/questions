<?php
/**
 * Add internal (workflow) answer form
 *
 * @package ElggQuestions
 */

$vars['entity'] = new ElggIntAnswer();
$vars['entity']->container_guid = $vars['container_guid'];
$vars['current_phase_guid'] = $vars['current_phase_guid'];

echo elgg_view('forms/object/intanswer/edit', $vars);
