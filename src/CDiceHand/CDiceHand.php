<?php
/**
 * A hand of dices, with graphical representation, to roll.
 *
 */
class CDiceHand extends CDice {
 
  /**
   * Properties
   *
   */
  private $dices;
  private $numDices;
  private $sum;
  private $sumRound;
  private $savedSum;
 
 
  /**
   * Constructor
   *
   * @param int $numDices the number of dices in the hand, defaults to six dices. 
   */
  public function __construct($numDices = 2) {
    for($i=0; $i < $numDices; $i++) {
	  $this->dices[] = new CDiceImage();
	}
	$this->numDices = $numDices;
	$this->sum = 0;
	$this->sumRound = 0;
	$this->savedSum = 0;
  }
 
 
  /**
   * Roll all dices in the hand.
   *
   */
  public function RollAll() {
    $this->sum = 0;
    for($i=0; $i < $this->numDices; $i++) {
      $roll = $this->dices[$i]->Roll(1);
	  if($roll === 1){
	    $this->lostUnsavedPoints();
		return false;
	  }
      $this->sum += $roll;
      $this->sumRound += $roll;
	}
	return true;
  }
  
  
  /**
   * Saving points.
   *
   */
  public function savePoints() {
    $this->savedSum = $this->sumRound;
  }
  
  /**
   * Loss of unsaved points.
   *
   */
  public function lostUnsavedPoints() {
    $this->sumRound = $this->savedSum;
  }
  
  /**
   * Init the round.
   *
   */
  public function InitRound() {
    $this->sumRound = 0;
  }
  
 
  /**
   * Get the sum of the last roll.
   *
   * @return int as a sum of the last roll, or 0 if no roll has been made.
   */
  public function GetTotal() {
    return $this->sum;
  }
  
  
  /**
   * Get the sum of the last roll.
   *
   * @return int as a sum of the last roll, or 0 if no roll has been made.
   */
  public function GetSaved() {
    return $this->savedSum;
  }
  
  
  /**
   * Get the accumulated sum of the round.
   *
   * @return int as a sum of the round, or 0 if no roll has been made.
   */
  public function GetRoundTotal() {
    return $this->sumRound;
  }
 
 
  /**
   * Get the rolls as a serie of images.
   *
   * @return string as the html representation of the last roll.
   */
  public function GetRollsAsImageList() {
    $html = "<ul class='dice'>";
    foreach($this->dices as $dice) {
      $val = $dice->GetLastRoll();
      $html .= "<li class='dice-{$val}'></li>";
    }
    $html .= "</ul>";
    return $html;
  }
  
}