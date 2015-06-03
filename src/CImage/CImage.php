<?php

class CImage {

  private $incomingArgs; //array
  private $imageInfo;	 //array

  private $image;
  private $pathToImage;
  private $fileExtension;
  private $cacheFileName;

  private $maxWidth;
  private $maxHeight;

  private $cropWidth;
  private $cropHeight;


  public function __construct($img_path, $cache_path) {
	//
	// Ensure error reporting is on
	//
	error_reporting(-1);              // Report all type of errors
	ini_set('display_errors', 1);     // Display all errors 
	ini_set('output_buffering', 0);   // Do not buffer outputs, write directly



	//
	// Define some constant values, append slash
	// Use DIRECTORY_SEPARATOR to make it work on both windows and unix.
	//
	define('IMG_PATH', $img_path);
	define('CACHE_PATH', $cache_path);
	
	$this->maxWidth = $this->maxHeight = 2000;

	$this->run();
  }

 

  public function run() {

  	// Setting and Extracting variables: src, verbose, saveAs, quality, ignoreCache, newWidth, newHeight, cropToFit, sharpen
  	$this->setIncomingArgs();
  	extract($this->incomingArgs);

  	// Setting and Extracting variables: width, height, type, attr, filesize
  	$this->setImageInfo($verbose);
  	extract($this->imageInfo);

  	$this->setNewImageDimensions();
  	$this->setCacheFileName();

	// Is there already a valid image in the cache directory, then use it and exit
	$imageModifiedTime = filemtime($this->pathToImage);
	$cacheModifiedTime = is_file($this->cacheFileName) ? filemtime($this->cacheFileName) : null;

	// display log
  	if($verbose) {
  	  
  	  $this->displayLog($verbose);

	  // If cached image is valid, output it.
	  if(!$ignoreCache && is_file($this->cacheFileName) && $imageModifiedTime < $cacheModifiedTime) {
	    $this->verbose("Cache file is valid, output it.");
	    $this->outputImage($this->cacheFileName, $verbose);
	  }

	  $this->verbose("Cache is not valid, process image and create a cached version of it.");
	  $this->verbose("File extension is: {$this->fileExtension}");
  	}


  	$this->openOriginalImage();

    $this->resizeImage();

    // Apply filters and postprocessing of image
    if($sharpen) {
      $this->image = sharpenImage($this->image);
    }
    
    $this->saveImage();

    
    
    
    // Output the resulting image
    $this->outputImage($verbose);

  }

 /**
   * Display error message.
   *
   * @param string $message the error message to display.
   */
  public function errorMessage($message) {
    header("Status: 404 Not Found");
    die('img.php says 404 - ' . htmlentities($message));
  }

  /**
   * Display log message.
   *
   * @param string $message the log message to display.
   */
  public function verbose($message) {
    echo "<p>" . htmlentities($message) . "</p>";
  }


  /**
   * Output an image together with last modified header.
   *
   * @param string $file as path to the image.
   * @param boolean $verbose if verbose mode is on or off.
   */
  public function outputImage($verbose) {
    $info = getimagesize($this->cacheFileName);
    !empty($info) or $this->errorMessage("The file doesn't seem to be an image.");
    $mime   = $info['mime'];
  
    $lastModified = filemtime($this->cacheFileName);  
    $gmdate = gmdate("D, d M Y H:i:s", $lastModified);
  
    if($verbose) {
      $this->verbose("Memory peak: " . round(memory_get_peak_usage() /1024/1024) . "M");
      $this->verbose("Memory limit: " . ini_get('memory_limit'));
      $this->verbose("Time is {$gmdate} GMT.");
    }
  
    if(!$verbose) header('Last-Modified: ' . $gmdate . ' GMT');
    if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified){
      if($verbose) { $this->verbose("Would send header 304 Not Modified, but its verbose mode."); exit; }
      header('HTTP/1.0 304 Not Modified');
    } else {  
      if($verbose) { $this->verbose("Would send header to deliver image with modified time: {$gmdate} GMT, but its verbose mode."); exit; }
      header('Content-type: ' . $mime);  
      readfile($this->cacheFileName);
    }
    exit;
  }


  /**
   * Sharpen image as http://php.net/manual/en/ref.image.php#56144
   * http://loriweb.pair.com/8udf-sharpen.html
   *
   * @param resource $image the image to apply this filter on.
   * @return resource $image as the processed image.
   */
  public function sharpenImage($image) {
    $matrix = array(
      array(-1,-1,-1,),
      array(-1,16,-1,),
      array(-1,-1,-1,)
    );
    $divisor = 8;
    $offset = 0;
    imageconvolution($image, $matrix, $divisor, $offset);
    return $image;
  }



  public function setIncomingArgs() {
  
    
    //
    // Get the incoming arguments
    //
    $src        = isset($_GET['src'])     ? $_GET['src']      : null;
    $verbose    = isset($_GET['verbose']) ? true              : null;
    $saveAs     = isset($_GET['save-as']) ? $_GET['save-as']  : null;
    $quality    = isset($_GET['quality']) ? $_GET['quality']  : 60;
    $ignoreCache = isset($_GET['no-cache']) ? true           : null;
    $newWidth   = isset($_GET['width'])   ? $_GET['width']    : null;
    $newHeight  = isset($_GET['height'])  ? $_GET['height']   : null;
    $cropToFit  = isset($_GET['crop-to-fit']) ? true : null;
    $sharpen    = isset($_GET['sharpen']) ? true : null;
    $pathToImage = realpath(IMG_PATH . $src);

    
    //
    // Validate incoming arguments
    //
    is_dir(IMG_PATH) or $this->errorMessage('The image dir is not a valid directory.');
    is_writable(CACHE_PATH) or $this->errorMessage('The cache dir is not a writable directory.');
    isset($src) or $this->errorMessage('Must set src-attribute.');
    preg_match('#^[a-z0-9A-Z-_\.\/]+$#', $src) or $this->errorMessage('Filename contains invalid characters.');
    substr_compare(IMG_PATH, $pathToImage, 0, strlen(IMG_PATH)) == 0 or $this->errorMessage('Security constraint: Source image is not directly below the directory IMG_PATH.');
    is_null($saveAs) or in_array($saveAs, array('png', 'jpg', 'jpeg')) or $this->errorMessage('Not a valid extension to save image as');
    is_null($quality) or (is_numeric($quality) and $quality > 0 and $quality <= 100) or $this->errorMessage('Quality out of range');
    is_null($newWidth) or (is_numeric($newWidth) and $newWidth > 0 and $newWidth <= $this->maxWidth) or $this->errorMessage('Width out of range');
    is_null($newHeight) or (is_numeric($newHeight) and $newHeight > 0 and $newHeight <= $this->maxHeight) or $this->errorMessage('Height out of range');
    is_null($cropToFit) or ($cropToFit and $newWidth and $newHeight) or $this->errorMessage('Crop to fit needs both width and height to work');
  
  	$this->pathToImage = $pathToImage;
    $this->incomingArgs = compact('src','verbose','saveAs','quality','ignoreCache','newWidth','newHeight','cropToFit','sharpen');
  }

  
  //
  // Displaying log 
  //
  public function displayLog($verbose) {
    $query = array();
    parse_str($_SERVER['QUERY_STRING'], $query);
    unset($query['verbose']);
    $url = '?' . http_build_query($query);
  

    echo <<<EOD
    <html lang='en'>
    <meta charset='UTF-8'/>
    <title>img.php verbose mode</title>
    <h1>Verbose mode</h1>
    <p><a href=$url><code>$url</code></a><br>
    <img src='{$url}' /></p>
EOD;

  }


  //
  // Get information on the image
  //
  public function setImageInfo($verbose) {
    
    $imgInfo = list($width, $height, $type, $attr) = getimagesize($this->pathToImage);
    !empty($imgInfo) or errorMessage("The file doesn't seem to be an image.");
    $mime = $imgInfo['mime'];
    
    if($verbose) {
      $filesize = filesize($this->pathToImage);
      $this->verbose("Image file: {$this->pathToImage}");
      $this->verbose("Image information: " . print_r($imgInfo, true));
      $this->verbose("Image width x height (type): {$width} x {$height} ({$type}).");
      $this->verbose("Image file size: {$filesize} bytes.");
      $this->verbose("Image mime type: {$mime}.");
    }

    // Variables that will be used in other funktioner.
    $this->imageInfo = compact('width','height','type','attr','filesize');
  }


  //
  // Calculate new width and height for the image
  //
  public function setNewImageDimensions() {
	extract($this->incomingArgs);
    extract($this->imageInfo);
    $aspectRatio = $width / $height;
    
    if($cropToFit && $newWidth && $newHeight) {
      $targetRatio = $newWidth / $newHeight;
      $cropWidth   = $targetRatio > $aspectRatio ? $width : round($height * $targetRatio);
      $cropHeight  = $targetRatio > $aspectRatio ? round($width  / $targetRatio) : $height;
      if($verbose) { $this->verbose("Crop to fit into box of {$newWidth}x{$newHeight}. Cropping dimensions: {$cropWidth}x{$cropHeight}."); }
      $this->cropHeight = $cropHeight;
      $this->cropWidth  = $cropWidth;
    }
    else if($newWidth && !$newHeight) {
      $newHeight = round($newWidth / $aspectRatio);
      if($verbose) { $this->verbose("New width is known {$newWidth}, height is calculated to {$newHeight}."); }
    }
    else if(!$newWidth && $newHeight) {
      $newWidth = round($newHeight * $aspectRatio);
      if($verbose) { $this->verbose("New height is known {$newHeight}, width is calculated to {$newWidth}."); }
    }
    else if($newWidth && $newHeight) {
      $ratioWidth  = $width  / $newWidth;
      $ratioHeight = $height / $newHeight;
      $ratio = ($ratioWidth > $ratioHeight) ? $ratioWidth : $ratioHeight;
      $newWidth  = round($width  / $ratio);
      $newHeight = round($height / $ratio);
      if($verbose) { $this->verbose("New width & height is requested, keeping aspect ratio results in {$newWidth}x{$newHeight}."); }
    }
    else {
      $newWidth = $width;
      $newHeight = $height;
      if($verbose) { $this->verbose("Keeping original width & heigth."); }
    }

    $this->incomingArgs['newWidth'] = $newWidth;
    $this->incomingArgs['newHeight'] = $newHeight;
    

  }


  //
  // Creating a filename for the cache
  //
  public function setCacheFileName() {
  	extract($this->incomingArgs);

    $parts          = pathinfo($this->pathToImage);
    $fileExtension  = $parts['extension'];
    $saveAs         = is_null($saveAs) ? $fileExtension : $saveAs;
    $quality_       = is_null($quality) ? null : "_q{$quality}";
    $cropToFit_     = is_null($cropToFit) ? null : "_cf";
    $sharpen_       = is_null($sharpen) ? null : "_s";
    $dirName        = preg_replace('/\//', '-', dirname($src));
    $cacheFileName = CACHE_PATH . "-{$dirName}-{$parts['filename']}_{$newWidth}_{$newHeight}{$quality_}{$cropToFit_}{$sharpen_}.{$saveAs}";
    $cacheFileName = preg_replace('/^a-zA-Z0-9\.-_/', '', $cacheFileName);
    
    if($verbose) { $this->verbose("Cache file is: {$cacheFileName}"); }

    $this->incomingArgs['saveAs'] = $saveAs;
    $this->fileExtension = $fileExtension;
    $this->cacheFileName = $cacheFileName;
  }


  //
  // Open up the original image from file
  //
  public function openOriginalImage() {

  	extract($this->incomingArgs);

    switch($this->fileExtension) {  
      case 'jpg':
      case 'jpeg': 
        $image = imagecreatefromjpeg($this->pathToImage);
        if($verbose) { $this->verbose("Opened the image as a JPEG image."); }
        break;  
      
      case 'png':  
        $image = imagecreatefrompng($this->pathToImage); 
        if($verbose) { $this->verbose("Opened the image as a PNG image."); }
        break;  
    
      default: errorPage('No support for this file extension.');
    }

    $this->image = $image;

  }


  //
  // Resize the image if needed
  //
  public function resizeImage() {

    extract($this->incomingArgs);
    extract($this->imageInfo);

    if($cropToFit) {
      if($verbose) { verbose("Resizing, crop to fit."); }
      $cropX = round(($width - $this->cropWidth) / 2);  
      $cropY = round(($height - $this->cropHeight) / 2);    
      $imageResized = imagecreatetruecolor($newWidth, $newHeight);
      imagecopyresampled($imageResized, $this->image, 0, 0, $cropX, $cropY, $newWidth, $newHeight, $this->cropWidth, $this->cropHeight);
      $this->image 	= $imageResized;
      $this->imageInfo['width'] = $newWidth;
      $this->imageInfo['height'] = $newHeight;
    }
    else if(!($newWidth == $width && $newHeight == $height)) {
      if($verbose) { vgerbose("Resizing, new height and/or width."); }
      $imageResized = imagecreatetruecolor($newWidth, $newHeight);
      imagecopyresampled($imageResized, $this->image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
      $this->image  = $imageResized;
      $this->imageInfo['width']  = $newWidth;
      $this->imageInfo['height'] = $newHeight;
    }
  }


  //
  // Save the image
  //
  public function saveImage() {

    extract($this->incomingArgs);

    switch($saveAs) {
      case 'jpeg':
      case 'jpg':
        if($verbose) { verbose("Saving image as JPEG to cache using quality = {$quality}."); }
        imagejpeg($this->image, $this->cacheFileName, $quality);
      break;  
    
      case 'png':  
        if($verbose) { verbose("Saving image as PNG to cache."); }
        imagepng($this->image, $this->cacheFileName);  
      break;  
    
      default:
        $this->errorMessage('No support to save as this file extension.');
      break;
    }
    
    if($verbose) { 
      clearstatcache();
      $cacheFilesize = filesize($this->cacheFileName);
      verbose("File size of cached file: {$cacheFilesize} bytes."); 
      verbose("Cache file has a file size of " . round($cacheFilesize/$filesize*100) . "% of the original size.");
    }
  }


}