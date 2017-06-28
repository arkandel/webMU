<!DOCTYPE html>
<html lang="en"><head>
<TITLE>Edit WebMU World Categories</title>
	<style>
		.current-row{background-color:#B24926;color:#FFF;}
		.current-col{background-color:#1b1b1b;color:#FFF;}
		.tbl-world{width: 100%;font-size:0.9em;background-color: #f5f5f5;}
		.tbl-world th.table-header {padding: 5px;text-align: left;padding:10px;}
		.tbl-world .table-row td {padding:10px;background-color: #FDFDFD;}
	</style>
	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.2.3/jquery-confirm.min.css">
	
		function AddRow()
		{
			$('#worldcategories').append([
			'<tr><td valign=\"top\" contenteditable=\"true\" onBlur=\"saveToDatabase(this,\'name\',\'\')\" onClick=\"showEdit(this);\"></td></td></tr>'
			].join(''));
		}
		

	</script>
	<script>
		function showEdit(editableObj) 
		{
			$(editableObj).css("background","#FFF");
		} 

		function saveToDatabase(editableObj,column,id)
		{
			$(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
			$.ajax
			(
				{
					url: "worldedit_categories.php",
					type: "POST",
					data:'column='+column+'&editval='+encodeURIComponent(editableObj.innerHTML)+'&id='+id,
					success: function(data)
					{
						$(editableObj).css("background","#FDFDFD");
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

<table id="worldcategories" class="tbl-World">
	  <tr>
		<td width="70%">Description</th>
	  </tr>

<?php
include_once '../includes/dbconnect.php';
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

$sql = "SELECT room_id, category_name FROM world_categories;";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) 
{
?>

<?php
    while($row = $result->fetch_assoc()) 
	{
		echo "<tr class=\"table-row\">";
?>
		<td valign="top" contenteditable="true" onBlur="saveToDatabase(this,'category_name','<?php echo $row["room_id"]; ?>')" onClick="showEdit(this);"><?php echo $row["category_name"]; ?></td>
<?php		
	}

}
else 
	{
    echo "The world is an empty place!";
	}
?>
<button onclick="AddRow();">Add category</button><div id="Messages">&nbsp;</div>
</body></html>