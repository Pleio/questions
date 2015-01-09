<?php
	$question = elgg_extract("question", $vars);
?>

<div>
	<?php echo elgg_echo("questions:workflow:claim", array($question->title)); ?>
</div>

<?php 
echo elgg_view("output/url", array(
	"href" => "action/object/question/workflow_claim?guid=" . get_input("guid"),
	"text" => elgg_echo("questions:workflow:claim:yes"),
	"is_action" => true,
	"class" => "elgg-button-action elgg-button"
));
echo "&nbsp;&nbsp;";
echo elgg_view("output/url", array(
	"href" => $question->getWorkflowURL(),
	"text" => elgg_echo("questions:workflow:claim:no"),
	"class" => "elgg-button-action elgg-button"
));

?>

