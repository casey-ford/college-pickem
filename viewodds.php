<?php
require('includes/application_top.php');

include('includes/header.php');

?>
	
<div class="row">
	<div id="content" class="col-md-12 col-xs-12">
		<?php
			$sql = "SELECT COUNT(*) AS undecidedBowls FROM bowls WHERE homeScore IS NULL AND visitorScore IS NULL;";
			$query = $mysqli->query($sql) or die($mysqli->error);
			if ($query->num_rows > 0) {
				while ($row = $query->fetch_assoc()) {
					$undecidedBowls = $row["undecidedBowls"];
				}
			}
			echo "<h2>" . $undecidedBowls . " Bowls Undecided - " . number_format(pow(2,$undecidedBowls),0,".",",") . " Possible Outcomes</h2>";
		?>
		<div class="divTable">
			<div class="divTableHeading">
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					Player
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					1st
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					2nd
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					3rd
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					4th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					5th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					6th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					7th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					8th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					9th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					10th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					11th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					12th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					13th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					14th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					15th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					16th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					17th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					18th
				</div>
				<div class="divTableCell" style="font-size: x-small; padding: 5px;">
					Toilet
				</div>		
			</div>
			<?php
				$sql = "SELECT *";
				$sql .= " FROM " . DB_PREFIX . "oddsview";
				$sql .= " ORDER BY 1ST DESC, 2ND DESC, 3RD DESC, 4TH DESC, 5TH DESC, 6TH DESC, 7TH DESC,";
				$sql .= " 8TH DESC, 9TH DESC, 10TH DESC, 11TH DESC, 12TH DESC, 13TH DESC, 14TH DESC, 15TH DESC, 16TH DESC, 17TH DESC, 18TH, 19TH DESC;";
				$query = $mysqli->query($sql) or die($mysqli->error);
								
				if ($query->num_rows > 0) {
					echo '<div class="divTableBody">';	
					while ($row = $query->fetch_assoc()) {
						echo '<div class="divTableRow">' . "\n";
						echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px;">' . "\n";
						echo '		' . $row['firstName'] . '<br />'  . $row['lastName'] . '</div>' . "\n";
						if ($row['1ST'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['1STpct'],2) . '%<br />';
							echo '(' . number_format($row['1ST'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['2ND'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['2NDpct'],2) . '%<br />';
							echo '(' . number_format($row['2ND'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['3RD'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['3RDpct'],2) . '%<br />';
							echo '(' . number_format($row['3RD'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['4TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['4THpct'],2) . '%<br />';
							echo '(' . number_format($row['4TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['5TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['5THpct'],2) . '%<br />';
							echo '(' . number_format($row['5TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['6TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['6THpct'],2) . '%<br />';
							echo '(' . number_format($row['6TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['7TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['7THpct'],2) . '%<br />';
							echo '(' . number_format($row['7TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['8TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['8THpct'],2) . '%<br />';
							echo '(' . number_format($row['8TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['9TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['9THpct'],2) . '%<br />';
							echo '(' . number_format($row['9TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['10TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['10THpct'],2) . '%<br />';
							echo '(' . number_format($row['10TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['11TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['11THpct'],2) . '%<br />';
							echo '(' . number_format($row['11TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['12TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['12THpct'],2) . '%<br />';
							echo '(' . number_format($row['12TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['13TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['13THpct'],2) . '%<br />';
							echo '(' . number_format($row['13TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['14TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['14THpct'],2) . '%<br />';
							echo '(' . number_format($row['14TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['15TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['15THpct'],2) . '%<br />';
							echo '(' . number_format($row['15TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['16TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['16THpct'],2) . '%<br />';
							echo '(' . number_format($row['16TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['17TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['17THpct'],2) . '%<br />';
							echo '(' . number_format($row['17TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['18TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['18THpct'],2) . '%<br />';
							echo '(' . number_format($row['18TH'],0,".",",") . ')</div>' . "\n";
						}
						if ($row['19TH'] == 0) {
							$blackout = ' background-color: #000; font-color: #000;"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '></div>' . "\n";
						}
						else {
							$blackout = '"';
							echo '	<div class="divTableCell" style="font-size: x-small; padding: 5px; ' . $blackout. '>' . "\n";
							echo '		' . number_format($row['19THpct'],2) . '%<br />';
							echo '(' . number_format($row['19TH'],0,".",",") . ')</div>' . "\n";
						}
						echo '</div>' . "\n";
					}
					echo '</div>' . "\n";
				}
			?>
		</div>
	</div>
</div>

<?php
require('includes/footer.php');
?>