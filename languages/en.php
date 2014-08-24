<?php
add_translation('en', array(
	'answers' => 'Answers',
	'answers:addyours' => 'Add Your Answer',
	
	/**
	 * General stuff
	 */
	'item:object:answer' => "Answers",
	'item:object:question' => "Questions",
	
	/**
	 * Menu items
	 */
	'questions:menu:user_hover:make_expert' => "Make Questions expert",
	'questions:menu:user_hover:make_expert:confirm' => "Are you sure you wish to make this user a Questions expert of %s?",
	'questions:menu:user_hover:remove_expert' => "Remove Questions expert",
	'questions:menu:user_hover:remove_expert:confirm' => "Are you sure you wish to remove this user form the Questions experts of %s?",
	
	'questions:menu:entity:answer:mark' => "This is correct",
	'questions:menu:entity:answer:unmark' => "No longer correct",

	'questions:menu:filter:updated' => "Recently updated",
	'questions:menu:filter:todo' => "Todo",
	'questions:menu:filter:todo_group' => "Group Todo",
	'questions:menu:filter:experts' => "Experts",
	
	'river:create:object:question' => '%s asked question %s',
	'river:create:object:answer' => '%s provided an answered for the question %s',
		
	'questions' => 'Questions',
	'questions:asked' => 'Asked by %s',
	'questions:answered' => 'Last answered by %s %s',
	'questions:answered:correct' => 'Correct answer provided by %s %s',

	'questions:everyone' => 'All Questions',
	'questions:add' => 'Add a Question',
	'questions:todo' => 'Todo',
	'questions:todo:none' => 'There is nothing Todo, keep up the good work!',
	'questions:updated' => 'Recently updated',
	'questions:owner' => "%s's Questions",
	'questions:none' => "No questions have been submitted yet.",
	'questions:group' => 'Group questions',
	'questions:enable' => 'Enable group questions',

	'questions:edit:question:title' => 'Question',
	'questions:edit:question:description' => "Details",
	'questions:edit:question:container' => "Where should this question be listed",
	'questions:edit:question:container:select' => "Please select a group",
	'questions:edit:question:move_to_discussions' => "Move to discussions",
	'questions:edit:question:move_to_discussions:confirm' => "Are you sure you wish to move this questions to the discussions? This can't be undone!!",
	
	/**
	 * experts page
	 */
	'questions:experts:title' => "Experts",
	'questions:experts:none' => "No experts have been assigned for %s yet.",
	
	/**
	 * expert notifications
	 */
	'questions:notify_experts:create:subject' => "A new question was asked",
	'questions:notify_experts:create:message' => "Hi %s

The question: %s was asked.

To answer the question go here:
%s",
	'questions:notify_experts:moving:subject' => "A question was moved",
	'questions:notify_experts:moving:message' => "Hi %s

The question: %s was moved so you'll have to answer it.

To answer the question go here:
%s",
	
	'questions:daily:notification:subject' => "Daily questions workload overview",
	'questions:daily:notification:message:more' => "View more",
	'questions:daily:notification:message:overdue' => "The following questions are overdue",
	'questions:daily:notification:message:due' => "The following questions need to be solved today",
	'questions:daily:notification:message:new' => "New questions asked",
	/**
	 * answers
	 */
	'questions:answer:edit' => "Update answer",
	'questions:answer:checkmark:title' => "%s marked this as the correct answer on %s",
		
	/**
	 * plugin settings
	 */
	'questions:settings:general:title' => "General settings",
	'questions:settings:general:close' => "Close a question when it gets a marked answer",
	'questions:settings:general:close:description' => "When an answer of a question is marked as the correct answer, close the question. This will mean no more answers can be given.",
	'questions:settings:general:solution_time' => "Set a default solution time in days",
	'questions:settings:general:solution_time:description' => "Questions should be answered before this time expires, groups can override this setting with their own time limit. 0 for no limit.",
	'questions:settings:general:limit_to_groups' => "Limit questions to group context only",
	'questions:settings:general:limit_to_groups:description' => "If set to 'yes', questions can no longer be made in the personal context.",
	
	'questions:settings:experts:title' => "Q&A expert settings",
	'questions:settings:experts:enable' => "Enable expert roles",
	'questions:settings:experts:enable:description' => "Experts have special privilages and can be assigned by site administrators and group owners.",
	'questions:settings:experts:answer' => "Only experts can answer a question",
	'questions:settings:experts:mark' => "Only experts can mark an answer as the correct answer",
	
	'questions:settings:access:title' => "Access settings",
	'questions:settings:access:personal' => "What will be the access level for personal questions",
	'questions:settings:access:group' => "What will be the access level for group questions",
	'questions:settings:access:options:user' => "User defined",
	'questions:settings:access:options:group' => "Group members",
	
	/**
	 * group settings
	 */
	'questions:group_settings:title' => "Questions settings",
	'questions:group_settings:solution_time:description' => "Questions should be answered before this time expires. 0 for no limit.",
	
	/**
	 * Widgets
	 */

	'widget:questions:title' => "Questions",
	'widget:questions:description' => "You can view the status of your questions.",
	'widget:questions:content_type' => "Which questions to show",
		
	/**
	 * Actions
	 */
	
	'questions:action:answer:save:error:container' => "You do not have permission to answer that question!",
	'questions:action:answer:save:error:body' => "A body is required: %s, %s",
	'questions:action:answer:save:error:save' => "There was a problem saving your answer!",
	'questions:action:answer:save:error:question_closed' => "The question you're trying to answer is already closed!",
	
	'questions:action:answer:toggle_mark:error:not_allowed' => "You're not allowed to mark answers as the correct answer",
	'questions:action:answer:toggle_mark:error:duplicate' => "There already is a correct answer to this question",
	'questions:action:answer:toggle_mark:success:mark' => "The answer is marked as the correct answer",
	'questions:action:answer:toggle_mark:success:unmark' => "The answer is no longer marked as the correct answer",
	
	'questions:action:question:save:error:container' => "You do not have permission to answer that question!",
	'questions:action:question:save:error:body' => "A title and description are required: %s, %s, %s",
	'questions:action:question:save:error:save' => "There was a problem saving your question!",
	'questions:action:question:save:error:limited_to_groups' => "Questions are limited to group, please select a group",
	
	'questions:action:question:move_to_discussions:error:move' => "You're not allowed to move questions to discussions",
	'questions:action:question:move_to_discussions:error:topic' => "An error occured while creating the discussion topic, please try again",
	'questions:action:question:move_to_discussions:success' => "The questions was moved to a discussion topic",
	
	'questions:action:toggle_expert:success:make' => "%s is now a questions expert for %s",
	'questions:action:toggle_expert:success:remove' => "%s is no longer a questions expert for %s",
	
	'questions:action:group_settings:success' => "The group settings were saved",
));
