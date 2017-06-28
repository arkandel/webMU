<!DOCTYPE html>
<html lang="en"><head>
<TITLE>Edit WebMU World</title>
	<style>
		.current-row{background-color:#B24926;color:#FFF;}
		.current-col{background-color:#1b1b1b;color:#FFF;}
		.tbl-world{width: 100%;font-size:0.9em;background-color: #f5f5f5;}
		.tbl-world th.table-header {padding: 5px;text-align: left;padding:10px;}
		.tbl-world .table-row td {padding:10px;background-color: #FDFDFD;}
	</style>
	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript">
		function AddRow()
		{
			$('#world').append([
			'<tr><td valign=\"top\" contenteditable=\"true\" onBlur=\"saveToDatabase(this,\'name\',\'\')\" onClick=\"showEdit(this);\"></td><td valign=\"top\" contenteditable=\"true\" onBlur=\"saveToDatabase(this,\'description\',\'\')\" onClick=\"showEdit(this);\"><br></td><td valign=\"top\"><img src=\'../images/button_delete.png\' height=\'20\' width=\'20\'></td></tr>'
			].join(''));
		}
		

	</script>
	<script>
		function showEdit(editableObj) 
		{
			$(editableObj).css("background","#FFF");
		} 

		function messageSet(data)
		{
			$("#Messages").html(data.message);
			$('#Messages').delay(4000).fadeOut();
		}

		function confirmDelete(roomID, roomName) 
		{
			var answer = confirm("Are you sure you want to delete \"" + roomName + "\" (#" + roomID +")?");
			if (answer)
			{
				$.ajax
				(
					{
						url: "worldedit.php",
						type: "POST",
						data:'delete=1&id='+roomID,
						dataType: "json",
						success: function(data)
						{
							messageSet(data);
							$('table#world tr#roomID' +data.extra).remove();
						}        
					}
				);
			}
		}
		
		function saveToDatabase(editableObj,column,id)
		{
			$(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
			$.ajax
			(
				{
					url: "worldedit.php",
					type: "POST",
					data:'column='+column+'&editval='+encodeURIComponent(editableObj.innerHTML)+'&id='+id,
					dataType: "json",
					success: function(data)
					{
						messageSet(data);
						$(editableObj).css("background","#FDFDFD");
						if(data.extra)
						{
							location.reload(true);
						}
					}        
				}
			);
		}
	</script>
	<style>
      tr:nth-of-type(odd) {
      background-color:#ccc;
    }
</style>
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
<table id="world" class="tbl-World">
	  <tr>
		<td>Room Name</td>
		<td>Description</td>
		<td>Actions</td>
	  </tr>

<?php
    while($row = $result->fetch_assoc()) 
	{
		echo "<tr class=\"table-row\" id=roomID".$row['id'].">";
?>
		<td valign="top" contenteditable="true" onBlur="saveToDatabase(this,'name','<?php echo $row["id"]; ?>')" onClick="showEdit(this);"><?php echo $row["name"]; ?></td>
		<td valign="top" contenteditable="true" onBlur="saveToDatabase(this,'description','<?php echo $row["id"]; ?>')" onClick="showEdit(this);"><?php echo $row["description"]; ?></td>
		<td valign="top"><img src='../images/button_delete.png' height='20' width='20' onclick='confirmDelete(<?php echo $row["id"]. ",\"". $row["name"]; ?>")'></td><tr>
<?php		
	}
	echo "</tbody></table>";
}
else 
	{
    echo "The world is an empty place!";
	}
?>
<button onclick="AddRow();">Add room</button><div id="Messages">&nbsp;</div>
</body></html>
