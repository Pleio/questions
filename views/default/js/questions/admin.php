<?php
/**
 * Javascript admin file for this plugin. This is added to the global site JS.
 *
 * @package ElggQuestions
 */
?>
//<script>
$(document).ready(function(){
  $('#questions-workflow-phases-ordering').sortable({
      update: function(event, ui) { 
        questions_workflow_phases_reorder();            
      },
      opacity: 0.6,
      tolerance: 'pointer',
      items: 'li'
  });


  $(".questions-popup").fancybox();  
});

function questions_workflow_phases_reorder(){
  var strArray = $('#questions-workflow-phases-ordering').sortable('serialize');
  $.post(elgg.security.addToken('<?php echo $vars['url'];?>action/questions/admin/workflow_phase/reorder?'), strArray);
}

function questions_workflow_phase_remove(guid) {
  if(confirm(elgg.echo("questions:workflow:actions:delete:confirm"))){
    $.getJSON(elgg.security.addToken('<?php echo $vars['url']; ?>action/questions/admin/workflow_phase/delete?guid=' + guid)).done(function(data){
      if(data['status'] == 0){
        $('#questions_workflow_phase_' + guid).hide('slow').parent().remove();
        questions_workflow_phases_reorder();
      } else {
        alert(elgg.echo("questions:workflow:actions:delete:error"));
      }
    });
  }
}