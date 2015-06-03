<?php
class CPage extends CContent {
	
  public function __contstruct($options) {
  	parent::__construct($options);
  }

  /**
   * Fetches all pages from Content
   *
   * @url array
   * @return object
   */
  public function selectByUrl($url = []) {
  	$sql = "
	  SELECT * 
	  FROM Content 
	  WHERE 
	    url=? AND
	    published <= NOW()
	";
	$res = $this->ExecuteSelectQueryAndFetchAll($sql, $url);
	return $res[0];
  }
}
?>