<?php
if(!isset($_SESSION['user'])) {
	ob_start(); // restricts output of the script to headers only
	header("Location: login.php");
	exit();
} else { 
	ob_start();
	header("Location: welcome.php");
}
?>