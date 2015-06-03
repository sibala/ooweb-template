<?php
/**
* define() defines a constant which unlike variables, can not be redefined. 
* Constants are also global meaning the can be reached in any scope.
*/

//xampp
//define("DSN", "mysql:host=localhost;dbname=my_db;");


//For MAMP
$db = new PDO("mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=movie;user=root;password=root", "root", "root");
define("USER", "root");
define("PASS", "root");
$opt = array(
    // any occurring errors will be thrown as PDOException
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // an SQL command to execute when connecting
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
);

$pdo = new PDO(DSN, USER, PASS, $opt);

//For XAMPP/WAMPP
//$db = new PDO("mysql:host=localhost;dbname=my_db;", "root", "");


$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
?>