<?php

class ElggQuestion extends ElggObject {
	const SUBTYPE = 'question';

	/**
	 * Intialize the ElggQuestion class
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	/**
	 * Get the answers of the question
	 *
	 * @return array|ElggAnswer answer of to the question
	 */
	public function getAnswers(array $options = array()) {
		$defaults = array(
			'order_by' => 'time_created desc',
		);

		$overrides = array(
			'type' => 'object',
			'subtype' => 'answer',
			'container_guid' => $this->guid,
		);

		return elgg_get_entities(array_merge($defaults, $options, $overrides));
	}

	/**
	 * Get the latest answer of the question
	 *
	 * @return ElggAnswer latest answer of to the question
	 */
	public function getLatestAnswer() {
		$settings = array(
			'order_by' => 'time_created desc',
			'type' => 'object',
			'subtype' => 'answer',
			'container_guid' => $this->guid,			
			'limit' => 1
		);

		return current(elgg_get_entities($settings));
	}

	/**
	 * Get the internal answers of the question
	 *
	 * @return array|ElggIntAnswer answer of the question
	 */
	public function getIntAnswers(array $options = array()) {
		$defaults = array(
			'order_by' => 'time_created desc',
		);

		$overrides = array(
			'type' => 'object',
			'subtype' => 'intanswer',
			'container_guid' => $this->guid,
		);

		return elgg_get_entities(array_merge($defaults, $options, $overrides));		
	}

	/**
	 * Are there new (untracked) answers?
	 *
	 * @return bool
	 */
	public function hasNewAnswers() {
		if ($this->currentPhase) {
			return false;
		} 

		if ($this->workflowLastView) {
			if ($this->workflowLastView > $this->getLatestAnswer()->time_created) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Get the URL of the question
	 *
	 * @return string the URL of the question
	 */
	public function getURL() {
		$url = "questions/view/" . $this->guid . "/" . elgg_get_friendly_title($this->title);
		return elgg_normalize_url($url);
	}

	/**
	 * Get the correct answer
	 *
	 * @return bool|ElggAnswer the answer or false if non are marked
	 */
	public function getCorrectAnswer() {
		$correct = $this->getEntitiesFromRelationship("correctAnswer",false,1);
		if ($correct) {
			return $correct[0];			
		}

		return null;
	}
	
	/**
	 * Set the status of the question.
	 * 
	 * This can be
	 * - 'open'
	 * - 'closed'
	 *
	 * @return void
	 */
	public function setStatus($status) {
		if (in_array($status, array('open','closed'))) {
			$this->status = $status;
			return true;
		}

		return false;
	}
	
	/**
	 * Get the current status of the question.
	 *
	 * This can be
	 * - 'open'
	 * - 'closed'
	 *
	 * @return string the current status
	 */
	public function getStatus() {
		if ($this->status) {
			return $this->status;
		}

		return "open";
	}

	/**
	 * Get the workflow URL of the question
	 *
	 * @return string the URL of the question in the workflow
	 */
	public function getWorkflowURL() {
		$url = "questions/workflow/view/" . $this->guid . "/" . elgg_get_friendly_title($this->title);
		return elgg_normalize_url($url);
	}

	/**
	 * Check if workflow is open
	 *
	 * @return bool
	 */
	public function isWorkflowOpen() {
		return isset($this->currentPhase);
	}

	/**
	 * Get current workflow phase
	 *
	 * @return QuestionsWorkflowPhase
	 */
	public function getCurrentWorkflowPhase() {
		if (!$this->isWorkflowOpen()) {
			return false;
		}

		return get_entity($this->currentPhase);
	}

	/**
	 * Get the total number of hours a question is open
	 *
	 * @return int hours
	 */
	public function getWorkflowLatestTotalTime() {
		if ($this->isWorkflowOpen()) {
			return round(questions_time_diff($this->workflowStart, time()));
		} else {
			if ($this->totalAnswerTimes) {
				$totalAnswerTimes = unserialize($this->totalAnswerTimes);
				return end($totalAnswerTimes);
			} else {
				return 0;
			}
		}
	}

	/**
	 * Append a total time to question timings
	 *
	 * @return int number of total times
	 */
	public function appendWorkflowTotalTime($totalTime) {
		if ($this->totalAnswerTimes) {
			$totalAnswerTimes = unserialize($this->totalAnswerTimes);
		} else {
			$totalAnswerTimes = array();
		}

		array_push($totalAnswerTimes, $totalTime);
 		$this->totalAnswerTimes = serialize($totalAnswerTimes);

		return true;
	}

	/**
	 * Change phase of the workflow
	 *
	 * @return bool
	 */
	public function changeWorkflowPhase($phaseGuid) {
		$availablePhases = questions_get_phases($groupGuid);

		if (!isset($this->currentPhase)) {
			return false;
		}

		$phase = $availablePhases[$phaseGuid];
		if ($phase->email && $this->currentPhase != $phaseGuid) {
			questions_send_workflow_notification($this, $phase);
		}

		if ($phaseGuid == end($availablePhases)->guid) {
			$this->setWorkflowClosed();
		} else {
			$this->currentPhase = $phaseGuid;
		}

		return true;
	}

	/**
	 * Open workflow for question and change phase to the first phase
	 *
	 * @return bool
	 */
	public function setWorkflowOpen() {
		if (!isset($this->currentPhase)) {
			$phases = questions_get_phases();

			$this->currentPhase = current($phases)->guid;
			$this->workflowStart = time();

			return true;
		}

		return false;
	}

	/**
	 * Close workflow for question
	 *
	 * @return bool
	 */
	public function setWorkflowClosed() {
		$latestTotalTime = $this->getWorkflowLatestTotalTime();
		$this->appendWorkflowTotalTime($latestTotalTime);
		$this->workflowLastView = time();

		unset($this->currentPhase);
		unset($this->workflowStart);

		return true;
	}

}
