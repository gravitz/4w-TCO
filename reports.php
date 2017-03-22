<?php 
	require ("functions.inc.php");
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
		<link rel="stylesheet" href="css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="css/buttons.dataTables.min.css">
		
		<!--<link rel="stylesheet" href="css/dc.css">-->
		<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dc/2.0.0-beta.27/dc.min.css">-->
		
		<link rel="stylesheet" href="css/layout.css">
		
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.buttons.min.js"></script>
		<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
		
		<script src="http://cdn.datatables.net/buttons/1.2.1/js/buttons.flash.min.js"></script>
		<!--<script src="http://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script> 
		<script src="http://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
		<script src="http://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>-->
		<script src="http://cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>
		<script src="http://cdn.datatables.net/buttons/1.2.1/js/buttons.print.min.js"></script>
	
		<!--<script src="js/jquery.modal.js" type="text/javascript"></script>-->
		
		<script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
		<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->
		
		<!--<script src="data/data.js"></script>-->

		
		<!--<script src="https://code.highcharts.com/maps/highmaps.js"></script>-->
		<!--<script src="js/highmaps.js"></script>-->
		<!--<script src="https://code.highcharts.com/maps/modules/data.js"></script>-->
		<!--<script src="js/data.js"></script>-->
		<!--<script src="https://code.highcharts.com/maps/modules/drilldown.js"></script>-->
		<!--<script src="js/drilldown.js"></script>-->
		<!--<script src="https://code.highcharts.com/mapdata/countries/us/us-all.js"></script>-->
		<!--<script src="js/customEvents.js"></script>-->
		<!--<script src="data/tz-all.js"></script>-->
		
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700|Open+Sans:400,300,300italic,400italic,600,600italic' rel='stylesheet' type='text/css'>
		
        <title>Tanzania - Development Activity (and Partners) Mapping and Visualization Dashboard</title>
       
		<!--<script src="js/custom.js"></script>-->
		
        	<!--<script src="js/d3.min.js"></script>-->
		<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.16/d3.min.js" charset="utf-8"></script>-->
		
       		<!--<script src="js/crossfilter.v1.min.js"></script>-->
	   	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/crossfilter/1.3.12/crossfilter.min.js"></script>-->
	
        <!--<script src="js/dc.js"></script>--> 

		<script type="text/javascript">
		$(document).ready(function () {
			$("#report-selector" ).change(function() {
				try
				{
					$.fn.dataTable.ext.errMode = 'none';
					var arr;
					var table = $('#report').DataTable( {
						serverSide: false,
						ajax: {
							"url": "report_generation.php",
							"type": "POST",
							"data": {
								"rpt": $("#report-selector option:selected").val()
							},
							"dataSrc": function ( json ) {
								//console.log(json.columns);
								//arr = json.columns;
							  /*for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
								//arr[] = json.data[i][0];
								console.log(json.console);
							  }
							  console.log(json.data);*/
							  arr = json.columns;
							  //console.log(arr);
							  this.cell( this ).index().column;
							  return json.data;
							}
						},
						columns: [
							{ data: 0 },
							{ data: 1 },
							{ data: 2 },
							{ data: 3 }
						],
						//columns: arr,
						
						dom: 'lfrtBip',

						buttons: [
							'copy', 'excel', 'print'
						],
						destroy: true,
						searching: false,
						pageLength: 30,
						paging: true
				} );
				}
				catch(err)
				{
					
				}
				
				table.on( 'xhr', function () {
						var json = table.ajax.json();
						//console.log(json.columns);
						//console.log(Object.keys(json.data[0]));
						//alert( json.data.length +' row(s) were loaded' );
					});
			});
		});
		
		/*$(document).ready(function() {
			$("#report-selector" ).change(function() {
				$.ajax({
					url: 'report_generation.php',
					type: 'POST',
					dataType: "JSON",
					data: {
						"rpt": $("#report-selector option:selected").val()
					},
					success: function(d) {
						console.log(d.columns);
						$('#report').DataTable({
							dom: 'lfrtBip',
							buttons: [
								'copy', 'excel', 'print'
							],
							destroy: true,
							searching: false,
							pageLength: 30,
							paging: true,
							data: d.data,
							columns: d.columns
						});
					}
				});
			});
		});*/
	</script>
        	       
    </head>
    <body>		
		<div id='wrapper'>
			<div id='header'>
				<div id='logoContainer'>	
					<div class="float-left">
						<a title="Home" href="index.php">
						<img alt="Home" title="Home" src="images/logo.png" />
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
					<li><a href="index.php">Home</a></li>
					<li><a href="about.php">About</a></li>
					<li><a href="reports.php">Reports</a></li>

					</ul>
			</div>
			<div id="content">
				<div class="container"> 
					<div class="row">
						<div class="col-md-4">
							<form role="form"  id="report-select-form" method="post" action="<?php $_PHP_SELF ?>">
							<div class="form-group">
									<!--<label for="report">Report</label>-->
									<select class="form-control selectpicker" id="report-selector" name="report-selector" data-live-search="true">
										<option selected disabled value="">Select Report</option>
										<option value="abr">Activities by Region</option>
										<option value="abd">Activities by District</option>
										<option value="abor">Activities by Organisation by Region</option>
										<option value="abod">Activities by Organisation by District</option>
										<!--<option value="abr">Activities by Region</option>-->
									</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">							
							<div class="row">	
								<table id="report" class="display" cellspacing="0" width="100%">
								<!--<thead>
									<tr>
										<th>Region</th>
										<th>Classification</th>
										<th># of Activities</th>
									</tr>
								</thead>-->
							</table>
							</div>
						</div>		 			  
					</div>
				</div>
			</div>
		</div>
    <!--<script type="text/javascript" src="js/site.js"></script> -->
	
	</body>
</html>