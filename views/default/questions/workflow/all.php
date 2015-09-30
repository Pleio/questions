<?php 
/**
 * Header for all questions workflow overview
 *
 * @package ElggQuestions
 */
?>
<div class="elgg-image-block clearfix">
  <div class="question-workflow-overview-left">
     <?php echo elgg_view_menu('workflow_filter'); ?>
  </div>
  <div class="question-workflow-overview-right">
    <div class="overview-element header">
      <?php echo elgg_echo("questions:workflow:overview:manager"); ?>
    </div>
    <div class="overview-element header">
      <?php echo elgg_echo("questions:workflow:overview:phase"); ?>
    </div>
    <div class="overview-element header">
      <?php echo elgg_echo("questions:workflow:overview:timespan"); ?>
    </div>
  </div>
</div>