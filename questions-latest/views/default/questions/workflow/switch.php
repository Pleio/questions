<?php
/**
 * Workflow switch to frontend or backend button
 *
 * @package ElggQuestions
 */

$to = elgg_extract("to", $vars); 
$question = elgg_extract("question", $vars); 

if ($to == "backend") {
  $value = elgg_echo("questions:workflow:switch:backend");
  $url = $question->getWorkflowURL();
} else {
  $value = elgg_echo("questions:workflow:switch:frontend");
  $url = $question->getURL();
}

?>
<div class="question-workflow-switch">
  <?php
    $button = elgg_view("input/submit", array(
      "value" => $value
    ));
    
    echo elgg_view("input/form", array(
      "disable_security" => true,
      "action" => $url,
      "method" => "GET",
      "body" => $button
    ));
  ?>
</div>