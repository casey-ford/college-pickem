<?php
require_once('includes/application_top.php');

function checkDeadline($currentTime, $cutOff) {
	if ($currentTime < strtotime($cutOff)) {
		$latePick = false;
	}
	else {
		$latePick = true;
	}
	return $latePick;
}

function emailPicks ($userID,$userEmail) {
	global $mysqli;
	
	$sql = "SELECT * FROM `picksview` WHERE userID = " . $userID;
	$query = $mysqli->query($sql) or die($mysqli->error);

	if ($query->num_rows > 0) {
		$message = '<html>';
		$message .= '	<head>';
		$message .= '	<title>Your Picks</title>';
		$message .= '	</head>';
		$message .= '	<body style="font-family:Ubuntu,sans-serif;">';
		$message .= '		<h3>Here are your picks:</h3>';
		$message .= '		<div style="display: table;	width: 50%;">';
		$message .= '			<div style="background-color: #EEE; display: table-header-group; font-weight: bold;">';
		$message .= '				<div style="display: table-row;">';
		$message .= '					<div style="border: 1px solid #999999; display: table-cell;	padding: 3px 10px;">Bowl Game</div>';
		$message .= '					<div style="border: 1px solid #999999; display: table-cell;	padding: 3px 10px;">Pick</div>';
		$message .= '					<div style="border: 1px solid #999999; display: table-cell;	padding: 3px 10px;">Points</div>';
		$message .= '				</div>';
		$message .= '			</div>';
		$message .= '			<div style="display: table-row-group;">';
		while ($row = $query->fetch_assoc()) {
			$message .= '				<div style="display: table-row;">';
			$message .= '					<div style="border: 1px solid #999999; display: table-cell; padding: 3px 10px;">' . $row['bowlName'] . '</div>';
			$message .= '					<div style="border: 1px solid #999999; display: table-cell; padding: 3px 10px;">' . $row['univName'] . '<br />' . $row['teamName'] . '</div>';
			$message .= '					<div style="border: 1px solid #999999; display: table-cell; padding: 3px 10px;">' . $row['points'] . '</div>';
			$message .= '				</div>';
		}
		$message .= '			</div>';
		$message .= '		</div>';
		$message .= '</body>';
		$message .= '</html>';
	}
	$query->free;
	
	$mail = new PHPMailer;
	$mail->SMTPDebug = 3; //Enable SMTP debugging.
	$mail->isSMTP(); //Set PHPMailer to use SMTP. 
	$mail->From = "caseyevan@gmail.com";
	$mail->FromName = "Pick 'Em Admin";
	$mail->addAddress($userEmail);
	$mail->isHTML(true);
	$mail->Subject = "2021 College Pick 'Em --- Your Picks Have Been Saved";
	$mail->Body = $message;
	
	if(!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} 
	else {
		echo "Message has been sent successfully";
	}
}

$deadline = "2021-12-17 11:55:00";
$activeTab = 'picks';

if (isset($_POST['submitButton'])) { 
	$afterDeadline = checkDeadline(time(),$deadline);
	$sql = "SELECT *";
	$sql .= " FROM " . DB_PREFIX . "bowls"; 
	$sql .= " WHERE (DATE_ADD(NOW(), INTERVAL " . SERVER_TIMEZONE_OFFSET . " HOUR)) < bowlDateTime";
	$sql .= " AND (DATE_ADD(NOW(), INTERVAL " . SERVER_TIMEZONE_OFFSET . " HOUR)) < '" . $deadline . "';";
	$query = $mysqli->query($sql);
	
	if ($query->num_rows > 0) {
		while ($row = $query->fetch_assoc()) {
			if (!empty($_POST['bowlpick_' . $row['bowlID']])) {
				$sql = "INSERT INTO " . DB_PREFIX . "picks (userID, bowlID, teamID, points)";
				$sql .= " VALUES (" . $user->userID . ", " . $row['bowlID'] . ", " . $_POST['bowlpick_' . $row['bowlID']] . ", " . $_POST['bowlpoints_' . $row['bowlID']] . ")";
				$sql .= " ON DUPLICATE KEY UPDATE";
				$sql .= " teamID = VALUES(teamID), points = VALUES(points);";
				$mysqli->query($sql) or die('Error inserting picks: ' . $mysqli->error); 
			}
		}
		emailPicks($user->userID,$user->email);
	}		

	$query->free;
	header('Location: makepicks.php');
	exit;
} 

$afterDeadline = checkDeadline(time(),$deadline);
$picks = array();
$sql = "SELECT *";
$sql .= " FROM " . DB_PREFIX . "picks ";
$sql .= " WHERE userID = " . $user->userID;
$sql .= " ORDER BY bowlID ASC";
$query = $mysqli->query($sql);
while ($row = $query->fetch_assoc()) {
	$picks += array($row["bowlID"] => array("userID" => $row["userID"], "teamID" => $row["teamID"], "points" => $row["points"]));
}
$query->free;

include('includes/header.php');
?>

<div class="row">
	<div id="content" class="col-md-12 col-xs-12">
		<h2><?php echo SEASON_YEAR; ?> College Bowl Games</h2>
		<form name="makePicks" id="submitForm" method="post" onsubmit="return validateForm()" action="./makepicks.php#openModal">
		<div class="divTable">
			<div class="divTableBody">
				<div class="divTableRow">&nbsp;</div>
				<div class="divTableRow">
					<div class="divTableCell" style="border-style: none; width: 80%;">&nbsp;</div>
					<div class="divTableCell" style="border-style: none;">
						<input type="button" name="randomizeButton" class="greenButton" value="Randomize Picks!" onClick="javascript:randomizePicks();" <?php if ($afterDeadline) {echo 'disabled';} ?>>
					</div>
					<div class="divTableCell" style="border-style: none;">
						<input type="button" name="randomizeButton" class="greenButton" value="Randomize Confidence!" onClick="javascript:randomizeConf();" <?php if ($afterDeadline) {echo 'disabled';} ?>>
					</div>
					
				</div>
				<div class="divTableRow">&nbsp;</div>
			</div>
		</div>
		<div class="divTable" style="border: 1px solid #000;">
			<div class="divTableHeading">
				<div class="divTableHead">Bowl</div>
				<div class="divTableHead">Location</div>
				<div class="divTableHead">Time</div>
				<div class="divTableHead">Visitor</div>
				<div class="divTableHead">Home</div>
				<div class="divTableHead">Confidence</div>
			</div>
			<div class="divTableBody">				
				<?php
					$sql = "SELECT b.*,";
					$sql .= " ht.university AS htUniv, ht.teamName AS htTeamName, ht.logoPath AS htLogoPath,";
					$sql .= " vt.university AS vtUniv, vt.teamName AS vtTeamName, vt.logoPath AS vtLogoPath";
					$sql .= " FROM " . $db_prefix . "bowls b";
					$sql .= " LEFT JOIN " . $db_prefix . "teams ht on b.homeID = ht.teamID";
					$sql .= " LEFT JOIN " . $db_prefix . "teams vt on b.visitorID = vt.teamID";
					$sql .= " ORDER BY bowlID, bowlDateTime";
					$query = $mysqli->query($sql) or die($mysqli->error);
					
					if ($query->num_rows > 0) {
						while ($row = $query->fetch_assoc()) {
							$homeTeam = $row['homeID'];
							$visitorTeam = $row['visitorID'];
							switch (empty($picks[$row['bowlID']]['points'])) {
								case false:
									$bowlPoints = $picks[$row['bowlID']]['points'];
									break;
								default:
									$bowlPoints = $row['bowlID'];
							}
							switch ($picks[$row['bowlID']]['teamID']) {
								case $visitorTeam:
									$vtImgState = "selected";
									$htImgState = "unselected";
									$bowlPickValue = $picks[$row['bowlID']]['teamID'];
									break;
								case $homeTeam:
									$vtImgState = "unselected";
									$htImgState = "selected";
									$bowlPickValue = $picks[$row['bowlID']]['teamID'];
									break;
								default:
									$vtImgState = "unselected";
									$htImgState = "unselected";
									$bowlPickValue = "";
							}
							
							$html = '';
							$html .= '<div class="divTableRow">' . "\n";
							$html .= '	<div class="divTableCell" style="font-weight: bold;">' . "\n";
							$html .= '		<img style="display:block;margin:0 auto;" height="50" src="' . $row['logoPath'] . '" />' . "\n";
							$html .= 		$row['bowlName'] . '</div>' . "\n";
							$html .= '	<div class="divTableCell">' . "\n";
							$html .= 		$row['bowlLocation'] . '</div>' . "\n";
							$html .= '	<div class="divTableCell">' . "\n";
							$html .= 		date('m/d/y', strtotime($row['bowlDateTime'])). '<br />' . date('g:i a', strtotime($row['bowlDateTime'])) . '</div>' . "\n";
							$html .= '	<div class="divTableCell" style="font-weight: bold;">' . "\n";
							$html .= '		<a href="javascript:;"><img id="' . $row['visitorID'] . '|' . $row['homeID'] . '" class="' . $vtImgState . '" onclick="toggleSelection(this,' . $row['bowlID'] . ',' . var_export($afterDeadline, true) . ');" style="display:block;margin:0 auto;" height="50" src="' . $row['vtLogoPath'] . '" /></a>' . "\n";
							$html .= 		$row['vtUniv'] . '<br />' . $row['vtTeamName'] . '</div>' . "\n";
							$html .= '	<div class="divTableCell" style="font-weight: bold;">' . "\n";
							$html .= '		<a href="javascript:;"><img id="' . $row['homeID'] . '|' . $row['visitorID'] . '" class="' . $htImgState . '" onclick="toggleSelection(this,' . $row['bowlID'] . ',' . var_export($afterDeadline, true) . ');" style="display:block;margin:0 auto;" height="50" src="' . $row['htLogoPath'] . '" /></a>' . "\n";
							$html .= 		$row['htUniv'] . '<br />' . $row['htTeamName'] . '</div>' . "\n";
							$html .= '	<div class="divTableCell" id="bowl_' . $row['bowlID'] . '">' . "\n";
							$html .= '		<span class="tooltiptext"></span>' . "\n"; 
							$html .= '		<script>buildSelectBox(bowl_' . $row['bowlID'] . ', ' . $row['bowlID'] . ', ' . $bowlPoints . ',' . var_export($afterDeadline, true) . ')</script>' . "\n";
							$html .= '		<input type="hidden" name="bowlpick_' . $row['bowlID'] . '" id="bowlpick_' . $row['bowlID'] . '" value="' . $bowlPickValue . '" />' . "\n";
							$html .= '		<input type="hidden" name="hidbowlpoints_' . $row['bowlID'] . '" id="hidbowlpoints_' . $row['bowlID'] . '" value="' . $bowlPoints . '" />' . "\n";
							$html .= '	</div>' . "\n";
							$html .= '</div>' . "\n";
							echo $html;
						}
					}
				?>
			</div>
		</div>
		<div class="divTable">
			<div class="divTableBody">
				<div class="divTableRow">&nbsp;</div>
				<div class="divTableRow">
					<div class="divTableCell" style="border-style: none; width: 80%;">&nbsp;</div>
					<div class="divTableCell" style="border-style: none;">
						<input type="submit" name="submitButton" class="greenButton" tabindex="6" value="Save Picks" <?php if ($afterDeadline) {echo 'disabled';} ?>>
					</div>
					<div class="divTableCell" style="border-style: none;">
						<input type="button" name="resetButton" class="grayButton" value="Cancel" onclick="window.location.href='makepicks.php'">
					</div>
				</div>
				<div class="divTableRow">&nbsp;</div>
			</div>
		</div>
		</form>
	</div>
</div>

<div id="openModal" class="modalDialog">
	<div>
		<a href="./makepicks.php" title="Close" class="closeModal">X</a>
		<h2>Your Picks Have Been Saved!</h2>
		<h5>You may come back and edit your picks until 11:55 AM EST on December 17, 2021</h5>
	</div>
</div>
<div id="oopsModal" class="modalDialog">
	<div>
		<a href="./makepicks.php" title="Close" class="closeModal">X</a>
		<h2>You Missed a Pick or Two!</h2>
		<h5>No worries, though. Your completed picks were saved. Please come back and complete the rest by 11:55 AM EST on December 17, 2021</p>
	</div>
</div>
<div id="deadlineModal" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="closeModal">X</a>
		<h2>You Missed The Deadline!</h2>
		<h5>:sad trumpet noise: The current time is after 11:55 AM EST on December 17, 2021<br />
		Your picks were not saved.<br />
		There is always next year!</h5>
	</div>
</div>
</body>

</html>