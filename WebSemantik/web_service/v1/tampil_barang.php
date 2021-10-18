<?php
$conn = mysqli_connect('localhost','root','12345','bes');
header('Content-Type: application/json');
$myArray = array();
if ($result = mysqli_query($conn, "SELECT * FROM stock")) {
    	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        	$myArray[] = $row;
    	}
	mysqli_close($conn);
    	echo json_encode($myArray);
}

