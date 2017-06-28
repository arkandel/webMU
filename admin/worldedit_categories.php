<?php
include_once '../includes/dbconnect.php';
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
mysqli_set_charset($mysqli, 'utf8');

$column = $_POST["column"];
$value = $mysqli->real_escape_string($_POST["editval"]);

$id = $_POST["id"];

if($id)
{
	$sql = "UPDATE world_categories SET $column='$value' WHERE room_id=$id";
}
else
{
	$sql = "INSERT INTO world_categories ($column) VALUES ('$value')";
}

if (!$result = $mysqli->query($sql)) 
	{
		return ("Something went wrong. Please talk to staff.")
		die ('There was an error running world fetch query[' . $mysqli->error . ']');
	}
return ("The database operation was successful!");
?>
