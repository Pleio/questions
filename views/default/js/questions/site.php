<?php
/**
 * Javascript file for this plugin. This is added to the global site JS.
 *
 * @package ElggQuestions
 */
?>
//<script>
elgg.provide("elgg.questions");

elgg.questions.init = function() {
	var currentPhase = $("#answer_phase").val();
	
	$("#answer_phase").change(function() {
		if ($(this).val() == $("#answer_phase option:last-child").val()) {
			$("#answer_frontend").show();
		} else {
			$("#answer_frontend_check").attr('checked', false);
			$("#answer_frontend").hide();
		}
	});

	$(".elgg-form-object-intanswer-add").submit(function() {
		if ($("#answer_phase").val() == currentPhase) {
			if (!confirm(elgg.echo("questions:workflow:phase:nochange:confirm"))) {
				return false;
			}
		}

		if ($("#answer_frontend_check").attr('checked')) {
			if (!confirm(elgg.echo("questions:workflow:answer:publish:frontend:confirm"))) {
				return false;
			}
		}

		return true;
	});

	$("form.questions-validate-container").submit(function() {
		var result = true;
		
		// validate there is a select
		if ($("#questions-container-guid").length) {
			if ($("select[name='container_guid']").val() == "") {
				result = false;

				alert(elgg.echo("questions:edit:question:container:select"));
			}
		}

		return result;
	});

	$("#questions-move-to-discussions").live("click", function() {
		var confirm_text = $(this).attr("rel");

		if (confirm(confirm_text)) {
			var $form = $(this).parents("form");

			$form.attr("action", elgg.get_site_url() + "action/object/question/move_to_discussions");
			$form.submit();
		}
	});
}

//register init hook
elgg.register_hook_handler("init", "system", elgg.questions.init);
