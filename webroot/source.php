<?php 
/**
 * This is a OOWeb pagecontroller.
 *
 */
// Include the essential config-file which also creates the $ooweb variable with its defaults.
include(__DIR__.'/config.php'); 
 
 // Add style for csource
$ooweb['stylesheets'][] = 'css/source.css';
 
// Create the object to display sourcecode
//$source = new CSource();
$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));
 
// Do it and store it all in variables in the OOWeb container.
$ooweb['title'] = "Visa källkod";
 
$ooweb['main'] = $source->View();

 

// Finally, leave it all to the rendering phase of OOWeb.
include(OOWEB_THEME_PATH);