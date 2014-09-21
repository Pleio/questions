<?php
/**
 * Answer object
 *
 * @package Questions
 *
 */

class ElggAnswer extends ElggObject {
	const SUBTYPE = "answer";

	/**
	 * Intialize the ElggAnswer class
	 *
	 * @return void
	 */
	function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

  /**
   * Get the question related to the answer
   *
   * @return ElggQuestion the question
   */
	public function getQuestion() {
		return $this->getContainerEntity();
	}

  /**
   * Get the URL of the answer
   *
   * @return string the URL of the answer
   */
	public function getURL() {
		$container_entity = $this->getContainerEntity();
		$url = $container_entity->getURL() . "#answer-" . $this->guid;
		return $url;
	}

	/**
	 * Mark answer as correct
	 *
	 * @return bool
	 */
	public function markCorrect() {
		if (questions_close_on_marked_answer()) {
			$this->getQuestion()->setStatus('closed');
		}

    return add_entity_relationship($this->getQuestion()->guid, "correctAnswer", $this->guid);    
	}

	/**
	 * Unmark answer as correct
	 *
	 * @return bool
	 */
	public function unmarkCorrect() {
    if ($this->getQuestion()->getStatus() == 'closed') {
      $this->getQuestion()->setStatus('open');
    }
		return remove_entity_relationships($this->getQuestion()->guid, "correctAnswer");
	}

  /**
   * Check if this is the correct answer
   *
   * @return bool
   */
	public function isCorrect() {
		return check_entity_relationship($this->getQuestion()->guid, "correctAnswer", $this->guid);
	}

  /**
   * Check if the answer can be commented
   *
   * @return bool
   */
  public function canComment() {
    return questions_can_comment();
  }

}
