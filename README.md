# Questions 2.0 for Elgg 1.8 #

Questions is a plugin for Elgg to provide your network with
Q&A functionality analagous to Quora, Stack Overflow, or Facebook Questions. Based on plugin of (ewinslow)[https://github.com/ewinslow/elgg-questions]. 

## Features ##

* Ask questions as an individual or to a group.
* Comment on questions + answers (ala StackOverflow).
* Like questions + answers.
* Assign site-wide experts.
* Create a workflow system to answer questions systematically with a group of people.

## Updating from version 1.* ##

The way correct answers are save in the databases has changed in this new version. After installing the new version of the plugin, make sure you run the update script from the command line by running:
    
    php scripts/migrate-old-corrects.php