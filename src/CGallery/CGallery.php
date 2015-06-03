<?php

class CGallery {
	
  private $pathToGallery;
  private $gallery;
  private $breadcrumb;


  public function __construct($gallery_path, $gallery_baseUrl) {
  	// Define the basedir for the gallery
	define('GALLERY_PATH', $gallery_path);
	define('GALLERY_BASEURL', $gallery_baseUrl);

	$this->run();
  }



  public function run() {
	// Validate and Set path to gallery
	$this->setPathToGallery();

	// Read and present images in the current directory
	if(is_dir($this->pathToGallery)) {
	  $this->readAllItemsInDir();
	}
	else if(is_file($this->pathToGallery)) {
	  $this->readItem();
	}
	
	// Prepare content and store it all in variables in the Anax container.
	$this->createBreadcrumb();

  }



  public function setPathToGallery() {

  // Get incoming parameters
	$path = isset($_GET['path']) ? $_GET['path'] : null;
	$pathComplete = GALLERY_PATH . DIRECTORY_SEPARATOR . $path;
	 
	$pathToGallery = realpath($pathComplete);
	$basePath      = realpath(GALLERY_PATH);
	 
	// Validate incoming arguments
	($pathToGallery !== false) or errorMessage("The path to the gallery image seems to be a non existing path.");
	($basePath !== false) or errorMessage("The basepath to the gallery, GALLERY_PATH, seems to be a non existing path.");
	is_dir(GALLERY_PATH) or errorMessage('The gallery dir "' . GALLERY_PATH . '" is not a valid directory.');
	substr_compare($basePath, $pathToGallery, 0, strlen($basePath)) == 0 or errorMessage("Security constraint: Source gallery is not directly below the directory GALLERY_PATH.\n" . $basePath . "\n" . $pathToGallery);
  	
  	$this->pathToGallery = $pathToGallery;
  }	 


  /**
   * Read directory and return all items in a ul/li list.
   *
   * @param string $path to the current gallery directory.
   * @param array $validImages to define extensions on what are considered to be valid images.
   * @return string html with ul/li to display the gallery.
   */
  public function readAllItemsInDir($validImages = array('png', 'jpg', 'jpeg')) {
    $files = glob($this->pathToGallery . '/*'); 
    $gallery = "<ul class='gallery'>\n";
    $len = strlen(GALLERY_PATH);
   
    foreach($files as $file) {
      $parts = pathinfo($file);
      $href  = str_replace('\\', '/', substr($file, $len + 1));
   
      // Is this an image or a directory
      if(is_file($file) && in_array($parts['extension'], $validImages)) {
        $item    = "<img src='img.php?src=" 
          . GALLERY_BASEURL 
          . $href 
          . "&amp;width=128&amp;height=128&amp;crop-to-fit' alt=''/>";
        $caption = basename($file); 
      }
      elseif(is_dir($file)) {
        $item    = "<img src='img/folder.png' alt=''/>";
        $caption = basename($file) . '/';
      }
      else {
        continue;
      }
   
      // Avoid to long captions breaking layout
      $fullCaption = $caption;
      if(strlen($caption) > 18) {
        $caption = substr($caption, 0, 10) . '…' . substr($caption, -5);
      }
   
      $gallery .= "<li><a href='?path={$href}' title='{$fullCaption}'><figure class='figure overview'>{$item}<figcaption>{$caption}</figcaption></figure></a></li>\n";
    }
    $gallery .= "</ul>\n";
   
    $this->gallery = $gallery;
  }


  /**
   * Read and return info on choosen item.
   *
   * @param array $validImages to define extensions on what are considered to be valid images.
   * @return string html to display the gallery item.
   */
  public function readItem($validImages = array('png', 'jpg', 'jpeg')) {
    $parts = pathinfo($this->pathToGallery);
    if(!(is_file($this->pathToGallery) && in_array($parts['extension'], $validImages))) {
      return "<p>This is not a valid image for this gallery.";
    }
   
    // Get info on image
    $imgInfo = list($width, $height, $type, $attr) = getimagesize($this->pathToGallery);
    $mime = $imgInfo['mime'];
    $gmdate = gmdate("D, d M Y H:i:s", filemtime($this->pathToGallery));
    $filesize = round(filesize($this->pathToGallery) / 1024); 
   
    // Get constraints to display original image
    $displayWidth  = $width > 800 ? "&amp;width=800" : null;
    $displayHeight = $height > 600 ? "&amp;height=600" : null;
   
    // Display details on image
    $len = strlen(GALLERY_PATH);
    $href = GALLERY_BASEURL . str_replace('\\', '/', substr($this->pathToGallery, $len + 1));
    $item = <<<EOD
  <p><img src='img.php?src={$href}{$displayWidth}{$displayHeight}' alt=''/></p>
  <p>Original image dimensions are {$width}x{$height} pixels. <a href='img.php?src={$href}'>View original image</a>.</p>
  <p>File size is {$filesize}KBytes.</p>
  <p>Image has mimetype: {$mime}.</p>
  <p>Image was last modified: {$gmdate} GMT.</p>
EOD;
   
    $this->gallery = $item;
  }
  
  
  
  /**
   * Create a breadcrumb of the gallery query path.
   * @return string html with ul/li to display the thumbnail.
   */
  public function createBreadcrumb() {
    $parts = explode('/', trim(substr($this->pathToGallery, strlen(GALLERY_PATH) + 1), '/'));
    $breadcrumb = "<ul class='breadcrumb'>\n<li><a href='?'>Hem</a> »</li>\n";
   
    if(!empty($parts[0])) {
      $combine = null;
      foreach($parts as $part) {
        $combine .= ($combine ? '/' : null) . $part;
        $breadcrumb .= "<li><a href='?path={$combine}'>$part</a> » </li>\n";
      }
    }
   
    $breadcrumb .= "</ul>\n";
    $this->breadcrumb = $breadcrumb;
  }

  public function getGallery() {
  	return $this->gallery;
  }

  public function getBreadcrumb() {
  	return $this->breadcrumb;
  }


}