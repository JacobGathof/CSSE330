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
		
		if(isset($_POST['characters'])){
			$sql = sqlsrv_query($db, "Update Player Set Active = 0 Where Name = '".$name."'");
			$name = $_POST['characters'];
			$_SESSION['name']=$name;
			$sql = sqlsrv_query($db, "Update Player Set Active = 1 Where Name = '".$name."'");
		}
	?>
		
	
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
				$sql = sqlsrv_query($db, "SELECT Name FROM Player");
				while($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
					echo '<option value="' . $row['Name'] . '">' . $row['Name'] . '</option>';
				}
			?>
		</select>
	</form>
	
	
	<div>
	<?php
		//Display Character Tile
		$query = sqlsrv_query($db, "SELECT Image_Path FROM Player WHERE Name = '" .$name ."'");
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
<form method="post" id="form">
	<input type="submit" name="reset" value="Reset" class="btn-warning"/>
</form>



<!-------------------------------------------------------->
<!-------------------Game Board Table--------------------->
<!-------------------------------------------------------->
<div id=tableArea class="well left">
	<table id = "BoardTable" class="table">
		
	</table>
</div>
	
<!-------------------------------------------------------->
<!---------------Display area for items------------------->
<!----------------Include a button for picking------------>
<!-------------------------------------------------------->
<div id="Items" class="row well left row">
	<p>Items</p>

	<form method="post" id="itemForm">
		<input type="submit"  name="item" value="Pick Item"/>
	</form>
	
	<?php
	
	if(isset($_POST['loseItem'])){
		global $db;
		$itemName = $_POST['loseItem'];
		$sql = sqlsrv_query($db, "Update Item Set Owner_Name = NULL Where Name='".$itemName."'");
	}
	
	
	if(isset($_POST['item'])){
	
		global $name;
		global $db;
	
		$name = $_SESSION['name'];
	
		$sql = sqlsrv_query($db, "SELECT TOP 1 * FROM Item Where [Owner_Name] Is NULL ORDER BY NEWID()");
		$row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
		
		$itemName = $row['Name'];
		$imagePath = $row['Image_Path'];
		
		$sql = "Update Item Set Owner_Name = '".$name."' Where Name='".$itemName."'";
		sqlsrv_query($db, $sql);
		
	}
	
	
	
	global $name;
	global $db;
	
	$sql = sqlsrv_query($db, "SELECT * FROM Item Where [Owner_Name]='".$name."'");
	echo '<form method="post">';
	while($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
		$imagePath = $row['Image_Path'];
		echo '<input name = "loseItem" value="'.$row['Name'].'" type=image src="Items/' . $imagePath . '" alt="' . $name . '" height="200" width="100">';
	}
	echo '</form>';
	
	
	?>

</div>



<!-------------------------------------------------------->
<!---------------Reset Functions-------------------------->
<!-------------------------------------------------------->
<?php

	if(isset($_POST['reset'])){
		resetAllStats();
		resetItemOwners();
		//resetPositions();
		resetPlayers();
		$_SESSION['currentRoom'] = "";
	}

	function resetItemOwners(){
		
		global $db;
		$sql = "Update Item Set [Owner_Name] = NULL";
		$output = sqlsrv_query($db, $sql);
	}
	
	function resetPlayers(){
		
		global $db;
		$sql = "Update Player Set Active = 0";
		$output = sqlsrv_query($db, $sql);
	}
	
	function resetPositions(){
		
		global $db;
		$sql = "Update Room Set [Position] = NULL";
		$output = sqlsrv_query($db, $sql);
	}
	
	function resetAllStats(){	
		resetStat(3,2,2,2,"Brandon Jaspers");
		resetStat(2,2,4,2,"Darrin Flash Williams");
		resetStat(4,3,2,2,"Father Rhinehardt");
		resetStat(2,4,2,2,"Heather Granville");
		resetStat(4,2,3,2,"Jenny LeClerc");
		resetStat(2,3,2,3,"Madame Zostra");
		resetStat(2,3,2,3,"Missy Dubourde");
		resetStat(2,2,4,2,"Ox Bellows");
		resetStat(3,2,3,2,"Peter Akimoto");
		resetStat(2,4,3,2,"Professor Longfellow");
		resetStat(2,3,3,3,"Vivian Lopez");
		resetStat(2,2,3,3,"Zoe Ingstrom");
	}
	
	function resetStat($a, $b, $c, $d, $playerName){
		global $db;
		$sql = "Update Stats Set [Sanity_Index]=".$a.", [Knowledge_Index]=".$b.", [Speed_Index]=".$c.", [Might_Index]=".$d." WHERE Name = '".$playerName."'";
		$output = sqlsrv_query($db, $sql);
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
	<form method="post" id="form">
		<input type="submit"  name="getRoom" value="Get Room"><br>
		<input type="submit"  name="rotateRoom" value="Rotate Room"></br>
		<input type="submit"  name="refreshRoom" value="Refresh Room"></br>
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



<div class="well">
	
	<?php
	global $name;
	global $db;
	
	$sql = "SELECT * FROM Player WHERE Active = 1 AND Name <> '".$name."'";
	$output = sqlsrv_query($db, $sql);
	
	echo 'Other Players';
	while($row = sqlsrv_fetch_array($output, SQLSRV_FETCH_ASSOC)){
		$imagePath = $row['Image_Path'];
		echo '<img src="Characters/' . $imagePath . '" alt="' . $name . '" height="100" width="100">';
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
		
		echo '<script> buildTableFromDatabase(' . $positions . ',' . $rotations . ',' . $imagePaths . ') </script>';
		
	?>
</div> 


</body>
</html>