<?php
require ("../includes/functions.inc.php");
$message = "";

function isPostedDataValid(){
	return isset($_POST["activity"]) && isset($_POST["region"]) && isset($_POST["organization"])
		&& !empty($_POST["activity"])  && !empty($_POST["region"]) && !empty($_POST["organization"]); 
		
	/* return isset($_POST["activity"]) && isset($_POST["region"]) && isset($_POST["organization"])
		&& $_POST["activity"] != "" && $_POST["region"] != "" && $_POST["organization"] != ""; */

}

if($_POST && isPostedDataValid()){	
	$activityTitle = trim($_POST["activity"]);
	$activityClassification = trim($_POST["activityClassification"]);
	$activitySubClassification = trim($_POST["activitySubClassification"]);
	$activityDesc = trim($_POST["desc"]);
	
	$code = getClassificationCode($activityClassification).'-'.getToken(5);//isset($_POST["code"])? $_POST["code"] : "";
	$region = trim($_POST["region"]);
	$district = trim($_POST["district"]);
	$ward = trim($_POST["ward"]);
	$organization = trim($_POST["organization"]);
	$orgType = isset($_POST["org-type"])? $_POST["org-type"] : "";
	$orgType = trim($orgType);
	
	insertActivity ($activityTitle, $activityClassification, $activitySubClassification, $activityDesc, $code, $region, $district, $ward, $organization, $orgType  );
	
	
} else if ($_POST && !isPostedDataValid()){
	$message = "Error: <br> Your form contained errors. Please fix the errors and re-submit.";
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../bootstrap-3.3.6-dist/css/bootstrap.min.css" type="text/css" media="screen">
		<link rel="stylesheet" href="../bootstrap-3.3.6-dist/css/bootstrap-select.min.css" type="text/css" media="screen">
		<link rel="stylesheet" href="../style/css/layout.css" type="text/css" media="screen">
		
		<link rel="stylesheet" href="../style/css/style.css" type="text/css" media="screen">
		<link rel="stylesheet" href="../style/css/responsive.css" type="text/css" media="screen">
        <!--<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" href="../style/css/font-awesome.css" type="text/css" media="screen">-->
		
		<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
		<script src="../js/jquery-1.11.2.min.js" type="text/javascript"></script>
		<script src="../js/editablegrid-2.1.0-b25.js" type="text/javascript"></script> 
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js" type="text/javascript"></script>
		<script src="../js/gridEditor.js" type="text/javascript"></script>
		<script type="text/javascript">
            var datagrid = new DatabaseGrid();
			window.onload = function() { 
                // key typed in the filter field
                $("#filter").keyup(function() {
                    datagrid.editableGrid.filter( $(this).val());

                    // To filter on some columns, you can set an array of column index 
                    //datagrid.editableGrid.filter( $(this).val(), [0,3,5]);
                  });
			}; 
		</script>
		
		<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
		<script src="../bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
		<script src="../bootstrap-3.3.6-dist/js/bootstrap-select.js"></script>
		
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700|Open+Sans:400,300,300italic,400italic,600,600italic' rel='stylesheet' type='text/css'>
		
        <title>Tanzania - Development Activity (and Partners) Mapping and Visualization Dashboard</title>
                 
    </head>
    <body>		
		<div id='wrapper'>
			<div id='header'>
				<div id='logoContainer'>	
					<div class="float-left">
						<a title="Home" href="../index.php">
						<img  alt="Home" title="Home" src="../style/images/logo.png" />
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
					<li><a href="../index.php">Home</a></li>
					<li><a href="../about.php">About</a></li>
					</ul>
			</div>
			<div id="content">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<h4>Edit Activities on Record</h4>
							<div id="message-container">
							</div>
							<div id="toolbar" class="pull-rights">
								<input type="text" id="filter" name="filter" placeholder="Filter: Enter text here"  />
							</div>
							
							
							<!-- Grid contents -->
							<div id="tablecontent">
							</div>
		
							<!-- Paginator control -->
							<div id="paginator"></div>
						</div>						
					</div>
				</div>
			</div>
        
<script type="text/javascript">
$(document).ready(function () {
	$('#message-container').html("<?php echo $message; ?>");
});
</script>
 