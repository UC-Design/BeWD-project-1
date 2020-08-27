<?php
// check whether user is logged in, redirect to login if not.
require "user-check.php";
?>

<?php
// this code will only execute after the submit button is clicked
if (isset($_POST['submit'])) {
	
	// include the config file that we created before
	require "../config.php";
	
	if( empty(trim($_POST['artistname'])) or empty(trim($_POST['worktitle'])) ){
        $input_err = "Please enter artist's name and work title.";
    }
	
	if( !empty($_FILES["imagelocation"]["name"]) ){
		//// https://www.w3schools.com/php/php_file_upload.asp
		$target_dir = "uploads/";

		//The name of the file on the client machine.
		$target_file = $target_dir . basename($_FILES["imagelocation"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Use getimagesize to check if image file is an actual image or fake
		// tmp_name is the temporary filename stored on the server.
		$check = getimagesize($_FILES["imagelocation"]["tmp_name"]);
		if($check !== false) {
			// echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			//echo "File is not an image.";
			$upload_err = "File is not an image.";
			$uploadOk = 0;
		}

		// Check if file already exists
		if (file_exists($target_file)) {
		    //echo "Sorry, a file with that name already exists.";
			$upload_err = "Sorry, a file with that name already exists.";
			$uploadOk = 0;
		}

		// Check file size (limit in bytes)
		if ($_FILES["imagelocation"]["size"] > 500000) {
		  //echo "Sorry, your file must be smaller than 500kb";
			$upload_err = "Sorry, your file must be smaller than 500kb.";
			$uploadOk = 0;
		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$upload_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
			//exit();
			// if everything is ok, try to upload file
		} else {
			if ( move_uploaded_file($_FILES["imagelocation"]["tmp_name"], $target_file) ) {
//				echo "The file ". basename( $_FILES["imagelocation"]["name"] ). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}
	////
	
	if( empty($input_err) and empty($upload_err) ){
		try {

			// FIRST: Connect to the database
			$connection = new PDO($dsn, $username, $password, $options);
			
			$userid = $_SESSION['id'];
			$artistname = $_POST['artistname'];
			$worktitle = $_POST['worktitle'];
			$workdate = $_POST['workdate'];
			$worktype = $_POST['worktype'];
			
			// SECOND: Get the contents of the form and store it in an array
			$new_work = array(
				"userid" => $userid,
				"artistname" => $artistname,
				"worktitle" => $worktitle,
				"workdate" => $workdate,
				"worktype" => $worktype,
				"imagelocation" => basename( $_FILES["imagelocation"]["name"])
			);

			// THIRD: Turn the array into a SQL statement
			$sql = "
					INSERT 
					INTO works (userid, artistname, worktitle, workdate, worktype, imagelocation) 
					VALUES (:userid, :artistname, :worktitle, :workdate, :worktype, :imagelocation)
			";

			// FOURTH: Now write the SQL to the database
			$statement = $connection->prepare($sql);
			$statement->execute($new_work);

		} catch(PDOException $error) {
			// if there is an error, tell us what it is
			echo $sql . "<br>" . $error->getMessage();
		}
	}
}
?>

<?php include "templates/header.php"; ?>

<?php 
if (isset($_POST['submit']) && $statement) {
	echo "<p class='success'><b>" . $worktitle . "</b> successfully added!</p>" ;
}
?>

<h2>Add a work</h2>

<!--form to collect data for each artwork-->
<form class="input" method="post" enctype="multipart/form-data">
	<?php
	// conditional: if there is an error add the message.
	echo (!empty($input_err)) ? '<p class="error">' . $input_err . '</p>' : ''; 
	echo (!empty($upload_err)) ? '<p class="error">' . $upload_err . '</p>' : ''; 
	?>
	<div class="form-group">
		<label for="artistname">Artist Name</label>
		<input type="text" name="artistname" id="artistname" value="<?php echo $artistname; ?>">
	</div>
	<div class="form-group">
		<label for="worktitle">Work Title</label>
		<input type="text" name="worktitle" id="worktitle" value="<?php echo $worktitle; ?>">
	</div>
	<div class="form-group">
		<label for="workdate">Work Date</label>
		<input type="text" name="workdate" id="workdate" value="<?php echo $workdate; ?>">
	</div>
	<div class="form-group">
		<label for="worktype">Work Type</label>
		<input type="text" name="worktype" id="worktype" value="<?php echo $worktype; ?>">
	</div>
	<div class="form-group">
		<label for="worktype">Work Image</label>
		<input type="file" name="imagelocation" id="imagelocation">
	</div>
	<input type="submit" name="submit" value="Submit">
</form>

<?php include "templates/footer.php"; ?>