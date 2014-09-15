<?php 
/**
 * Display admin workflow phase list
 *
 * @package ElggQuestions
 */

$options = array(
    "type" => "object",
    "subtype" => QUESTIONS_WORKFLOW_PHASE,
    "limit" => false,
    "order_by_metadata" => array(array('name' => 'order', 'direction' => "asc", 'as' => "integer")),
    "owner_guid" => elgg_get_site_entity()->guid,
    "pagination" => false,
    "full_view" => false
  );

$list = elgg_list_entities_from_metadata($options); 

if(empty($list)){
  $list = elgg_echo("questions:phases:no_fields");
}
  
?>
<div class="elgg-module elgg-module-inline">
  <div class="elgg-body" id="questions-workflow-phases-ordering">
    <?php echo $list; ?>
  </div>
  <br /><?php echo elgg_view("output/url", array("text" => elgg_echo("add"), "href" => $vars["url"] . "questions/admin/forms/phase_field", "class" => "elgg-button elgg-button-action questions-popup"));?></div>
</div>