<?php
require_once('includes/application_top.php');

$activeTab = 'bowls';

include('includes/header.php');
?>

<div class="row">
	<div id="content" class="col-md-12 col-xs-12">
		<h2><?php echo SEASON_YEAR; ?> College Bowl Results</h2>
		<div class="divTable" style="border: 1px solid #000;">
			<div class="divTableHeading">
					<div class="divTableHead">Bowl</div>
					<div class="divTableHead">Location</div>
					<div class="divTableHead">Time</div>
					<div class="divTableHead">Visitor</div>
					<div class="divTableHead">Score</div>
					<div class="divTableHead">Home</div>
					<div class="divTableHead">Score</div>
			</div>
			<div class="divTableBody">				
				<?php
				$sql = "SELECT b.*,";
				$sql .= " ht.university AS htUniv, ht.teamName AS htTeamName, ht.logoPath AS htLogoPath,";
				$sql .= " vt.university AS vtUniv, vt.teamName AS vtTeamName, vt.logoPath AS vtLogoPath";
				$sql .= " FROM "  . DB_PREFIX .  "bowls b";
				$sql .= " LEFT JOIN " . DB_PREFIX . "teams ht on b.homeID = ht.teamID";
				$sql .= " LEFT JOIN " . DB_PREFIX . "teams vt on b.visitorID = vt.teamID";
				$sql .= " ORDER BY bowlDateTime";
				$query = $mysqli->query($sql);
				
				if ($query->num_rows > 0) {
					while ($row = $query->fetch_assoc()) {
						switch (true) {
							case $row['visitorScore'] > $row['homeScore']:
								$vtImgState = "selected";
								$htImgState = "unselected";
								break;
							case $row['visitorScore'] < $row['homeScore']:
								$vtImgState = "unselected";
								$htImgState = "selected";
								break;
							default:
								$vtImgState = "selected";
								$htImgState = "selected";
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
						$html .= '		<img class="' . $vtImgState . '" style="display:block;margin:0 auto;" height="50" src="' . $row['vtLogoPath'] . '" />' . "\n";
						$html .= 		$row['vtUniv'] . '<br />' . $row['vtTeamName'] . '</div>' . "\n";
						$html .= '	<div class="divTableCell" style="font-weight: bold;">' . "\n";
						$html .= 		$row['visitorScore'] . '</div>' . "\n";
						$html .= '	<div class="divTableCell" style="font-weight: bold;">' . "\n";
						$html .= '		<img class="' . $htImgState . '" style="display:block;margin:0 auto;" height="50" src="' . $row['htLogoPath'] . '" />' . "\n";
						$html .= 		$row['htUniv'] . '<br />' . $row['htTeamName'] . '</div>' . "\n";
						$html .= '	<div class="divTableCell" style="font-weight: bold;">' . "\n";
						$html .= 		$row['homeScore'] . '</div>' . "\n";
						$html .= '</div>' . "\n";
						echo $html;
					}
					
				}
			?>
			</div>
		</div>
	</div>
</div>
<?php
require('includes/footer.php');
?>
