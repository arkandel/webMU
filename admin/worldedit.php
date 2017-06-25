<?php

$column = $_POST["column"];
$value = $_POST["editval"];
$id = $_POST["id"];
$sql = "UPDATE world SET $column='$value' WHERE id=$id";

include_once '../includes/dbconnect.php';
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

if (!$result = $mysqli->query($sql)) 
	{
		die ('There was an error running world fetch query[' . $mysqli->error . ']');
	}

?>
