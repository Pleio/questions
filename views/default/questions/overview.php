<?php
/**
 * Frontend question overview
 * Displays the switch button
 *
 * @package ElggQuestions
 */

$question = elgg_extract("question", $vars); 
?>

<div class="question-workflow-overview-full">
  <?php echo elgg_view('questions/workflow/switch', array('question'=>$question, 'to'=>'backend')); ?>
</div>