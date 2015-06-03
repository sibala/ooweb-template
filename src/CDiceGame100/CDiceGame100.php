<?php
/**
 * A dice game  called tärningsspelet 100.
 *
 */
class CDiceGame100 extends CDiceHand {
 
 
  /**
   * Properties
   *
   */
  private $round;

 
  /**
   *Start a named session
   *
   */
  public function __construct($players = 1) {
    session_name('dice100');
	for($i=0; $i < $players; $i++) {
	  $this->round = new CDiceHand(2);
	}
  }
  
  
  /**
   *Starts the game and saves the round in a session
   *
   *Sets a message that is stored in a session
   *
   */
  public function playGame() {
    $_SESSION['msg'] = "<p>Försök att får totalt 100+ poäng på så få kast som möjligt. Får du en etta förlorar du alla dina osparade poäng .</p>
				  <p><a href='?init'>Starta en ny runda</a>.</p>
				  <p><a href='?roll'>Gör ett kast</a>.</p>
				  <p><a href='?save'>Spara dina poäng.</a>.</p>
				  <p><a href='?destroy'>Avsluta</a>.</p>";
    if(isset($_SESSION['hand'])) {
      $this->round = $_SESSION['hand'];
    } else {
      $this->round = new CDiceHand(2);
      $_SESSION['hand'] = $this->round;
    }
	
	if(isset($_GET['roll'])) {
      $_SESSION['msg'] .= "<i>Spelet är igång<br /><br /></i>";
      if($this->round->RollAll() === false){
        $_SESSION['msg'] .= "Du fick en etta <a href='?roll'>Gör ett nytt kast</a>.</p>.";
      }
      if($this->round->GetRoundTotal() >= 100){
        $_SESSION['msg'] .= "Grattis du nådde upp till 100+ poäng</a></p>.";
      }
    }

    else if(isset($_GET['init'])) {
      $_SESSION['msg'] .= "<i>Spelet är igång<br /><br /></i>";
      $this->round->InitRound();
    }
  }

  
  /**
   *Ends the game and destroys the game session 
   *
   *Sets a message that is stored in a session
   */
  public function endGame() {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
      );
    }
    session_destroy();
    $_SESSION['msg'] = "Spelet avslutad, <a href='?init'>Starta en ny omgång?</a>";
  }
  
}