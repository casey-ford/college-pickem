<?php
require('includes/application_top.php');

include('includes/header.php');
?>

<div class="row">
	<div id="content" class="col-md-12 col-xs-12">
		<h2><?php echo SEASON_YEAR; ?> College Bowl Games</h2>
		<a href="./docs/2021-allpicks.pdf">Download printable copy of everyone's picks</a><br /><br />
		<div class="viewAllPicks">
		<?php
			$html = '<table class="header">' . "\n";				
			$html .= '		<tr>' . "\n";
			$html .= '			<td style="width:50px;background:#fff;border-left:none;border-top:none;border-right:none;border-bottom:none;">&nbsp;</td>' . "\n";
			
			$sql = "SELECT firstName, lastName";
			$sql .= " FROM " . DB_PREFIX . "users";
			$sql .= " WHERE firstName != 'admin' ";
			$sql .= " ORDER BY firstName, lastName";			
			$query = $mysqli->query($sql) or die($mysqli->error);
			$columns = $query->num_rows;
			if ($query->num_rows > 0) {
				while ($row = $query->fetch_assoc()) {
					$html .= '				<td><h6>' . $row['firstName'] . '<br />' . $row['lastName'] . '</h6></td>' . "\n";
				}
			}
			$html .= '			<td style="width:15.5px;background:#fff;border-right:none;border-top:none;border-bottom:none;"></td>' . "\n";
			$html .= '			</tr>' . "\n";
			$html .= '		</table>' . "\n";
			$html .= '		<div class="viewAllPicks_inner_table">' . "\n";
			$html .= '			<table>' . "\n";
			$sql = "SELECT bowlID, bowlName";
			$sql .= " FROM " . DB_PREFIX . "bowls";
			$sql .= " ORDER BY bowlDateTime";
			$query = $mysqli->query($sql) or die($mysqli->error);
			if ($query->num_rows > 0) {
				while ($row = $query->fetch_assoc()) {
					$html .= '			<tr>' . "\n";
					$html .= '				<td style="width:50px;"><h6>' . $row['bowlName'] . '</h6></td>' . "\n";
					$sql = "SELECT *";
					$sql .= " FROM " . DB_PREFIX . "picksview";
					$sql .= " WHERE bowlID = " . $row['bowlID'];
					$sql .= " ORDER BY firstName, lastName";
					$q = $mysqli->query($sql) or die($mysqli->error);
					if ($q->num_rows > 0) {
						while ($r = $q->fetch_assoc()) {
							if ($r['winner'] == '0') {
								$isWinner = 'class="unselected"';
							}
							else {
								$isWinner = 'class="selected"';
							}
							$html .= '				<td style="text-align:center;font-size:0.8em">' . "\n";
							$html .= '				<img style="display:block;margin:0 auto;" height="30" src="' . $r['logoPath'] . '" ' . $isWinner . ' />' . "\n";
							$html .= '				<br />' . $r['points'] . '</td>' . "\n";			
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
