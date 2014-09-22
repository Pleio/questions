<?php
/**
 * English language
 *
 * @package Questions
 */
$language = array(
	'answers' => 'Answers',
	'answers:addyours' => 'Add Your Answer',
	'item:object:answer' => "Answers",
	'item:object:question' => "Questions",
	'questions:menu:user_hover:make_expert' => "Make Questions expert",
	'questions:menu:user_hover:make_expert:confirm' => "Are you sure you wish to make this user a Questions expert of %s?",
	'questions:menu:user_hover:remove_expert' => "Remove Questions expert",
	'questions:menu:user_hover:remove_expert:confirm' => "Are you sure you wish to remove this user form the Questions experts of %s?",
	'questions:menu:entity:answer:mark' => "This is correct",
	'questions:menu:entity:answer:unmark' => "No longer correct",
	'questions:menu:filter:experts' => "Experts",
  'questions:menu:filter:todo' => "Todo",
  'questions:menu:filter:todo_group' => "Todo",
  'questions:cancomment' => 'Is is possible to comment on a question?',
  'questions:cancomment:description' => 'Comments are different from answers. Comments are replies on answers.',
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
	'questions:experts:title' => "Experts",
	'questions:experts:none' => "No experts have been assigned for %s yet.",
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
	'questions:answer:edit' => "Update answer",
	'questions:answer:checkmark:title' => "%s marked this as the correct answer on %s",
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
	'questions:settings:experts:edit' => "Experts can edit all questions",
	'questions:settings:experts:notify' => "Send experts a notification when a new question is placed",	
	'questions:settings:access:title' => "Access settings",
	'questions:settings:access:personal' => "What will be the access level for personal questions",
	'questions:settings:access:group' => "What will be the access level for group questions",
	'questions:settings:access:options:user' => "User defined",
	'questions:settings:access:options:group' => "Group members",  
	'questions:group_settings:title' => "Questions settings",
	'questions:group_settings:solution_time:description' => "Questions should be answered before this time expires. 0 for no limit.",
	'widget:questions:title' => "Questions",
	'widget:questions:description' => "You can view the status of your questions.",
	'widget:questions:content_type' => "Which questions to show",
  'questions:action:answer:save:error:container' => "You do not have permission to answer that question!",
	'questions:action:answer:save:error:body' => "A body is required",
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
  'questions:action:intanswer:save:nophase' => "No phase specified",
  'questions:action:intanswer:save:notimeworked' => "No time worked specified",
	'questions:action:toggle_expert:success:make' => "%s is now a questions expert for %s",
	'questions:action:toggle_expert:success:remove' => "%s is no longer a questions expert for %s",
	'questions:action:group_settings:success' => "The group settings were saved",
  'questions:workflow' => 'Workflow',
  'questions:menu:workflow' => 'Workflow',
  'questions:workflow:hours' => 'hours',
  'questions:workflow:nogroup' => 'Not a valid group',
  'questions:workflow:notenabled' => 'Workflow is not active',
  'questions:workflow:noaccess' => 'No access to workflow',
  'questions:workflow:intanswers' => 'internal answers',
  'questions:workflow:addyours' => 'Add internal answer to %s',
  'questions:workflow:changephaseto' => 'and change phase to',
  'questions:workflow:open:title' => 'Open',
  'questions:workflow:open:explanation' => 'This question is not yet opened in the workflow system. Click the button to open the question.',
  'questions:workflow:open' => 'Open question',
  'questions:workflow:open:success' => 'Question opened in workflow',
  'questions:workflow:answer:frontend' => 'Publish answer on the frontend',
  'questions:workflow:answer:publish:frontend:confirm' => 'Are you sure you want to publish the answer to the frontend?',
  'questions:workflow:phase:nochange:confirm' => 'Do you really not want to change the phase? In this case the question will not be forwarded to the next department.',
  'questions:workflow:email:subject' => 'New workflow question: %s',
  'questions:workflow:email:body' => 'A new message is added to the workflow. Please answer this question by visiting: %s',
  'questions:workflow:email:notification:sent' => 'Notification sent',    
  'questions:workflow:keep:closed' => 'Keep closed',
  'questions:settings:workflow:title' => 'Workflow system',
  'questions:settings:workflow:enable' => 'Activate',
  'questions:settings:workflow:enable:description' => 'With the workflow system questions can be answered systematically.',
  'questions:settings:workflow:phases' => 'Phases',
  'questions:settings:workflow:phases:description' => 'This setting allows you to configure the different phases of the workflow system. The minimal amount of phases is two. A new question is automatically placed in the last phase. When the question is opened in the workflow system, it automatically will be placed in the first phase.',
  'questions:settings:workflow:workingtimes' => 'Office hours',    
  'questions:settings:workflow:workingtimes:from' => 'From',
  'questions:settings:workflow:workingtimes:to' => 'To',
  'questions:workflow:action:phases:save:success' => 'The phase was saved.',
  'questions:workflow:action:phases:save:error' => 'The phase could not be saved.',  
  'questions:workflow:action:phases:save:email:error' => 'The given e-mailadress is not valid.',
  'questions:workflow:action:phases:delete:error' => 'The phase could not be deleted.',
  'questions:workflow:action:phases:delete:error:type' => 'The entity is not of the type phase.',
  'questions:workflow:action:phases:delete:error:guid' => 'The field guid is not given.',  
  'questions:intanswer:edit' => 'Edit internal answer',
  'questions:intanswer:unknown' => 'Unknown internal answer',
  'questions:workflow:newanswers' => 'New messages',
  'questions:workflow:nophase' => 'No phase',
  'questions:workflow:phase:edit' => 'Change phase',
  'questions:workflow:phase:name' => 'Name',
  'questions:workflow:phase:due' => 'Due time',
  'questions:workflow:timeworked' => 'Time worked on',
  'questions:workflow:phase:due:description' => 'When a question is longer in a phase then this time, the mark will color red.',
  'questions:workflow:phase:due:nolimit' => 'No limit',
  'questions:workflow:phase:email' => 'E-mail notification',
  'questions:workflow:phase:email:description' => 'An e-mail is send to this address when a question is placed in this phase.',
  'questions:workflow:closed' => 'workflow closed',
  'questions:phases:no_fields' => 'No phases created yet',
  'questions:settings:predefined_tags:title' => 'Predefined tags',
  'questions:workflow:switch:backend' => 'To backend',
  'questions:workflow:switch:frontend' => 'To frontend',  
  'questions:workflow:none' => 'None',
  'questions:workflow:publishedFront' => 'published on frontend',
  'questions:workflow:overview:manager' => 'Manager',
  'questions:workflow:overview:phase' => 'Phase',
  'questions:workflow:overview:timespan' => 'Duration',
  'questions:workflow:keep:closed:success' => 'Question is marked as closed',
  'admin:questions:csv_export' => 'Download Workflow CSV export',
  'questions:workflow:csv_export:description' => 'In this screen you can download an overview of all the questions and times in the workflow system. The CSV file contains the realised times per phase. When a question is answered in multiple cycles, for each cycle a line can be found in the CSV.',
  'questions:workflow:csv_export:download' => 'Download CSV export',  
  'questions:workflow:csv_export:timeunit' => 'Time unit',
  'questions:workflow:csv_export:hours' => 'Hours',
  'questions:workflow:csv_export:minutes' => 'Minutes',
  'questions:workflow:csv_export:seconds' => 'Seconds',
  'questions:question:notification:subject'=> 'A new question has been placed',
  'questions:answer:notification:subject'=> 'A new answer has been given',  
  'questions:notify:newresponse:subject' => 'A new response was given for: %s',
  'questions:notify:newresponse:message' => 'A new response was placed for the question %s.

  To view the response, go to:
  %s
  ',
  'friendlytimespan:seconds' => '%s seconds',
  'friendlytimespan:seconds:singular' => '%s second',
  'friendlytimespan:minutes' => '%s minutes',
  'friendlytimespan:minutes:singular' => '%s minute',
  'friendlytimespan:hours' => '%s hours',
  'friendlytimespan:hours:singular' => '%s hour',
  'Monday'=>'Monday',
  'Tuesday'=>'Tuesday',
  'Wednesday'=>'Wednesday',
  'Thursday'=>'Thursday',
  'Friday'=>'Friday',
  'Saturday'=>'Saturday',
  'Sunday'=>'Sunday'
);
add_translation("en", $language);