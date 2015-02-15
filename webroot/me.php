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
<h1>Om mig</h1>
<p>
Jag heter Sibar Al-ani och bor i Stockhom. Jag har tidigare studerat webbutveckling på Högskolan Väst, HKR och nyligen tog jag även kursen php-mvc på BTH.
Har jobbat extra som webbutvecklare och då var det mestadels med språken sql/php/css/html. Jag är sjävlärd inom oophp och har utvecklat en del med yii-ramverket men känner att jag behöver en bättre grund att stå på inom designpatterns och bestpractice.
<br />
Parallellt med denna kurs så läser jag även kursen javascript på BTH. 
<br /><br />
På fritiden gillar jag att hålla igång. Under åren har det varit fotboll, kickboxning, MMA, simning och en massa annat kul. Idag är det mest kondition- & styrketräning och ibland sparkar jag boll med kompisar. 
</p>
EOD;
 
$ooweb['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Sibar Al-ani | <a href='https://github.com/sibala/ooweb'>OOWeb på GitHub</a></span></footer>
EOD;
 
 
// Finally, leave it all to the rendering phase of OOWeb.
include(OOWEB_THEME_PATH);