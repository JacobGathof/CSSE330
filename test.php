<!DOCTYPE html>
<html>
<head>
	
	<!-- Header, scripts, stylesheets-->
	<!--------------------------------->
	
	<title>Betrayal at House on the Hill</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.0/knockout-min.js" type="text/javascript" charset="utf-8"></script>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>
	
	<script src="test.js" type="text/javascript"></script>
	<link rel="stylesheet" href="test.css">
	
	
</head>
<body>


<!-------------------------------------------------------->
<!---------------Connect to the database------------------>
<!---------------Reload all session variables------------->
<!-------------------------------------------------------->

	<?php
		session_start();
		$name=$_SESSION['name'];
		//$_SESSION['currentRoom'];
		
		
	
		$connectionInfo = array("Database"=>'Betrayal', "UID"=>'kildufje', "PWD"=>'betrayal');
		$db = sqlsrv_connect("titan.csse.rose-hulman.edu", $connectionInfo)
		or die("Couldn't connect");
		
		$sql = sqlsrv_query($db, "Exec SetEntranceTiles");
		
		if(isset($_POST['characters'])){
			$sql = sqlsrv_query($db, "Update Player Set Active = 0 Where Name = '".$name."'");
			$name = $_POST['characters'];
			$_SESSION['name']=$name;
			$sql = sqlsrv_query($db, "Update Player Set Active = 1 Where Name = '".$name."'");
		}
	?>
		
	
<div class="topDiv">
	
<!-------------------------------------------------------->
<!---------------Select and Display Character------------->
<!-------------------------------------------------------->
<!-------------------------------------------------------->
<div class="well left round row">
	<h4>Select a character</h4>
	
	<form method="post" id="form">
		<select name="characters" onchange="this.form.submit()" class="form-control">
			<option disabled selected value>--Select a character--</option>
			
			<?php
				global $db;
				$sql = sqlsrv_query($db, "Exec GetPlayerNames");
				while($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
					echo '<option value="' . $row['Name'] . '">' . $row['Name'] . '</option>';
				}
			?>
		</select>
	</form>
	
	
	<div>
	<?php
		//Display Character Tile
		$query = sqlsrv_query($db, "Exec GetPlayerImage '".$name."'");
		$output = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
		$imagePath = $output['Image_Path'];	
		echo '<img class="CharBox" src="Characters/' . $imagePath . '" alt="' . $name . '" height="250" width="250">';
	?>
	</div>
	
</div>	
	

	
<!-------------------------------------------------------->
<!---------------Display Player Statistics---------------->
<!---------------Buttons to adjust the values------------->
<!----------------And Display Dice Rolls------------------>
<!-------------------------------------------------------->

<div class = "well left round">
	<div class="Statistics">
		<div id="StatsTable">
			<table id = "Stats" class="table table-bordered">
				<tr>
					<th>Name</th>
					<th>Sanity</th> 
					<th>Speed</th>
					<th>Might</th>
					<th>Knowledge</th>
				 </tr>
				 
				 <tr id="stats_index">
					<td></td><td></td><td></td><td></td><td></td>
				 </tr>
				 <tr id="stats_values">
					<td></td><td></td><td></td><td></td><td></td>
				 </tr>
				 <tr id="stats_no_dice">
					<td></td><td></td><td></td><td></td><td></td>
				 </tr>
			</table>
		</div>
		
		
		<div id=DiceRolls>
			<table class="table table-bordered">
			
				<tr>
				<th><button class="btn" onclick="rollSanity()">Roll Sanity</button></th>
				<th><button class="btn" onclick="rollSpeed()">Roll Speed</button></th>
				<th><button class="btn" onclick="rollMight()">Roll Might</button></th>
				<th><button class="btn" onclick="rollKnowledge()">Roll Knowledge</button></th>
				</tr>
				
				<tr>
				<th><button class="btn" onclick="addStat('1', 'sanity')">+1</button>
				<button class="btn" onclick="addStat('-1', 'sanity')">-1</button></th>
				<th><button class="btn" onclick="addStat('1', 'speed')">+1</button>
				<button class="btn" onclick="addStat('-1', 'speed')">-1</button></th>
				<th><button class="btn" onclick="addStat('1', 'might')">+1</button>
				<button class="btn" onclick="addStat('-1', 'might')">-1</button></th>
				<th><button class="btn" onclick="addStat('1', 'knowledge')">+1</button>
				<button class="btn" onclick="addStat('-1', 'knowledge')">-1</button></th>
				</tr>
				
			</table>
			
			<table id = "Dice"  class="table">
				<tr>
					<th></th>
					<th></th> 
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</table>
		</div>
	</div>
</div>
	
	
	
	
<!-------------------------------------------------------->
<!-----------------Random Functions----------------------->
<!-------------------------------------------------------->
<!-------------------------------------------------------->	



<!-------------------------------------------------------->
<!-------------------Game Board Table--------------------->
<!-------------------------------------------------------->
<div id=tableArea class="well left">
	<form method="post" id="thisForm" action="">
		<table id = "BoardTable" class="table">
		
		</table>
	</form>
</div>
	
	
<form method="post" id="form">
	<input type="submit" name="reset" value="Reset" class="btn-warning"/>
</form>

</div>



<div class="botDiv">	

<!-------------------------------------------------------->
<!---------------Display area for items------------------->
<!----------------Include a button for picking------------>
<!-------------------------------------------------------->


<div class="well left">
	
	<?php
	global $name;
	global $db;
	
	$sql = "Exec GetOtherPlayers '".$name."'";
	$output = sqlsrv_query($db, $sql);
	
	echo 'Other Players';
	while($row = sqlsrv_fetch_array($output, SQLSRV_FETCH_ASSOC)){
		$imagePath = $row['Image_Path'];
		echo '<img src="Characters/' . $imagePath . '" alt="' . $name . '" height="100" width="100">';
	}
	
	?>
</div>



<div id="Items" class="row well left row">
	<p>Items</p>

	<form method="post" id="itemForm">
		<input type="submit"  name="item" value="Pick Item"/>
	</form>
	
	<?php
	
	if(isset($_POST['loseItem'])){
		global $db;
		$itemName = $_POST['loseItem'];
		$sql = sqlsrv_query($db, "Exec RemoveItem '".$itemName."'");
	}
	
	
	if(isset($_POST['item'])){
	
		global $name;
		global $db;
	
		$name = $_SESSION['name'];
	
		$sql = sqlsrv_query($db, "Exec GetRandomItem");
		$row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
		
		$itemName = $row['Name'];
		$imagePath = $row['Image_Path'];
		
		$sql = "Exec SetItemOwner '".$itemName."','".$name."'";
		sqlsrv_query($db, $sql);
		
	}
	
	
	
	global $name;
	global $db;
	
	$sql = sqlsrv_query($db, "SELECT * FROM Item Where [Owner_Name]='".$name."'");
	echo '<form method="post">';
	while($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
		$imagePath = $row['Image_Path'];
		echo '<input name = "loseItem" value="'.$row['Name'].'" type=image src="Items/' . $imagePath . '" alt="' . $name . '" height="300" width="150">';
	}
	echo '</form>';
	
	
	?>

</div>



<!-------------------------------------------------------->
<!---------------Reset Functions-------------------------->
<!-------------------------------------------------------->
<?php

	if(isset($_POST['reset'])){
		
		global $db;
		$sql = "Exec Reset";
		$output = sqlsrv_query($db, $sql);
		
		$_SESSION['currentRoom'] = "";
	}

?>


<!-------------------------------------------------------->
<!----------Get the player stats-------------------------->
<!-------------------------------------------------------->
<!-------------------------------------------------------->
<?php
	
	global $name;
	global $db;
	
	$sql = "SELECT * FROM Stats WHERE Name = '" .$name ."'";
	$output = sqlsrv_query($db, $sql);
	
	$row=sqlsrv_fetch_array($output, SQLSRV_FETCH_ASSOC);
	
	echo '<div id="PlayerStats">';
	echo '<p id="'.htmlspecialchars($row['Name']).'"/p>';
	echo '<p id="'.htmlspecialchars($row['Sanity_Index']).'"/>';
	echo '<p id="'.htmlspecialchars($row['Sanity_Values']).'"/>';
	echo '<p id="'.htmlspecialchars($row['Speed_Index']).'"/>';
	echo '<p id="'.htmlspecialchars($row['Speed_Values']).'"/>';
	echo '<p id="'.htmlspecialchars($row['Might_Index']).'"/>';
	echo '<p id="'.htmlspecialchars($row['Might_Values']).'"/>';
	echo '<p id="'.htmlspecialchars($row['Knowledge_Index']).'"/>';
	echo '<p id="'.htmlspecialchars($row['Knowledge_Values']).'"/>';
	echo '</div>';
		
?>


<div id = 'getRoom'>
 	<form method="post" id="form" class="well up">
 		<input type="submit"  class= "btn" name="getRoom" value="Get Room"><br>
 		<input type="submit"  class= "btn" name="rotateRoom" value="Rotate Room"></br>
 		<input type="submit"  class= "btn" name="refreshRoom" value="Set Room"></br>
 		<input id = "roomPosition" type="hidden" name="roomPosition">
 	</form>
 
 	<?php
 		global $db;
 		global $currentRoom;
 		
 		// do the query for either a random room or the current room
 		if ($currentRoom == $_SESSION['currentRoom']) {
 				$query = "{call [GetRandomRoom] (?)}";
 				$value = 0;
 				$params = array(array($value, SQLSRV_PARAM_IN));
 				$output = sqlsrv_query($db, $query, $params);
 				
 				if ($output === false) {
 					die (print_r(sqlsrv_errors(), true));
 				}
 			} else {
 				$output = sqlsrv_query($db, "SELECT * FROM Room WHERE Name = '".$_SESSION['currentRoom']."'");
 			}
 			
 		$room = sqlsrv_fetch_array($output, SQLSRV_FETCH_ASSOC );
 		
 		// display rom
 		if(isset($_POST['getRoom'])){
 			$rotations = ['0', '90', '180', '270'];
 			$_SESSION['currentRoom'] = $room['Name'];
 			echo "<img id = 'nextRoom' src = Rooms/" . $room['Image_Path'] . " class='rotateimg" . $rotations[$room['Rotation']] . "' style='width:200px;height:200px;>";
 		}
 		
 		// rotate room
 		if(isset($_POST['rotateRoom'])){
 			$sql = "UPDATE Room SET Rotation = ((SELECT Rotation FROM Room WHERE Name = '" . $room['Name'] . "') + 1) % 4 WHERE Name = '" . $room['Name'] . "'";
 			$query = sqlsrv_query($db, $sql);
 			$currentRoom = $room['Name'];
 		}
 		
 		if(isset($_POST['refreshRoom'])){
 			$sql = "UPDATE Room SET Position = '" . $_POST['roomPosition'] . "' WHERE Name = '" . $room['Name'] . "'";
 			$query = sqlsrv_query($db, $sql);
 			$_SESSION['currentRoom'] = '';
 		}
 		
 	?>
 </div> 






<!-------------------------------------------------------->
<!-------------------------------------------------------->
<script>
	update();
</script>


<!-------------------------------------------------------->
<!-------------------------------------------------------->
<div id = 'getPlacedRooms'>
	<?php
	
		global $db;
		$positions = '[';
		$imagePaths = '[';
		$rotations = '[';
		
		$sql = sqlsrv_query($db, "SELECT Image_Path, Position, Rotation FROM Room WHERE Position IS NOT NULL");
		while($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
			$positions = $positions . '"' . $row['Position'] . '",';
			$imagePaths = $imagePaths . '"' . $row['Image_Path'] . '",';
			$rotations = $rotations . '"' . $row['Rotation'] . '",';
		}
		
		$positions =  rtrim($positions, ',') . ']';
		$imagePaths =  rtrim($imagePaths, ',') . ']';
		$rotations =  rtrim($rotations, ',') . ']';
		
		echo '<script> buildTableFromDatabase(' . $positions . ',' . $rotations . ',' . $imagePaths . '); </script>';
		
		
		
		$positions = '[';
		$imagePaths = '[';
		
		$sql = sqlsrv_query($db, "SELECT * FROM Player WHERE Active = 1");
		while($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
			$positions = $positions . '"' . $row['Position'] . '",';
			$imagePaths = $imagePaths . '"' . $row['Image_Path'] . '",';
		}
		
		$positions =  rtrim($positions, ',') . ']';
		$imagePaths =  rtrim($imagePaths, ',') . ']';
		
		echo '<script> buildPlayerPositions(' . $positions . ',' . $imagePaths . '); </script>';
		
		
		
	?>
</div>



</div>


</body>
</html>