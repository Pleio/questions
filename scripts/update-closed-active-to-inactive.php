<?php
/**
 * Perform migration of old correct question answer labels to new ones
 *
 * @package Questions
 */

set_time_limit(0);

if (php_sapi_name() !== 'cli') {
  throw new Exception('This script must be run from the CLI.');
}

// Configure with "main site". Needed so subsite_manager can identify our instance.

// Production
$_SERVER["HTTP_HOST"] = "ffd.pleio.dev";
$_SERVER["HTTPS"] = false;

// Development
//$_SERVER["HTTP_HOST"] = "pleio.localhost.nl";

require_once(dirname(dirname(dirname(__FILE__))) . "/../engine/start.php");
$ia = elgg_set_ignore_access(true);

$options = array(
  'type' => 'object',
  'subtype' => 'question',
  'limit' => false
);

$metastring_id = get_metastring_id('workflow_lastaction');
$dbprefix = elgg_get_config('dbprefix');

$batch = new ElggBatch('elgg_get_entities', $options);
foreach($batch as $question) {
  if ($question->workflowLastView && $question->workflowLastView < $question->last_action) {
    echo "[Moved to inactive] " . $question->title . PHP_EOL;
    $question->workflowLastView = time();
    $question->save();
  }
}

elgg_set_ignore_access($ia);