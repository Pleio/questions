<?php
/**
 * Page handlers
 *
 * @package Questions
 *
 */

/**
 * Handles all question pages
 *
 * @param array $segments
 */
function questions_page_handler($segments) {
	elgg_push_breadcrumb(elgg_echo('questions'), "questions/all");

	$pages = dirname(dirname(__FILE__)) . "/pages/questions";

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

		case "todo":
			if (isset($segments[1]) && is_numeric($segments[1])) {
				set_input("group_guid", $segments[1]);
			}
			include "$pages/todo.php";
			break;

		case "edit":
			gatekeeper();
			set_input('guid', $segments[1]);
			include "$pages/edit.php";
			break;

		case "group":
			group_gatekeeper();
			set_input("group_guid", $segments[1]);
			$container = get_entity($segments[1]);
			if (!$container instanceof ElggGroup) {
				register_error(elgg_echo('questions:workflow:nogroup'));
				forward(REFERER);
			}

			switch ($segments[2]) {
				case "all":
					include "$pages/owner.php";
					break;
				case "workflow":
					if (!questions_workflow_enabled($container)) {
						register_error(elgg_echo('questions:workflow:notenabled'));
						forward(REFERER);
					}

					questions_expert_gatekeeper();
					$workflow = true;

					include "$pages/workflow.php";
					break;
				default:
					include "$pages/owner.php";
			}
			
			break;

		case "workflow":
			if (!questions_workflow_enabled()) {
				register_error(elgg_echo('questions:workflow:notenabled'));
				forward(REFERER);
			}

			questions_expert_gatekeeper();
			$workflow = true;
			
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
