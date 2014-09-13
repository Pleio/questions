<?php 
/**
 * Open workflow form
 *
 * @package ElggQuestions
 */

$question = elgg_extract("question", $vars); 
?>

<p>
  <?php echo elgg_echo("questions:workflow:open:explanation"); ?>
</p>

<?php
$open = elgg_view("input/submit", array(
  "value" => elgg_echo("questions:workflow:open")
));

echo elgg_view("input/form", array(
  "action"=> $vars["url"] . "/action/object/question/workflow_open?guid=" . $question->guid,
  "body"=>$open
));

if ($question->hasNewAnswers()) {
  $keep_closed = elgg_view("input/submit", array(
    "value"=>elgg_echo("questions:workflow:keep:closed")
  ));
  echo elgg_view("input/form", array(
    "action"=> $vars["url"] . "/action/object/question/workflow_keep_closed?guid=" . $question->guid,
    "body"=>$keep_closed
  ));
}