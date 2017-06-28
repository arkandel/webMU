<?php
include_once '../includes/dbconnect.php';
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
mysqli_set_charset($mysqli, 'utf8');

$id = $_POST["id"];
if (isset($_POST["column"]) && !empty($_POST["column"]))
	$column = $_POST["column"];

if (isset($_POST["editval"]) && !empty($_POST["editval"]))
	$value = $mysqli->real_escape_string($_POST["editval"]);

if($id)
{
	if(isset($_POST["delete"]))
	{
		$sql = "DELETE FROM world WHERE id=$id";
		$result = $mysqli->query($sql);
		header('Content-Type: application/json');
		$response["message"]="Room deleted!";
		$response["extra"]="$id";
		echo json_encode($response);
	}
	else
	{
		$sql = "UPDATE world SET $column='$value' WHERE id=$id";
		$result = $mysqli->query($sql);
		$response["message"]="Room edited!";
		$response["extra"]="";
		echo json_encode($response);
	}
}
else
if (isset($_POST["column"]) && $_POST["column"])
{
	$sql = "INSERT INTO world ($column) VALUES ('$value')";
	$result = $mysqli->query($sql);
	$newID = mysqli_insert_id($mysqli);
	$response["message"]="New room inserted!";
	$response["extra"]=$newID;
	echo json_encode($response);
}

/*if (!$result = $mysqli->query($sql)) 
	{
		die ('There was an error running world fetch query[' . $mysqli->error . ']');

	}
*/
error_log($sql);
?>
