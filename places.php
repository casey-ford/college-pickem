<?php
set_time_limit(0);
require('includes/application_top.php');
include('includes/header.php');

$startTime = microtime(true);
//Empties the places table
$sql = "TRUNCATE TABLE places;";
$mysqli->query($sql) or die($mysqli->error);

//Gets the number of combos existing in the projections table
$sql = "SELECT MAX(comboID) AS maximum FROM projections;";
$query = $mysqli->query($sql) or die($mysqli->error);
$row = $query->fetch_assoc();

sortProjections((int)$row["maximum"]);
calculateOdds();

$endTime = microtime(true);

$executionTime = $endTime - $startTime;
echo nl2br("Done!\nExecution Time (s) = " . $executionTime);

function sortProjections($comboCount) {
	global $mysqli;
	
	$insertStmt = "INSERT INTO places (comboID, P01, P02, P03, P04, P05, P06, P07, P08, P09, P10, P11, P12, P13, P14, P15, P16, P17, P18, P19)";
	$insertStmt .= " VALUES ";
	for ($x = 1; $x <= $comboCount; $x++) {
		$sql = "SELECT userID, score FROM projections WHERE comboID = " . $x . " ORDER BY score DESC;";
		$query = $mysqli->query($sql) or die($mysqli->error);
		
		$insertStmt .= "(" . $x . ", ";
		for ($y = 1; $y <= $query->num_rows; $y++) {
			$row = $query->fetch_assoc();
			if ($y == $query->num_rows) {
				$insertStmt .= $row["userID"] . "),";
			}
			else {
				$insertStmt .= $row["userID"] . ", ";
			}
		}
	}
	$insertStmt = substr_replace($insertStmt,";",-1);
	$mysqli->query($insertStmt) or die($mysqli->error);	
}

function calculateOdds() {
	global $mysqli;
	$sql = "TRUNCATE TABLE odds;";
	$mysqli->query($sql) or die($mysqli->error);
	
	$sql = "SELECT userID FROM users WHERE userID != 127;";
	$query = $mysqli->query($sql) or die($mysqli->error);
	
	if ($query->num_rows > 0) {
		while ($row = $query->fetch_assoc()) {
			$sql = "INSERT INTO odds (userID, P01, P02, P03, P04, P05, P06, P07, P08, P09, P10, P11, P12, P13, P14, P15, P16, P17, P18, P19)";
			$sql .= " VALUES (";
			$sql .= $row["userID"] . ", ";
			$sql .= "(SELECT COUNT(*) AS P01 FROM places WHERE P01 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P02 FROM places WHERE P02 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P03 FROM places WHERE P03 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P04 FROM places WHERE P04 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P05 FROM places WHERE P05 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P06 FROM places WHERE P06 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P07 FROM places WHERE P07 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P08 FROM places WHERE P08 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P09 FROM places WHERE P09 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P10 FROM places WHERE P10 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P11 FROM places WHERE P11 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P12 FROM places WHERE P12 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P13 FROM places WHERE P13 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P14 FROM places WHERE P14 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P15 FROM places WHERE P15 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P16 FROM places WHERE P16 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P17 FROM places WHERE P17 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P18 FROM places WHERE P18 = " . $row["userID"] . "), ";
			$sql .= "(SELECT COUNT(*) AS P18 FROM places WHERE P19 = " . $row["userID"] . ")); ";
			$mysqli->query($sql) or die($mysqli->error);
		}
	}
}

?>