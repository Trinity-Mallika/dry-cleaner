<?php
date_default_timezone_set('Asia/Kolkata');
class Database
{
	public $con;
	public function __construct()
	{
		// echo $_SERVER["SERVER_NAME"];
		if ($_SERVER["SERVER_NAME"] == "localhost"  || $_SERVER["SERVER_NAME"] == "trinity") {
			//echo "asdfasd";die;
			$dbhost = "localhost";
			$dbuser = "root";
			$dbpass = "";
			$db = "dry-cleaner";
			$this->con = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
		} else {
			$dbhost = "localhost";
			$dbuser = "fgspogor_trinitycrm";
			$dbpass = "S4L#,h_5@9-B";
			$db = "fgspogor_ganpati";
			$this->con = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
		}
		if (!$this->con) {
			die('Could not connect:' . mysqli_connect_error());
		}
	}
}
