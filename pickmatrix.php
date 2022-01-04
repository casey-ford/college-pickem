<?php
require('includes/application_top.php');

include('includes/header.php');
?>

<div class="row">
	<div id="content" class="col-md-12 col-xs-12">
		<h2>Pick Similarity Matrix</h2>
		<div class="viewAllPicks">
		<?php
			$html = '<table class="header">' . "\n";				
			$html .= '		<tr>' . "\n";
			$html .= '			<td style="width:50px;background:#fff;border-left:none;border-top:none;border-right:none;border-bottom:none;">&nbsp;</td>' . "\n";
			
			$sql = "SELECT firstName, lastName, userID";
			$sql .= " FROM " . DB_PREFIX . "users";
			$sql .= " WHERE userID != 127 ";
			$sql .= " ORDER BY firstName, lastName";

			$query = $mysqli->query($sql) or die($mysqli->error);
			$userIDArray = array();
			if ($query->num_rows > 0) {
				while ($row = $query->fetch_assoc()) {
					array_push($userIDArray,$row['userID']);
					$html .= '				<td><h6>' . $row['firstName'] . '<br />' . $row['lastName'] . '</h6></td>' . "\n";
				}
			}

 			$html .= '			<td style="width:17px;background:#fff;border-right:none;border-top:none;border-bottom:none;"></td>' . "\n";
			$html .= '			</tr>' . "\n";
			$html .= '		</table>' . "\n";
			$html .= '		<div class="viewAllPicks_inner_table">' . "\n";
			$html .= '			<table>' . "\n";
			
			$query = $mysqli->query($sql) or die($mysqli->error);
			if ($query->num_rows > 0) {
				while ($row = $query->fetch_assoc()) {
					$html .= '			<tr>' . "\n";
					$html .= '				<td style="width:50px;height:75px;"><h6>' . $row['firstName'] . ' ' . $row['lastName'] . '</h6></td>' . "\n";
					
					foreach ($userIDArray as $value) {
						$s = "SELECT COUNT(*) AS samePickCount";
						$s .= " FROM `picks` p";
						$s .= " INNER JOIN `picks` p2 ON p.teamID = p2.teamID";
						$s .= " WHERE p.userID = " . $row['userID'] . " AND p2.userID = " . $value . ";";
						
						$q = $mysqli->query($s) or die($mysqli->error);
						$cellStyle = "";
						if ($q->num_rows > 0) {
							while ($r = $q->fetch_assoc()) {
								switch ($r['samePickCount']) {
									case ($r['samePickCount'] == 40):
										$cellStyle = "text-align:center;font-size:0.8em;background-color:Crimson;";
									break;
									case ($r['samePickCount'] >= 35 && $r['samePickCount'] <= 39):
										$cellStyle = "text-align:center;font-size:0.8em;background-color:#D30F02;";
									break;
									case ($r['samePickCount'] >= 30 && $r['samePickCount'] <= 34):
										$cellStyle = "text-align:center;font-size:0.8em;background-color:#FA2D08;";
									break;
									case ($r['samePickCount'] >= 25 && $r['samePickCount'] <= 29):
										$cellStyle = "text-align:center;font-size:0.8em;background-color:#F7C20E;";
									break;
									case ($r['samePickCount'] >= 20 && $r['samePickCount'] <= 24):
										$cellStyle = "text-align:center;font-size:0.8em;background-color:#AFA605;";
									break;
									case ($r['samePickCount'] >= 15 && $r['samePickCount'] <= 19):
										$cellStyle = "text-align:center;font-size:0.8em;background-color:#809507;";
									break;
									default:
										$cellStyle = "text-align:center;font-size:0.8em;background-color:LightBlue;";
									break;
								}
								if ($row['userID'] != $value) {
									$html .= '				<td style="' . $cellStyle . '">' . $r['samePickCount'] . '</td>' . "\n";
								}
								else {
									$html .= '				<td style="background-color:#000000;">&nbsp;</td>' . "\n";
								}					
							}
						}
					
					}	
					$html .= '			</tr>' . "\n";						
				}
			}
			$html .= '		</table>' . "\n";
			$html .= '</div>' . "\n";
			echo $html;
		?>
	</div>
</div>
<?php
include('includes/footer.php');
?>
