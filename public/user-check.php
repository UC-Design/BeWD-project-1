<?php
session_start(); // gives access to session variables
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
}
else
{
	header("Location: login.php");
	exit();
}
?>