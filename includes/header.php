<?php
header('Content-Type:text/html; charset=utf-8');
header('X-UA-Compatible:IE=Edge,chrome=1'); //IE8 respects this but not the meta tag when under Local Intranet
?>
<!DOCTYPE html>
<html xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>College Bowl Game Pick 'Em <?php echo SEASON_YEAR; ?></title>

	<base href="<?php echo SITE_URL; ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/all.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/jquery.countdown.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/cef-custom.css?v=1" />
	<link rel="icon" href="images/logos/ncaa.png" type="image/png" />
	<link rel="shortcut icon" href="images/logos/ncaa.png" />
	<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/modernizr-2.7.0.min.js"></script>
	<script type="text/javascript" src="js/svgeezy.min.js"></script>
	<script type="text/javascript" src="js/jquery.main.js"></script>

	<script type="text/javascript" src="js/jquery.jclock.js"></script>
	<script type="text/javascript" src="js/jquery.plugin.min.js"></script>
	<script type="text/javascript" src="js/jquery.countdown.min.js"></script>
	<script type="text/javascript" src="js/cef-custom.js?v=1"></script>
</head>

<body>
	<div class="container">
		<header id="header" class="row">
			<div id="top-nav" class="col-sm-12">
				<!-- Static navbar -->
				<div class="navbar navbar-default" role="navigation">
					<div class="container-fluid">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<div id="logo" class="navbar-brand"><img src="images/logos/ncaa.png" alt="College Bowl Game Pick 'Em <?php echo SEASON_YEAR; ?>" class="img-responsive" /></div>
							<div id="site-title" class="navbar-brand">College Bowl Game Pick 'Em <?php echo SEASON_YEAR; ?></div>
						</div>
						<div class="navbar-collapse collapse">
							<ul class="nav navbar-nav">
								<li<?php echo (($activeTab == 'home') ? ' class="active"' : ''); ?>><a href="./">Home</a></li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Picks <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="makepicks.php">My Picks</a></li>
										<li><a href="viewpicks.php">View All Picks</a></li>
										<li><a href="pickvalues.php">Remaining Pick Values</a></li>
										<li><a href="pickmatrix.php">Pick Similarity Matrix</a></li>
										<li><a href="pickmatrixremaining.php">Pick Similarity Matrix (remaining)</a></li>
									</ul>
								</li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Bowls <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="bowls.php">Results</a></li>
										<li><a href="viewodds.php">Projections</a></li>
									</ul>
								</li>
								<?php if ($_SESSION['logged'] === 'yes' && $user->is_admin) { ?>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="editbowls.php">Update Bowls</a></li>
										<li><a href="scenarios.php">Generate Scenarios</a></li>
										<li><a href="projections.php">Generate Projections</a></li>
										<li><a href="places.php">Generate Places</a></li>
									</ul>
								</li>
								<?php } ?>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<!--<li><a href="rules.php" title="Rules/Help"><span class="glyphicon glyphicon-book"></span> <span class="text">Rules/Help</span></a></li>-->
								<li class="dropdown">
									<!--a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['loggedInUser']; ?> <b class="caret"></b></a-->
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <span class="text"><?php echo $_SESSION['loggedInUser']; ?></span> <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="user_edit.php">My Account</a></li>
										<li><a href="logout.php">Logout <?php echo $user->userName; ?></a></li>
									</ul>
								</li>
							</ul>
						</div><!--/.nav-collapse -->
					</div>
				</div>
			</div>
		</header>
		<div id="pageContent">
		<?php
		if ($user->is_admin && is_array($warnings) && sizeof($warnings) > 0) {
			echo '<div id="warnings">';
			foreach ($warnings as $warning) {
				echo $warning;
			}
			echo '</div>';
		}
		?>
