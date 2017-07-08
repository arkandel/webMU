<?php
// check request
if(isset($_POST['id']) && isset($_POST['id']) != "")
{
    include_once '../../includes/dbconnect.php';
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    // get user id
    $room_id = $_POST['id'];

    // delete User
    $query = "DELETE FROM world WHERE id = '$room_id'";
    if (!$result = $mysqli->query($query)) {
        exit($mysql->error);
    }
}
?>