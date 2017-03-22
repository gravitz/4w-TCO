<?php 
	require ("includes/functions.inc.php");
?>
<!--<!DOCTYPE html>-->
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">-->
		
		<!--<link href="css/font-awesome.css" rel="stylesheet">-->
		<link href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
		
		<link rel="stylesheet" href="style/css/dc.min.css">
		<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dc/2.0.0-beta.27/dc.min.css">-->
		
		<link rel="stylesheet" href="style/css/layout.css">
		
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700|Open+Sans:400,300,300italic,400italic,600,600italic' rel='stylesheet' type='text/css'>
		
        <title>Tanzania - Development Activity (and Partners) Mapping and Visualization Dashboard</title>
		
        <?php //echo getDataJavaScriptString($servername, $username, $password, $dbname, $tableName); ?>        
    </head>
    <body>	
		<div id='wrapper'>
		<nav class="navbar navbar-toggleable-md navbar-light bg-faded" >
			<div class="container-fullwidth" >
				<div class="row xpanel xpanel-default" style="display: none">
					<a class="navbar-brand" title="Home" href="index.php">
						<img src="style/images/logo.png" class="d-inline-block align-top img-responsive img-rounded" alt="Home" title="Home" />
					</a>
					<span class="navbar-text">
						<h2>Tanzania</h2>
						<h4>Development Activity (and Partners) Mapping and Visualization Dashboard</h4>
						<h6><?php $currentDate =  getdate(); echo $currentDate['weekday'] . " " . $currentDate['mday'] . ", " . $currentDate['month'] . " " . $currentDate['year']; ?></h6>
					</span>
					<span class="navbar-text" id='userDetail'>		
						<p></p>
						<p></p>
					</span>
				</div>
				<div class="row">
					<div class="collapse navbar-collapse" id="menu">
						<!--<div class="nav navbar-nav">
							<a class="nav-item nav-link active" href="index.php">Home<span class="sr-only">(current)</span></a>
							<a class="nav-item nav-link" href="about.php">About</a>
						</div>-->
						<ul class="nav nav-tabs">
						  <li class="active"><a class="nav-item nav-link active" href="index.php">Home<span class="sr-only">(current)</span></a></li>
						  <li><a class="nav-item nav-link" href="about.php">About</a></li>
						</ul>
					</div>
				</div>
			</div>
		</nav>	
		<div class="container-fluid">
			<div id="content">
				<div class="col-md-3">									
					<div class="row">
						<h5>Sector <span class='locality'></span></h5>
						<div id="sectors" style="width:400px; height:250px">
							<span class='reset small' style='display: none;'> <a class='reset' href='javascript:sector_chart.filterAll();dc.redrawAll();'>Reset</a>
								| Active Filter(s): <span class='filter bg-info'></span></span>
						</div>
					</div>   
					<div class="row"> 	
						<h5>Sub-sectors<span class='locality'></span></h5>
						<div id="subsectors">
							<span class='reset small' style='display: none;'> <a class='reset' href='javascript:subsector_chart.filterAll();dc.redrawAll();'>Reset</a>
								| Active Filter(s): <span class='filter bg-info'></span></span>
						</div>	
						<!--<a href="#AccOrgByType" data-toggle="collapse">Show Orgs by Type</a>-->
					</div>	
					<!--<div class="row collapse" id="AccOrgByType"> 	
						<h5>Types of Organization <span class='locality'></span></h5>
						<div class="caption-container" id="aorgtype" style="width:200px; height:200px"></div>		
					</div>	-->
				</div>
				<div class="col-md-6">
					<div class="row button-container">
					<span class="pull-left">
						<a class="btn btn-primary btn-sm disabled" id="activity_counter">Total Activities: </a>
					</span>
					<span class="pull-right">
						<a class="reset btn btn-primary btn-sm" id="reset" href="javascript: resetAll();">Reset All</a>
						<a class="reset btn btn-primary btn-sm" id="export" href="javascript: /*getExport()*/ downloadAll();">Export All</a>
						<a class="reset btn btn-primary btn-sm" id="add" href="admin/addactivity.php">Add an Activity</a>
						<br />
						<span id ="fetch"></span>
					</span>
					</div>
					<div class="row">	
						<!--<h5>Regions</h5>-->							
						<!--<div id="country" style="width:700px; height:550px;"></div>--> 
						<div id="map_container" style="height: 550px; min-width: 700px; max-width: 800px; margin: 0 auto"></div>
						<div id="table_container" style="display:none; max-width: 500px; margin: 0 auto;">
							<div id="regtable_caption" class="text-center">
								<h5>Summary of Activities <span class='locality'></span>.</h5>
							</div>
							<div id="regtable"  class='table table-hover' style="min-width: 460px; max-width: 460px;"></div>
							<div id="regtable_paging">
								Showing <span id="begin"></span>-<span id="end"></span> of <span id="size"></span>.
								<input id="last" class="btn" type="button" value="Last" onclick="javascript:last()" />
								<input id="next" class="btn" type="button" value="Next" onclick="javascript:next()" />
								<input id="dnld" class="btn" type="button" value="Download" onclick="javascript:download()" />
							</div>
						</div>
					</div>
					<!--<div class="row">
						<h5>Districts</h5>
						<div class="modal fade" id="myModal" role="dialog">
							<div class="modal-dialog">

								 Modal content
								<div class="modal-content">
									<div id="region" style="width:700px; height:550px"></div>
								</div>
							</div>
						</div>
					</div>-->
				</div>	
				<div class="col-md-3">
					<div class="row">
						<h5>Funding by Sector<span class='locality'></span></h5>
						<div id="funding" style="width:400px; height:250px">
							<span class='reset small' style='display: none;'> <a class='reset' href='javascript:funding_by_sector_chart.filterAll();dc.redrawAll();'>Reset</a>
								| Active Filter(s): <span class='filter bg-info'></span></span>
						</div>
					</div>
					<div class="row"> 	
						<h5>Funding Organisations <span class='locality'></span></h5>
						<div id="fund_org">
							<span class='reset small' style='display: none;'> <a class='reset' href='javascript:funding_org_chart.filterAll();dc.redrawAll();'>Reset</a>
								| Active Filter(s): <span class='filter bg-info'></span></span>
						</div>	
						<a href="#FundOrgByType" data-toggle="collapse">Show Orgs by Type</a>
					</div>	
					<div class="row collapse" id="FundOrgByType"> 	
						<h5>Types of Organization <span class='locality'></span></h5>
						<div class="caption-container" id="forgtype" style="width:200px; height:200px"></div>		
					</div>
					<!--<div class="row caption-container">
						<h5>Tanzania Developmental Activities Map</h5>
						<p class="small">This interactive map shows the distribution of developmental activities within Tanzania</p>
					</div> -->
				</div>						 			  
			</div>
		</div>
		<script src="js/jquery-2.2.4.min.js" type="text/javascript"></script>
		<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>-->
		<script src="bootstrap-3.3.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
		<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->
		<script src="js/crossfilter.min.js" type="text/javascript"></script>
		<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/crossfilter/1.3.12/crossfilter.min.js"></script>-->
		<script src="js/d3.min.js" type="text/javascript"></script>
		<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.16/d3.min.js" charset="utf-8"></script>-->
        <script src="js/dc.min.js" type="text/javascript"></script>
		<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/dc/2.1.3/dc.min.js" charset="utf-8"></script>-->
		<script src="js/d3-queue.min.js" type="text/javascript"></script>
		<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/d3-queue/3.0.4/d3-queue.min.js"></script>-->
		<script src="js/highmaps.js" type="text/javascript"></script>
		<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/highmaps/5.0.7/js/highmaps.js"></script>-->
		<script src="js/drilldown.js" type="text/javascript"></script> 
		<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/highmaps/5.0.7/js/modules/drilldown.js"></script>-->
		

		<link href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

		<div id="container" style="height: 500px; min-width: 310px; max-width: 800px; margin: 0 auto"></div>

		<script src="data/tz-all.js" type="text/javascript"></script>

		
		<script src="js/FileSaver.js" type="text/javascript"></script>
		<!--<script src="data/data.js"></script>-->
		<script type="text/javascript" src="js/3w.index.js"></script> 
		
	</div>
	</body>
</html>