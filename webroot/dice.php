<?php


/**
 * This is a Anax pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 


// Do it and store it all in variables in the OOWeb container.
$ooweb['title'] = "Tärningsspelet 100";

/*
$ooweb['main'] = <<<EOD
<h1>Tärningsspelet 100</h1>
EOD;*/
$ooweb['main'] = "";
if(isset($_GET['destroy'])) {
  $_SESSION['game']->endGame();
  $ooweb['main'] = $_SESSION['msg'];
}


if(!isset($_SESSION['game'])) {
  $_SESSION['game'] = new CDiceGame100();
}

if(!isset($_GET['destroy'])){
  $_SESSION['game']->playGame();
  $ooweb['main'] .= $_SESSION['msg'];
}

if(isset($_GET['roll']) || isset($_GET['init'])){
  $ooweb['main'] .= "<p>" . $_SESSION['hand']->GetRollsAsImageList() . "</p>";
  $ooweb['main'] .= "<p>Summan av detta kast " .$_SESSION['hand']->GetTotal() . "</p>";
  $ooweb['main'] .= "<p>Summan i spelomgången " .$_SESSION['hand']->GetRoundTotal() . "</p>";
  $ooweb['main'] .= "<p>Summan av din sparade poäng " .$_SESSION['hand']->GetSaved() . "</p>";
}

if(isset($_GET['save'])) {
  $ooweb['main'] .= "<i>Game ongoing<br /><br /></i>";
  $_SESSION['hand']->savePoints();
  $ooweb['main'] .= "<p>" . $_SESSION['hand']->GetRollsAsImageList() . "</p>";
  $ooweb['main'] .= "<p>SSumman av detta kast " .$_SESSION['hand']->GetTotal() . "</p>";
  $ooweb['main'] .= "<p>Summan i spelomgången " .$_SESSION['hand']->GetRoundTotal() . "</p>";
  $ooweb['main'] .= "<p>Summan av din sparade poäng " .$_SESSION['hand']->GetSaved() . "</p>";
}




// Finally, leave it all to the rendering phase of Anax.
include(OOWEB_THEME_PATH);
