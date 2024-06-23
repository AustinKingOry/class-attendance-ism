<?php 
$host = "localhost";
$database = "class-attendance";
$user = "root";
$password = "";
$conn = new mysqli($host,$user,$password,$database);
if($conn)
if ($conn->connect_error) {
	die('Connect Error (' .
	$conn->connect_errno . ') '.
	$conn->connect_error);
}
date_default_timezone_set('Africa/Nairobi');
$current_date = date("Y-m-d");
$current_time = date('G:i:s T');
$cur_time_stamp = date('Y-m-d G:i:s');