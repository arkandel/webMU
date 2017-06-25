<!DOCTYPE html>
<html lang="en"><head>
<TITLE>Edit WebMU World</title>
	<style>
		.current-row{background-color:#B24926;color:#FFF;}
		.current-col{background-color:#1b1b1b;color:#FFF;}
		.tbl-qa{width: 100%;font-size:0.9em;background-color: #f5f5f5;}
		.tbl-qa th.table-header {padding: 5px;text-align: left;padding:10px;}
		.tbl-qa .table-row td {padding:10px;background-color: #FDFDFD;}
	</style>
	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
	<script>
		function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		} 
		
		function saveToDatabase(editableObj,column,id)
		{
			$(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
			$.ajax
			(
				{
					url: "worldedit.php",
					type: "POST",
					data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
					success: function(data)
					{
						$(editableObj).css("background","#FDFDFD");
					}        
				}
			);
		}
	</script>
</head>
<body>
<?php
include_once '../includes/dbconnect.php';
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

$sql = "SELECT id, name, description FROM world";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) 
{
?>
<table class="tbl-World">
  <thead>
	  <tr>
		<th class="table-header" width="10%">Room ID</th>
		<th class="table-header">Name</th>
		<th class="table-header">Description</th>
	  </tr>
  </thead>
  <tbody>

  
  
<?php
    while($row = $result->fetch_assoc()) 
	{
		echo "<tr class=\"table-row\">";
        echo "<td>" . $row["id"]. "</td>";
?>
		<td contenteditable="true" onBlur="saveToDatabase(this,'name','<?php echo $row["id"]; ?>')" onClick="showEdit(this);"><?php echo $row["name"]; ?></td>
		<td contenteditable="true" onBlur="saveToDatabase(this,'description','<?php echo $row["id"]; ?>')" onClick="showEdit(this);"><?php echo $row["description"]; ?></td>
<?php		
	}
	echo "</tr></tbody></table>";
}
else 
	{
    echo "The world is an empty place!";
	}
?>
</body></html>