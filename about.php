<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/layout.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700|Open+Sans:400,300,300italic,400italic,600,600italic' rel='stylesheet' type='text/css'>
		
        <title>Tanzania - Development Activity (and Partners) Mapping and Visualization Dashboard</title>
       
        <script src="js/jquery.js"></script>
	</head>
	<body>		
		<div id='wrapper'>
			<div id='header'>
				<div id='logoContainer'>	
					<div class="float-left">
						<a title="Home" href="index.php">
						<img  alt="Home" title="Home" src="images/logo.png" />
						</a>
					</div>
				</div>
				<div id="titleContainer">
						  <h2>Tanzania</h2>
						  <h4>Development Activity (and Partners) Mapping and Visualization Dashboard</h4>
						  <p><?php $currentDate =  getdate(); echo $currentDate['weekday'] . " " . $currentDate['mday'] . ", " . $currentDate['month'] . " " . $currentDate['year']; ?> </p>

					</div>
				<div id='userDetail'>		
					<p></p>
					<p></p>
				</div>
				<div></div>
			</div>
			<div id='menu'>
				<ul class="nav nav-tabs">
					<li class=""><a href="index.php">Home</a></li>
					<li class="active"><a href="about.php">About</a></li>
				</ul>
			</div>
			<div id="content">
				<div class="full-width">
					<div class="indent">
						<div>
							<h2>About Tanzania - Development Activity (and Partners) Mapping and Visualization Dashboard</h2>
							This dashboard presents an interactive overview of the presence of various developmental organizations within Tanzania, thier operational focus areas and the geographical distibution of these interventions.  The dashboard helps to coordinate efforts, identify misrepresented regions, avoid overlapping interventional programs as well as identify potential partners.
							<p>The map shows an activity heat map of developmental organizations, which can be used in combination with the charts, to quickly and interactively associate partners with both their geographic and developmental focus areas. It also aims to provide a general overview and understanding of the presence and scope of different needs.
						</div>					
					</div>					
				</div>
			</div>
		</div>	
	</body>	
</html>