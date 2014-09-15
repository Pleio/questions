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
  <div class="question-workflow-overview-full">    
    <div class="overview-element">
      <?php echo elgg_echo("questions:workflow:overview:manager"); ?>
      <?php 
      if ($manager = $question->getWorkflowManager()) {
        echo elgg_view("output/url", array("href" => $manager->getURL(), "text" => $manager->name));
      } else {
        echo elgg_echo("questions:workflow:none");
      }

      ?>
    </div>

    <div class="overview-element">
      <?php echo elgg_echo("questions:workflow:overview:phase"); ?>
      <?php echo ($currentPhase = $question->getCurrentWorkflowPhase()) ? $currentPhase->name : elgg_echo("questions:workflow:none"); ?>
    </div>

    <div class="overview-element">
      <?php echo elgg_echo("questions:workflow:overview:timespan"); ?>
      <?php echo questions_get_friendly_timespan($question->getWorkflowLatestTotalTime()); ?>
    </div> 

    <div class="overview-state">
      <?php echo elgg_view('questions/workflow/status', array('question'=>$question)); ?>
    </div>

    <?php echo elgg_view('questions/workflow/switch', array('question'=>$question, 'to'=>'frontend')); ?>
    
  </div>
<?php else: ?>
  <div class="question-workflow-overview-right">
    <div class="overview-element">
      <?php 
      if ($manager = $question->getWorkflowManager()) {
        echo elgg_view("output/url", array("href" => $manager->getURL(), "text" => $manager->name));
      } else {
        echo elgg_echo("questions:workflow:none");
      }

      ?>
    </div>

    <div class="overview-element">
      <?php echo ($currentPhase = $question->getCurrentWorkflowPhase()) ? $currentPhase->name : elgg_echo("questions:workflow:none"); ?>
    </div>

    <div class="overview-element">
      <?php echo questions_get_friendly_timespan($question->getWorkflowLatestTotalTime()); ?>
    </div>     
  </div>
<?php endif ?>