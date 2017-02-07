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
	color: green;
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

div.question-workflow-overview-right .header {
	font-weight:bold;
}

div.question-workflow-overview-left {
	float:left;
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

div.question-workflow-list .elgg-content {
	width: 550px;
}

div.question-workflow-list .elgg-tags {
	width: 550px;
}

div.elgg-module.collapsable .elgg-head h3:before {
	content: "\25BA";
	font-size: smaller;
	margin-right: 4px;
}
div.elgg-module.collapsable.collapsed .elgg-head h3:before {
	content: "\25BC";
}

div.elgg-module.collapsable .elgg-list {
	display:none;
}

.elgg-menu-workflow-filter .elgg-menu-parent {
	font-weight: bold;
}

.elgg-menu-workflow-filter a {
	color: black;
}

.elgg-menu-item-workflow-filter {
	position: relative;
	float: left;
	border-bottom: 0px none;
	display: inline-block;
}

.elgg-menu-item-workflow-filter > ul {
	width: 180px;
	position: absolute;
	display: none;
	border: 1px solid #CCC;
	padding: 5px;
	z-index: 1;
	background: white;
}

.elgg-menu-item-workflow-filter:hover > ul {
	display: block;
}

.questions-widget-button {
	float: right;
	margin-left: 4px;
	margin-bottom: 5px;
}

/* End of Questions plugin */
