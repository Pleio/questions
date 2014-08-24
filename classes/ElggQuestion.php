<?php

class ElggQuestion extends ElggObject {
	const SUBTYPE = 'question';

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	public function getAnswers(array $options = array()) {
		$defaults = array(
			'order_by' => 'time_created asc',
		);

		$overrides = array(
			'type' => 'object',
			'subtype' => 'answer',
			'container_guid' => $this->guid,
		);

		return elgg_get_entities(array_merge($defaults, $options, $overrides));
	}

	public function getIntAnswers(array $options = array()) {
		$defaults = array(
			'order_by' => 'time_created asc',
		);

		$overrides = array(
			'type' => 'object',
			'subtype' => 'intanswer',
			'container_guid' => $this->guid,
		);

		return elgg_get_entities(array_merge($defaults, $options, $overrides));		
	}

	public function listAnswers(array $options = array()) {
		return elgg_list_entities($options, array($this, 'getAnswers'));
	}
	
	public function getURL() {
		$url = "questions/view/" . $this->guid . "/" . elgg_get_friendly_title($this->title);
		return elgg_normalize_url($url);
	}

	public function getWorkflowURL() {
		$url = "questions/workflow/view/" . $this->guid . "/" . elgg_get_friendly_title($this->title);
		return elgg_normalize_url($url);
	}
	
	public function getWorkflowTotalTime() {
		if (isset($this->current_phase_guid) && $this->current_phase_guid == -1 && isset($this->total_answer_time)) {
			return $this->total_answer_time;
		} else {
			if ($answers = $this->getIntAnswers()) {
				return round(questions_time_diff($answers[0]->time_created, time()));
			} else {
				return 0;
			}
		}
	}

	/**
	 * Get the answer that was marked as the correct answer.
	 *
	 * @return bool|ElggAnswer the answer or false if non are marked
	 */
	public function getMarkedAnswer() {
		$result = false;
		
		$options = array(
			"type" => "object",
			"subtype" => "answer",
			"limit" => 1,
			"container_guid" => $this->getGUID(),
			"metadata_name_value_pairs" => array(
				"name" => "correct_answer",
				"value" => true
			)
		);
		
		$answers = elgg_get_entities_from_metadata($options);
		if (!empty($answers)) {
			$result = $answers[0];
		}
		
		return $result;
	}
	
	/**
	 * Helper function to close a question from further answers.
	 *
	 * @return void
	 */
	public function close() {
		$this->status = "closed";
	}
	
	/**
	 * Reopen the question for more answers.
	 *
	 * @return void
	 */
	public function reopen() {
		$this->status = "open";
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
		$result = "open";
		
		// do we even support status
		if (questions_close_on_marked_answer()) {
			// make sure the status is correct
			switch ($this->status) {
				case "open":
					// is it still open, so no marked answer
					if ($this->getMarkedAnswer()) {
						$result = "closed";
					}
					break;
				case "closed":
					$result = "closed";
					// is it still open, so no marked answer
					if (!$this->getMarkedAnswer()) {
						$result = "open";
					}
					break;
				default:
					// no setting yet
					if ($this->getMarkedAnswer()) {
						$result = "closed";
					}
					break;
			}
		}
		
		return $result;
	}
}
