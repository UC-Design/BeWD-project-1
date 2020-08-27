<!doctype html>
<html lang="en">
<head>
    <title>Collection Tracker</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/style.css">
<!--	<link rel="stylesheet" href="assets/css/bootstrap.css">-->
</head>
<body>
	<header>
		<div class="wrapper">
			<nav>
				<h1><a href="index.php">
					<?php if( isset($_SESSION["username"]) ){
					echo htmlspecialchars($_SESSION["username"]) . "'s ";
					}  ?>
					Collection Tracker</a></h1>
				<ul>
					<li><a href="create.php">Add a new artwork</a></li>
					<li><a href="update.php">Edit an artwork</a></li>
					<li><a href="delete.php">Delete an artwork</a></li>
					<li><a href="read.php">Find an artwork</a></li>
				</ul>
			</nav>
		</div>
	</header>
	<main>
		<div class="wrapper">