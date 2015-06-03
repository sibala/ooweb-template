<?php
class CMovieSearch extends CDatabase{
	
  private $genres;
  private $genreLinks;
  protected $result;
  protected $rows;
  protected $max;


  public function __construct($option) {
  	parent::__construct($option);
  }
  

  /**
   * Use the current querystring as base, modify it according to $options and return the modified query string.
   *
   * @param array $options to set/change.
   * @param string $prepend this to the resulting query string
   * @return string with an updated query string.
   */
  public function getQueryString($options=array(), $prepend='?') {
    // parse query string into array
    $query = array();
    parse_str($_SERVER['QUERY_STRING'], $query);
  
    // Modify the existing query string with new options
    $query = array_merge($query, $options);
  
    // Return the modified querystring
    return $prepend . htmlentities(http_build_query($query));
  }


  public function getActiveGenre($genre = null) {

  	$sql = '
  	  SELECT DISTINCT G.name
  	  FROM Genre AS G
  	    INNER JOIN Movie2Genre AS M2G
  	      ON G.id = M2G.idGenre
  	';
  	$res = $this->ExecuteSelectQueryAndFetchAll($sql);
    $this->genres = $res;

    $this->genreLinks = null;
    foreach($res as $val) {

        $this->genreLinks .= "<a href='" . $this->getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
      
    }

  }


  public function searchResult($title, $genre, $hits, $page, $year1, $year2, $orderby, $order ) {

    // Prepare the query based on incoming arguments
    $sqlOrig = '
      SELECT 
        M.*,
        GROUP_CONCAT(G.name) AS genre
      FROM Movie AS M
        LEFT OUTER JOIN Movie2Genre AS M2G
          ON M.id = M2G.idMovie
        INNER JOIN Genre AS G
          ON M2G.idGenre = G.id
    ';
    $where    = null;
    $groupby  = ' GROUP BY M.id';
    $limit    = null;
    $sort     = " ORDER BY $orderby $order";
    $params   = array();

    // Select by title
    if($title) {
      $where .= ' AND title LIKE ?';
      $params[] = $title;
    } 

    // Select by year
    if($year1) {
      $where .= ' AND year >= ?';
      $params[] = $year1;
    } 
    if($year2) {
      $where .= ' AND year <= ?';
      $params[] = $year2;
    } 

    // Select by genre
    if($genre) {
      $where .= ' AND G.name = ?';
      $params[] = $genre;
    } 

    // Pagination
    if($hits && $page) {
      $limit = " LIMIT $hits OFFSET " . (($page - 1) * $hits);
    }

    // Complete the sql statement
    $where = $where ? " WHERE 1 {$where}" : null;
    $sql = $sqlOrig . $where . $groupby . $sort . $limit;
    $this->result = $this->ExecuteSelectQueryAndFetchAll($sql, $params);


    // Get max pages for current query, for navigation
    $sql = "
      SELECT
        COUNT(id) AS rows
      FROM 
      (
        $sqlOrig $where $groupby
      ) AS Movie
    ";
    $res = $this->ExecuteSelectQueryAndFetchAll($sql, $params);
    
    $this->rows = $res[0]->rows;
    $this->max = ceil($this->rows / $hits);

  }




  public function getSearchForm($title, $genre, $hits, $year1, $year2) {
  	$this->getActiveGenre($genre);

  	$form = "<form>
  	  <fieldset>
  	  <legend>Sök</legend>
  	  <input type=hidden name=genre value='{$genre}'/>
  	  <input type=hidden name=hits value='{$hits}'/>
  	  <input type=hidden name=page value='1'/>
  	  <p><label>Titel (delsträng, använd % som *): <input type='search' name='title' value='{$title}'/></label></p>
  	  <p><label>Välj genre:</label> {$this->genreLinks}</p>
  	  <p><label>Skapad mellan åren: 
  	      <input type='text' name='year1' value='{$year1}'/></label>
  	      - 
  	      <label><input type='text' name='year2' value='{$year2}'/></label>
  	  </p>
  	  <p><input type='submit' name='submit' value='Sök'/></p>
  	  <p><a href='?'>Visa alla</a></p>
  	  </fieldset>
  	</form>";

  	return $form;
  }

  public function getResult() {
  	return $this->result;
  }


  public function getGenres() {
    return $this->genres;
  }
  
}