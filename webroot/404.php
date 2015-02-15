<?php 
/**
 * This is a OOWeb pagecontroller.
 *
 */
// Include the essential config-file which also creates the $ooweb variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the OOWeb container.
$ooweb['title'] = "404";
$ooweb['header'] = "";
$ooweb['main'] = "This is a OOWeb 404. Document is not here.";
$ooweb['footer'] = "";
 
// Send the 404 header 
header("HTTP/1.0 404 Not Found");
 
 
// Finally, leave it all to the rendering phase of OOWeb.
include(OOWEB_THEME_PATH);