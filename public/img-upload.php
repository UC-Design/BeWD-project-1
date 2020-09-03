<?php
//// https://www.w3schools.com/php/php_file_upload.asp
		$target_dir = "uploads/";

		//The name of the file on the client machine.
		$target_file = $target_dir . basename($_FILES["imagelocation"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		
		// A bit suss, should have a more robust system for getting the ID
		$imgid = $_POST['id'] . "." . $imageFileType;
		$idname = $target_dir . $imgid;

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

		// Check if file already exists - code replaced by later block that deletes old img
		//if (file_exists($target_file)) {
				//$upload_err = "A file with that name already exists.";
				//$uploadOk = 0;
		//}

		// Check file size (limit in bytes)
		if ($_FILES["imagelocation"]["size"] > 500000) {
		  //echo "Sorry, your file must be smaller than 500kb";
			$upload_err = "Your file must be smaller than 500kb.";
			$uploadOk = 0;
		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$upload_err = "Only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded: " . $upload_err;
			//exit();
			// if everything is ok, try to upload file
		} else {
			if (file_exists($idname)) {
				// unlink effectively deletes a file and returns true if successful
				if( !unlink($idname) ){
					echo "File could not be deleted.";
					// end?
				}
			}
			//if ( move_uploaded_file($_FILES["imagelocation"]["tmp_name"], $target_file) ) {
			if ( move_uploaded_file($_FILES["imagelocation"]["tmp_name"], $idname) ) {
				// file is uploaded
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
?>