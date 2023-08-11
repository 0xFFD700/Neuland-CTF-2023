<?php
$target_dir = "uploads/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $file_name = basename($_FILES["image"]["name"]);

	$extension = pathinfo($file_name, PATHINFO_EXTENSION);
	$blacklist = array('php', 'php3', 'php4', 'php5', 'php6', 'php7',  'phps', '.pgif');

	if (in_array($extension, $blacklist)) {
        header("Location: index.php?result=Only images are allowed!&status=error");
        die();
	}

    if (!preg_match('/^(?=.*\.(jpg|jpeg|png|gif)).*$/', $file_name)) {
        header("Location: index.php?result=Only images are allowed!&status=error");
        die();
	}

	$file_id = uniqid();
	$new_file_name = $target_dir . $file_name;

	if (move_uploaded_file($_FILES["image"]["tmp_name"], $new_file_name)) {
 	    header("Location: index.php?result=Thank you for your valuable feedback. We have successfully received your review. Your insights are important to us.&status=success");
	} else {
    	header("Location: index.php?result=Sorry, there was an error uploading your file.&status=error");
	}
	die();
}
?>
