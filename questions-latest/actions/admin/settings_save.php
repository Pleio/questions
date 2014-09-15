<?php
/**
 * Save question plugin settings
 * 
 * @package Questions
 *
 */

$params = get_input('params');
$plugin_id = get_input('plugin_id');
$plugin = elgg_get_plugin_from_id($plugin_id);

$params['workflow_workingtimes_days'] = serialize($params['workflow_workingtimes_days']);

$result = false;
foreach ($params as $k => $v) {
  $result = $plugin->setSetting($k, $v);
  if (!$result) {
    register_error(elgg_echo('plugins:settings:save:fail', array($plugin_name)));
    forward(REFERER);
    exit;
  }
}

system_message(elgg_echo('plugins:settings:save:ok', array($plugin_name)));
forward(REFERER);