<?php
require_once('includes/application_top.php');

$activeTab = 'bowls';

include('includes/header.php');
?>

<div class="row">
	<div id="content" class="col-md-12 col-xs-12">
		<h2>Pick Values Remaining</h2>
		<div class="divTable" style="border: 1px solid #000;">
			<div class="divTableBody">				
				<?php
				$sql = "SELECT userID, firstName, lastName";
				$sql .= " FROM "  . DB_PREFIX .  "users";
				$sql .= " WHERE userID != 127";
				$sql .= " ORDER BY firstName";
				$query = $mysqli->query($sql);
				
				if ($query->num_rows > 0) {
					while ($row = $query->fetch_assoc()) {					
						$html .= '<div class="divTableRow">' . "\n";
						$html .= '	<div class="divTableCell" style="padding: 2px;">' . "\n";
						$html .= 	$row['firstName'] . '<br />' . $row['lastName'] . '</div>' . "\n";
						$sql = "SELECT userID, points, teamID, winner, bowlID";
						$sql .= " FROM "  . DB_PREFIX .  "picksview";
						$sql .= " WHERE userID = " . $row['userID'];
						$sql .= " ORDER BY firstName, points";
						$subquery = $mysqli->query($sql);
						if ($subquery->num_rows > 0) {
							while ($subrow = $subquery->fetch_assoc()) {
								switch (true) {
									case ($subrow['winner'] == NULL):
										if ($subrow['bowlID'] == 115) {
											$cellStyle = 'style="font-size: small;padding: 2px;width: 2.2%;color: #fff; background-color: black;"';
										} else {
											$cellStyle = 'style="font-size: small;padding: 2px;width: 2.2%;"';
										}
										break;
									case ($subrow['winner'] == $subrow['teamID']):
										$cellStyle = 'style="font-size: small;padding: 2px;width: 2.2%;color: #fff; background-color: forestgreen;"';
										break;
									case ($subrow['winner'] != $subrow['teamID']):
										$cellStyle = 'style="font-size: small;padding: 2px;width: 2.2%;color: #fff; background-color: firebrick;"';
										break;
								}
								$html .= '	<div class="divTableCell" ' . $cellStyle . '>' . "\n";
								$html .= 		$subrow['points'] . '</div>' . "\n";
							}
						}
						$html .= '</div>' . "\n";
					}
				}
				echo $html;
				?>
			</div>
		</div>
	</div>
</div>
<?php
require('includes/footer.php');
?>
