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
<h1>kmom1</h1>
<p>
<b>Vilken utvecklingsmiljö använder du?</b><br />
Min utvecklingsmiljö består av xampp, git, notepad++, filezilla, putty, win7. Jag har haft en liknande miljö under en längre period nu och tycker att det har funkat väldigt bra.
<br /><br />
<b>Berätta hur det gick att jobba igenom guiden “20 steg för att komma igång PHP”, var något nytt eller kan du det?</b><br />
Repetitionen på PHP är väldigt bra skriven. Jag lärde mig några enstaka php tricks som att skapa nedladdning med header(). Annars har jag rätt bra koll på grunderna och kände att jag kunde skumma igenom dokumentet rätt snabbt.
<br /><br />
<b>Vad döpte du din webbmall Anax till?</b><br />
Jag valde att döpa min Anax till OOWeb.
<br /><br />
<b>Vad anser du om strukturen i Anax, gjorde du några egna förbättringar eller något du hoppade över?</b><br />
Det var riktigt kul att se hur mos väljer att strukturera sin kod och hur olika delar implementeras som navigation, template, config, på denna grundnivå. Jag har läst och klarat phpmvc  kursen och tyckte att det var bland de bästa och svåraste programmeringskurserna som jag har läst. Samtidigt känns det som att jag saknar förståelsen för några av de designvalen som gjordes i Anax-mvc ramverket och tror att denna kurs kommer att förklara mycket om de  olika designval i mvc ramverket. 
<br /><br />
<b>Gick det bra att inkludera source.php? Gjorde du det som en modul i ditt Anax</b><br />
Inga problem. Anvisningarna är du väldigt tydliga och så finns det även exempel på hur man implemeterar source.php.
<br /><br />
<b>Gjorde du extrauppgiften med GitHub? </b><br />
Ja.
<br /><br />
Github: <a href="https://github.com/sibala/ooweb">https://github.com/sibala/ooweb</a><br />


</p>
EOD;
 
$ooweb['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Sibar Al-ani | <a href='https://github.com/sibala/ooweb'>OOWeb på GitHub</a></span></footer>
EOD;
 
 
// Finally, leave it all to the rendering phase of OOWeb.
include(OOWEB_THEME_PATH);