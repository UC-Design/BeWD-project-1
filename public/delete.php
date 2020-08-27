<?php
// check whether user is logged in, redirect to login if not.
require "user-check.php";
?>

<?php
    require "../config.php";
    require "common.php";

    // This code will only run if the delete button is clicked
    if (isset($_GET["id"])) {
	    // this is called a try/catch statement 
        try {
            // define database connection
            $connection = new PDO($dsn, $username, $password, $options);
            
            // set id variable
            $id = $_GET["id"];
            
            // Create the SQL 
            $sql = "DELETE FROM works WHERE id = :id";

            // Prepare the SQL
            $statement = $connection->prepare($sql);
            
            // bind the id to the PDO
            $statement->bindValue(':id', $id);
            
            // execute the statement
            $statement->execute();

            // Success message
            $success = "Work successfully deleted";

        } catch(PDOException $error) {
            // if there is an error, tell us what it is
            echo $sql . "<br>" . $error->getMessage();
        }
    };

    // This code runs on page load
    try {
        $connection = new PDO($dsn, $username, $password, $options);
		
        // SECOND: Create the SQL 
        $sql = "SELECT * FROM works";
        
        // THIRD: Prepare the SQL
        $statement = $connection->prepare($sql);
        $statement->execute();
        
        // FOURTH: Put it into a $result object that we can access in the page
        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
?>

<?php include "templates/header.php"; ?>

<h2>Delete works</h2>
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
			<a class="red" href='delete.php?id=<?php echo $row['id']; ?>'>Delete</a>
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