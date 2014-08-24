<?php

class ElggIntAnswer extends ElggObject {
  const SUBTYPE = 'intanswer';
  
  function initializeAttributes() {
    parent::initializeAttributes();
    $this->attributes['subtype'] = self::SUBTYPE;
  }

  public function getURL() {
    $container_entity = $this->getContainerEntity();
    $url = $container_entity->getWorkflowURL() . "#intanswer-" . $this->guid;
    return $url;
  }
}
