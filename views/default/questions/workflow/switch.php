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
    echo elgg_view("output/url", array(
      "text" => $value,
      "href" => $url,
      "class" => "elgg-button elgg-button-submit"
    ));
    echo "&nbsp;";
    echo elgg_view("output/url", array(
      "text" => elgg_echo("questions:workflow:switch:overview"),
      "href" => "questions/workflow",
      "class" => "elgg-button elgg-button-submit"
    ));    
  ?>
</div>