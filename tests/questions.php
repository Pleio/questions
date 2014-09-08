<?php

/**
 * The time difference function
 */
class QuestionsTimeDiffTest extends ElggCoreUnitTest {

  /**
   * Called before each test object.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Called before each test method.
   */
  public function setUp() {
    
  }

  /**
   * Called after each test method.
   */
  public function tearDown() {
    // do not allow SimpleTest to interpret Elgg notices as exceptions
    $this->swallowErrors();
  }

  /**
   * Called after each test object.
   */
  public function __destruct() {
    // all __destruct() code should go above here
    parent::__destruct();
  }

  /**
   * Time difference of 5 hours
   */  
  public function testTimeDiffInDay() {
    $midnight = strtotime('midnight');
    $evening = strtotime('+20 hours', $midnight);

   // $result = questions_time_diff($midnight, $evening);
   // $this->assertEqual($result, 3600*8);
  }

  /**
   * Time difference of one day
   */  
  public function testTimeDiffDay() {
    $monday = strtotime('next monday');
    $tuesday = strtotime('+1 day', $monday);

    $result = questions_time_diff($monday, $tuesday);
    $this->assertEqual($result, 3600*8);
  }

  /**
   * Time difference of two days
   */  
  public function testTimeDiffTwoDays() {
    $day1 = strtotime('last sunday');
    $day2 = strtotime('+2 days', $day1);
    
    $result = questions_time_diff($day1, $day2);
    $this->assertEqual($result, 3600*8);
  }

  /**
   * Time difference of one week
   */  
  public function testTimeDiffWeek() {
    $lastWeek = strtotime('-1 week');
    $now = strtotime('now');    

    $result = questions_time_diff($lastWeek, $now);
    $this->assertEqual($result, 3600*8*5);
  }


}