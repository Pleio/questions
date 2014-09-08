<?php

/**
 * Handles all question pages
 *
 * @param array $segments
 */
function questions_page_handler($segments) {
	elgg_push_breadcrumb(elgg_echo('questions'), "questions/all");

	$pages = dirname(dirname(__FILE__)) . "/pages/questions";
	set_input('workflow', false);


	switch ($segments[0]) {
		case "all":
			include "$pages/all.php";
			break;
			
		case "owner":
			include "$pages/owner.php";
			break;

		case "view":
			set_input('guid', $segments[1]);
			include "$pages/view.php";
			break;

		case "add":
			gatekeeper();
			include "$pages/add.php";
			break;

		case "edit":
			gatekeeper();
			set_input('guid', $segments[1]);
			include "$pages/edit.php";
			break;

		case "group":
			group_gatekeeper();
			include "$pages/owner.php";
			break;

		case "workflow":
			questions_expert_gatekeeper();
			set_input('workflow', true);

			switch ($segments[1]) {
				case "view":
					set_input('guid', $segments[2]);
					include "$pages/view.php";
					break;
					
				default:
					include "$pages/workflow.php";				
			}

			break;
			
		case "experts":
			if (isset($segments[1]) && is_numeric($segments[1])) {
				elgg_set_page_owner_guid($segments[1]);
			}
			include "$pages/experts.php";
			break;

		case "admin":
			if ($segments[1] == "forms" && $segments[2] == "phase_field") {
				set_input('guid', $segments[3]);
				include dirname(dirname(__FILE__))  . "/pages/forms/phase_field.php";
			} else {
				return false;
			}
			break;

		default:
			forward("questions/all");
			return false;
	}

	return true;
}

/**
 * Handles all answer pages
 *
 * @param array $segments
 */
function answers_page_handler($segments) {
	elgg_push_breadcrumb(elgg_echo('questions'), "questions/all");

	$pages = dirname(dirname(__FILE__)) . "/pages/answers";

	switch ($segments[0]) {
		case "edit":
			gatekeeper();
			set_input('guid', $segments[1]);
			include "$pages/edit.php";
			break;
		default:
			forward("questions/all");
			return false;
	}

	return true;
}

/**
 * Handles all intanswer pages
 *
 * @param array $segments
 */
function intanswers_page_handler($segments) {
	elgg_push_breadcrumb(elgg_echo('questions'), "questions/all");

	$pages = dirname(dirname(__FILE__)) . "/pages/intanswers";

	switch ($segments[0]) {
		case "edit":
			gatekeeper();
			set_input('guid', $segments[1]);
			include "$pages/edit.php";
			break;
		default:
			forward("questions/all");
			return false;
	}

	return true;
}
