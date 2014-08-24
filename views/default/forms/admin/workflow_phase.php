<?php 
  
$form_title = elgg_echo('questions:workflow:phase:add');

if($vars["entity"]){
  $form_title = elgg_echo('questions:workflow:phase:edit');

  $guid = $vars["entity"]->guid;
  $name = $vars["entity"]->name;
  $due = $vars["entity"]->due;
} 

$formbody .= elgg_echo('questions:workflow:phase:name') . ":" . elgg_view('input/text', array('name' => 'name', "value" => $name));
$formbody .= "<br />";
$formbody .= elgg_echo('questions:workflow:phase:due') . ":" . elgg_view('input/text', array('name' => 'due', "value" => $due));
$formbody .= "<br />";

$formbody .= elgg_view('input/hidden', array('name' => 'guid', "value" => $guid));
$formbody .= elgg_view('input/submit', array('value' => elgg_echo('save')));

$form = elgg_view('input/form', array('body' => $formbody, 'action' => $vars['url'] . 'action/questions/admin/workflow_phase/add'));
    
?>
<div class="elgg-module elgg-module-inline" id="custom_fields_form">
  <div class="elgg-head">
    <h3>
      <?php echo $form_title; ?>
    </h3>
  </div>
  <div class="elgg-body">
    <?php echo $form; ?>
  </div>
</div>