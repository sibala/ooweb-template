<?php

class CHTMLTable extends CMovieSearch {

  public function getHTMLTable($hits, $page) {

  $rows = count($this->result);
	$hitsPerPage = $this->getHitsPerPage(array(2, 4, 8), $hits);
	$navigatePage = $this->getPageNavigation($hits, $page);

  	// Put results into a HTML-table
  	$table  = "<div class='dbtable cf' >";
  	$table .= "<div class='rows'>{$this->rows} hits. {$hitsPerPage}</div>";
  	$table .= "<table>";
    $table .= "<tr><th>Id " . $this->orderby('id') . "</th><th>Picture</th><th>Title " . $this->orderby('title') . "</th><th>Year " . $this->orderby('year') . "</th><th>Genre</th>";
    $table .= isset($_SESSION['user']) ? "<th>Action</th>" : null;
    $table .= "</tr>";
    foreach($this->result AS $key => $val) {
      $table .= "<td>{$val->id}</td>";   
      $table .= "<td><a href='movie_view.php?id={$val->id}'><img src='img.php?src={$val->image}&width=100' alt='{$val->title}' /><a/></td>";
      $table .= "<td><a href='movie_view.php?id={$val->id}'>{$val->title}<a/></td>";
      $table .= "<td>{$val->year}</td>";
      $table .= "<td>{$val->genre}</td>";
      $table .= isset($_SESSION['user']) ? "<td> <a href='movie_edit.php?id={$val->id}'>edit<a/> <a href='?delete={$val->id}'>delete<a/></td>" : null;
      $table .= "</tr>";
    }
  	$table .= "</table>";
  	$table .= "<div class='pages'>{$navigatePage}</div>";
	$table .= "</div>";

    return $table;
  }
  

  
  /**
   * Create links for hits per page.
   *
   * @param array $hits a list of hits-options to display.
   * @param array $current value.
   * @return string as a link to this page.
   */
  function getHitsPerPage($hits, $current=null) {
    $nav = "Hits per page: ";
    foreach($hits AS $val) {
      if($current == $val) {
        $nav .= "$val ";
      }
      else {
        $nav .= "<a href='" . $this->getQueryString(array('hits' => $val)) . "'>$val</a> ";
      }
    }  
    return $nav;
  }
  
  
  
  /**
   * Create navigation among pages.
   *
   * @param integer $hits per page.
   * @param integer $page current page.
   * @param integer $max number of pages. 
   * @param integer $min is the first page number, usually 0 or 1. 
   * @return string as a link to this page.
   */
  function getPageNavigation($hits, $page, $min=1) {
    $nav  = ($page != $min) ? "<a href='" . $this->getQueryString(array('page' => $min)) . "'>&lt;&lt;</a> " : '&lt;&lt; ';
    $nav .= ($page > $min) ? "<a href='" . $this->getQueryString(array('page' => ($page > $min ? $page - 1 : $min) )) . "'>&lt;</a> " : '&lt; ';
  
    for($i=$min; $i<=$this->max; $i++) {
      if($page == $i) {
        $nav .= "$i ";
      }
      else {
        $nav .= "<a href='" . $this->getQueryString(array('page' => $i)) . "'>$i</a> ";
      }
    }
  
    $nav .= ($page < $this->max) ? "<a href='" . $this->getQueryString(array('page' => ($page < $this->max ? $page + 1 : $this->max) )) . "'>&gt;</a> " : '&gt; ';
    $nav .= ($page != $this->max) ? "<a href='" . $this->getQueryString(array('page' => $this->max)) . "'>&gt;&gt;</a> " : '&gt;&gt; ';
    return $nav;
  }
  
  
  
  /**
   * Method to create links for sorting
   *
   * @param string $column the name of the database column to sort by
   * @return string with links to order by column.
   */
  function orderby($column) {
    $nav  = "<a href='" . $this->getQueryString(array('orderby'=>$column, 'order'=>'asc')) . "'>&darr;</a>";
    $nav .= "<a href='" . $this->getQueryString(array('orderby'=>$column, 'order'=>'desc')) . "'>&uarr;</a>";
    return "<span class='orderby'>" . $nav . "</span>";
  }
}