<!DOCTYPE html>
<html>

<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="../js/hexgrid.js"></script>
	<link rel="stylesheet" href="hexgrid.css" />
	<script type="text/javascript" src="../js/bootstrap-3.3.5-dist/js/bootstrap.min.js">

	</script>
	<link rel="stylesheet" type="text/css" href="../js/bootstrap-3.3.5-dist/css/bootstrap.css"/>


</head>

<body>

	<hex-grid id="Grid">

	<?php
	include_once '../includes/dbconnect.php';
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

	function getRoomFromCoordinates($world, $x,$y)
	{
		$returnArray = array();
		foreach ($world as $room => $value)
		{
			if( $value['X']==$x && $value['Y']==$y )
			{
				$returnArray["name"] = $value['name'];
			}
		}
		return($returnArray);
	}

	$query = "SELECT * from world WHERE X and Y IS NOT NULL";
	if (!$result = $mysqli->query($query)) {
		exit("Database error! ".$mysqli->error);
	}

	$world = array();
	
	// First we populate an array with the coordinates of all 'destination' rooms
	if( mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$world[] = ['X' => $row["X"], 'Y' => $row["Y"], 'name' => $row["name"]];
			}
		}
		else
		{
				echo 'No viable destinations in the database yet!';
		}

	// These variables need to be eventually moved to a config file or table. For now I'll hardcode them.
	$grid["config"]["rows"]=15;
	$grid["config"]["columns"]=15;

	for ($print_row=1; $print_row<=$grid["config"]["rows"]; $print_row++)
	{
		echo "<hex-row>";

	for ($print_column=1; $print_column<=$grid["config"]["columns"]; $print_column++)
		{
			$room = getRoomFromCoordinates($world, $print_row, $print_column);
			if (!empty($room))
			{
				$tooltip = "<table><tr><td><img src='../images/hive.png' width='20' height='20'></td><td>".$room['name']."</td></tr></table>";
				$icon = "<img src='../images/1.png' width='30' height='30'>";
			}
			else
			{
				$tooltip= "Some room.";
				$icon="";
			}

			echo "<hex-tile data-container='body' data-html='true' data-toggle='tooltip' data-title=\"$tooltip\" id='".$print_row."_".$print_column."'>$icon</hex-tile>";
		}
		echo "</hex-row>";
	}
	?>

	</hex-grid>
	<script type="text/javascript">

		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();   
		});

	
		// testing
		$(function () {
			var grid = $("#Grid")[0];
			//grid.getTile(1, 1).innerText = "1,1";
			//grid.getTile(1, 1).innerHTML = "<img src='../images/1.png' width='30' height='30'>";
		});

		$('hex-tile').click(function () {
			var hex = getPosition2($(this));
			console.log(hex);
			var image = "<img src='../images/1.png' width='30' height='30'>";
			if(!$(this).html())
				$(this).html(image);
			else
				$(this).html('');
		});
	</script>

</body>

</html>