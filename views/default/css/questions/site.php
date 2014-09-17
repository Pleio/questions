<?php 
/**
 * Plugin styling
 *
 * @package ElggQuestions
 */
?>

/* Begin of Questions plugin */

.questions-checkmark {
	width: 40px;
	height: 40px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>mod/questions/_graphics/checkmark.png) no-repeat;
}

.question-solution-time {
	border: 1px solid #CCC;
	padding: 0 5px;
	
	font-size: 80%;
	color: #CCC;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}

.question-solution-time-due {
	border-color: orange;
	color: orange;
}

.question-solution-time-late {
	border-color: red;
	color: red;
}

.question-listing-checkmark {
	background-position: 0 -126px;
}

span.question-workflow-status {
	color: blue;
	font-weight: normal;
	font-size: 2em;
	padding-right: 5px;
	vertical-align: sub;
}

span.question-workflow-status.check {
	color: black;
}

span.question-workflow-status.late {
	color:red;
}

div.question-workflow-switch {
	float:right;
}

div.question-workflow-overview-full {
	border-bottom:1px solid #cccccc;
	height:20px;
	padding-bottom:10px;
}

div.question-workflow-overview-full .overview-state {
	float:left;
	padding-right:40px;
}

div.question-workflow-overview-full .overview-element {
	float:left;
	padding-right:60px;
}

div.question-workflow-overview-right {
	float:right;
}

div.question-workflow-overview-right .overview-element {
	float:left;
	width: 100px;	
	padding-left: 10px;
	padding-right: 10px;		
}


/* End of Questions plugin */
