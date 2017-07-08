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

    // Updaste User details
    $query = "UPDATE world SET name = '$name', description = '$description' WHERE id = '$id'";

    if (!$result = $mysqli->query($query)) {
        exit($mysqli->error);
    }
}