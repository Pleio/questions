<?php
/**
 * Workflow question object view
 * Button and explanation to open the question or keep the question closed.
 * 
 * @package ElggQuestions
 */
?>
<?php 
  $question_guid = elgg_extract("question_guid", $vars); 
?>
<p><?php echo elgg_echo("questions:workflow:open:explanation"); ?></p>
<?php
echo elgg_view("input/hidden", array("name" => "guid", "value" => $question_guid));
echo elgg_view("input/button", array("value" => elgg_echo("questions:workflow:open")));
echo elgg_view("input/button", array("value" => elgg_echo("questions:workflow:keep:closed")));
