<?php

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

  public function save() {
    if (!$this->timeSpent) {
      $this->timeSpent = $this->calculateAnswerTime();
    }

    parent::save();
  }
}
