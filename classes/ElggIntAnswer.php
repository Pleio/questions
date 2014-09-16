<?php
/**
 * Internal (workflow) answer object
 *
 * @package Questions
 *
 */

class ElggIntAnswer extends ElggObject {
  const SUBTYPE = 'intanswer';
  
  /**
   * Intialize the ElggIntAnswer class
   *
   * @return void
   */  
  function initializeAttributes() {
    parent::initializeAttributes();
    $this->attributes['subtype'] = self::SUBTYPE;
  }


  /**
   * Return the question the answer belongs to
   *
   * @return ElggQuestion| the question
   */ 
  public function getQuestion() {
    return $this->getContainerEntity();
  }

  /**
   * Return the phase of the answer
   *
   * @return QeustionsWorkflowPhase| workflow phase
   */ 
  public function getPhase() {
    access_show_hidden_entities(true);

    if ($this->phase_guid) {
      $phase = get_entity($this->phase_guid);
    }

    access_show_hidden_entities(false);

    return $phase;
  }

  /**
   * Get the URL of the answer
   *
   * @return string the URL of the answer
   */
  public function getURL() {
    $container_entity = $this->getContainerEntity();
    $url = $container_entity->getWorkflowURL() . "#intanswer-" . $this->guid;
    return $url;
  }

  /**
   * Calculate the time that was needed to answer this question
   *
   * @return float $answerTime
   */
  public function calculateAnswerTime() {
    $question = $this->getQuestion();

    if ($previousAnswers = $question->getIntAnswers()) {
      $latestAnswer = $previousAnswers[0];

      if ($question->workflowStart > $latestAnswer->time_created) {
        $startTime = $question->workflowStart;        
      } else {
        $startTime = $latestAnswer->time_created;
      }

    } else {
      $startTime = $question->workflowStart;
    }

    return questions_time_diff($startTime, time());
  }

  /**
   * Publish post also on frontend
   *
   * @return bool
   */
  public function publishOnFrontend() {
    if ($this->answerGuid == 1) {
      $answer = new ElggAnswer();
      $answer->description = $this->description;
      $answer->intanswerGuid = $this->guid;
      $answer->container_guid = $this->container_guid;
      $answer->access_id = $this->getQuestion()->access_id;
      $answer->save();

      $this->answerGuid = $answer->guid;

    } elseif (is_int($this->answerGuid)) {
      $answer = get_entity($this->answerGuid);

      if ($answer instanceof ElggAnswer) {
        $answer->description = $this->description;
        $answer->save();
      }
    }
  }

  /**
   * Save the entity.
   *
   * @return bool
   */
  public function save() {
    if (!$this->timeSpent) {
      $this->timeSpent = $this->calculateAnswerTime();
    }

    if ($this->answerGuid) {
      $this->publishOnFrontend();
    }

    return parent::save();
  }

  /**
   * Delete the entity.
   *
   * @return bool
   */  
  public function delete() {
    if ($this->answerGuid && $this->answerGuid != 1) {
      $answer = get_entity($this->answerGuid);
      if ($answer instanceof ElggAnswer) {
        $answer->delete();
      }
    }

    return parent::delete();
  }

}
