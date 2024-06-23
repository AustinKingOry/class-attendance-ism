<?php 
date_default_timezone_set('Africa/Nairobi');
session_start();
if(isset($_SESSION)){
    session_destroy();
}
$_SESSION = array();

header('Location: ' . $_SERVER['HTTP_REFERER']);

// header('Location: /');