<?php
	if(isset($_POST['name']) && isset($_POST['description']))
	{
	include_once '../../includes/dbconnect.php';
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

		// get values 
		$name = $mysqli->real_escape_string($_POST['name']);
		$description = $mysqli->real_escape_string($_POST['description']);

		if(isset($_POST['x']))
			$x = $mysqli->real_escape_string($_POST['x']);
		else
			$x = "NULL";
		if(isset($_POST['y']))
			$y = $mysqli->real_escape_string($_POST['y']);
		else
			$y = "NULL";



		$query = "INSERT INTO world(name,description,X,Y) VALUES('$name', '$description', $x, $y)";
		error_log($query);
		if (!$result = $mysqli->query($query)) {
	        exit($mysqli->error);
	    }
	    echo "1 Record Added!";
	}
?>