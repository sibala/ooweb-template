<?php
class CUser extends CDatabase{

  public function __construct($option) {
  	parent::__construct($option);
  }


  public function doLogin($acronym, $password) {

  	$sql = "SELECT acronym, name FROM User WHERE acronym = ? AND password = md5(concat(?, salt))";
    $params = array($acronym, $password);
	$res = $this->ExecuteSelectQueryAndFetchAll($sql, $params);

    if(isset($res[0])) {
      $_SESSION['user'] = $res[0];
    }
  
  }


  public function doLogout() {
  	unset($_SESSION['user']);
	header('Location: login.php');
  }


  public function getStatus() {
  	// Check if user is authenticated.
    $acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
     
    if($acronym) {
      $output = "Logged in as: $acronym ({$_SESSION['user']->name}) | <a href='logout.php'>logout</a>";
    }
    else {
      $output = "You are NOT logged in | <a href='login.php'>login</a>";
    }

    return $output;
  }


  public function getLoginForm() {

  	$form = "<div class='dbtable'>
              <form action='' method='POST'>
              <fieldset>
                <legend>Login</legend>
                <label>Name: </label><br>
                <p><input type='text' name='acronym'></p>
                <label>Password: </label><br>
                <p><input type='password' name='password'></p>
                <p><input type='submit' name='doLogin' value='login'></p>
              </fieldset>
            </div>";

    return $form;
  }



}