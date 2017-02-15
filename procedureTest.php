<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">	
</head>
<body>

<?php 
	$connectionInfo = array("Database"=>'Betrayal', "UID"=>'kildufje', "PWD"=>'betrayal');
	$db = sqlsrv_connect("titan.csse.rose-hulman.edu", $connectionInfo) or die("Couldn't connect");
	
	// how to use a procedure with parameters
	$query = "{call [GetRandomRoom] (?)}";
	$value = 0;
	$params = array(array($value, SQLSRV_PARAM_IN));
	$output = sqlsrv_query($db, $query, $params);
	
	if ($output === false) {
		die (print_r(sqlsrv_errors(), true));
	}
	
	$room = sqlsrv_fetch_array($output, SQLSRV_FETCH_ASSOC );
	echo '<p>room: ' . $room['Name'] . '</p>';

	// how to use a procedure with no parameters
	$query = "{call [GetCharacters]}";
	$output = sqlsrv_query($db, $query, array());
	
	if ($output === false) {
		die (print_r(sqlsrv_errors(), true));
	}
	
	$i = 0;
	while($row=sqlsrv_fetch_array($output, SQLSRV_FETCH_ASSOC)){
		echo '<input type="radio" name="characters" value="' . $row['Name'] . '"> <img src="Characters/' . $row['Image_Path'] . '" alt="' . $row['Name'] . '" height="250" width="250">';
		$i += 1;
		if ($i % 3 == 0) {
			echo '<br>';
		}
	}
?>

</body>
</html>