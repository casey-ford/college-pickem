<?php
require_once('includes/application_top.php');

$activeTab = 'home';

include('includes/header.php');
?>

<div class="row">
	<div class="col-md-4 col-xs-12 col-right">
		<?php
		include('includes/column_right.php');
		?>
	</div>
	<div id="content" class="col-md-8 col-xs-12">
		<!--<h3>Paths to Victory</h3>
			<h4>Elaine</h4>
			<p>All 8 combinations</p>
			<hr />-->
		<h3>Upcoming Games</h3>
				<div class="divTable"  style="height: 100%;">
					<div class="divTableHeading">
						<div class="divTableCell">
							<p>Bowl</p>
						</div>
						<div class="divTableCell">
							<p>Teams</p>
						</div>
						<div class="divTableCell">
							<p>Date/Time</p>
						</div>
					</div>
					<?php
						$sql = "SELECT b.bowlName, b.bowlDateTime, b.logoPath,";
						$sql .= " ht.university AS htUniv, ht.teamName AS htTeamName, ht.logoPath AS htLogoPath,";
						$sql .= " vt.university AS vtUniv, vt.teamName AS vtTeamName, vt.logoPath AS vtLogoPath";
						$sql .= " FROM "  . DB_PREFIX .  "bowls b";
						$sql .= " LEFT JOIN " . DB_PREFIX . "teams ht on b.homeID = ht.teamID";
						$sql .= " LEFT JOIN " . DB_PREFIX . "teams vt on b.visitorID = vt.teamID";
						$sql .= " WHERE homeScore IS NULL AND visitorScore IS NULL";						
						$sql .= " ORDER BY bowlDateTime";
						$sql .= " LIMIT 4";
						$query = $mysqli->query($sql);
						if ($query->num_rows > 0) {
							while ($row = $query->fetch_assoc()) {
								$html = '';
								$html .= '<div class="divTableRow" style="height: 100%;">' . "\n";
								$html .= '	<div class="divTableCell" style="font-weight: bold;">' . "\n";
								$html .= '		<img style="display:block;margin:0 auto;" height="50" src="' . $row['logoPath'] . '" />' . "\n";
								$html .= 		$row['bowlName'] . '</div>' . "\n";
								$html .= '	<div class="divTableCell" style="height: 100%; font-weight: bold; display: grid; grid-template-columns: 2fr 1fr 2fr; /* fraction*/">' . "\n";
								$html .= '		<div><img style="display:block;margin:0 auto;" height="50" src="' . $row['vtLogoPath'] . '" />' . "\n";
								$html .= 		$row['vtUniv'] . ' ' . $row['vtTeamName'] . '</div><div style="display: flex; align-items: center; justify-content: center;">v.</div><div>' . "\n";
								$html .= '		<img style="display:block;margin:0 auto;" height="50" src="' . $row['htLogoPath'] . '" />' . "\n";
								$html .= 		$row['htUniv'] . ' ' . $row['htTeamName'] . '</div></div>' . "\n";		
								$html .= '	<div class="divTableCell">' . "\n";
								$html .= 		date('m/d/y', strtotime($row['bowlDateTime'])). '<br />' . date('g:i a', strtotime($row['bowlDateTime'])) . '</div>' . "\n";
								$html .= '</div>' . "\n";
								echo $html;
							}
						}
					?>
				</div>
		<hr />
		<h3>Leaderboard</h3>
				<div class="divTable">
					<div class="divTableHeading">
						<div class="divTableCell">
							<p>Place</p>
						</div>
						<div class="divTableCell">
							<p>Player</p>
						</div>
						<div class="divTableCell">
							<p>Score</p>
						</div>
						<div class="divTableCell">
							<p>Record</p>
						</div>
						<div class="divTableCell">
							<p>Points Remaining</p>
						</div>
						<div class="divTableCell">
							<p>Maximum Possible</p>
						</div>			
					</div>
					<?php
						$sql = "SELECT *";
						$sql .= " FROM " . DB_PREFIX . "scoreboard";
						$sql .= " WHERE firstName != 'Admin'";
						$query = $mysqli->query($sql) or die($mysqli->error);
						$i=1;
						if ($query->num_rows > 0) {
							while ($row = $query->fetch_assoc()) {	
								echo '<div class="divTableRow">' . "\n";
								echo '	<div class="divTableCell">' . "\n";
								echo '		<p>'  . $i . '</p></div>' . "\n";
								echo '	<div class="divTableCell">' . "\n";
								echo '		<p>' . $row['firstName'] . '<br />'  . $row['lastName'] . '</p></div>' . "\n";
								echo '	<div class="divTableCell">' . "\n";
								echo '		<p>' . $row['currentScore'] . '</p></div>' . "\n";
								echo '	<div class="divTableCell">' . "\n";
								echo '		<p>' . $row['wins']. '-' . $row['losses'] . '</p></div>' . "\n";
								echo '	<div class="divTableCell">' . "\n";
								echo '		<p>' . $row['pointsRemaining']. '</p></div>' . "\n";
								echo '	<div class="divTableCell">' . "\n";
								echo '		<p>' . $row['maxPossible']. '</p></div>' . "\n";
								echo '</div>' . "\n";
								$i++;
							}
						}
					?>
				</div>
		<hr />
		<p>Some statistics of our picks ...
		<ul>
			<li><span style="font-size: 0.8em;">Bowls with largest average wager: Duke's Mayo (32.21), Arizona (29.94), Cotton (27.79)</span></li>
			<li><span style="font-size: 0.8em;">Bowls with smallest average wager: 1st Responder (16.89), Quick Lane (17.26), Pinstripe (17.84)</span></li>
			<li><span style="font-size: 0.8em;">Bowls with most range of wagers: Cheez-It (43 - 1), Music City (43 - 1), Peach (43 - 1), CFB Championship (43 - 1)</span></li>
			<li><span style="font-size: 0.8em;">Bowls with least range of wagers: Arizona (38 - 10), Quick Lane (30 - 2), Cotton (43 - 13)</span></li>
			<li><span style="font-size: 0.8em;">Bowls with most deviation in wagers: CFB Championship, New Mexico, Peach</span></li>
			<li><span style="font-size: 0.8em;">Bowls with least deviation in wagers: Arizona, Duke's Mayo, Quick Lane</span></li>
			<li><span style="font-size: 0.8em;">Bowls with greatest count of same picks: Independence (16 for BYU), Armed Forces (16 for Army)</span></li>
			<li><span style="font-size: 0.8em;">Bowls with an even distribution of picks: Numerous :)</span></li>
		</ul></p>	
		<hr />
		<div class="divTable">
			<div class="divTableBody">
				<div class="divTableCell" style="border: 0;">
					<h4><span style="font-weight: bold;">...And ... We're back!</span></h4>
					<img style="display:block;margin:0 auto; width: 100%;" src="./images/eh.jpg" />
				</div>
			</div>
		</div>
		<p>Welcome to the 14th annual College Bowl Pick 'Em!
		 A new player has entered the game, this brings us up to 19! Let's welcome Tallula Bell Orloski!</p>
		<div class="divTable">
			<div class="divTableBody">
				<div class="divTableCell" style="border: 0;">
					<img style="display:block;margin:0 auto; width: 100%;" src="./images/tbo.jpg" />
				</div>
			</div>
		</div>
		<h4><span style="font-weight: bold;">Introductions and cute pictures aside, on to the rules.</span></h4>
		<ul>
			<li>First and most importantly, picks <span style="font-weight: bold;">MUST</span> be submitted prior to <span style="font-weight: bold;">
			11:55 AM EST on December 17, 2021</span>. Picks will not be accepted after that time, no exceptions.</li>
			<br />
			<li>To make your picks, click "My Picks" from the "Picks" menu in the top navigation bar. You will be presented with all <span style="text-decoration:line-through">40</span> 43
			bowl games and their matchups, as well as the National Championship game. Click the logo of the team you predict to win the bowl and assign a confidence
			value for your prediction. The value reflects the confidence in your pick. Assign larger values to your more 
			confident picks and lesser values to the more unsure picks. Each value will only be used once. Shortly after the first game has started, 
			I will add "View All Picks" to the menu. This will show you a fancy comparison of everyone's picks.</li>
			<br />
			<li>For each correct prediction, the value you assigned will be added to your score. Each incorrect prediction
			will be subtracted from your score. After all bowl games have been completed, the person with the best score wins. Last time, Elaine had claimed 1st Place with 4 ganes remaining, her 2nd win in 3 years! 
			As for the Toilet Trophy, it has been proudly displayed at the Hennessey household throughout the pandemic. I suppose it came in handy with the early TP shortages.</li>
			<br />
			<li>For the National Championship, the teams playing will not be known until after the Cotton Bowl and the Orange Bowl.
			The winner of Cincinnati and Alabama will play the winner of Georgia and Michigan. You will select either "Orange Bowl Winner"
			or "Cotton Bowl Winner" for the National Championship. You will also assign a confidence value as you would normally.<br /><br />
			Here are a couple of scenarios. Say you picked Alabama, Georgia, and Cotton Bowl Winner. You would get points for Alabama winning the 
			Cotton Bowl, Georgia winning the Orange Bowl, and you would want Cotton Bowl Winner, Alabama to beat Georgia in the National Championship.
			Now say Alabama lost to Cincinnati in the Cotton Bowl and Georgia wins the Orange Bowl. You would lose points for picking Alabama, 
			you would get your Georgia points and you would get points if Cotton Bowl Winner, Cincinnati beats Georgia.
			</li>
		</ul>
		<p>Keep checking back here for the current scores and projections (available starting 12/31). This home page will have our Pick 'Em
			scoreboard, as well as a glance of the next 5 bowls on the schedule. Full bowl results and schedule can be found be clicking "Results"
			from the "Bowls" menu in the top navigation.</p>
		
	</div><!-- end col -->
</div><!-- end entry-form -->
<?php
require('includes/footer.php');