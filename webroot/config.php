<?php
/**
 * Config-file for Anax. Change settings here to affect installation.
 *
 */
 
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly
 
 
/**
 * Define Anax paths.
 *
 */
define('OOWEB_INSTALL_PATH', __DIR__ . '/..');
define('OOWEB_THEME_PATH', OOWEB_INSTALL_PATH . '/theme/render.php');
 
 
/**
 * Include bootstrapping functions.
 *
 */
include(OOWEB_INSTALL_PATH . '/src/bootstrap.php');
 

 
/**
 * Create the OOWeb variable.
 *
 */
$ooweb = array();
 
 
/**
 * Site wide settings.
 *
 */
$ooweb['lang']         = 'sv';
$ooweb['title_append'] = ' | OOWeb en webbtemplate';

/**
 * Theme related settings.
 *
 */
//$ooweb['stylesheet'] = 'css/style.css';
$ooweb['stylesheets'] = array('css/style.css', 'css/dice.css', 'css/gallery.css', 'css/figure.css', 'css/breadcrumb.css');
$ooweb['favicon']    = 'img/favicon.ico';


/**
 * Settings for JavaScript.
 *
 */
$ooweb['modernizr'] = 'js/modernizr.js';
$ooweb['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js';
//$ooweb['jquery'] = null; // To disable jQuery
$ooweb['javascript_include'] = array();
//$ooweb['javascript_include'] = array('js/main.js'); // To add extra javascript files


/**
 * Google analytics.
 *
 */
$ooweb['google_analytics'] = 'UA-22093351-1'; // Set to null to disable google analytics


/**
  * The menu/navbar
  *
  */
$ooweb['menu'] = array(
  'class' => 'navbar',
  'items' => array(
    'example'    => array('text'=>'Exampel sida',   'url'=>'example.php',        'title' => 'Exempel sida'),
    'dice'    => array('text'=>'Tärningsspel',   'url'=>'dice.php',        'title' => 'Tärningsspel'),
    'gallery'    => array('text'=>'Galleri',   'url'=>'gallery.php',        'title' => 'Galleri'),
    'source'    => array('text'=>'Källkod',   'url'=>'source.php',        'title' => 'Källkod')
  ),
  'callback_selected' => function($url) {
    if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
      return true;
    }
  }
);


$ooweb['header'] = <<<EOD
<img class='sitelogo' src='img/mouse.jpg' alt='OOWeb Logo'/>
<span class='sitetitle'>OOWeb en webbtemplate</span>
<span class='siteslogan'>en under titel</span><br>
EOD;



$ooweb['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) OOWeb</span></footer>
EOD;
