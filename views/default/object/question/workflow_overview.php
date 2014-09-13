<?php
/**
 * Workflow question overview
 * Displays the question manager, time spent en current phase.
 *
 * @package ElggQuestions
 */

$question = elgg_extract("question", $vars); 
$full_view = elgg_extract("full_view", $vars);
?>

<?php if ($full_view): ?>
  <div class="question-workflow-overview full">
  <?php echo elgg_view('object/question/workflow_status', array('question'=>$question)); ?>
<?php else: ?>
  <div class="question-workflow-overview right">
<?php endif ?>

  <div class="question-workflow-overview manager">
    <?php
    if ($manager = $question->getWorkflowManager()) {
      echo $manager->name;
    } else {
      echo elgg_echo("questions:workflow:none");
    }
    ?>
  </div>

  <div class="question-workflow-overview phase">
    <?php 
    if ($currentPhase = $question->getCurrentWorkflowPhase()) {
      echo $currentPhase->name;
    } else {
      echo elgg_echo("questions:workflow:none");
    }
    ?>
  </div>

  <div class="question-workflow-overview timespan">
    <?php echo questions_get_friendly_timespan($question->getWorkflowLatestTotalTime()); ?>
  </div>

</div>