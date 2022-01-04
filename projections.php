<?php
set_time_limit(0);
require('includes/application_top.php');
include('includes/header.php');

function clearTable () {
	global $mysqli;
	$sql = "TRUNCATE TABLE projections;";
	$query = $mysqli->query($sql) or die($mysqli->error);
}

function getComboCount () {
	global $mysqli;
	$sql = "SELECT COUNT(DISTINCT comboID) AS comboCount FROM scenarios;";
	$query = $mysqli->query($sql) or die($mysqli->error);
	if ($query->num_rows > 0) {
		$row = $query->fetch_assoc();
		$comboCount = $row["comboCount"];
	}
	return $comboCount;
}

function generateProjections ($userID, $currentScore, $comboCount) {
	global $mysqli;
	$sql = "SELECT DISTINCT p.userID AS user, p.bowlID AS bowl, p.teamID AS team, p.points AS points";
	$sql .= " FROM picks p";
	$sql .= " INNER JOIN bowls b ON p.bowlID = b.bowlID";
	$sql .= " WHERE p.userID = " . $userID;
	$sql .= " AND b.homeScore IS NULL";
	$sql .= " ORDER BY bowl;";
	$queryPicks = $mysqli->query($sql) or die($mysqli->error);
	
	$sql = "SELECT comboID, bowlID, teamID";
	$sql .= " FROM scenarios";
	$sql .= " ORDER BY comboID, bowlID;";
	
	$queryScenarios = $mysqli->query($sql) or die($mysqli->error);

	$runningScore = $currentScore;
	$count = 0;

	if ($queryScenarios->num_rows > 0) {
		$sql = "INSERT INTO projections (comboID, userID, score)";
		$sql .=	" VALUES ";
		while ($rsScenarios = $queryScenarios->fetch_assoc()) {
			while ($rsPicks = $queryPicks->fetch_assoc()) {
				$count++;
				if ($rsPicks["team"] == $rsScenarios["teamID"]) {
					$runningScore = $runningScore + $rsPicks["points"];
				}
				else {
					$runningScore = $runningScore - $rsPicks["points"];
				}				
				if ($count != $queryPicks->num_rows) {
					$rsScenarios = $queryScenarios->fetch_assoc();
				}
			}
			$sql .=	"(" . $rsScenarios["comboID"] . ", " . $userID . ", " . $runningScore . "),";
			

			mysqli_data_seek($queryPicks,0);
			$count = 0;
			$runningScore = $currentScore;
		}
		$sql = substr_replace($sql,";",-1);
		$mysqli->query($sql) or die($mysqli->error);
	}
	else {
		echo "dammit!"; 
	}
}

$startTime = microtime(true);
clearTable();
$comboCount = getComboCount();

$sql = "SELECT userID, currentScore FROM scoreboard WHERE userID != 127 ORDER BY currentScore DESC;";
$query = $mysqli->query($sql) or die($mysqli->error);

if ($query->num_rows > 0) {
	while ($row = $query->fetch_assoc()) {
		generateProjections($row["userID"], $row["currentScore"], $comboCount);
	}
}
$endTime = microtime(true);

$executionTime = $endTime - $startTime;
echo nl2br("Done!\nExecution Time (s) = " . $executionTime);
?>