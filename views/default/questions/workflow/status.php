<?php
/**
 * Workflow question status
 * Displays the question status image (red,green,black)
 * 
 * @package ElggQuestions
 */

$question = elgg_extract("question", $vars); 

if ($question->isWorkflowTooLate()) {
  echo "<span class='question-workflow-status late'>";
} elseif ($question->hasNewAnswers()) {
  echo "<span class='question-workflow-status check'>";
} else {
  echo "<span class='question-workflow-status'>";
}

echo "&bull;</span>";
?>