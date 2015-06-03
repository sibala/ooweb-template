<?php 
/**
 * This is a PHP skript to process images using PHP GD.
 *
 */
// Include the essential config-file which also creates the $ooweb variable with its defaults.
include(__DIR__.'/config.php'); 
// Includes CImage for processing images
require(__DIR__ . '/../src/CImage/CImage.php');

$image_path = __DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
$cache_path = __DIR__ . '/cache/';


// Do it and store it all in variables in the OOWeb container.
$ooweb['title'] = "img";
 
$img = new CImage($image_path, $cache_path);
$ooweb['main'] = <<<EOD
{$img}
EOD;
 
 
// Finally, leave it all to the rendering phase of OOWeb.
include(OOWEB_THEME_PATH);