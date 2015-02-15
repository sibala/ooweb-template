<?php 
/**
 * This is a OOWeb pagecontroller.
 *
 */
// Include the essential config-file which also creates the $ooweb variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the OOWeb container.
$ooweb['title'] = "Hello World";
 
$ooweb['header'] = <<<EOD
<img class='sitelogo' src='img/mouse.png' alt='OOWeb Logo'/>
<span class='sitetitle'>OOWeb webbtemplate</span>
<span class='siteslogan'>Återanvändbara moduler för webbutveckling med PHP</span>
EOD;
 
$ooweb['main'] = <<<EOD
<h1>Hej Världen</h1>
<p>Detta är en exempelsida som visar hur OOWeb ser ut och fungerar.</p>
EOD;
 
$ooweb['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Sibar Al-ani | <a href='https://github.com/sibala/ooweb'>OOWeb på GitHub</a></span></footer>
EOD;
 
 
// Finally, leave it all to the rendering phase of OOWeb.
include(OOWEB_THEME_PATH);