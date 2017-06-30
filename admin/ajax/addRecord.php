<?php
	if(isset($_POST['name']) && isset($_POST['description']))
	{
	include_once '../../includes/dbconnect.php';
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

		// get values 
		$name = $mysqli->real_escape_string($_POST['name']);
		$description = $mysqli->real_escape_string($_POST['description']);

		$query = "INSERT INTO world(name,description) VALUES('$name', '$description')";
		error_log($query);
		if (!$result = $mysqli->query($query)) {
	        exit($mysqli->error);
	    }
	    echo "1 Record Added!";
	}
?>