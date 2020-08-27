<?php
// check whether user is logged in, redirect to login if not.
require "user-check.php";
?>

<?php
// include the config file that we created before
require "../config.php";
// this is called a try/catch statement
try {
	// FIRST: Connect to the database
	$connection = new PDO($dsn, $username, $password, $options);
	// SECOND: Create the SQL
	$sql = "SELECT * FROM works WHERE userid =" . $_SESSION['id'];
	// THIRD: Prepare the SQL
	$statement = $connection->prepare($sql);
	$statement->execute();
	// FOURTH: Put it into a $result object that we can access in the page
	$result = $statement->fetchAll();
} catch(PDOException $error) {
	// if there is an error, tell us what it is.  The dots are string concatenations
	echo $sql . "<br>" . $error->getMessage();
}
?>

<?php include "templates/header.php"; ?>

<h2>Edit works</h2>
<?php
// This is a loop, which will loop through each result in the array
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
		<a href='update-work.php?id=<?php echo $row['id']; ?>'>Edit</a>
	</div>
	<?php
	// this willoutput all the data from the array
	//echo '<pre>'; var_dump($row);
	?>
<?php }; //close the foreach 
?>

<form method="post">
<input type="submit" name="submit" value="View all">
</form>
<?php include "templates/footer.php"; ?>