<?php
/**
 * A CDice class to play around with a dice.
 *
 */
class CDice {

  /**
   * Properties
   *
   */
  private $faces;
  protected $rolls = array();
  private $last;


  /**
   * Constructor
   *
   * @param int $faces the number of faces to use.
   */
  public function __construct($faces=6) {
    //echo __METHOD__;
    $this->faces = $faces;
  }


  /**
   * Destructor
   *
   */
  public function __destruct() {
    //echo __METHOD__;
  }


  /**
   * Roll the dice
   *
   */
  public function Roll($times) {

    for($i = 0; $i < $times; $i++) {
      $this->last = rand(1, $this->faces);
	  $this->rolls[] = $this->last;
    }
	return $this->last;
  }


  /**
   * Get the total from the last roll(s).
   *
   */
  public function GetTotal() {
    return array_sum($this->rolls);
  }


  /**
   * Get the average from the last roll(s).
   *
   */
  public function GetAverage() {
    return round(array_sum($this->rolls) / count($this->rolls), 1);
  }

  
  /**
   *@return faces
   *
   */
  public function GetFaces(){
    return $this->faces;
  }
  
  
  /**
   *@return rolls
   *
   */
  public function GetRollsAsArray(){
    return $this->rolls;
  }
  
  
  /**
   *@return rolls
   *
   */
  public function GetLastRoll(){
    return $this->last;
  }
  
  
  /**
   *Get the rolls as a string with each roll separated by a comma.
   *
   */
  public function GetRollsAsSerie() {
    $html = null;
	foreach($this-rolls as $val) {
	  $html .= "{$val}, ";
	}
	return substr($html, 0, strlen($html) - 2);
  }
  
  
}