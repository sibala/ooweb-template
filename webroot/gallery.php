<?php

// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 


// Define the basedir for the gallery
$gallery_path = __DIR__ . DIRECTORY_SEPARATOR . 'img';
$gallery_baseUrl = '';

include(__DIR__.'/../src/CGallery/CGallery.php'); 
$CGallery = new CGallery($gallery_path, $gallery_baseUrl);

$breadcrumb = $CGallery->getBreadcrumb();
$gallery = $CGallery->getGallery();

$ooweb['title'] ="Ett galleri";

$ooweb['main'] = <<<EOD
 
$breadcrumb
 
$gallery
 
EOD;
 
// Finally, leave it all to the rendering phase of Anax.
include(OOWEB_THEME_PATH);


