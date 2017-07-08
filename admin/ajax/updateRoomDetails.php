<?php
// include Database connection file
include_once '../../includes/dbconnect.php';
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

// check request
if(isset($_POST))
{
    // get values
    $id = $mysqli->real_escape_string($_POST['id']);
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

    // Updaste User details
    $query = "UPDATE world SET name = '$name', description = '$description', X=$x, Y=$y WHERE id = '$id'";

    if (!$result = $mysqli->query($query)) {
        exit($mysqli->error);
    }
}