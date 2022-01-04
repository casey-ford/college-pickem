<?php
set_time_limit(0);
require_once('includes/application_top.php');
include('includes/header.php');

function getTotalBowlCount() {
	global $mysqli;
	$sql = "SELECT COUNT(*) AS bowlCount FROM bowls";
	$query = $mysqli->query($sql) or die($mysqli->error);

	if ($query->num_rows > 0) {
		while ($row = $query->fetch_assoc()) {
			$bowlCount = $row["bowlCount"];
		}
	}

	return $bowlCount;
}

function getUndecidedBowlCount () {
	global $mysqli;
	$sql = "SELECT COUNT(*) AS undecidedBowls FROM bowls WHERE homeScore IS NULL AND visitorScore IS NULL;";
	$query = $mysqli->query($sql) or die($mysqli->error);
	if ($query->num_rows > 0) {
		while ($row = $query->fetch_assoc()) {
			$undecidedBowls = $row["undecidedBowls"];
		}
	}
	return $undecidedBowls;
}

function generateScenarios($undecidedBowls, $bowlCount) {
	global $mysqli;
	$x = 0;
	$y = pow(2,$undecidedBowls) - 1;
	$sql = "TRUNCATE TABLE scenarios;";
	$query = $mysqli->query($sql) or die($mysqli->error);
	
	$sql = "INSERT INTO scenarios VALUES ";
	do {
		$sql .= insertScenario($x, strlen(decbin($y)), $bowlCount);
		$x++;
	} while ($x <= $y);

	$sql = substr_replace($sql,";",-1);
	$mysqli->query($sql) or die($mysqli->error);
	
	echo pow(2,$undecidedBowls) * $undecidedBowls . " scenarios inserted";
}

function insertScenario ($combo, $comboLength, $bowlCount) {
	global $mysqli;
	
	$winner = 0;
	$binaryCombo = str_pad(decbin($combo),$comboLength,"0",STR_PAD_LEFT);
	
	//echo $combo;
	for ($bowlNumber = $bowlCount; $bowlNumber >= (($bowlCount + 1) - ($comboLength)); $bowlNumber--) {
		$sql .= "(";
		if (substr($binaryCombo, ($bowlCount - $bowlNumber), 1) % 2) {
			# Generate Visitor
			$winner = $bowlNumber;
		}
		else {
			#Generate Home
			$winner = $bowlNumber + $bowlCount;
		}
		$sql .= $combo + 1 . ", " . $bowlNumber . ", " . $winner . "),";
	}
	
    return $sql;
}
$startTime = microtime(true);
$totalBowlCount = getTotalBowlCount();
$undecidedBowlCount = getUndecidedBowlCount();
generateScenarios($undecidedBowlCount, $totalBowlCount);
$endTime = microtime(true);

$executionTime = $endTime - $startTime;
echo nl2br("\nExecution Time (s) = " . $executionTime);
?>