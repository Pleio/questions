<?php
  
class QuestionsWorkflowPhase extends ElggObject {
  const SUBTYPE = "questions_workflow_phase";
    
  protected function initializeAttributes() {
    parent::initializeAttributes();
    $this->attributes['subtype'] = self::SUBTYPE;
  }
}