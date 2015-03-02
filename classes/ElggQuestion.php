<?php
/**
 * Question object
 *
 * @package Questions
 *
 */

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
	 * Get the latest internal answer of the question
	 *
	 * @return ElggAnswer latest answer of to the question
	 */
	public function getLatestIntAnswer() {
		$settings = array(
			'order_by' => 'time_created desc',
			'type' => 'object',
			'subtype' => 'intanswer',
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
			if ($this->workflowLastView >= $this->getLatestAnswer()->time_created) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Can the user comment to the question?
	 * Note: this is different then giving an "answer".
	 *
	 * @return bool
	 */
	public function canComment($user_guid = 0) {
		if (!questions_can_comment()) {
			return false;
		}

		return $this->canAnnotate($user_guid);
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
	 * Set the status of the question (todo).
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
	 * Get the current status of the question (todo).
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
	 * Check if workflow is too late
	 *
	 * @return bool
	 */
	public function isWorkflowTooLate() {
		if (!$this->isWorkflowOpen()) {
			return false;
		}

		$currentPhase = $this->getCurrentWorkflowPhase();
		if ($currentPhase->due && questions_time_diff($this->currentPhaseStart, time()) > $currentPhase->due) {
			return true;
		} else {
			return false;
		}
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

		access_show_hidden_entities(true);
		$entity = get_entity($this->currentPhase);
		access_show_hidden_entities(false);

		return $entity;
	}

	/**
	 * Get the total number of hours a question is open
	 *
	 * @return int hours
	 */
	public function getWorkflowLatestTotalTime() {
		if ($this->isWorkflowOpen()) {
			return questions_time_diff($this->workflowStart, time());
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
	 * Get the assigned manager
	 *
	 * @return bool|ElggUser
	 */
	public function getWorkflowManager() {
		if ($this->workflowManager && $manager = get_entity($this->workflowManager)) {
			return $manager;
		} else {
			return false;
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

		unset($this->workflowManager);

		$phase = $availablePhases[$phaseGuid];
		if ($this->currentPhase != $phaseGuid && $phase->email) {
			questions_send_workflow_notification($this, $phase);
		}

		if ($phaseGuid == end($availablePhases)->guid) {
			$this->setWorkflowClosed();
		} else {
			$this->setCurrentPhaseStart();
			$this->currentPhase = $phaseGuid;
		}

		return true;
	}

	/**
	 * Configure the start time when a new phase is started.
	 * In some cases this start time is earlier, because we
	 * want to take the time into account when the same phase was
	 * started earlier in this cycle.
	 * 
	 * @return bool
	 */
	public function setCurrentPhaseStart() {

		$intAnswers = $this->getIntAnswers(array(
			'created_time_lower' => $this->workflowStart
		));

		if (!$intAnswers) {
			$this->currentPhaseStart = time();
			return true;
		}

		$bringForward = 0;
		foreach ($intAnswers as $intAnswer) {
			if ($intAnswer->phase_guid == $this->currentPhase) {
				$bringForward = $bringForward + $intAnswer->timeSpent;
			}
		}

		$this->currentPhaseStart = time() - $bringForward;
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
			$this->setCurrentPhaseStart();
			
			$this->workflowStart = time();
			$this->workflowManager = elgg_get_logged_in_user_guid();

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

		$latestAnswer = current($this->getIntAnswers());
		$latestAnswer->workflowCloseCycle = true;
		$latestAnswer->save();

		unset($this->currentPhase);
		unset($this->currentPhaseStart);		
		unset($this->workflowStart);

		return true;
	}

}
