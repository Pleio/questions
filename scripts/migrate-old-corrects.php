<?php
/**
 * Perform migration of old correct question answer labels to new ones
 *
 * @package Questions
 */

/*if (php_sapi_name() !== 'cli') {
  throw new Exception('This script must be run from the CLI.');
}*/

// Configure with "main site". Needed so subsite_manager can identify our instance.

// Production
$_SERVER["HTTP_HOST"] = "www.pleio.nl";
$_SERVER["HTTPS"] = true;

// Development
//$_SERVER["HTTP_HOST"] = "pleio.localhost.nl";

require_once(dirname(dirname(dirname(__FILE__))) . "/../engine/start.php");
$ia = elgg_set_ignore_access(true);

$sites = subsite_manager_get_subsites(0);

foreach($sites as $site) {
  echo "[Subsite] " . $site->name . "\n";

  $options = array(
    'metadata_names' => array('correct_answer'),
    'metadata_values' => array('1'),
    'site_guid' => $site->guid,
    'limit' => false
  );

  $batch = new ElggBatch('elgg_get_entities_from_metadata', $options);
  foreach($batch as $answer) { 
    $question = $answer->getContainerEntity();
    
    if(add_entity_relationship($question->guid, "correctAnswer", $answer->guid)) {
      unset($answer->correct_answer);
      $answer->save();    

      echo "[Answer] With GUID " . $answer->guid . " updated.\n";    
    }
  }

}

elgg_set_ignore_access($ia);