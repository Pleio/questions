<?php 
/**
 * Add workflow form
 *
 * @package ElggQuestions
 */

$form_title = elgg_echo('questions:workflow:phase:add');

$due_options = array(
  0 => elgg_echo("questions:workflow:phase:due:nolimit"),
  60*15 => elgg_echo("friendlytimespan:minutes", array("15")),
  60*30 => elgg_echo("friendlytimespan:minutes", array("30")),
  60*45 => elgg_echo("friendlytimespan:minutes", array("45")),
  3600 => elgg_echo("friendlytimespan:hours:singular", array("1")),
);

for ($i = 2; $i <= 40; $i++) {
  $due_options[3600*$i] = elgg_echo("friendlytimespan:hours", array($i));
}

if($vars["entity"]){
  $form_title = elgg_echo('questions:workflow:phase:edit');

  $guid = $vars["entity"]->guid;
  $name = $vars["entity"]->name;
  $due = $vars["entity"]->due;
  $email = $vars["entity"]->email;
} 

$formbody .= elgg_echo('questions:workflow:phase:name') . ":" . elgg_view('input/text', array('name' => 'name', "value" => $name));
$formbody .= "<br /><br />";
$formbody .= elgg_echo('questions:workflow:phase:due') . ":" . elgg_view('input/dropdown', array('name' => 'due', "value" => $due, "options_values" => $due_options));
$formbody .= "<div class=\"elgg-subtext\">" . elgg_echo('questions:workflow:phase:due:description') . "</div>";
$formbody .= "<br />";
$formbody .= elgg_echo('questions:workflow:phase:email') . ":" . elgg_view('input/text', array('name' => 'email', "value" => $email));
$formbody .= "<div class=\"elgg-subtext\">" . elgg_echo('questions:workflow:phase:email:description') . "</div>";
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