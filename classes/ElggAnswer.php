<?php

class ElggAnswer extends ElggObject {
	const SUBTYPE = "answer";

	function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	public function getURL() {
		$container_entity = $this->getContainerEntity();
		$url = $container_entity->getURL() . "#answer-" . $this->guid;
		return $url;
	}
	
	public function getCorrectAnswerMetadata() {
		$result = false;
		
		$options = array(
			"metadata_name" => "correct_answer",
			"guid" => $this->guid
		);
		
		$metadata = elgg_get_metadata($options);
		if ($metadata) {
			$result = $metadata[0];
		}
		
		return $result;
	}
	
	/**
	 * Mark an answer as the correct answer for this question
	 */
	public function markAsCorrect() {
		// first set the mark
		$this->correct_answer = true;
		
		// depending of the plugin settings, we also need to close the question
		if (questions_close_on_marked_answer()) {
			$question = $this->getContainerEntity();
			
			$question->close();
		}
	}
	
	/**
	 * This answer is nog longer the correct answer for this question
	 */
	public function undoMarkAsCorrect() {
		unset($this->correct_answer);
		
		// don't forget to reopen the question
		$question = $this->getContainerEntity();
			
		$question->reopen();
	}
}
