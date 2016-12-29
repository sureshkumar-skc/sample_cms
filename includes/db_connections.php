<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "widget_corp";

define("DB_SERVER", $dbhost);
define("DB_USER", $dbuser);
define("DB_PASS", $dbpass);
define("DB_NAME", $dbname);

$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if(mysqli_connect_errno()){
	die("Database Connection failed: ". mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" );
}
?>