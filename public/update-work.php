<?php 
    require "user-check.php";
	require "../config.php";
    require "common.php";

    // run when submit button is clicked
    if (isset($_POST['submit'])) {
		
		if( !empty($_FILES["imagelocation"]["name"]) ){
			include "img-upload.php";
		}
		
        try {
            $connection = new PDO($dsn, $username, $password, $options);  
            
            //grab elements from form and set as variable
            $work =[
              "id"         => $_POST['id'],
              "artistname" => $_POST['artistname'],
              "worktitle"  => $_POST['worktitle'],
              "workdate"   => $_POST['workdate'],
              "worktype"   => $_POST['worktype'],
              "date"	   => $_POST['date'],
			  "imagelocation" => $imgid
            ];
            
            // create SQL statement
            $sql = "UPDATE `works` 
                    SET artistname = :artistname, 
                        worktitle = :worktitle, 
                        workdate = :workdate, 
                        worktype = :worktype, 
                        date = :date,
						imagelocation = :imagelocation
                    WHERE id = :id";

            //prepare sql statement
            $statement = $connection->prepare($sql);
            
            //execute sql statement
            $statement->execute($work);

        } catch(PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }

    // GET data from DB
    //simple if/else statement to check if the id is available
    if (isset($_GET['id'])) {
        //yes the id exists 
        
        try {
            // standard db connection
            $connection = new PDO($dsn, $username, $password, $options);
            
            // set if as variable
            $id = $_GET['id'];
			$uid = $_SESSION['id'];
            
            //select statement to get the right data
            $sql = "SELECT * FROM works WHERE id = :id AND userid = :uid";
            
            // prepare the connection
            $statement = $connection->prepare($sql);
            
            //bind the id to the PDO id
            $statement->bindValue(':id', $id);
			$statement->bindValue(':uid', $uid);
            
            // now execute the statement
            $statement->execute();
            
            // attach the sql statement to the new work variable so we can access it in the form
            $work = $statement->fetch(PDO::FETCH_ASSOC);
            
        } catch(PDOExcpetion $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    } else {
        // no id, show error
        echo "No id - something went wrong";
        //exit;
    };


?>

<?php include "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) : ?>
	<p>Work successfully updated.</p>
<?php endif; ?>

<h2>Edit a work</h2>
<h3>ID: <?php echo escape($work['id']); ?></h3>
<?php
			if( $work["imagelocation"] !== NULL && $work["imagelocation"] !== "" ){
				echo "<img src='uploads/" . $work["imagelocation"] . "' alt='" . $work['worktitle'] ." by " . $work['artistname'] . "'>";
			}
			else
			{
				echo "<p class='small'>No image available.</p>";
			}
			?>
<form class="input" method="post" enctype="multipart/form-data">
    <div class="form-group">
<!--    	<label for="id">ID</label>-->
<!-- Make the ID hidden and readonly so the user doesn't edit the wrong item in the DB -->
    	<input readonly type="hidden" name="id" id="id" value="<?php echo escape($work['id']); ?>" >
    </div>
	<div class="form-group">
    	<label for="artistname">Artist Name</label>
    	<input type="text" name="artistname" id="artistname" value="<?php echo escape($work['artistname']); ?>">
	</div>
	<div class="form-group">
    	<label for="worktitle">Work Title</label>
    	<input type="text" name="worktitle" id="worktitle" value="<?php echo escape($work['worktitle']); ?>">
	</div>
	<div class="form-group">
    	<label for="workdate">Work Date</label>
    	<input type="text" name="workdate" id="workdate" value="<?php echo escape($work['workdate']); ?>">
	</div>
	<div class="form-group">
    	<label for="worktype">Work Type</label>
    	<input type="text" name="worktype" id="worktype" value="<?php echo escape($work['worktype']); ?>">
	</div>
	<div class="form-group">
    	<label for="date">Date Modified</label>
    	<input type="text" name="date" id="date" value="<?php echo escape($work['date']); ?>">
	</div>
	<div class="form-group">
		<label for="worktype">Upload/update Work Image</label>
		<input type="file" name="imagelocation" id="imagelocation">
	</div>
    <input type="submit" name="submit" value="Save">

</form>

<?php include "templates/footer.php"; ?>
