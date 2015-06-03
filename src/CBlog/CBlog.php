<?php
class CBlog extends CContent {
  

  private $posts;


  public function __contstruct($options) {
  	parent::__construct($options);
  	
  }


  /**
   * Fetches posts from Content
   *
   * @slug string
   * @category string
   */
  public function setPosts($category, $slug) {
	$slugSql = $slug ? 'slug = ?' : '1';
	$categorySql = $category ? 'category = ?' : '1';
	$params = $slug ? [$slug] : [$category];
  	
  	$sql = "
	  SELECT *
	  FROM Content
	  WHERE
	    type = 'post' AND
	    $slugSql AND
	    $categorySql AND
	    published <= NOW()
	  ORDER BY updated DESC
	";

	$this->posts = $this->ExecuteSelectQueryAndFetchAll($sql, $params);

  }


  /**
   * @return object
   */
  public function getPosts() {
  	return $this->posts;
  }


  /**
   * Displays and filters post/posts
   *
   * @return string
   */
  public function showPosts() {
  	$filter = new CTextFilter();	
  	$output  = "<section style='float:left;width:68%;'>";
  	$output .= isset($_SESSION['user']) ? "<a href='blog_create.php'>Create new post</a>" : null ;
  	if(isset($this->posts[0])) {
	  foreach($this->posts as $c) {
		
	    $title  = htmlentities($c->title, null, 'UTF-8');
	    $data   = $filter->doFilter(htmlentities($c->DATA, null, 'UTF-8'), $c->FILTER);
	 	
	    $output .= "<article>";
		$output .= "<header>";
		$output .= "<h1><a href='blog.php?slug={$c->slug}'>{$title}</a>";
		$output .= isset($_SESSION['user']) ? "<span style='text-decoration:none;'> (<a href='blog_edit.php?id={$c->id}'>edit</a>) (<a href='?delete={$c->id}'>delete</a>)</span></h1>" : "</h1>" ;
		$output .=  "<b>Created:</b> {$c->created}";
		$output .=  "</header>";
		$output .=  count($this->posts) > 1 ? substr($data, 0, 100) . " <a href='blog.php?slug={$c->slug}'>read more...</a>" : "{$data}";
		$output .=  "<footer><a href='blog.php'></a></footer>";
		$output .=  "</article>";
	  } 
	}
	$output .= "</section>";
	return $output;
  }


  public function showCategoryMenu() {
	$sql = "
	  SELECT DISTINCT(category) FROM Content
	";
	$categories = $this->ExecuteSelectQueryAndFetchAll($sql);

	$output  = "<aside style='float:right;width:30%;'>";
	$output .= "<h3>List posts by category</h3>";
	$output .= "<ul>";

	foreach($categories as $c){
	  $output .= "<li><a href='?category={$c->category}'>{$c->category}</a></li>";
	}

	$output .= "<li><a href='blog.php'>View all</a></li>";
	$output .= "</ul>";
	$output .="</aside>";

	return $output;

  }

}
?>