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

	$filtered_query = $query; // saving this for pagination later.

	if(isset($_POST['currentPage']) && $_POST['currentPage']!="") // Let's cut down on selects some.
	{
			$currentPage = $mysqli->real_escape_string($_POST['currentPage']);
			$perPage = $mysqli->real_escape_string($_POST['perPage']);

			if($currentPage==1)
				$currentPage=0;
			else
				$currentPage=($currentPage-1)*$perPage;
			
			$query .= " LIMIT $currentPage,$perPage";
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
					<button onclick="GetRoomDetails('.$row['id'].')" class="btn btn-warning">Update</button>
				</td>
				<td>
					<button onclick="DeleteRoom('.$row['id'].',\''. $row['name'].'\')" class="btn btn-danger">Delete</button>
				</td>
    		</tr>';
    		$number++;
    	}

		$result = $mysqli->query($filtered_query); // We need the total number of rooms to paginate.
		$totalRooms = mysqli_num_rows($result);
		$totalPages = ceil($totalRooms/$perPage);
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