<?php
require ("../includes/functions.inc.php");
$message = "";

//Checks for input data validity
function isPostedDataValid(){
	
	//Temporarily disabled checks
	return true;
	
	return isset($_POST["activity"]) && isset($_POST["region"]) && isset($_POST["Orgs"]) && 
	isset($_POST["iOrganization"]) && !empty($_POST["activity"])  && !empty($_POST["region"]) && !empty($_POST["Orgs"]) && !empty($_POST["iOrganization"]); 
		
	/* return isset($_POST["activity"]) && isset($_POST["region"]) && isset($_POST["organization"])
		&& $_POST["activity"] != "" && $_POST["region"] != "" && $_POST["organization"] != ""; */

}

if($_POST && isPostedDataValid()){
	//echo json_encode($_POST);

	$activityTitle = trim($_POST["activity"]);
	$activityDesc = trim($_POST["desc"]);
	$activitysDate = trim($_POST["sDate"]);
	$activityeDate = trim($_POST["eDate"]);	
	
	$activityClassification = trim($_POST["activityClassification"]);
	$activitySubClassification =  trim($_POST["activitySubClassification"]);
	
	$activityTheme = isset($_POST["activityTheme"])? $_POST["activityTheme"] : "";
	$activityTheme = trim($activityTheme);
	
	//$code = getClassificationCode($activityClassification).'-'.getToken(5);
	$code = $activityClassification.'-'.getToken(5);
		
	/*$iOrganization = trim($_POST["iOrganization"]);
	$iOrgType = isset($_POST["iOrgType"])? $_POST["iOrgType"] : "";
	$iOrgType = trim($iOrgType);
	
	$fOrganization = trim($_POST["fOrganization"]);
	$fOrgType = isset($_POST["fOrgType"])? $_POST["fOrgType"] : "";
	$fOrgType = trim($fOrgType);*/
	
	$orgs = $_POST["Orgs"]; //Array of organisations (and classification), and their roles in the activity
	
	$activityStatus = trim($_POST["activityStatus"]);
	$activityType = trim($_POST["activityType"]);
	
	$fundingStatus = trim($_POST["fundingStatus"]);
	$fundingType = trim($_POST["fundingType"]);
	$fundingCurrency = trim($_POST["fundingCurrency"]);
	$fundingAmount = trim($_POST["fundingAmount"]);
	
	$focalPoint = trim($_POST["focalPoint"]);
	
	$region = $_POST["region"];
	$district = $_POST["district"];
	$ward = $_POST["ward"];
	
	insertActivity ($activityTitle, $activityDesc, $activitysDate, $activityeDate, $activityClassification, $activitySubClassification, $activityTheme, $code, $orgs, $activityStatus, $activityType, $fundingStatus, $fundingType, $fundingCurrency, $fundingAmount, $focalPoint,$region, $district, $ward);
	/*insertActivity ($activityTitle, $activityClassification, $activitySubClassification, $activityDesc, $code, $region, $district, $ward, $fOrganization, $fOrgType, $iOrganization, $iOrgType );*/
} else if ($_POST && !isPostedDataValid()){
	$message = "Error: <br> Your form contained errors. Please fix the errors and re-submit.";
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="../bootstrap-3.3.6-dist/css/bootstrap-select.min.css">
		<!--<link rel="stylesheet" href="../bootstrap-3.3.6-dist/css/typeahead.css">-->
		<link rel="stylesheet" href="../bootstrap-3.3.6-dist/css/typeaheadjs.css">
		<link rel="stylesheet" href="../style/css/layout.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<!--<script src="../js/jquery-2.2.4.min.js" type="text/javascript"></script>-->
		<script type="text/javascript">
			if (typeof jQuery == 'undefined') {
			  document.write(unescape("%3Cscript src='../js/jquery-1.11.2.min.js' type='text/javascript'%3E%3C/script%3E"));
			}
		</script>
		<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
		<script src="../bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
		<script src="../bootstrap-3.3.6-dist/js/bootstrap-select.js"></script>
		<!--<script src="../bootstrap-3.3.6-dist/js/bootstrap3-typeahead.min.js"></script>-->
		<!--<script src="../bootstrap-3.3.6-dist/js/bootstrap3-typeahead.min.js"></script>-->
		<!--<script src="../bootstrap-3.3.6-dist/js/typeahead.jquery.js"></script>-->
		<script src="../bootstrap-3.3.6-dist/js/typeahead.jquery.min.js"></script>
		<script src="../bootstrap-3.3.6-dist/js/bloodhound.min.js"></script>
		<!--<script src="../bootstrap-3.3.6-dist/js/typeahead.bundle.min.js"></script>-->
		<script src="../js/jquery.validate.min.js"></script>  
		<script type="text/javascript" src="../js/3w.addActivity.js"></script> 

		<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700|Open+Sans:400,300,300italic,400italic,600,600italic' rel='stylesheet' type='text/css'>
		
        <title>Tanzania - Development Activity (and Partners) Mapping and Visualization Dashboard</title>
                 
    </head>
    <body>		
		<div id='wrapper'>
			<nav class="navbar navbar-toggleable-md navbar-light bg-faded" >
			<div class="container-fullwidth" >
				<div class="row xpanel xpanel-default">
					<a class="navbar-brand" title="Home" href="../index.php">
						<img src="../style/images/logo.png" class="d-inline-block align-top img-responsive img-rounded" alt="Home" title="Home" />
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
						  <li><a class="nav-item nav-link active" href="../index.php">Home<span class="sr-only">(current)</span></a></li>
						  <li><a class="nav-item nav-link" href="about.php">About</a></li>
						</ul>
					</div>
				</div>
			</div>
		</nav>	
		<div class="container-fluid">
			<div id="content">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<h4>Add a new Activity Record</h4>
							<br />
							<form role="form" class="form-horizontal" id="add-activity-form" method="post" action="<?php $_PHP_SELF ?>">
							<ul class="nav nav-tabs form-tabs">
								<li id="aProfile" class="active">
									<a data-toggle="tab" href="#activityProfile">Basic Information</a>
								</li>
								<li class="" id="aOrganisations"> 
									<a data-toggle="tab" href="#activityOrganisations">Advanced Information</a>
								</li>
								<li class="" id="aCoverage"> 
									<a data-toggle="tab" href="#activityCoverage">Activity Coverage</a>
								</li>
							</ul>
							<div class="tab-content">
								<fieldset id="activityProfile" class="tab-pane active">
									<br />
									<div class="form-group row">							
										<label for="activity" class="col-sm-2 control-label col-form-label">Activity (Title):</label>	
										<div class="col-sm-10">
										<input type="text" class="form-control" id="activity" name="activity" aria-describedby="activityHelpBlock">
										<p id="activityHelpBlock" class="form-text text-muted">
											A statement or two, signifying the official (or otherwise) title of the activity.
										</p>
										</div>
									</div>
									<!--<div class="form-group row">
										<label for="code">Code:</label>
										<input type="text" class="form-control" id="code" name="code">
									</div>-->
									<div class="form-group row">
										<label for="desc" class="col-sm-2 control-label col-form-label">Activity Description:</label>
										<div class="col-sm-10">
										<textarea class="form-control" rows="5" id="desc" name="desc" aria-describedby="descHelpBlock"></textarea>
										<p id="descHelpBlock" class="form-text text-muted">
											A more detailed description of the activity.
										</p>
										</div>
									</div>
									<div class="form-group row">
										<label for="sDate" class="col-sm-2 control-label col-form-label">Activity Start Date:</label>
										<div class="col-sm-4">
										<input type="date" class="form-control" id="sDate" name="sDate" aria-describedby="sDateHelpBlock">
										<p id="sDateHelpBlock" class="form-text text-muted">
											The date when the activity started. An estimate may suffice.
										</p>
										</div>
										<label for="eDate" class="col-sm-2 control-label col-form-label">Activity End Date:</label>
										<div class="col-sm-4">
										<input type="date" class="form-control" id="eDate" name="eDate" aria-describedby="eDateHelpBlock">
										<p id="eDateHelpBlock" class="form-text text-muted">
											The scheduled or actual end date (if any) of the activity.
										</p>
										</div>
									</div>
									<div class="form-group row">
										<label for="activityClassification" class="col-sm-2 control-label col-form-label">Intervention Area:</label>
										<div class="col-sm-3">
										<select class="form-control selectpicker" id="activityClassification" name="activityClassification" aria-describedby="activityClassificationHelpBlock" data-live-search="true">
											<?php echo getClassificationDropdownOptions(); ?>
										</select>
										<p id="activityClassificationHelpBlock" class="form-text text-muted">
											Sectoral classification under which the activity falls.
										</p>
										</div>
										<label for="activitySubClassification" class="col-sm-2 control-label col-form-label">Sub classification:</label>
										<div class="col-sm-5">
										<select class="form-control selectpicker" id="activitySubClassification" name="activitySubClassification" aria-describedby="activitySubClassificationHelpBlock" data-live-search="true">
											<?php //echo getSubClassificationDropdownOptions(3); ?>
										</select>
										<p id="activitySubClassificationHelpBlock" class="form-text text-muted">
											Sectoral sub-classification (if any) under which the activity falls.
										</p>
										</div>
									</div>
									<div class="form-group row">
										<label for="activityTheme" class="col-sm-2 control-label col-form-label">Activity Theme:</label>
										<div class="col-sm-10">
										<select class="form-control selectpicker" id="activityTheme" name="activityTheme" aria-describedby="activityThemeHelpBlock" data-live-search="true">
											<?php echo getThemeDropdownOptions(); ?>
										</select>
										<p id="activityThemeHelpBlock" class="form-text text-muted">
											Thematic grouping (if any) under which the activity falls.
										</p>
										</div>
									</div>
									<div class="form-group row">
										<label for="activityStatus" class="col-sm-2 control-label col-form-label">Activity Status:</label>
										<div class="col-sm-10">
										<label class="radio-inline" for="activityStatusItem0">
											<input type="radio" name="activityStatus" id="activityStatusItem0" value="Pipeline/Identification" >Pipeline/Identification
										</label>
										<label class="radio-inline" for="activityStatusItem1">
											<input type="radio" name="activityStatus" id="activityStatusItem1" value="Implementation" >Implementation
										</label>
										<label class="radio-inline" for="activityStatusItem2">
											<input type="radio" name="activityStatus" id="activityStatusItem2" value="Completion" >Completion
										</label>
										<label class="radio-inline" for="activityStatusItem3">
											<input type="radio" name="activityStatus" id="activityStatusItem3" value="Post-completion" >Post-completion
										</label>
										<label class="radio-inline" for="activityStatusItem4">
											<input type="radio" name="activityStatus" id="activityStatusItem4" value="Cancelled" >Cancelled
										</label>
										<label class="radio-inline" for="activityStatusItem5">
											<input type="radio" name="activityStatus" id="activityStatusItem5" value="Suspended" >Suspended
										</label>
										</div>
										<!--<select class="form-control selectpicker" id="activityStatus" name="activityStatus" data-live-search="true">
											<?php //echo getSubClassificationDropdownOptions(3); ?>
											<option>Pipeline/Identification</option>
											<option>Implementation</option>
											<option>Completion</option>
											<option>Post-completion</option>
											<option>Cancelled</option>
											<option>Suspended</option>
										</select>-->
									</div>
									<div class="form-group row">
										<label for="activityType" class="col-sm-2 control-label col-form-label">Activity Type:</label>
										<div class="col-sm-10">
										<label class="radio-inline" for="activityTypeItem0">
											<input type="radio" name="activityType" id="activityTypeItem0" value="Development" >Development
										</label>
										<label class="radio-inline" for="activityTypeItem0">
											<input type="radio" name="activityType" id="activityTypeItem0" value="Humanitarian Relief" >Humanitarian Relief
										</label>
										<label class="radio-inline" for="activityTypeItem0">
											<input type="radio" name="activityType" id="activityTypeItem0" value="Recovery, Rehabilitation and Reconstruction" >Recovery, Rehabilitation and Reconstruction
										</label>
										</div>
										<!--<select class="form-control selectpicker" id="activityType" name="activityType" data-live-search="true">
											<?php //echo getSubClassificationDropdownOptions(3); ?>
											<option>Development</option>
											<option>Humanitarian Relief</option>
											<option>Recovery, Rehabilitation and Reconstruction</option>
										</select>-->
									</div>
								</fieldset>
								<fieldset id="activityOrganisations" class="tab-pane ">	
									<br />
									<div class="form-group row">
										<label for="focalPoint" class="col-sm-2 control-label col-form-label">Activity Focal Point:</label>
										<div class="col-sm-10">
										<input type="text" class="form-control" id="focalPoint"  name="focalPoint"  aria-describedby="focalPointHelpBlock">
										<p id="focalPointHelpBlock" class="form-text text-muted">
											Designated focal point, if any (may include contact information) for activity
										</p>
										</div>
									</div>
									<div class="form-group row" >
										<label for="fundingCurrency" class="col-sm-2 control-label col-form-label">Funding Currency:</label>
										<div class="col-sm-4">
										<select class="form-control selectpicker" id="fundingCurrency" name="fundingCurrency" aria-describedby="fundingCurrencyHelpBlock" data-live-search="true">
											<?php echo getCurrencyDropdownOptions(); ?>
										</select>
										<p id="fundingCurrencyHelpBlock" class="form-text text-muted">
											Funding currency
										</p>
										</div>
										<label for="fundingAmount" class="col-sm-2 control-label col-form-label">Funding Amount:</label>
										<div class="col-sm-4">
										<input type="number" class="form-control" id="fundingAmount"  name="fundingAmount" min="1" step="any" aria-describedby="fundingHelpBlock" >
										<p id="fundingHelpBlock" class="form-text text-muted">
											Total funding for the activity
										</p>
										</div>
									</div>
									<div class="form-group row">
										<label for="fundingStatus" class="col-sm-2 control-label col-form-label">Funding Status:</label>	
										<div class="col-sm-10">
										<label class="radio-inline" for="fundingStatusItem0">						<input type="radio" name="fundingStatus" id="fundingStatusItem0" value="Pledge" >Pledge</label>
										<label class="radio-inline" for="fundingStatusItem1">						<input type="radio" name="fundingStatus" id="fundingStatusItem1" value="Commitment" >Commitment</label>
										<label class="radio-inline" for="fundingStatusItem2">						<input type="radio" name="fundingStatus" id="fundingStatusItem2" value="Contribution" >Contribution</label>
										</div>
									</div>
									<div class="form-group row">
										<label for="fundingType" class="col-sm-2 control-label col-form-label">Funding Type:</label>
										<div class="col-sm-10">
										<label class="radio-inline" for="fundingTypeItem0">
											<input type="radio" name="fundingType" id="fundingTypeItem0" value="Assessed Contributions" >Assessed Contributions
										</label>
										<label class="radio-inline" for="fundingTypeItem1">
											<input type="radio" name="fundingType" id="fundingTypeItem1" value="Voluntary Contributions" >Voluntary Contributions
										</label>
										<label class="radio-inline" for="fundingTypeItem2">
											<input type="radio" name="fundingType" id="fundingTypeItem2" value="In-kind Donations" >In-kind Donations
										</label>
										<label class="radio-inline" for="fundingTypeItem3">
											<input type="radio" name="fundingType" id="fundingTypeItem3" value="Multi-Donor Trust Funds" >Multi-Donor Trust Funds
										</label>
										<label class="radio-inline" for="fundingTypeItem4">
											<input type="radio" name="fundingType" id="fundingTypeItem4" value="Bi-Lateral" >Bi-Lateral
										</label>
										<label class="radio-inline" for="fundingTypeItem5">
											<input type="radio" name="fundingType" id="fundingTypeItem5" value="Multi-Lateral" >Multi-Lateral
										</label>
										</div>
									</div>
									<div id="cloneableSec1" class="clonedInput">
									<fieldset class="form-group row">
									    <!--<legend>Organisations</legend>-->
										<h5 id="reference" name="reference" class="heading-reference">Organisation #1</h5>
										<label for="Orgs[Organization][0]" class="lbl_org col-sm-1 control-label col-form-label">Name:</label>
										<div class="col-sm-3" style="position: relative" >
										<input type="text" class="text_org form-control" name="Orgs[Organization][0]" placeholder="Organisation's official name" >
										</div>
										<label for="OrgType" class="lbl_orgtype col-sm-1 control-label col-form-label">Type:</label>
										<div class="col-sm-2">
										<select class="input_orgtype form-control selectpicker" name="Orgs[OrgType][]" data-live-search="true">
											<?php echo getOrganisationTypeDropdownOptions(); ?>
										</select>
										</div>
										<label for="OrgRole" class="lbl_orgrole col-sm-1 control-label col-form-label">Role:</label>
										<div class="col-sm-2">
										<select class="input_orgrole form-control selectpicker" name="Orgs[OrgRole][]" data-live-search="true">
											<option value="" selected="selected" disabled="disabled">Select Organisation Role</option>
											  <option value="Funding">Funding Organisation</option>
											  <!--<option value="Accountable">Accountable Organisation</option>-->
											  <option value="Extending">Extending Organisation</option>
											  <option value="Implementing">Implementation Partner</option>
											<?php //echo getOrganisationTypeDropdownOptions(); ?>
										</select>
										</div>
										<!--<div class="col-xs-1">
											<button type="button" id="btnAdd" name="btnAdd" class="btn btn-info">+ </button>
										</div>
										<div class="col-xs-1">
											<button type="button" id="btnDel" name="btnDel" class="btn btn-danger">- </button>
										</div>-->
										<!--<div class="btn-group">
										  <button type="button" id="btnAdd" name="btnAdd" class="btn btn-info">+ </button>
										  <button type="button" id="btnDel" name="btnDel" class="btn btn-danger">- </button>
										</div>-->
										<div class="col-sm-2 pull-left">
										  <button type="button" id="btnAdd" name="btnAdd" class="btn btn-success">+ </button>
										  <button type="button" id="btnDel" name="btnDel" class="btn btn-info">- </button>
										</div>
									</fieldset><!-- end form-row -->
									</div><!-- end #cloneableSec1 -->
									<!--
									<div class="form-group row">
										<label for="organization">Implementing Organization:</label>
										<input type="text" class="form-control" id="iOrganization"  name="iOrganization">	
									</div>
									<div class="form-group row">
										<label for="org-type">Implementing Organization Type:</label>
										<select class="form-control selectpicker" id="iOrgType" name="iOrgType" data-live-search="true">
											<?php //echo getOrganisationTypeDropdownOptions(); ?>
										</select>
									</div>
									<div class="form-group row">
										<label for="organization">Funding Organization:</label>
										<input type="text" class="form-control" id="fOrganization"  name="fOrganization">	
									</div>
									<div class="form-group row">
										<label for="org-type">Funding Organization Type:</label>
										<select class="form-control selectpicker" id="fOrgType" name="fOrgType" data-live-search="true">
										<?php //echo getOrganisationTypeDropdownOptions(); ?>
										</select>
									</div>-->
								</fieldset>
								<fieldset id="activityCoverage" class="tab-pane ">
									<br />
									<div class="form-group row">
										<label for="region" class="col-sm-1 control-label col-form-label">Region:</label>
										<div class="col-sm-3">
										<select multiple data-selected-text-format="values" data-actions-box="true" class="form-control selectpicker show-tick" id="region" name="region[]" data-live-search="true" data-header="Select Region(s)" title="Select Region(s)" aria-describedby="regionHelpBlock">
										<?php echo getRegionsDropdownOptions(); ?>
										</select>
										<p id="regionHelpBlock" class="form-text text-muted">
											You may select multiple regions covered by the activity
										</p>
										</div>
										<label for="district" class="col-sm-1 control-label col-form-label">District:</label>
										<div class="col-sm-3">
										<select multiple data-selected-text-format="values" data-actions-box="true" class="form-control selectpicker show-tick" id="district" name="district[]" data-live-search="true" data-header="Select District(s)" title="Select District(s)" aria-describedby="districtsHelpBlock">
											<?php //echo getDistrictDropdownOptions('Mbeya'); ?>
										</select>
										<p id="districtsHelpBlock" class="form-text text-muted">
											You may select multiple districts
										</p>
										</div>
										<label for="ward" class="col-sm-1 control-label col-form-label">Ward:</label>
										<div class="col-sm-3">
										<select multiple data-selected-text-format="values" data-actions-box="true"  class="form-control selectpicker" id="ward" name="ward[]" data-live-search="true" data-header="Select Ward(s)" title="Select Ward(s)" aria-describedby="wardsHelpBlock">
											<option>Bumbuta</option>
											<option>Pahi</option>
											<option>Busi</option>
											<option>Haubi</option>
											<option>Kalamba</option>
											<option>Kwadelo</option>
											<option>Mondo</option>
											<option>Dalai</option>
											<option>Jangalo</option>
											<option>Mrijo</option>
											<option>Chandama</option>
											<option>Goima</option>
											<option>Chemba</option>
											<option>Paranga</option>
											<option>Gwandi</option>
											<option>Farkwa</option>
											<option>Mpendo</option>
											<option>Sanzawa</option>
											<option>Kwamtoro</option>
											<option>Lalta</option>
											<option>Suruke</option>
											<option>Kingale</option>
											<option>Kondoa Mjini</option>
											<option>Kolo</option>
											<option>Changaa</option>
											<option>Thawi</option>
											<option>Mnenia</option>
											<option>Soera</option>
											<option>Masange</option>
											<option>Kikilo</option>
											<option>Bereko</option>
											<option>Kisese</option>
											<option>Kikore</option>
											<option>Makorongo</option>
											<option>Ovada</option>
											<option>Mazae</option>
											<option>Vin'ghawe</option>
											<option>Matomondo</option>
											<option>Kimagai</option>
											<option>Kibakwe</option>
											<option>Lumuma</option>
											<option>Luhundwa</option>
											<option>Massa</option>
											<option>Ipera</option>
											<option>Rudi</option>
											<option>Mlunduzi</option>
											<option>Wotta</option>
											<option>Mima</option>
											<option>Berege</option>
											<option>Chunyu</option>
											<option>Mbuga</option>
											<option>Godegode</option>
											<option>Mpwapwa Mjini</option>
											<option>Kongwa</option>
											<option>Sejeli</option>
											<option>Hogoro</option>
											<option>Zoissa</option>
											<option>Mkoka</option>
											<option>Njoge</option>
											<option>Mtanana</option>
											<option>Pandambili</option>
											<option>Mlali</option>
											<option>Iduo</option>
											<option>Sagara</option>
											<option>Kibaigwa</option>
											<option>Ugogoni</option>
											<option>Chamkoroma</option>
											<option>Haneti</option>
											<option>Itiso</option>
											<option>Segala</option>
											<option>Dabalo</option>
											<option>Membe</option>
											<option>Msanga</option>
											<option>Chilonwa</option>
											<option>Buigiri</option>
											<option>Majeleko</option>
											<option>Manchali</option>
											<option>Ikowa</option>
											<option>Msamalo</option>
											<option>Igandu</option>
											<option>Muungano</option>
											<option>Mvumi Makulu</option>
											<option>Handali</option>
											<option>Mvumi Mission</option>
											<option>Makang'wa</option>
											<option>Idifu</option>
											<option>Iringa Mvumi</option>
											<option>Manzase</option>
											<option>Fufu</option>
											<option>Mlowa Bwawani</option>
											<option>Mwitikira</option>
											<option>Mpwayungu</option>
											<option>Nghambaku</option>
											<option>Chinugulu</option>
											<option>Manda</option>
											<option>Huzi</option>
											<option>Mpalanga</option>
											<option>Chibelela</option>
											<option>Mtitaa</option>
											<option>Ibugule</option>
											<option>Nondwa</option>
											<option>Chali</option>
											<option>Chipanga</option>
											<option>Chikola</option>
											<option>Bahi</option>
											<option>Mpamantwa</option>
											<option>Ibihwa</option>
											<option>Kigwe</option>
											<option>Ilindi</option>
											<option>Makanda</option>
											<option>Lamaiti</option>
											<option>Mundemu</option>
											<option>Msisi</option>
											<option>Zanka</option>
											<option>Babayu</option>
											<option>Viwandani</option>
											<option>Uhuru</option>
											<option>Chamwino</option>
											<option>Kiwanja cha ndege</option>
											<option>Makole</option>
											<option>Miyuji</option>
											<option>Msalato</option>
											<option>Makutopora</option>
											<option>Chihanga</option>
											<option>Hombolo</option>
											<option>Ipala</option>
											<option>Nzuguni</option>
											<option>Dodoma Makulu</option>
											<option>Mtumba</option>
											<option>Kikombo</option>
											<option>Ng'hong'ona</option>
											<option>Mpunguzi</option>
											<option>Tambukareli</option>
											<option>Kilimani</option>
											<option>Kikuyu South</option>
											<option>Kikuyu North</option>
											<option>Mkonze</option>
											<option>Mbabala</option>
											<option>Zuzu</option>
											<option>Hazina</option>
											<option>Madukani</option>
											<option>Majengo</option>
											<option>Kizota</option>
											<option>Nala</option>
											<option>Mbalawala</option>
											<option></option>
											<option>Monduli Mjini</option>
											<option>Engutoto</option>
											<option>Monduli Juu</option>
											<option>Sepeko</option>
											<option>Lolkisale</option>
											<option>Moita</option>
											<option>Makuyuni</option>
											<option>Esilalei</option>
											<option>Mto wa Mbu</option>
											<option>Selela</option>
											<option>Engaruka</option>
											<option>Kitumbeine</option>
											<option>Gelai Meirugoi</option>
											<option>Gelai Lumbwa</option>
											<option>Engarenaibor</option>
											<option>Matale</option>
											<option>Namanga</option>
											<option>Longido</option>
											<option>Tingatinga</option>
											<option>Ol -molog</option>
											<option>Oldonyosambu</option>
											<option>Ngarenanyuki</option>
											<option>Leguruki</option>
											<option>King'ori</option>
											<option>Kikatiti</option>
											<option>Maroroni</option>
											<option>Makiba</option>
											<option>Mbuguni</option>
											<option>Bwawani</option>
											<option>Kikwe</option>
											<option>Maji ya chai</option>
											<option>USA river</option>
											<option>Nkoaranga</option>
											<option>Songoro</option>
											<option>Poli</option>
											<option>Singisi</option>
											<option>Akheri</option>
											<option>Nkoarisambu</option>
											<option>Nkanrua</option>
											<option>Moshono</option>
											<option>Mlangarini</option>
											<option>Nduruma</option>
											<option>Oljoro</option>
											<option>Murieti</option>
											<option>Mateves</option>
											<option>Kisongo</option>
											<option>Kiranyi</option>
											<option>Kimnyaki</option>
											<option>Moivo</option>
											<option>Oltroto</option>
											<option>Sokoni II</option>
											<option>Oltrumet</option>
											<option>Musa</option>
											<option>Mwandeti</option>
											<option>Olkokola</option>
											<option>Ilkiding'a</option>
											<option>Bangata</option>
											<option>Kati</option>
											<option>Kaloleni</option>
											<option>Sekei</option>
											<option>Kimandolu</option>
											<option>Baraa</option>
											<option>Oloirien</option>
											<option>Themi</option>
											<option>Lemara</option>
											<option>Terrat</option>
											<option>Sokon I</option>
											<option>Daraja Mbili</option>
											<option>Unga Ltd</option>
											<option>Sombetini</option>
											<option>Ngarenaro</option>
											<option>Levolosi</option>
											<option>Elerae</option>
											<option>Karatu</option>
											<option>Endamarariek</option>
											<option>Buger</option>
											<option>Endabash</option>
											<option>Kansay</option>
											<option>Baray</option>
											<option>Mang'ola</option>
											<option>Daa</option>
											<option>Oldeani</option>
											<option>Qurus</option>
											<option>Ganako</option>
											<option>Rhotia</option>
											<option>Mbulumbulu</option>
											<option>Orgosorok</option>
											<option>Digodigo</option>
											<option>Oldonyo - Sambu</option>
											<option>Pinyinyi</option>
											<option>Sale</option>
											<option>Malambo</option>
											<option>Nayobi</option>
											<option>Nainokanoka</option>
											<option>Olbalbal</option>
											<option>Ngorongoro</option>
											<option>Enduleni</option>
											<option>Kakesio</option>
											<option>Arash</option>
											<option>Soit Sambu</option>
											<option>Mamsera</option>
											<option>Mahida/Holili</option>
											<option>Mengwe Manda</option>
											<option>Keni/Mengeni</option>
											<option>Keni/Aleni</option>
											<option>Shimbi</option>
											<option>Makiidi</option>
											<option>Kelamfua/Mokala</option>
											<option>Ushiri/Ikuini</option>
											<option>Mrao Keryo</option>
											<option>Kirwa/Keni</option>
											<option>Katangara/Mrere</option>
											<option>Kisale Masangara</option>
											<option>Olele</option>
											<option>Kirongo/Samanga</option>
											<option>Kitirima/Kingachi</option>
											<option>Ubetu kahe</option>
											<option>Nanjala Reha</option>
											<option>Tarakea Motamburu</option>
											<option>Motamburu kitendeni</option>
											<option>Kileo</option>
											<option>Mwanga</option>
											<option>Msangeni</option>
											<option>Kifula</option>
											<option>Kighare</option>
											<option>Kirongwe</option>
											<option>Kwakoa</option>
											<option>Lembeni</option>
											<option>Jipe</option>
											<option>Mwaniko</option>
											<option>Chomvu</option>
											<option>Ngujini</option>
											<option>Kirya</option>
											<option>Kilomeni</option>
											<option>Shighatini</option>
											<option>Lang'ata</option>
											<option>Same - Mjini</option>
											<option>Ruvu</option>
											<option>Njoro</option>
											<option>Kisiwani</option>
											<option>Msindo</option>
											<option>Mshewa</option>
											<option>Mhezi</option>
											<option>Mwembe</option>
											<option>Vudee</option>
											<option>Vuje</option>
											<option>Bombo</option>
											<option>Mtii</option>
											<option>Maore</option>
											<option>Ndungu</option>
											<option>Kihurio</option>
											<option>Bendera</option>
											<option>Myamba</option>
											<option>Mpinji</option>
											<option>Bwambo</option>
											<option>Vunta</option>
											<option>Chome</option>
											<option>Suji</option>
											<option>Makanya</option>
											<option>Hedaru</option>
											<option>Kirangare</option>
											<option>Mwika Kusini</option>
											<option>Mwika kaskazini</option>
											<option>Mamba kaskazini</option>
											<option>Mamba kusini</option>
											<option>Marangu Mashariki</option>
											<option>Marangu Magharibi</option>
											<option>Kilema Kaskazini</option>
											<option>Kilema Kusini</option>
											<option>Kirua Vunjo Mashariki</option>
											<option>Kirua Vunjo Magharibi</option>
											<option>Kahe</option>
											<option>Kahe Mashariki</option>
											<option>Old Moshi East</option>
											<option>Old moshi West</option>
											<option>Mbokomu</option>
											<option>Uru Mashariki</option>
											<option>Uru Shimbwe</option>
											<option>Uru South Mawela</option>
											<option>Uru Kaskazini</option>
											<option>Mabogini</option>
											<option>Arusha Chini</option>
											<option>Kibosho Mashariki</option>
											<option>Kibosho Kati</option>
											<option>Kibosho Magharibi</option>
											<option>Kindi</option>
											<option>Kirua Vunjo Kusini</option>
											<option>Kirima</option>
											<option>Okaoni Kibosho</option>
											<option>Kimochi</option>
											<option>Kilema Kati</option>
											<option>Machame Mashariki</option>
											<option>Machame Kusini</option>
											<option>Machame Kaskazini</option>
											<option>Machame Magharibi</option>
											<option>Machame Uroki</option>
											<option>Masama Mashariki</option>
											<option>Masama Magharibi</option>
											<option>Masama Kusini</option>
											<option>Siha Mashariki</option>
											<option>Siha Kati</option>
											<option>Siha Magharibi</option>
											<option>Masama Rundugai</option>
											<option>Hai Mjini</option>
											<option>Siha Kaskazini</option>
											<option>Kilimanjaro</option>
											<option>Mji Mpya</option>
											<option>Mawenzi</option>
											<option>Rau</option>
											<option>Korongoni</option>
											<option>Kiusa</option>
											<option>Bondeni</option>
											<option>Pasua</option>
											<option>Kiboriloni</option>
											<option>Msaranga</option>
											<option>Karanga</option>
											<option>Longuo</option>
											<option>Lushoto</option>
											<option>Gare</option>
											<option>Kwai</option>
											<option>Ubiri</option>
											<option>Soni</option>
											<option>Vuga</option>
											<option>Mponde</option>
											<option>Mamba</option>
											<option>Mbuzii</option>
											<option>Tamota</option>
											<option>Bumbuli</option>
											<option>Funta</option>
											<option>Mayo</option>
											<option>Baga</option>
											<option>Milingano</option>
											<option>Mgwashi</option>
											<option>Mtae</option>
											<option>Sunga</option>
											<option>Rangwi</option>
											<option>Mnazi</option>
											<option>Lunguza</option>
											<option>Mbaramo</option>
											<option>Mng'aro</option>
											<option>Mlalo</option>
											<option>Mwangoi</option>
											<option>Shume</option>
											<option>Malindi</option>
											<option>Hemtoye</option>
											<option>Malibwi</option>
											<option>Mlola</option>
											<option>Ngwelo</option>
											<option>Mashewa</option>
											<option>Kizara</option>
											<option>Magoma</option>
											<option>Kerenge</option>
											<option>Kwamndolwa</option>
											<option>Kwagunda</option>
											<option>Mnyuzi</option>
											<option>Korogwe</option>
											<option>Ngombezi</option>
											<option>Msambiazi</option>
											<option>Vugiri</option>
											<option>Dindira</option>
											<option>Bungu</option>
											<option>Lutindi</option>
											<option>Chekelei</option>
											<option>Mombo</option>
											<option>Mkalamo</option>
											<option>Mazinde</option>
											<option>Mkomazi</option>
											<option>Kilulu</option>
											<option>Mkuzi</option>
											<option>Mtindiro</option>
											<option>Lusanga</option>
											<option>Kicheba</option>
											<option>Magila</option>
											<option>Magoroto</option>
											<option>Misalai</option>
											<option>Maramba</option>
											<option>Daluni</option>
											<option>Kigongoi</option>
											<option>Gombero</option>
											<option>Ngomeni</option>
											<option>Kigombe</option>
											<option>Pande</option>
											<option>Bwembwera</option>
											<option>Nkumba</option>
											<option>Songa</option>
											<option>Potwe</option>
											<option>Mkinga</option>
											<option>Duga</option>
											<option>Mwakijembe</option>
											<option>Kwale</option>
											<option>Mtimbwani</option>
											<option>Moa</option>
											<option>Masuguru</option>
											<option>Misozwe</option>
											<option>Manza</option>
											<option>Mhinduro</option>
											<option>Zirai</option>
											<option>Kwafungo</option>
											<option>Tingeni</option>
											<option>Central</option>
											<option>Nguvumali</option>
											<option>Chumbageni</option>
											<option>Ngamiani Kaskazini</option>
											<option>Ngamiani Kati</option>
											<option>Ngamiani Kusini</option>
											<option>Usagara</option>
											<option>Makorora</option>
											<option>Mzingani</option>
											<option>Msambweni</option>
											<option>Mwanzange</option>
											<option>Tangasisi</option>
											<option>Mabawa</option>
											<option>Tongoni</option>
											<option>Marungu</option>
											<option>Pongwe</option>
											<option>Maweni</option>
											<option>Mzizima</option>
											<option>Mabokweni</option>
											<option>Kirare</option>
											<option>Kiomoni</option>
											<option>Chongoleani</option>
											<option>Pangani Mashariki</option>
											<option>Pangani Magharibi</option>
											<option>Bweni</option>
											<option>Madanga</option>
											<option>Kimang'a</option>
											<option>Bushiri</option>
											<option>Mwera</option>
											<option>Tungamaa</option>
											<option>Kipumbwi</option>
											<option>Mikinguni</option>
											<option>Ubangaa</option>
											<option>Mkwaja</option>
											<option>Segera</option>
											<option>Ndolwa</option>
											<option>Mazingara</option>
											<option>Kwamsisi</option>
											<option>Kwasunga</option>
											<option>Kwaluguru</option>
											<option>Kang'ata</option>
											<option>Kwankonje</option>
											<option>Vibaoni</option>
											<option>Sindeni</option>
											<option>Misima</option>
											<option>Kiva</option>
											<option>Kabuku</option>
											<option>Kwamatuku</option>
											<option>Kwedizinga</option>
											<option>Mgambo</option>
											<option>Komkonga</option>
											<option>Mkata</option>
											<option>Chanika</option>
											<option>Lwande</option>
											<option>Kikunde</option>
											<option>Songe</option>
											<option>Pagwi</option>
											<option>Masagalu</option>
											<option>Kimbe</option>
											<option>Kilindi</option>
											<option>Negero</option>
											<option>Mkindi</option>
											<option>Mvungwe</option>
											<option>Kwediboma</option>
											<option>Saunyi</option>
											<option>Jaila</option>
											<option>Msanja</option>
											<option>Kisangasa</option>
											<option>Chakwale</option>
											<option>Iyogwe</option>
											<option>Berega</option>
											<option>Magubike</option>
											<option>Mamboya</option>
											<option>Dumila</option>
											<option>Magole</option>
											<option>Msowero</option>
											<option>Rudewa</option>
											<option>Chanzuru</option>
											<option>Kimamba 'A'</option>
											<option>Kimamba 'B'</option>
											<option>Mbumi</option>
											<option>Mkwatani</option>
											<option>Magomeni</option>
											<option>Kasiki</option>
											<option>Mabwerebwere</option>
											<option>Kilangali</option>
											<option>Mikumi</option>
											<option>Ruhembe</option>
											<option>Kidodi</option>
											<option>Vidunda</option>
											<option>Malolo</option>
											<option>Kisanga</option>
											<option>Uleling'ombe</option>
											<option>Ulaya</option>
											<option>Zombo</option>
											<option>Masanze</option>
											<option>Kidete</option>
											<option>Lubuji</option>
											<option>Chanjale</option>
											<option>Chagongwe</option>
											<option>Mandege</option>
											<option>Rubeho</option>
											<option>Gairo</option>
											<option>Kibedya</option>
											<option>Kasanga</option>
											<option>Kolero</option>
											<option>Mvuha</option>
											<option>Selembala</option>
											<option>Bwakila Chini</option>
											<option>Bwakila Juu</option>
											<option>Kisaki</option>
											<option>Mngazi</option>
											<option>Singisa</option>
											<option>Mkambalani</option>
											<option>Mikese</option>
											<option>Kidugalo</option>
											<option>Mkulazi</option>
											<option>Ngerengere</option>
											<option>Tununguo</option>
											<option>Kinole</option>
											<option>Kiroka</option>
											<option>Mkuyuni</option>
											<option>Tegetero</option>
											<option>Kibogwa</option>
											<option>Kibungo Juu</option>
											<option>Kisemu</option>
											<option>Lundi</option>
											<option>Mtombozi</option>
											<option>Tawa</option>
											<option>Kidatu</option>
											<option>Sanje</option>
											<option>Mkula</option>
											<option>Mang'ula</option>
											<option>Kisawasawa</option>
											<option>Kiberege</option>
											<option>Kibaoni</option>
											<option>Ifakara</option>
											<option>Lumelo</option>
											<option>Idete</option>
											<option>Mbingu</option>
											<option>Mofu</option>
											<option>Mchombe</option>
											<option>Chita</option>
											<option>Chisano</option>
											<option>Mlimba</option>
											<option>Utengule</option>
											<option>Masagati</option>
											<option>Uchindile</option>
											<option>Minepa</option>
											<option>Lupiro</option>
											<option>Kichangani</option>
											<option>Msogezi</option>
											<option>Vigoi</option>
											<option>Mahenge</option>
											<option>Isongo</option>
											<option>Ruaha</option>
											<option>Chirombola</option>
											<option>Sali</option>
											<option>Euga</option>
											<option>Mwaya</option>
											<option>Lukande</option>
											<option>Ilonga</option>
											<option>Kilosa Mpepo</option>
											<option>Ngoheranga</option>
											<option>Biro</option>
											<option>Malinyi</option>
											<option>Sofi</option>
											<option>Usangule</option>
											<option>Mtimbira</option>
											<option>Itete</option>
											<option>Iragua</option>
											<option>Sabasaba</option>
											<option>Uwanja wa Taifa</option>
											<option>Uwanja wa ndege</option>
											<option>Kingo</option>
											<option>Mji mkuu</option>
											<option>Sultan Area</option>
											<option>Mafiga</option>
											<option>Mazimbu</option>
											<option>Mwembesongo</option>
											<option>Kilakala</option>
											<option>Boma</option>
											<option>Mlimani</option>
											<option>Mbuyuni</option>
											<option>Kingolwira</option>
											<option>Bigwa</option>
											<option>Mzinga</option>
											<option>Kihonda</option>
											<option>Mvomero</option>
											<option>Hembeti</option>
											<option>Maskati</option>
											<option>Kibati</option>
											<option>Sungaji</option>
											<option>Mhonda</option>
											<option>Diongoya</option>
											<option>Mtibwa</option>
											<option>Kanga</option>
											<option>Bunduki</option>
											<option>Kikeo</option>
											<option>Langali</option>
											<option>Tchenzema</option>
											<option>Mzumbe</option>
											<option>Doma</option>
											<option>Melela</option>
											<option>Kiwangwa</option>
											<option>Msata</option>
											<option>Miono</option>
											<option>Mkange</option>
											<option>Dunda</option>
											<option>Kiromo</option>
											<option>Zinga</option>
											<option>Yombo</option>
											<option>Vigwaza</option>
											<option>Talawanda</option>
											<option>Chalinze</option>
											<option>Lugoba</option>
											<option>Ubenazomozi</option>
											<option>Mbwewe</option>
											<option>Kibindu</option>
											<option>Tumbi</option>
											<option>Kibaha</option>
											<option>Magindu</option>
											<option>Soga</option>
											<option>Visiga</option>
											<option>Mlandizi</option>
											<option>Kwala</option>
											<option>Maili Moja</option>
											<option>Kisarawe</option>
											<option>Msimbu</option>
											<option>Masaki</option>
											<option>Kibuta</option>
											<option>Marumbo</option>
											<option>Maneromango</option>
											<option>Marui</option>
											<option>Cholesamvula</option>
											<option>Vikumbulu</option>
											<option>Mafinzi</option>
											<option>Kuruhi</option>
											<option>Mzenga</option>
											<option>Vihingo</option>
											<option>Kiluvya</option>
											<option>Mkuranga</option>
											<option>Tambani</option>
											<option>Vikindu</option>
											<option>Mbezi</option>
											<option>Shungubweni</option>
											<option>Kisiju</option>
											<option>Magawa</option>
											<option>Kitomondo</option>
											<option>Lukanga</option>
											<option>Nyamato</option>
											<option>Kimanzichana</option>
											<option>Mkamba</option>
											<option>Panzuo</option>
											<option>Bupu</option>
											<option>Mwalusembe</option>
											<option>Ikwiriri</option>
											<option>Mgomba</option>
											<option>Umwe</option>
											<option>Utete</option>
											<option>Mkongo</option>
											<option>Ngorongo</option>
											<option>Mwaseni</option>
											<option>Kibiti</option>
											<option>Mahege</option>
											<option>Mchukwi</option>
											<option>Chumbi</option>
											<option>Mbwara</option>
											<option>Mtunda</option>
											<option>Ruaruke</option>
											<option>Salale</option>
											<option>Mbuchi</option>
											<option>Kiongoroni</option>
											<option>Maparoni</option>
											<option>Baleni</option>
											<option>Kilindoni</option>
											<option>Mibulani</option>
											<option>Kiegeani</option>
											<option>Jibondo</option>
											<option>Makurumla</option>
											<option>Ndugumbi</option>
											<option>Tandale</option>
											<option>Mwananyamala</option>
											<option>Msasani</option>
											<option>Kinondoni</option>
											<option>Mzimuni</option>
											<option>Kigogo</option>
											<option>Mabibo</option>
											<option>Manzese</option>
											<option>Ubungo</option>
											<option>Kibamba</option>
											<option>Goba</option>
											<option>Kawe</option>
											<option>Kunduchi</option>
											<option>Mbweni</option>
											<option>Bunju</option>
											<option>Makuburi</option>
											<option>Mburahati</option>
											<option>Makumbusho</option>
											<option>Sinza</option>
											<option>Kijito Nyama</option>
											<option>Kimara</option>
											<option>Mikocheni</option>
											<option>Hananasif</option>
											<option>Ukonga</option>
											<option>Pugu</option>
											<option>Msongola</option>
											<option>Tabata</option>
											<option>Kinyerezi</option>
											<option>Ilala</option>
											<option>Mchikichini</option>
											<option>Vingunguti</option>
											<option>Kipawa</option>
											<option>Buguruni</option>
											<option>Kariakoo</option>
											<option>Jangwani</option>
											<option>Gerezani</option>
											<option>Kisutu</option>
											<option>Mchafukoge</option>
											<option>Upanga Mashariki</option>
											<option>Upanga Magharibi</option>
											<option>Kivukoni</option>
											<option>Kiwalani</option>
											<option>Segerea</option>
											<option>Kitunda</option>
											<option>Kigamboni</option>
											<option>Vijibweni</option>
											<option>Kibada</option>
											<option>Kisarawe II</option>
											<option>Somangira</option>
											<option>Kimbiji</option>
											<option>Mbagala</option>
											<option>Chamazi</option>
											<option>Yombo Vituka</option>
											<option>Charambe</option>
											<option>Toangoma</option>
											<option>Miburani</option>
											<option>Temeke</option>
											<option>Mtoni</option>
											<option>Keko</option>
											<option>Kurasini</option>
											<option>Azimio</option>
											<option>Tandika</option>
											<option>Sandali</option>
											<option>Chang'ombe</option>
											<option>Mbagala Kuu</option>
											<option>Makangarawe</option>
											<option>Pemba Mnazi</option>
											<option>Mji mwema</option>
											<option>Tingi</option>
											<option>Miteja</option>
											<option>Mingumbi</option>
											<option>Kinjumbi</option>
											<option>Chumo</option>
											<option>Kipatimu</option>
											<option>Kandawale</option>
											<option>Njinjo</option>
											<option>Mitole</option>
											<option>Miguruwe</option>
											<option>Likawage</option>
											<option>Nanjirinji</option>
											<option>Kiranjeranje</option>
											<option>Mandawa</option>
											<option>Lihimalyao</option>
											<option>Kikole</option>
											<option>Kivinje/Singino</option>
											<option>Songosongo</option>
											<option>Masoko</option>
											<option>Mipingo</option>
											<option>Kitomanga</option>
											<option>Mchinga</option>
											<option>Kilolambwani</option>
											<option>Mbanja</option>
											<option>Kiwalala</option>
											<option>Mingoyo</option>
											<option>Mnolela</option>
											<option>Sudi</option>
											<option>Nachunyu</option>
											<option>Mtama</option>
											<option>Nyangao</option>
											<option>Namupa</option>
											<option>Nyengedi</option>
											<option>Mtua</option>
											<option>Nahukahuka</option>
											<option>Ngangamara</option>
											<option>Mandwanga</option>
											<option>Mnara</option>
											<option>Chiponda</option>
											<option>Ng`apa</option>
											<option>Tandangongoro</option>
											<option>Rutamba</option>
											<option>Milola</option>
											<option>Kiwawa</option>
											<option>Chikonji</option>
											<option>Matimba</option>
											<option>Nangaru</option>
											<option>Nambambo</option>
											<option>Kilimani hewa</option>
											<option>Ruponda</option>
											<option>Mnero</option>
											<option>Namapwia</option>
											<option>Kipara Mnero</option>
											<option>Lionja</option>
											<option>Namikango</option>
											<option>Nditi</option>
											<option>Kilima Rondo</option>
											<option>Mbondo</option>
											<option>Kiegei</option>
											<option>Chiola</option>
											<option>Mpiruka</option>
											<option>Nangowe</option>
											<option>Mkotokuyana</option>
											<option>Naipanga</option>
											<option>Stesheni</option>
											<option>Naipingo</option>
											<option>Mnero Ngongo</option>
											<option>Matekwe</option>
											<option>Marambo</option>
											<option>Namatula</option>
											<option>Ndomoni</option>
											<option>Liwale mjini</option>
											<option>Mihumo</option>
											<option>Ngongowele</option>
											<option>Mlembwe</option>
											<option>Makata</option>
											<option>Barikiwa</option>
											<option>Mkutano</option>
											<option>Mbaya</option>
											<option>Kimambi</option>
											<option>Kiang'ara</option>
											<option>Ndumbu</option>
											<option>Nangano</option>
											<option>Mpigamiti</option>
											<option>Mirui</option>
											<option>Liwale 'B'</option>
											<option>Mangirikiti</option>
											<option>Ruangwa</option>
											<option>Mbekenyera</option>
											<option>Nkowe</option>
											<option>Luchelegwa</option>
											<option>Chienjere</option>
											<option>Namichiga</option>
											<option>Narungombe</option>
											<option>Makanjiro</option>
											<option>Likunja</option>
											<option>Mnacho</option>
											<option>Nambilanje</option>
											<option>Q</option>
											<option>Mandarawe</option>
											<option>Ndoro</option>
											<option>Makonde</option>
											<option>Mikumbi</option>
											<option>Mitandi</option>
											<option>Rahaleo</option>
											<option>Mwenge</option>
											<option>Matopeni</option>
											<option>Wireless</option>
											<option>Nachingwea</option>
											<option>Rasbura</option>
											<option>Mtanda</option>
											<option>Jamhuri</option>
											<option>Msinjahili</option>
											<option>Madimba</option>
											<option>Ziwani</option>
											<option>Nanguruwe</option>
											<option>Mahurunga</option>
											<option>Kitaya</option>
											<option>Kiromba</option>
											<option>Njengwa</option>
											<option>Nitekela</option>
											<option>Nanyamba</option>
											<option>Mtiniko</option>
											<option>Dihimba</option>
											<option>Mnima</option>
											<option>Kitere</option>
											<option>Ndumbwe</option>
											<option>Mayanga</option>
											<option>Naumbu</option>
											<option>Chawi</option>
											<option>Namtumbuka</option>
											<option>Luchingu</option>
											<option>Makote</option>
											<option>Mkunya</option>
											<option>Mcholi I</option>
											<option>Namiyonga</option>
											<option>Mnekachi</option>
											<option>Chitekete</option>
											<option>Mnyambe</option>
											<option>Chilangala</option>
											<option>Mkoma II</option>
											<option>Kitangari</option>
											<option>Malatu</option>
											<option>Mchemo</option>
											<option>Mtopwa</option>
											<option>Chiwonga</option>
											<option>Maputi</option>
											<option>Makukwe</option>
											<option>Mkwedu</option>
											<option>Mcholi II</option>
											<option>Masasi</option>
											<option>Lisekese</option>
											<option>Marika</option>
											<option>Mpindimbi</option>
											<option>Lukuledi</option>
											<option>Namatutwe</option>
											<option>Mikangaula</option>
											<option>Maratani</option>
											<option>Nandete</option>
											<option>Chiwata</option>
											<option>Chigugu</option>
											<option>Mwena</option>
											<option>Nanganga</option>
											<option>Napacho</option>
											<option>Lumesule</option>
											<option>Likokona</option>
											<option>Mkonona</option>
											<option>Nanyumbu</option>
											<option>Nangomba</option>
											<option>Lipumburu</option>
											<option>Chiungutwa</option>
											<option>Nanjota</option>
											<option>Lulindi</option>
											<option>Namalenga</option>
											<option>Mkundi</option>
											<option>Mkululu</option>
											<option>Sindano</option>
											<option>Mchauru</option>
											<option>Mnavira</option>
											<option>Namajani</option>
											<option>Chipuputa</option>
											<option>Sengenya</option>
											<option>Tandahimba</option>
											<option>Kitama</option>
											<option>Michenjele</option>
											<option>Mihambwe</option>
											<option>Mkoreha</option>
											<option>Maundo</option>
											<option>Naputa</option>
											<option>Namikupa</option>
											<option>Mnyawa</option>
											<option>Lukokoda</option>
											<option>Mahuta</option>
											<option>Nanhyanga</option>
											<option>Chingungwe</option>
											<option>Mdimba Mnyoma</option>
											<option>Milongodi</option>
											<option>Lyenje</option>
											<option>Chaume</option>
											<option>Mkonojowano</option>
											<option>Luagala</option>
											<option>Ngunja</option>
											<option>Mkwiti</option>
											<option>Chikongola</option>
											<option>Likombe</option>
											<option>Railway</option>
											<option>Shangani</option>
											<option>Vigaeni</option>
											<option>Chuno</option>
											<option>Kisungure</option>
											<option>Mitengo</option>
											<option>Mtonya</option>
											<option>Ufukoni</option>
											<option>Magengeni</option>
											<option>Kalulu</option>
											<option>Ligunga</option>
											<option>M/Mashariki</option>
											<option>Mindu</option>
											<option>Ngapa</option>
											<option>Nakapanya</option>
											<option>Muhuwesi</option>
											<option>Tuwemacho</option>
											<option>Ligoma</option>
											<option>Misechela</option>
											<option>Namasakata</option>
											<option>Mtina</option>
											<option>Mchesi</option>
											<option>Lukumbule</option>
											<option>Nalasi</option>
											<option>Mchoteka</option>
											<option>Marumba</option>
											<option>Mbesa</option>
											<option>Mlingoti Magharibi</option>
											<option>Kidodoma</option>
											<option>Nandembo</option>
											<option>Nampungu</option>
											<option>Matemanga</option>
											<option>Namwinyu</option>
											<option>Wino</option>
											<option>Ndongosi</option>
											<option>Ifinga</option>
											<option>Tanga</option>
											<option>Gumbiro</option>
											<option>Mpitimbi</option>
											<option>Muhukuru</option>
											<option>Magagura</option>
											<option>Litisha</option>
											<option>Kilagano</option>
											<option>Maposeni</option>
											<option>Lilambo</option>
											<option>Mahanje</option>
											<option>Matimira</option>
											<option>Ruanda</option>
											<option>Litumbandyosi</option>
											<option>Kigonsera</option>
											<option>Kihangi Mahuka</option>
											<option>Utiri</option>
											<option>Mbinga Urban</option>
											<option>Mbangamao</option>
											<option>Liparamba</option>
											<option>Chiwanda</option>
											<option>Mtipwili</option>
											<option>Mbamba bay</option>
											<option>Kingerikiti</option>
											<option>Nyoni</option>
											<option>Lipingo</option>
											<option>Maguu</option>
											<option>Liuli</option>
											<option>Kihagara</option>
											<option>Mikalanga</option>
											<option>Langiro</option>
											<option>Mbuji</option>
											<option>Litembo</option>
											<option>Ngima</option>
											<option>Myangayanga</option>
											<option>Mkumbi</option>
											<option>Linda</option>
											<option>Matiri</option>
											<option>Ukata</option>
											<option>Ngumbo</option>
											<option>Mbaha</option>
											<option>Mpepai</option>
											<option>Kilosa</option>
											<option>Mpapa</option>
											<option>Kitura</option>
											<option>Lituhi</option>
											<option>Mjini</option>
											<option>Misufini</option>
											<option>Mfaranyaki</option>
											<option>Lizaboni</option>
											<option>Matarawe</option>
											<option>Bombambili</option>
											<option>Matogoro</option>
											<option>Ruvuma</option>
											<option>Subira</option>
											<option>Ruhuwiko</option>
											<option>Mshangano</option>
											<option>Mletele</option>
											<option>Rwinga</option>
											<option>Ligera</option>
											<option>Lusewa</option>
											<option>Magazini</option>
											<option>Luchili</option>
											<option>Namabengo</option>
											<option>Kitanda</option>
											<option>Luegu</option>
											<option>Namtumbo</option>
											<option>Mgombasi</option>
											<option>Kalenga</option>
											<option>Kiwere</option>
											<option>Dutwa</option>
											<option>Nzihi</option>
											<option>Ulanda</option>
											<option>Mseke</option>
											<option>Magulilwa</option>
											<option>Mgama</option>
											<option>Ifunda</option>
											<option>Lumuli</option>
											<option>Maboga</option>
											<option>Wasa</option>
											<option>Mahuninga</option>
											<option>Idodi</option>
											<option>Mlowa</option>
											<option>Itunundu</option>
											<option>Ilolompya</option>
											<option>Nduli</option>
											<option>Kihorogota</option>
											<option>Izazi</option>
											<option>Malengamakali</option>
											<option>Kiyowela</option>
											<option>Makungu</option>
											<option>Mninga</option>
											<option>Igowole</option>
											<option>Mtambula</option>
											<option>Itandula</option>
											<option>Mbalamaziwa</option>
											<option>Idunda</option>
											<option>Malangali</option>
											<option>Nyololo</option>
											<option>Ihowanza</option>
											<option>Ikweha</option>
											<option>Sadani</option>
											<option>Igombavanu</option>
											<option>Bumilanga</option>
											<option>Mafinga</option>
											<option>Isalavanu</option>
											<option>Rungemba</option>
											<option>Ifwagi</option>
											<option>Mdabulo</option>
											<option>Ihalimba</option>
											<option>Kibengu</option>
											<option>Mapanda</option>
											<option>Mpanga tazara</option>
											<option>Ihanu</option>
											<option>Luhunga</option>
											<option>Mtwango</option>
											<option>Lupalilo</option>
											<option>Iwawa</option>
											<option>Mang'oto</option>
											<option>Lupila</option>
											<option>Ukwama</option>
											<option>Bulongwa</option>
											<option>Kipagalo</option>
											<option>Iniho</option>
											<option>Ipelele</option>
											<option>Kigulu</option>
											<option>Matamba</option>
											<option>Mlondwe</option>
											<option>Kitulo</option>
											<option>Ikuwo</option>
											<option>Mfumbi</option>
											<option>Ipepo</option>
											<option>Mbalatse</option>
											<option>Njombe Mjini</option>
											<option>Imalinyi</option>
											<option>Igosi</option>
											<option>Wangama</option>
											<option>Wanging'ombe</option>
											<option>Saja</option>
											<option>Ilembula</option>
											<option>Luduga</option>
											<option>Makambako</option>
											<option>Mahongole</option>
											<option>Igongolo</option>
											<option>Ikuka</option>
											<option>Mdandu</option>
											<option>Usuka</option>
											<option>Lusuka</option>
											<option>Lupembe</option>
											<option>Kidegembye</option>
											<option>Ikondo</option>
											<option>Idamba</option>
											<option>Uwemba</option>
											<option>Iwungilo</option>
											<option>Luponde</option>
											<option>Matola</option>
											<option>Kifanya</option>
											<option>Yakobi</option>
											<option>Lumbila</option>
											<option>Kilondo</option>
											<option>Mawengi</option>
											<option>Lupanga</option>
											<option>Mlangali</option>
											<option>Milo</option>
											<option>Lugarawa</option>
											<option>Madope</option>
											<option>Madilu</option>
											<option>Mundindi</option>
											<option>Mavanga</option>
											<option>Ibumi</option>
											<option>Nkomang'ombe</option>
											<option>Luilo</option>
											<option>Iwela</option>
											<option>Lupingu</option>
											<option>Ludewa</option>
											<option>Ludende</option>
											<option>Luana</option>
											<option>Kihesa</option>
											<option>Mtwivila</option>
											<option>Gangilonga</option>
											<option>Kitanzini</option>
											<option>Mshindo</option>
											<option>Mivinjeni</option>
											<option>Mlandege</option>
											<option>Mwangata</option>
											<option>Kwakilosa</option>
											<option>Makorongoni</option>
											<option>Mkwawa</option>
											<option>Kitwiru</option>
											<option>Image</option>
											<option>Irole</option>
											<option>Ilula</option>
											<option>Uhambingeto</option>
											<option>Udekwa</option>
											<option>Mtitu</option>
											<option>Dabaga</option>
											<option>Ukumbi</option>
											<option>Ukwega</option>
											<option>Boma la Ng'ombe</option>
											<option>Kambikatoto</option>
											<option>Mafyeko</option>
											<option>Matwiga</option>
											<option>Mtanila</option>
											<option>L/ tingatinga</option>
											<option>Luwalaje</option>
											<option>Makongorosi</option>
											<option>Mkongorosi</option>
											<option>Itewe</option>
											<option>Chokaa</option>
											<option>Mbugani</option>
											<option>Chalangwa</option>
											<option>Ifumbo</option>
											<option>Galula</option>
											<option>Totowe</option>
											<option>Namkukwe</option>
											<option>Mkwajuni</option>
											<option>Mbangala</option>
											<option>Kapalala</option>
											<option>Gua</option>
											<option>Ngwala</option>
											<option>Ihango</option>
											<option>Ulenje</option>
											<option>Tembela</option>
											<option>Ijombe</option>
											<option>Santilya</option>
											<option>Ilembo</option>
											<option>Iwiji</option>
											<option>Isuto</option>
											<option>Igale</option>
											<option>Iwindi</option>
											<option>UT/Usongwe</option>
											<option>Mshewe</option>
											<option>anga</option>
											<option>Ikukwa</option>
											<option>Iyunga Mapinduzi</option>
											<option>Bonde la Usongwe</option>
											<option>Inyala</option>
											<option>Ilungu</option>
											<option>Lusungo</option>
											<option>Makwale</option>
											<option>Matema</option>
											<option>Kyela Mjini</option>
											<option>Kajunjumele</option>
											<option>Bujonde</option>
											<option>Ikolo</option>
											<option>Katumba Songwe</option>
											<option>Ngana</option>
											<option>Busole</option>
											<option>Ipande</option>
											<option>Ikama</option>
											<option>Ipinda</option>
											<option>Ngonga</option>
											<option>Bulyaga</option>
											<option>Katumba</option>
											<option>Suma</option>
											<option>Kandete</option>
											<option>Luteba</option>
											<option>Mpombo</option>
											<option>Isange</option>
											<option>Kabula</option>
											<option>Lwangwa</option>
											<option>Rufiryo</option>
											<option>Kisegese</option>
											<option>Lupata</option>
											<option>Kambasegela</option>
											<option>Masukulu</option>
											<option>Kisiba</option>
											<option>Bujela</option>
											<option>Ilima</option>
											<option>Kisondela</option>
											<option>Ikuti</option>
											<option>Malindo</option>
											<option>Mpuguso</option>
											<option>Bagamoyo</option>
											<option>Lufingo</option>
											<option>Nkunga</option>
											<option>Kyimo</option>
											<option>Kinyala</option>
											<option>Kiwira</option>
											<option>Isongole</option>
											<option>Itumba</option>
											<option>Itale</option>
											<option>Ibaba</option>
											<option>Ndola</option>
											<option>Luswisi</option>
											<option>Ngulilo</option>
											<option>Lubanda</option>
											<option>Ngulugulu</option>
											<option>Sange</option>
											<option>Ikinga</option>
											<option>Kafule</option>
											<option>Bupigu</option>
											<option>Chitete</option>
											<option>Mbebe</option>
											<option>Chilulumo</option>
											<option>Kamsamba</option>
											<option>Ivuna</option>
											<option>Nambinzo</option>
											<option>Itaka</option>
											<option>Isansa</option>
											<option>Iyula</option>
											<option>Nyimbili</option>
											<option>Myovizi</option>
											<option>Igamba</option>
											<option>Halungu</option>
											<option>Msia</option>
											<option>Mlowo</option>
											<option>Vwawa</option>
											<option>Isandula</option>
											<option>Ihanda</option>
											<option>Tunduma</option>
											<option>Chiwezi</option>
											<option>Msangano</option>
											<option>Ndalambo</option>
											<option>Kapele</option>
											<option>Myunga</option>
											<option>Nkangamo</option>
											<option>Msangaji</option>
											<option>Madibira</option>
											<option>Mawindi</option>
											<option>Rujewa</option>
											<option>Mapogoro</option>
											<option>Chimala</option>
											<option>Utengule/Usangu</option>
											<option>Ruiwa</option>
											<option>Ubaruku</option>
											<option>Igurusi</option>
											<option>Sisimba</option>
											<option>Isanga</option>
											<option>Iganzo</option>
											<option>Mwansenkwa</option>
											<option>Itagano</option>
											<option>Itezi</option>
											<option>Nsalaga</option>
											<option>Igawilo</option>
											<option>Iganjo</option>
											<option>Uyole</option>
											<option>Iduda</option>
											<option>Mwasanga</option>
											<option>Ilomba</option>
											<option>Mwakibete</option>
											<option>Ilemi</option>
											<option>Isyesye</option>
											<option>Iyela</option>
											<option>Sinde</option>
											<option>Maanga</option>
											<option>Mbalizi Road</option>
											<option>Forest</option>
											<option>Mabatini</option>
											<option>Nzovwe</option>
											<option>Kalobe</option>
											<option>Iyunga</option>
											<option>Iwambi</option>
											<option>Itende</option>
											<option>Iziwa</option>
											<option>Nsoho</option>
											<option>Ghana</option>
											<option>Nonde</option>
											<option>Itiji</option>
											<option>Maendeleo</option>
											<option>Kiomboi</option>
											<option>Kisiriri</option>
											<option>Tulya</option>
											<option>Kidaru</option>
											<option>Mpambala</option>
											<option>Ibaga</option>
											<option>Mwangeza</option>
											<option>Nkinto</option>
											<option>Ilunda</option>
											<option>Nduguti</option>
											<option>Gumanga</option>
											<option>Msingi</option>
											<option>Kinyangili</option>
											<option>Iguguno</option>
											<option>Kinampanda</option>
											<option>Kyengege</option>
											<option>Kaselya</option>
											<option>Mbelekese</option>
											<option>Ndago</option>
											<option>Urughu</option>
											<option>Mtekente</option>
											<option>Ulemo</option>
											<option>Mtoa</option>
											<option>Shelui</option>
											<option>Ntwike</option>
											<option>Ughandi</option>
											<option>Mtinko</option>
											<option>Makuro</option>
											<option>Ilongero</option>
											<option>Ikhanoda</option>
											<option>Maghojoa</option>
											<option>Merya</option>
											<option>Kinyeto</option>
											<option>Ngimu</option>
											<option>Mgori</option>
											<option>Siuyu</option>
											<option>Misughaa</option>
											<option>Mungaa</option>
											<option>Ntuntu</option>
											<option>Mangonyi</option>
											<option>Issuna</option>
											<option>Ikungu</option>
											<option>Dungunyi</option>
											<option>Puma</option>
											<option>Ihanja</option>
											<option>Minyughe</option>
											<option>Muhintiri</option>
											<option>Mgungira</option>
											<option>Mwaru</option>
											<option>Sepuka</option>
											<option>Irisya</option>
											<option>Mudida</option>
											<option>Manyoni</option>
											<option>Kilimatinde</option>
											<option>Makuru</option>
											<option>Chikuyu</option>
											<option>Kintinku</option>
											<option>Majiri</option>
											<option>Sasajira</option>
											<option>Idodyandole</option>
											<option>Heka - Azimio</option>
											<option>Nkonko</option>
											<option>Sanza</option>
											<option>Isseke</option>
											<option>Rungwa</option>
											<option>Mgandu</option>
											<option>Itigi</option>
											<option>Sanjaranda</option>
											<option>Aghondi</option>
											<option>Mtipa</option>
											<option>Mughanga</option>
											<option>Mitunduruni</option>
											<option>Unyambwa</option>
											<option>Mungumaji</option>
											<option>Unyamikumbi</option>
											<option>Mtamaa</option>
											<option>kindai</option>
											<option>Ipembe</option>
											<option>Utemini</option>
											<option>Mwankoko</option>
											<option>Mandewa</option>
											<option>Puge</option>
											<option>Nkiniziwa</option>
											<option>Budushi</option>
											<option>Mwakanshahala</option>
											<option>Tongi</option>
											<option>Mizibaziba</option>
											<option>Milambo - Itobo</option>
											<option>Magengati</option>
											<option>Ndala</option>
											<option>Nzega Mjini</option>
											<option>Wela</option>
											<option>Mbogwe</option>
											<option>Miguwa</option>
											<option>Itilo</option>
											<option>Muhugi</option>
											<option>Utwigu</option>
											<option>Ijanija</option>
											<option>Nzega Ndogo</option>
											<option>Lusu</option>
											<option>Nata</option>
											<option>Isanzu</option>
											<option>Itobo</option>
											<option>Mwangoye</option>
											<option>Sigili</option>
											<option>Mwamala</option>
											<option>Igusule</option>
											<option>Shigamba</option>
											<option>Kasela</option>
											<option>Karitu</option>
											<option>Bukene</option>
											<option>Mogwa</option>
											<option>Mambali</option>
											<option>Kahamanhalanga</option>
											<option>Uduka</option>
											<option>Semembela</option>
											<option>Isagenhe</option>
											<option>Ikindwa</option>
											<option>Igunga</option>
											<option>Bukoko</option>
											<option>Isakamaliwa</option>
											<option>Nyandekwa</option>
											<option>Nanga</option>
											<option>Nguvumoja</option>
											<option>Mbutu</option>
											<option>Kining'inila</option>
											<option>Igurubi</option>
											<option>Mwamashimba</option>
											<option>Kinungu</option>
											<option>Ntobo</option>
											<option>Itunduru</option>
											<option>Mwamashiga</option>
											<option>Choma</option>
											<option>Mwashiku</option>
											<option>Ziba</option>
											<option>Ndembezi</option>
											<option>Nkinga</option>
											<option>Ngulu</option>
											<option>Simbo</option>
											<option>Igoweko</option>
											<option>Mwisi</option>
											<option>Chabutwa</option>
											<option>Sungwizi</option>
											<option>Lutende</option>
											<option>Kizengi</option>
											<option>Goweko</option>
											<option>Igalula</option>
											<option>Ilolangulu</option>
											<option>Mabama</option>
											<option>Ndono</option>
											<option>Ufuluma</option>
											<option>Usagali</option>
											<option>Ibiri</option>
											<option>Bukumbi</option>
											<option>Ikongolo</option>
											<option>Upuge</option>
											<option>Magiri</option>
											<option>Isikizya</option>
											<option>Shitage</option>
											<option>Loya</option>
											<option>Urambo</option>
											<option>Imalamakoye</option>
											<option>Itundu</option>
											<option>Songambele</option>
											<option>Ukondamoyo</option>
											<option>Vumilia</option>
											<option>Kapilula</option>
											<option>Usoke</option>
											<option>Uyumbu</option>
											<option>Kiloleni</option>
											<option>Usisya</option>
											<option>Uyowa</option>
											<option>Kashishi</option>
											<option>Ichemba</option>
											<option>Mwongozo</option>
											<option>Kanindo</option>
											<option>Milambo</option>
											<option>Igombe Mkulu</option>
											<option>Kaliua</option>
											<option>Ushokola</option>
											<option>Kazaroho</option>
											<option>Igagala</option>
											<option>Usinge</option>
											<option>Ukumbisiganga</option>
											<option>Ugunga</option>
											<option>Tutuo</option>
											<option>Kiloleli</option>
											<option>Kipanga</option>
											<option>Sikonge</option>
											<option>Igigwa</option>
											<option>Kiloli</option>
											<option>Kipili</option>
											<option>Pangale</option>
											<option>Ipole</option>
											<option>Kanyenye</option>
											<option>Gongoni</option>
											<option>Chemchem</option>
											<option>Mtendeni</option>
											<option>Isevya</option>
											<option>Ipuli</option>
											<option>Cheyo</option>
											<option>Kitete</option>
											<option>Ng'ambo</option>
											<option>Kakola</option>
											<option>Uyui</option>
											<option>Itonjanda</option>
											<option>Ndevelwa</option>
											<option>Itetemia</option>
											<option>Kalunde</option>
											<option>Misha</option>
											<option>Kasokola</option>
											<option>Ugalla</option>
											<option>Mtapenda</option>
											<option>Inyonga</option>
											<option>Ilunde</option>
											<option>Ilela</option>
											<option>Utende</option>
											<option>Mbede</option>
											<option>Urwira</option>
											<option>Nsimbo</option>
											<option>Magamba</option>
											<option>Sitalike</option>
											<option>Usevya</option>
											<option>Machimboni</option>
											<option>karema</option>
											<option>Ikola</option>
											<option>Kabungu</option>
											<option>Mwese</option>
											<option>Mishamo</option>
											<option>Katuma</option>
											<option>Mpanda Ndogo</option>
											<option>Shanwe</option>
											<option>Kawajense</option>
											<option>Nsemulwa</option>
											<option>Misunkumilo</option>
											<option>Kashaulili</option>
											<option>Mkowe</option>
											<option>Msanzi</option>
											<option>Matai</option>
											<option>Sopa</option>
											<option>Mwazye</option>
											<option>Katazi</option>
											<option>Mwimbi</option>
											<option>Mambwekenya</option>
											<option>Mambwenkoswe</option>
											<option>Legezamwendo</option>
											<option>Miangalula</option>
											<option>Laela</option>
											<option>Lusaka</option>
											<option>Kalambazite</option>
											<option>Mpui</option>
											<option>Kaengesa</option>
											<option>Sandulula</option>
											<option>Muze</option>
											<option>Mtowisa</option>
											<option>Milepa</option>
											<option>Kaoze</option>
											<option>Kipeta</option>
											<option>Namanyere</option>
											<option>Mtenga</option>
											<option>Mkwamba</option>
											<option>Chala</option>
											<option>Kipande</option>
											<option>Isale</option>
											<option>Kate</option>
											<option>Sintali</option>
											<option>Kala</option>
											<option>Wampelembe</option>
											<option>Ninde</option>
											<option>Kirando</option>
											<option>Kabwe</option>
											<option>Mazwi</option>
											<option>Izia</option>
											<option>Katandala</option>
											<option>Old Sumbawanga</option>
											<option>Kizwite</option>
											<option>Ntendo</option>
											<option>Senga</option>
											<option>Mollo</option>
											<option>Pito</option>
											<option>Milanzi</option>
											<option>Matanga</option>
											<option>Kasense</option>
											<option>Kibondo Mjini</option>
											<option>Misezero</option>
											<option>Bunyambo</option>
											<option>Kitahana</option>
											<option>Busagara</option>
											<option>Rugongwe</option>
											<option>Murungu</option>
											<option>Kakonko</option>
											<option>Rugenge</option>
											<option>Kasuga</option>
											<option>Muhange</option>
											<option>Nyabibuye</option>
											<option>Nyamtukuza</option>
											<option>Kasanda</option>
											<option>Gwanumpu</option>
											<option>Mugunzu</option>
											<option>Mabamba</option>
											<option>Kizazi</option>
											<option>Kumsenga</option>
											<option>Itaba</option>
											<option>Kitanga</option>
											<option>Heru Shingo</option>
											<option>Buhoro</option>
											<option>Nyamidaho</option>
											<option>Kagera Nkanda</option>
											<option>Kitagata</option>
											<option>Nyakitonto</option>
											<option>Nyamnyusi</option>
											<option>Msambara</option>
											<option>Ruhita</option>
											<option>Titye</option>
											<option>Kigondo</option>
											<option>Murufiti</option>
											<option>Rungwe Mpya</option>
											<option>Muzye</option>
											<option>Rusesa</option>
											<option>Kwaga</option>
											<option>Munzeze</option>
											<option>Muhunga</option>
											<option>Janda</option>
											<option>Rusaba</option>
											<option>Muhinda</option>
											<option>Munanila</option>
											<option>Buhigwe</option>
											<option>Nyamugali</option>
											<option>Munyegera</option>
											<option>Kajana</option>
											<option>Muyama</option>
											<option>Kilelema</option>
											<option>Kasulu Mjini</option>
											<option>Mkigo</option>
											<option>Kalinzi</option>
											<option>Bitale</option>
											<option>Mahembe</option>
											<option>Matendo</option>
											<option>Uvinza</option>
											<option>Mtego wa Noti</option>
											<option>Nguruka</option>
											<option>Mganza</option>
											<option>Kalya</option>
											<option>Buhingu</option>
											<option>Sigunga</option>
											<option>Sunuka</option>
											<option>Ilagala</option>
											<option>Kandaga</option>
											<option>Mngonya</option>
											<option>Mwandiga</option>
											<option>Kagongo</option>
											<option>Mwamgongo</option>
											<option>Kagunga</option>
											<option>Gungu</option>
											<option>Buhanda Businde</option>
											<option>Kagera</option>
											<option>Kasimbu</option>
											<option>Rubuga</option>
											<option>Machinjioni</option>
											<option>Kasingirima</option>
											<option>Kitongoni</option>
											<option>Rusimbi</option>
											<option>Mwanga South</option>
											<option>Kigoma Bangwe</option>
											<option>Mwanga Kaskazini</option>
											<option>Sapiwi</option>
											<option>Mwaubingi</option>
											<option>Mwadobana</option>
											<option>Nyakabindi</option>
											<option>Somanda</option>
											<option>Nkololo</option>
											<option>Bumera</option>
											<option>Sagata</option>
											<option>Mwaswale</option>
											<option>Chinamili</option>
											<option>Mhunze</option>
											<option>Lagangabilili</option>
											<option>Bunamhala</option>
											<option>Nkoma</option>
											<option>Mwamapalala</option>
											<option>Zagayu</option>
											<option>Kinang'weli</option>
											<option>Mbita</option>
											<option>Lugulu</option>
											<option>Bariadi</option>
											<option>Sakwe</option>
											<option>Mhango</option>
											<option>Kasoli</option>
											<option>Gamboshi</option>
											<option>Ikungulyabashashi</option>
											<option>Buchambi</option>
											<option>Masela</option>
											<option>Nyalikungu</option>
											<option>Lalago</option>
											<option>Dakama</option>
											<option>Sukuma</option>
											<option>Mpindo</option>
											<option>Budekwa</option>
											<option>Ipililo</option>
											<option>Malampaka</option>
											<option>Badi</option>
											<option>kulimi</option>
											<option>Nyabubinza</option>
											<option>Shishiyu</option>
											<option>Busilili</option>
											<option>Kadoto</option>
											<option>Nguliguli</option>
											<option>Imesela</option>
											<option>Usule</option>
											<option>Ilola</option>
											<option>Didia</option>
											<option>Itwangi</option>
											<option>Tinde</option>
											<option>Mwakitolyo</option>
											<option>Salawe</option>
											<option>Solwa</option>
											<option>Iselemagazi</option>
											<option>Lyabukande</option>
											<option>Mwantini</option>
											<option>Pandagichiza</option>
											<option>Samuye</option>
											<option>Usanda</option>
											<option>Bugarama</option>
											<option>Runguya</option>
											<option>Segese</option>
											<option>Chela</option>
											<option>Bulige</option>
											<option>Ngaya</option>
											<option>Kinaga</option>
											<option>Jana</option>
											<option>Isaka</option>
											<option>Mwalugulu</option>
											<option>Isagehe</option>
											<option>Mwendakulima</option>
											<option>Kilago</option>
											<option>Chona</option>
											<option>Chambo</option>
											<option>Kisuke</option>
											<option>Ukune</option>
											<option>Uyogo</option>
											<option>Ushetu</option>
											<option>Ulowa</option>
											<option>Bulungwa</option>
											<option>Idahina</option>
											<option>Igwamanoni</option>
											<option>Mpunze</option>
											<option>Kinamapula</option>
											<option>Ngongwa</option>
											<option>Busangi</option>
											<option>Malunga</option>
											<option>Mhongolo</option>
											<option>Kahama Mjini</option>
											<option>Nyihogo</option>
											<option>Zongomera</option>
											<option>Bukandwe</option>
											<option>Masumbwe</option>
											<option>Iyogelo</option>
											<option>Iponya</option>
											<option>Bukombe</option>
											<option>Ushirombo</option>
											<option>Runzewe</option>
											<option>Ikunguigazi</option>
											<option>Mbongwe</option>
											<option>Ushirika</option>
											<option>Nyasato</option>
											<option>Uyovu</option>
											<option>Lugunga</option>
											<option>Mwanhuzi</option>
											<option>Kamali</option>
											<option>Mwamishali</option>
											<option>Itinje</option>
											<option>Kisesa</option>
											<option>Mwandoya</option>
											<option>Lingeka</option>
											<option>Sakasaka</option>
											<option>Imalaseko</option>
											<option>Mwabuzo</option>
											<option>Mwamalole</option>
											<option>Mwanjoro</option>
											<option>Mwabuma</option>
											<option>Mwabusalu</option>
											<option>Lubiga</option>
											<option>Mwamanongu</option>
											<option>Ng'hoboko</option>
											<option>Bukundi</option>
											<option>Mwamalili</option>
											<option>Kolandoto</option>
											<option>Ngokolo</option>
											<option>Ibadakuli</option>
											<option>Chamaguha</option>
											<option>Ibinzamata</option>
											<option>Kitangili</option>
											<option>Kizumbi</option>
											<option>Mwawaza</option>
											<option>Kambarage</option>
											<option>Chibe</option>
											<option>Bunambiyu</option>
											<option>Bubiki</option>
											<option>Songwa</option>
											<option>Seke/Bukoro</option>
											<option>Mwadui Lohumbo</option>
											<option>Uchunga</option>
											<option>Kishapu</option>
											<option>Mwakipoya</option>
											<option>Shagihilu</option>
											<option>Somagedi</option>
											<option>Mwamalasa</option>
											<option>Masanga</option>
											<option>Lagana</option>
											<option>Mwamashele</option>
											<option>Ngofila</option>
											<option>Ukenyenge</option>
											<option>Talaga</option>
											<option>Itima</option>
											<option>Kamuli</option>
											<option>Mabira</option>
											<option>Igurwa</option>
											<option>Kihanga</option>
											<option>Kituntu</option>
											<option>Rwabwere</option>
											<option>Nkwenda</option>
											<option>Kimuli</option>
											<option>Ndama</option>
											<option>Kayanga</option>
											<option>Bugene</option>
											<option>Nyakahanga</option>
											<option>Nyaishozi</option>
											<option>Ihembe</option>
											<option>Rugu</option>
											<option>Nyakasimbi</option>
											<option>Nyakakika</option>
											<option>Bweranyange</option>
											<option>Kibondo</option>
											<option>Nyabiyonza</option>
											<option>Kiruruma</option>
											<option>Kyerwa</option>
											<option>Isingiro</option>
											<option>Kaisho</option>
											<option>Kibingo</option>
											<option>Murongo</option>
											<option>Bugomora</option>
											<option>Nsunga</option>
											<option>Minziro</option>
											<option>Kasambya</option>
											<option>Kyaka</option>
											<option>Bugorora</option>
											<option>Kilimilile</option>
											<option>Kakunyu</option>
											<option>Ruzinga</option>
											<option>Kashenye</option>
											<option>Kanyigo</option>
											<option>Ishunju</option>
											<option>Ishozi</option>
											<option>Gera</option>
											<option>Bwanjai</option>
											<option>Bugandika</option>
											<option>Kitobo</option>
											<option>Buyango</option>
											<option>Rubafu</option>
											<option>Kishanje</option>
											<option>Kaagya</option>
											<option>Buhendangabo</option>
											<option>Nyakato</option>
											<option>Katoma</option>
											<option>Karabagaine</option>
											<option>Maruku</option>
											<option>Kanyangereko</option>
											<option>Kyamuraile</option>
											<option>Katoro</option>
											<option>Kaibanja</option>
											<option>Nyakibimbili</option>
											<option>Kasharu</option>
											<option>Bujugo</option>
											<option>Katerero</option>
											<option>Ibwera</option>
											<option>Mikoni</option>
											<option>Ruhunga</option>
											<option>Izimbya</option>
											<option>Buterankuzi</option>
											<option>Rubale</option>
											<option>Kikomero</option>
											<option>Kibirizi</option>
											<option>Muhutwe</option>
											<option>Mayondwe</option>
											<option>Goziba</option>
											<option>Bumbile</option>
											<option>Izigo</option>
											<option>Kagoma</option>
											<option>Bureza</option>
											<option>Muleba</option>
											<option>Mazinga</option>
											<option>Magata/Karutanga</option>
											<option>Kibanga</option>
											<option>Kasharunga</option>
											<option>Kimwani</option>
											<option>Kyebitembe</option>
											<option>Karambi</option>
											<option>Mubunda</option>
											<option>Burungura</option>
											<option>Biirabo</option>
											<option>Rushwa</option>
											<option>Ngenge</option>
											<option>kabirizi</option>
											<option>Nshamba</option>
											<option>Kashasha</option>
											<option>Ijumbi</option>
											<option>Kishanda</option>
											<option>Buganguzi</option>
											<option>Ibuga</option>
											<option>Bulyakashaju</option>
											<option>Kamachumu</option>
											<option>Ruhanga</option>
											<option>B'mulo Mjini</option>
											<option>Nyarubungo</option>
											<option>Muganza</option>
											<option>Kigongo</option>
											<option>Nyamirembe</option>
											<option>Ichwankima</option>
											<option>Ilemela</option>
											<option>Chato</option>
											<option>Katende</option>
											<option>Kachwamba</option>
											<option>Bukome</option>
											<option>Nyamigogo</option>
											<option>Makurugusi</option>
											<option>Buseresere</option>
											<option>Bwanga</option>
											<option>Bwera</option>
											<option>Buziku</option>
											<option>Nyabusozi</option>
											<option>Runazi</option>
											<option>Lusahunga</option>
											<option>Kalenge</option>
											<option>Nyakahura</option>
											<option>Rusumo</option>
											<option>Nyakisasa</option>
											<option>Rulenge</option>
											<option>Keza</option>
											<option>Murusagamba</option>
											<option>Bukiriro</option>
											<option>Kabanga</option>
											<option>Mabawe</option>
											<option>Kanazi</option>
											<option>Mugoma</option>
											<option>Kirushya</option>
											<option>Ntobeye</option>
											<option>Nyamiyaga</option>
											<option>Ngara Mjini</option>
											<option>Kibimba</option>
											<option>Hamugembe</option>
											<option>Nshambya</option>
											<option>Buhembe</option>
											<option>Kahororo</option>
											<option>Kashai</option>
											<option>Miembeni</option>
											<option>Bilele</option>
											<option>Bakoba</option>
											<option>Ijuganyondo</option>
											<option>Kitendaguro</option>
											<option>Kibeta</option>
											<option>Kagondo</option>
											<option>Nyanga</option>
											<option>Rwamishenye</option>
											<option>Nansio</option>
											<option>Nakatunguru</option>
											<option>Kakerege</option>
											<option>Bukongo</option>
											<option>Nkilizya</option>
											<option>Bukanda</option>
											<option>Mukituntu</option>
											<option>Igalla</option>
											<option>Bwiro</option>
											<option>Muriti</option>
											<option>Ilangala</option>
											<option>Namilembe</option>
											<option>Murutunguru</option>
											<option>Kagunguli</option>
											<option>Bukindo</option>
											<option>Namagondo</option>
											<option>Ngoma</option>
											<option>Bwisya</option>
											<option>Bukungu</option>
											<option>Nyamanga</option>
											<option>Bukiko</option>
											<option>Irugwa</option>
											<option>Bujashi</option>
											<option>Lutale</option>
											<option>Kongolo</option>
											<option>Nyanguge</option>
											<option>Kitongo - Sima</option>
											<option>Mwamanga</option>
											<option>Kahangara</option>
											<option>Nyigogo</option>
											<option>Mwamabanza</option>
											<option>Lubugu</option>
											<option>Mwamanyili</option>
											<option>Shigala</option>
											<option>Kabita</option>
											<option>Kalemela</option>
											<option>Igalukilo</option>
											<option>Ngasamo</option>
											<option>Malili</option>
											<option>Badugu</option>
											<option>Nyaluhande</option>
											<option>Ng'haya</option>
											<option>Nkungulu</option>
											<option>Shishani</option>
											<option>Magu mjini</option>
											<option>Nyamanoro</option>
											<option>Igogo</option>
											<option>Pamba</option>
											<option>Nyamagana</option>
											<option>Mirongo</option>
											<option>Isamilo</option>
											<option>Kirumba</option>
											<option>Kitangiri</option>
											<option>Wala</option>
											<option>Bungulwa</option>
											<option>Sumve</option>
											<option>Mantare</option>
											<option>Ngula</option>
											<option>Mwabomba</option>
											<option>Mwagi</option>
											<option>Iseni</option>
											<option>Nyambiti</option>
											<option>Maligisu</option>
											<option>Mwandu</option>
											<option>Malya</option>
											<option>Lyoma</option>
											<option>Mwang'halanga</option>
											<option>Nyamilama</option>
											<option>Mwakilyambiti</option>
											<option>Hungumalwa</option>
											<option>Kikubiji</option>
											<option>Mhande</option>
											<option>Bupamwa</option>
											<option>Fukalo</option>
											<option>Ng'hundi</option>
											<option>Igongwa</option>
											<option>Ngudu</option>
											<option>Sengerema</option>
											<option>Nyamazugo</option>
											<option>Chifunfu</option>
											<option>Katunguru</option>
											<option>Kasungamile</option>
											<option>Nyamatongo</option>
											<option>Tabaruka</option>
											<option>Busisi</option>
											<option>Buyagu</option>
											<option>Sima</option>
											<option>Nyakasungwa</option>
											<option>Kalebezo</option>
											<option>Nyehunge</option>
											<option>Kafunzo</option>
											<option>Bupandwamhela</option>
											<option>Katwe</option>
											<option>Maisome</option>
											<option>Kazunzu</option>
											<option>Lugata</option>
											<option>Nyakalilo</option>
											<option>Nyakasasa</option>
											<option>Buzilasoga</option>
											<option>Nyanzenda</option>
											<option>Nzera</option>
											<option>Nkome</option>
											<option>Kagu</option>
											<option>Chigunga</option>
											<option>Nyachiluluma</option>
											<option>Kharumwa</option>
											<option>Bukwimba</option>
											<option>Nyugwa</option>
											<option>Kakora</option>
											<option>Busanda</option>
											<option>Bukoli</option>
											<option>Nyamalimbe</option>
											<option>Nyakamwaga</option>
											<option>Kamena</option>
											<option>Nyang'hwale</option>
											<option>Busolwa</option>
											<option>Shabaka</option>
											<option>Mwingiro</option>
											<option>Kalangalala</option>
											<option>Mtakuja</option>
											<option>Ihanamilo</option>
											<option>Kasamwa</option>
											<option>Bulela</option>
											<option>Kamhanga</option>
											<option>Lubanga</option>
											<option>Kaseme</option>
											<option>Nyakagomba</option>
											<option>Bukondo</option>
											<option>Kafita</option>
											<option>Lwamgasa</option>
											<option>Nyarugusu</option>
											<option>Bulemeji</option>
											<option>Idetemya</option>
											<option>Ukiriguru</option>
											<option>Kanyelele</option>
											<option>Koromije</option>
											<option>Igokelo</option>
											<option>Missungwi</option>
											<option>Misasi</option>
											<option>Kijima</option>
											<option>Shilalo</option>
											<option>Buhingo</option>
											<option>Busongo</option>
											<option>Nhundulu</option>
											<option>Luburi</option>
											<option>Ilujamate</option>
											<option>Mbarika</option>
											<option>Sumbugu</option>
											<option>Kasololo</option>
											<option>Pasiansi</option>
											<option>Butimba</option>
											<option>Igoma</option>
											<option>Sangabuye</option>
											<option>Bugogwa</option>
											<option>Mkolani</option>
											<option>Buhongwa</option>
											<option>Buswelu</option>
											<option>Susuni</option>
											<option>Mwema</option>
											<option>Sirari</option>
											<option>Pemba</option>
											<option>Nyakonga</option>
											<option>Nyarero</option>
											<option>Nyamwaga</option>
											<option>Muriba</option>
											<option>Nyanungu</option>
											<option>Gorong'a</option>
											<option>Nyarokoba</option>
											<option>Kemambo</option>
											<option>Kibasuka</option>
											<option>Binagi</option>
											<option>Turwa</option>
											<option>Nyandoto</option>
											<option>Bukwe</option>
											<option>Manga</option>
											<option>Nyathorogo</option>
											<option>Kisumwa</option>
											<option>Rabour</option>
											<option>Komuge</option>
											<option>Nyamunga</option>
											<option>Kyang'ombe</option>
											<option>Kirogo</option>
											<option>Nyamagaro</option>
											<option>Nyamtinga</option>
											<option>Nyahongo</option>
											<option>Tai</option>
											<option>Mkoma</option>
											<option>Bukura</option>
											<option>Roche</option>
											<option>Kitembe</option>
											<option>Goribe</option>
											<option>Ikoma</option>
											<option>Mirare</option>
											<option>Kigunga</option>
											<option>Koryo</option>
											<option>Matongo</option>
											<option>Kenyamonta</option>
											<option>Busawe</option>
											<option>Kisaka</option>
											<option>Kebanchabancha</option>
											<option>Ring'wani</option>
											<option>Rung'abure</option>
											<option>Machochwe</option>
											<option>Kisangura</option>
											<option>Mugumu Mjini</option>
											<option>Natta</option>
											<option>Issenye</option>
											<option>Rigicha</option>
											<option>Nyambureti</option>
											<option>Nyamoko</option>
											<option>Manchira</option>
											<option>Kyambahi</option>
											<option>Nyamatare</option>
											<option>Buswahili</option>
											<option>Nyamimange</option>
											<option>Bwiregi</option>
											<option>Muriaza</option>
											<option>Buhemba</option>
											<option>Butiama</option>
											<option>Masaba</option>
											<option>Kyanyari</option>
											<option>Kukirango</option>
											<option>Buruma</option>
											<option>Butuguri</option>
											<option>Bukabwa</option>
											<option>Nyankanga</option>
											<option>Etaro</option>
											<option>Nyakatende</option>
											<option>Mugango</option>
											<option>Kiriba</option>
											<option>Tegeruka</option>
											<option>Suguti</option>
											<option>Nyambono</option>
											<option>Nyamrandirira</option>
											<option>Bugwema</option>
											<option>Murangi</option>
											<option>Ukima</option>
											<option>Makojo</option>
											<option>Bwasi</option>
											<option>Bukumi</option>
											<option>Nyamuswa</option>
											<option>Salama</option>
											<option>Mihingo</option>
											<option>Mugeta</option>
											<option>Hunyari</option>
											<option>Mcharo</option>
											<option>Sazira</option>
											<option>Kunzugu</option>
											<option>Bunda</option>
											<option>Guta</option>
											<option>Neruma</option>
											<option>Kibara</option>
											<option>Nansimo</option>
											<option>Kisorya</option>
											<option>Igundu</option>
											<option>Iramba</option>
											<option>Namhula</option>
											<option>Wariku</option>
											<option>Kabasa</option>
											<option>Mukendo</option>
											<option>Mwigobero</option>
											<option>Iringo</option>
											<option>Kitaji</option>
											<option>Nyasho</option>
											<option>Bweri</option>
											<option>Kigera</option>
											<option>Kamunyonge</option>
											<option>Mwisenge</option>
											<option>Buhare</option>
											<option>Makoko</option>
											<option>Babati</option>
											<option>Mamire</option>
											<option>Gallapo</option>
											<option>Qash</option>
											<option>Singe</option>
											<option>Bonga</option>
											<option>Gidas</option>
											<option>Duru</option>
											<option>Riroda</option>
											<option>Sigino</option>
											<option>Arri</option>
											<option>Dareda</option>
											<option>Dabil</option>
											<option>Ufana</option>
											<option>Bashnet</option>
											<option>Madunga</option>
											<option>Kiru</option>
											<option>Magugu</option>
											<option>Magara</option>
											<option>Mwada</option>
											<option>Nkaiti</option>
											<option>Balangdalalu</option>
											<option>Gehandu</option>
											<option>Laghanga</option>
											<option>Getanuwas</option>
											<option>Hirbadaw</option>
											<option>Bassodesh</option>
											<option>Bassotu</option>
											<option>Gendabi</option>
											<option>Mogitu</option>
											<option>Gitting</option>
											<option>Masakta</option>
											<option>Masqaroda</option>
											<option>Endasak</option>
											<option>Gidahababieg</option>
											<option>Measkron</option>
											<option>Hidet</option>
											<option>Simbay</option>
											<option>Sirop</option>
											<option>Gisambalang</option>
											<option>Nangwa</option>
											<option>Katesh</option>
											<option>Ganana</option>
											<option>Daudi</option>
											<option>Bargish</option>
											<option>Kainam</option>
											<option>Murray</option>
											<option>Sanu</option>
											<option>Mbulu Mjini</option>
											<option>Tlawi</option>
											<option>Bashay</option>
											<option>Dongobesh</option>
											<option>Tumati</option>
											<option>Maretadu</option>
											<option>Maghang</option>
											<option>Haidom</option>
											<option>Yaeda chini</option>
											<option>Masieda</option>
											<option>Orkesumet</option>
											<option>Naberera</option>
											<option>Loibor - Siret</option>
											<option>Emboreet</option>
											<option>Oljoro N0. 5</option>
											<option>Shambarai</option>
											<option>Mererani</option>
											<option>Msitu wa Tembo</option>
											<option>Ngorika</option>
											<option>Loiborsoit</option>
											<option>Ruvu Remit</option>
											<option>Kibaya</option>
											<option>Partimbo</option>
											<option>Olbolot</option>
											<option>Makame</option>
											<option>Ndedo</option>
											<option>Kijungu</option>
											<option>Lengatei</option>
											<option>Sunya</option>
											<option>Dongo</option>
											<option>Dosidosi</option>
											<option>Engusero</option>
											<option>Matui</option>
											<option>Bwagamoyo</option>
											<option>Mkokotoni</option>
											<option>Mto wa Pwani</option>
											<option>Pale</option>
											<option>Kivunge</option>
											<option>Tumbatu Gomani</option>
											<option>Tumbatu Jongowe</option>
											<option>Kibeni</option>
											<option>Muwange</option>
											<option>Pitanazako</option>
											<option>Potoa</option>
											<option>Fukuchani</option>
											<option>Kidoti</option>
											<option>Tazari</option>
											<option>Kigunda</option>
											<option>Nungwi</option>
											<option>Matemwe</option>
											<option>Kijini</option>
											<option>Pwani Mchangani</option>
											<option>Gamba</option>
											<option>Moga</option>
											<option>Chaani Masingini</option>
											<option>Mchenza Shauri</option>
											<option>Chaani Kubwa</option>
											<option>Kikobweni</option>
											<option>Bandamaji</option>
											<option>Kinyasini</option>
											<option>Kandwi</option>
											<option>Makoba</option>
											<option>Manga Pwani</option>
											<option>Fujoni</option>
											<option>Kiomba mvua</option>
											<option>D/Mchangani</option>
											<option>Mkadini</option>
											<option>Zingwe zingwe</option>
											<option>Kitope</option>
											<option>Mahonda</option>
											<option>Mnyimbi</option>
											<option>Donge Mtambile</option>
											<option>Kinduni</option>
											<option>Donge Karange</option>
											<option>Donge Mbiji</option>
											<option>Donge Kipange</option>
											<option>Donge Vijibweni</option>
											<option>Upenja</option>
											<option>Kiwengwa</option>
											<option>Pangeni</option>
											<option>Kilombero</option>
											<option>Muwada</option>
											<option>Dunga Bweni</option>
											<option>Ubago</option>
											<option>Kidimini</option>
											<option>Machui</option>
											<option>Kiboje Mwembe shauri</option>
											<option>Miwani</option>
											<option>Kiboje Mkwajuni</option>
											<option>Koani</option>
											<option>Mgeni haji</option>
											<option>Uzini</option>
											<option>Mitakawani</option>
											<option>Tunduni</option>
											<option>Bambi</option>
											<option>Pagali</option>
											<option>Umbuji</option>
											<option>Mchangani</option>
											<option>Dunga Kiembeni</option>
											<option>Ndijani</option>
											<option>Jendele</option>
											<option>Chwaka</option>
											<option>Marumbi</option>
											<option>Uroa</option>
											<option>Jumbi</option>
											<option>Tunguu</option>
											<option>Binguni</option>
											<option>Cheju</option>
											<option>Bungi</option>
											<option>Unguja Ukuu/Kae Pwani</option>
											<option>Kikungwi</option>
											<option>Uzi</option>
											<option>Ng'ambwa</option>
											<option>Charawe</option>
											<option>Ukongoroni</option>
											<option>Michamvi</option>
											<option>Unguja/ukuu - Kaebona</option>
											<option>Nganani</option>
											<option>Mzuri</option>
											<option>Kajengwa</option>
											<option>Jambini kikadini</option>
											<option>Mtende</option>
											<option>Kibuteni</option>
											<option>Kizimkazi/Dimbani</option>
											<option>Kizimkazi/Mkunguni</option>
											<option>Muyuni 'A'</option>
											<option>Muyuni 'B'</option>
											<option>Muyuni 'C'</option>
											<option>Pete</option>
											<option>Muungoni</option>
											<option>Paje</option>
											<option>Jambiani Kibigija</option>
											<option>Bwejuu</option>
											<option>Bububu</option>
											<option>Chuini</option>
											<option>Kama</option>
											<option>Mfenesini</option>
											<option>Mwakaje</option>
											<option>Fuoni Kibondeni</option>
											<option>kianga</option>
											<option>Dole</option>
											<option>Kizimbani</option>
											<option>Mbuzini</option>
											<option>Bumbwisudi</option>
											<option>Maungani</option>
											<option>Shakani</option>
											<option>Kiembesamaki</option>
											<option>Chukwani</option>
											<option>Fumba</option>
											<option>Bweleo</option>
											<option>Dimani</option>
											<option>Kombeni</option>
											<option>Mwanakwerekwe</option>
											<option>Mto Pepo</option>
											<option>Magogoni</option>
											<option>Mwanyanya</option>
											<option>Fuoni Kijitoupele</option>
											<option>Tomondo</option>
											<option>Welezo</option>
											<option>Mkunazini</option>
											<option>Kiponda</option>
											<option>Muembe Ladu</option>
											<option>Gulioni</option>
											<option>Makadara</option>
											<option>Shaurimoyo</option>
											<option>Muembe Makumbi</option>
											<option>Chumbuni</option>
											<option>Kwamtipura</option>
											<option>Kilimahewa</option>
											<option>Amaani</option>
											<option>Nyerere</option>
											<option>Sebleni</option>
											<option>Mpendae</option>
											<option>Urusi</option>
											<option>Kikwajuni Juu</option>
											<option>Kikwajuni Bondeni</option>
											<option>Kisima Majongoo</option>
											<option>Vikokotoni</option>
											<option>Mwembetanga</option>
											<option>Mwembeshauri</option>
											<option>Kwaalamsha</option>
											<option>Mikunguni</option>
											<option>Mkele</option>
											<option>Sogea</option>
											<option>Jang'ombe</option>
											<option>Kidongo Chekundu</option>
											<option>Matarumbeta</option>
											<option>Kwahani</option>
											<option>Kwalinato</option>
											<option>Karakana</option>
											<option>Kipangani</option>
											<option>Mtambwe Kaskazini</option>
											<option>Fundo</option>
											<option>M/Mdogo</option>
											<option>Kambini</option>
											<option>Kojani</option>
											<option>Ole</option>
											<option>Kangagani</option>
											<option>Kiuyu</option>
											<option>Piki</option>
											<option>Gando</option>
											<option>Ukunjwi</option>
											<option>Pandani</option>
											<option>Shengejuu</option>
											<option>Bopwe</option>
											<option>Bobwe</option>
											<option>utaani</option>
											<option>Mtambwe   Kusini</option>
											<option>Micheweni</option>
											<option>Msuka</option>
											<option>Kinowe</option>
											<option>Tumbe</option>
											<option>Mgogoni</option>
											<option>Shumba Viamboni</option>
											<option>Finya</option>
											<option>Konde</option>
											<option>Wingwi Mapofu</option>
											<option>Kiuyu Maziwa N'gombe</option>
											<option>Makangale</option>
											<option>Wingwi/Njuguni</option>
											<option>Shumba Mjini</option>
											<option>Chanjaani</option>
											<option>Wawi</option>
											<option>Pujini</option>
											<option>Ndagoni</option>
											<option>Vitongoji</option>
											<option>Ngambwa</option>
											<option>Shungi</option>
											<option>Chonga</option>
											<option>Mgelema</option>
											<option>Tibirinzi</option>
											<option>Chachani</option>
											<option>Wara</option>
											<option>Mvumoni</option>
											<option>Wesha</option>
											<option>Uwandani</option>
											<option>Ngombeni</option>
											<option>Makombeni</option>
											<option>Makoongwe</option>
											<option>Shidi</option>
											<option>Mkanyageni</option>
											<option>Michenzani</option>
											<option>Chokocho</option>
											<option>Kisiwa Panza</option>
											<option>Kangani</option>
											<option>Kengeja</option>
											<option>Muambe</option>
											<option>Kiwani</option>
											<option>Mtambile</option>
											<option>Mizingani</option>
											<option>Ngwachani</option>
											<option>Chambani</option>
											<option>Wambaa</option>
											<option>Mbuguani</option>
											<option>Uweleni</option>
											<option>Mtangani</option>
											<option>Ukutini</option>
										</select>
										<p id="wardsHelpBlock" class="form-text text-muted">
											You may select multiple wards
										</p>
										</div>
									</div>
									<div class="row">
										<br />	<br />							
									</div>
									<div class="form-group row">
									<div class="col-sm-12  text-right">
									  <button type="submit" class="btn btn-primary">Submit</button>
									  <button type="cancel" class="btn btn-primary">Cancel</button>
									</div>
									</div>
								</fieldset>
							</div>
							</form>
						</div> 			  
						<div class="col-md-4">
							<div id="message-container" class="alert alert-info"></div>
							
							<!--<div id="message-container" class="modal fade" role="dialog">
							  <div class="modal-dialog">
								
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Results</h4>
								  </div>
								  <div class="modal-body">
									<p></p>
								  </div>
								  <div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								  </div>
								</div>

							  </div>
							</div>-->


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</body>
</html>
        
