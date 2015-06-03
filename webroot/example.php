<?php 
/**
 * This is a OOWeb pagecontroller.
 *
 */
// Include the essential config-file which also creates the $ooweb variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the OOWeb container.

$filter = new CTextFilter();
$text = "
Detta är en exempelsida som med text i markdown som implementeras via klassen CTextFilter. 
Gå in i koden och inspektera de olika klasser och funktioner som medföljer denna mall. 
Mappen src hittar du klasser och funktioner. 
Mappen webroot är där dina sidkontroller, bilder, och css skall vara.
Mappen theme är din mall för sidans struktur vad gäller presentation av innehåll.
";

$markdownText = $filter->doFilter($text, 'markdown');

$ooweb['title'] = "En exempelsida";
$ooweb['main'] = <<<EOD
<p>
{$markdownText}
</p>
EOD;

 
// Finally, leave it all to the rendering phase of OOWeb.
include(OOWEB_THEME_PATH);