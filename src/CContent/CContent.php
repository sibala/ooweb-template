<?php
class CContent extends CDatabase{

  public function __construct($options) {
  	parent::__construct($options);
  }

  /**
   * Creates table Content and resets tabel data
   *
   * @return bool
   */
  public function resetTable() {
    $sql = " 
      --
	  -- Create table for Content
	  --
	  DROP TABLE IF EXISTS Content;
	  CREATE TABLE Content
	  (
	    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
	    slug CHAR(80) UNIQUE,
	    url CHAR(80) UNIQUE,
	   
	    TYPE CHAR(80),
	    title VARCHAR(80),
	    DATA TEXT,
	    FILTER CHAR(80),
	   
	    published DATETIME,
	    created DATETIME,
	    updated DATETIME,
	    deleted DATETIME
	   
	  ) ENGINE INNODB CHARACTER SET utf8;
    ";
    $res1 = $this->ExecuteQuery($sql);

    $sql = '  
      INSERT INTO Content (slug, url, TYPE, title, DATA, FILTER, published, created) VALUES
        ("hem", "hem", "page", "Hem", "Detta är min hemsida. Den är skriven i [url=http://en.wikipedia.org/wiki/BBCode]bbcode[/url] vilket innebär att man kan formattera texten till [b]bold[/b] och [i]kursiv stil[/i] samt hantera länkar.\n\nDessutom finns ett filter \"nl2br\" som lägger in <br>-element istället för \\n, det är smidigt, man kan skriva texten precis som man tänker sig att den skall visas, med radbrytningar.", "bbcode,nl2br", NOW(), NOW()),
        ("om", "om", "page", "Om", "Detta är en sida om mig och min webbplats. Den är skriven i [Markdown](http://en.wikipedia.org/wiki/Markdown). Markdown innebär att du får bra kontroll över innehållet i din sida, du kan formattera och sätta rubriker, men du behöver inte bry dig om HTML.\n\nRubrik nivå 2\n-------------\n\nDu skriver enkla styrtecken för att formattera texten som **fetstil** och *kursiv*. Det finns ett speciellt sätt att länka, skapa tabeller och så vidare.\n\n###Rubrik nivå 3\n\nNär man skriver i markdown så blir det läsbart även som textfil och det är lite av tanken med markdown.", "markdown", NOW(), NOW()),
        ("blogpost-1", NULL, "post", "Välkommen till min blogg!", "Detta är en bloggpost.\n\nNär det finns länkar till andra webbplatser så kommer de länkarna att bli klickbara.\n\nhttp://dbwebb.se är ett exempel på en länk som blir klickbar.", "clickable,nl2br", NOW(), NOW()),
        ("blogpost-2", NULL, "post", "Nu har sommaren kommit", "Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost.", "nl2br", NOW(), NOW()),
        ("blogpost-3", NULL, "post", "Nu har hösten kommit", "Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost", "nl2br", NOW(), NOW())
  	';
    $res2 = $this->ExecuteQuery($sql);

    return $res1 && $res2;
  }
  

  /**
   * Delete from Content
   *
   * @params = array
   * @return bool
   */
  public function delete($params = []) {
   	$sql = "
	  DELETE FROM Content
  	  WHERE id = ?;
  	";

  	return $this->ExecuteQuery($sql, $params);

  }


  /**
   * Insert into Content
   *
   * @params = array
   * @return bool
   */
  public function create($params = []) {
  	$sql = "
  	  INSERT INTO Content (slug, url, TYPE, title, category, DATA, FILTER, published, created) VALUES
	    (?, ?, ?, ?, ?, ?, ?, NOW(), NOW());
	";

    return $this->ExecuteQuery($sql, $params);
  }

  /**
   * Update Content
   *
   * @params = array
   * @return bool
   */
  public function update($params =[]) {
    $sql = "
      UPDATE Content SET
        slug     = ?,
        url      = ?,
        type     = ?,
        title    = ?,
        category = ?,
        data     = ?,
        filter   = ?,
        published = ?,
        updated = NOW()
      WHERE 
        id = ?
    ";

    return $this->ExecuteQuery($sql, $params);
  }


  /**
   * Fetches all data from Content
   *
   * @return object
   */
  public function selectAll() {
  	$sql = "
  	  SELECT *, (published <= NOW()) AS available 
  	  FROM Content;
  	";
	
	  return $this->ExecuteSelectQueryAndFetchAll($sql);
  }

    /**
   * Fetches all data from Content
   *
   * @return object
   */
  public function selectAllCategories() {
    $sql = "
      SELECT DISTINCT(category) FROM Content;
    ";
  
    return $this->ExecuteSelectQueryAndFetchAll($sql);
  }


  /**
   * Fetches data from Content by id 
   * 
   * @params = array
   * @return object
   */
  public function selectById($params = []) {
  	$sql = " 
  	  SELECT *, (published <= NOW()) AS available 
  	  FROM Content
  	  WHERE id = ?;
  	";
	
	  $res = $this->ExecuteSelectQueryAndFetchAll($sql, $params);
	  return $res[0];
  }

  /**
   * Create a link to the content, based on its type.
   *
   * @param object $content to link to.
   * @return string with url to display content.
   */
  public function getUrlToContent($content) {
    switch($content->TYPE) {
      case 'page': return "page.php?url={$content->url}"; break;
      case 'post': return "blog.php?slug={$content->slug}"; break;
      default: return null; break;
    }
  }
}
?>