<?php
// check whether user is logged in, redirect to login if not.
require "user-check.php";
?>

<?php
session_start();
// this code will only execute after the submit or search buttons are clicked
if (isset($_POST['search']) or isset($_POST['submit'])) {
	
	// include the config file that we created before
	require "../config.php";
	
	// to escape the search term
	require "common.php";
	
	// this is called a try/catch statement
	try {
		// FIRST: Connect to the database
		$connection = new PDO($dsn, $username, $password, $options);
		
		// SECOND: Create the SQL
		if(isset($_POST['search'])){
			$uid = $_SESSION['id'];
			$query = escape($_POST['q']);
			$sql = "SELECT DISTINCT * FROM works WHERE 
			userid = $uid
				AND
			(artistname LIKE '%" . $query . "%'
				OR 
			worktitle LIKE '%" . $query . "%'
				OR
			workdate LIKE '%" . $query . "%'
				OR
			worktype LIKE '%" . $query . "%')
				";
		}else{
			$sql = "SELECT * FROM works WHERE userid =" . $_SESSION['id'];
		}
		// THIRD: Prepare the SQL
		$statement = $connection->prepare($sql);
		$statement->execute();
		
		// FOURTH: Put it into a $result object that we can access in the page
		$result = $statement->fetchAll();
	} catch(PDOException $error) {
		// if there is an error, tell us what it is.  The dots are string concatenations
		echo $sql . "<br>" . $error->getMessage();
	}
}
?>

<?php include "templates/header.php"; ?>

<?php
if (isset($_POST['search']) or isset($_POST['submit'])) {
	
	//if there are some results
	if ($result && $statement->rowCount() > 0) { 
?>
		<h2>Result</h2>
		<?php
		// Loop through each result in the array
		foreach($result as $row) {
		?>
		<div class="result">
			<?php
			if( $row["imagelocation"] !== NULL && $row["imagelocation"] !== "" ){
				echo "<img src='uploads/" . $row["imagelocation"] . "' alt='" . $row['worktitle'] ." by " . $row['artistname'] . "'>";
			}
			else
			{
				echo "<p class='small'>No image available.</p>";
			}
			?>
			<p>ID:<?php echo $row["id"]; ?></p>
			<p>Artist Name: <?php echo $row['artistname']; ?></p>
			<p>Work Title: <?php echo $row['worktitle']; ?></p>
			<p>Work Date: <?php echo $row['workdate']; ?></p>
			<p>Work type: <?php echo $row['worktype']; ?></p>
		</div>
			<?php
			// this willoutput all the data from the array
			//echo '<pre>'; var_dump($row);
			?>
		<?php } //close the foreach
	}
	else
	{
		if(isset($_POST['search'])){
			echo "<p>No results found matching your query: " . $query . "</p>";
		}
		else
		{
			echo "<p>No items in collection</p>";
		}
	}
}
?>
<form method="post">
	<label for="collection-search">Search the collection:</label>
	<input type="search" id="collection-search" name="q" placeholder="">
	<input type="submit" name="search" value="Go">
</form>

<p>OR</p>

<form method="post">
	<input type="submit" name="submit" value="View all">
</form>

<?php include "templates/footer.php"; ?>