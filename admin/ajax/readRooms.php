<?php
	// include Database connection file 
	include_once '../../includes/dbconnect.php';
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

	// Design initial table header 
	$data = '<table class="table table-bordered table-striped">
						<tr>
							<th>No.</th>
							<th>Title</th>
							<th>Description</th>
						</tr>';

	$query = "SELECT * FROM world";

	if(isset($_POST['filter']) && $_POST['filter']!="") // Let's cut down on selects some.
	{
			$filter = $mysqli->real_escape_string($_POST['filter']);
			$query .= " WHERE name LIKE '%$filter%'";
	}	

	if(isset($_POST['currentPage']) && $_POST['currentPage']!="")
	{
			$currentPage = $mysqli->real_escape_string($_POST['currentPage']);
			$perPage = $mysqli->real_escape_string($_POST['perPage']);

			$result = $mysqli->query($query); // We need the total number of filtered rooms to paginate.
			$totalRooms = mysqli_num_rows($result);
			$totalPages = ceil($totalRooms/$perPage);
			
			if(($currentPage-1)*$perPage >= $totalRooms ) // In case we just at the last page then we changed the filters or deleted the top room in the last page, and the 'current' page number is now invalid.
			{
				$currentPage=(int)(max(1,($totalRooms/$perPage)));
			}

			$startAtRecord = ($currentPage-1)*$perPage; // "Real" pages since it starts from 0.
			$query .= " LIMIT $startAtRecord,$perPage";
	}

	if (!$result = $mysqli->query($query)) {
        exit($mysqli->error);
    }

    // Our big loop
    if( mysqli_num_rows($result) > 0)
    {
    	$number = 1;
    	while($row = mysqli_fetch_assoc($result))
    	{
    		$data .= '<tr>
				<td>'.$number.'</td>
				<td>'.$row['name'].'</td>
				<td>'.$row['description'].'</td>
				<td>
					<button onclick="GetRoomDetails('.$mysqli->real_escape_string($row['id']).')" class="btn btn-warning">Update</button>
				</td>
				<td>
					<button onclick="DeleteRoom('.$mysqli->real_escape_string($row['id']).',\''. $mysqli->real_escape_string($row['name']).'\')" class="btn btn-danger">Delete</button>
				</td>
    		</tr>';
    		$number++;
    	}
    }
    else
    {
    	// records not found 
    	$data .= '<tr><td colspan="6">Records not found!</td></tr>';
    }

    $data .= '</table>';

	$response['html'] = $data;
    $response['totalPages'] = $totalPages;

    echo json_encode($response);
?>