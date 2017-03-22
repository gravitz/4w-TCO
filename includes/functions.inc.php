<?php
/**
 * @author      Engelbert Chuwa <echuwa[at]unicef.org; chochote[at]gmail.com>
 * @version     1.1 
 * @since 		December 2016 
 */
 
require_once ("config.inc.php");

 /**
 * Function to fetch activity data from DB, convert it into JSON (Crossfilter acceptable format) and save it in 
 * the filesystem 
 *
 * @param  None
 * @return Nothing.
 */
 function getDataJavaScriptStringOld(){
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	/*$sql = "SELECT id, Region, District, Ward, fOrganisation as Organisation, ActivityTitle, ActivityDesc, y.classification as ActivityClassification, ActivitySubClassification, `fOrg_type` as `orgtype` FROM $tableName AS x,  _metaactivityclassifications AS y WHERE x.ActivityClassification=y.classificationId";*/
	
	$sql = "SELECT a.activity_Id AS `Pid`, r.name AS `Region`, IFNULL(d.name, \"\") AS `District`, ward_id AS `Ward`, a.title AS `ActivityTitle`, a.description AS `ActivityDesc`, a.startDate AS `StartDate`, a.endDate AS `EndDate`, a.pCode AS `ActivityCode`, a.activityStatus AS `ActivityStatus`, a.activityType AS `ActivityType`, a.fundingStatus AS `FundingStatus`, a.fundingType AS `FundingType`, a.currencyCode AS `FundingCurrency`, a.fundingAmount AS `FundingAmount`, s.subclassification AS `ActivitySubClassification`, a.activityThemes_id AS `ActivityTheme`, c.classification AS `ActivityClassification`, IF(o.organisation_role='Funding', org.name, '')  AS `FundingOrg`, IF(o.organisation_role='Accountable', org.name, '')  AS `AccountableOrg`, IF(o.organisation_role='Implementing', org.name, '')  AS `ImplementingOrg`, IF(o.organisation_role='Extending', org.name, '')  AS `ExtendingOrg`, orgType.type AS OrgType
	FROM ".ACTIVITY_TABLE." a
	JOIN activityclassifications as c ON a.classificationCode = c.classificationCode
	JOIN activitysubclassifications as s  ON  a.subclassification_id = s.subclassification_id 
	LEFT JOIN activitycoverage v ON v.activity_Id = a.activity_Id 
	JOIN regions AS r ON v.regHasc = r.regHasc
	LEFT JOIN districts AS d ON v.distHasc !='' AND v.distHasc = d.distHasc 
	LEFT JOIN activityorganisations o ON o.activity_id = a.activity_Id
	JOIN organisations as org ON o.organisation_id = org.organisation_id
	JOIN organisationtypes as orgType ON orgType.type_id = org.type_id"; //GROUP BY a.activity_Id
	
	/* $sql = "SELECT a.activity_Id AS `Pid`, group_concat(DISTINCT(r.name) separator ', ') AS `Regions`, group_concat(DISTINCT(d.name) separator ', ') AS `District`, ward_id AS `Ward`, a.title AS `ActivityTitle`, a.description AS `ActivityDesc`, a.startDate AS `StartDate`, a.endDate AS `EndDate`, a.pCode AS `ActivityCode`, a.activityStatus AS `ActivityStatus`, a.activityType AS `ActivityType`, a.fundingStatus AS `FundingStatus`, a.fundingType AS `FundingType`, a.currencyCode AS `FundingCurrency`, a.fundingAmount AS `FundingAmount`, s.subclassification AS `ActivitySubClassification`, a.activityThemes_id AS `ActivityTheme`, c.classification AS `ActivityClassification`, IF(o.organisation_role='Funding', group_concat(DISTINCT(org.name) separator ', '), '')  AS `FundingOrg`, IF(o.organisation_role='Accountable', group_concat(DISTINCT(org.name) separator ', '), '')  AS `AccountableOrg`, IF(o.organisation_role='Implementing', group_concat(DISTINCT(org.name) separator ', '), '')  AS `ImplementingOrg`, IF(o.organisation_role='Extending', group_concat(DISTINCT(org.name) separator ', '), '')  AS `ExtendingOrg`, group_concat(DISTINCT(orgType.type) separator ', ')  AS `OrgType`
	FROM ".ACTIVITY_TABLE." a
	JOIN activityclassifications as c ON a.classificationCode = c.classificationCode
	JOIN activitysubclassifications as s  ON  a.subclassification_id = s.subclassification_id 
	LEFT JOIN activitycoverage v ON v.activity_Id = a.activity_Id 
	JOIN regions AS r ON v.regHasc = r.regHasc
	LEFT JOIN districts AS d ON v.distHasc !='' AND v.distHasc = d.distHasc 
	LEFT JOIN activityorganisations o ON o.activity_id = a.activity_Id
	JOIN organisations as org ON o.organisation_id = org.organisation_id
	JOIN organisationtypes as orgType ON orgType.type_id = org.type_id
	GROUP BY a.pCode"; */
	
	/* $sql = "SELECT a.activity_Id AS `pid`, r.name AS `Region`, IFNULL(d.name, \"\") AS `District`, ward_id AS `Ward`, a.title AS `ActivityTitle`, a.description AS `ActivityDesc`, a.startDate, a.endDate, a.pCode, a.activityStatus, a.activityType, a.fundingStatus, a.fundingType, a.currencyCode, a.fundingAmount, s.subclassification AS `ActivitySubClassification`, a.activityThemes_id, c.classification AS `ActivityClassification`, IF(o.organisation_role='Funding', org.name, '')  AS `FundingOrg`, IF(o.organisation_role='Accountable', org.name, '')  AS `AccountableOrg`, IF(o.organisation_role='Implementing', org.name, '')  AS `ImplementingOrg`, IF(o.organisation_role='Extending', org.name, '')  AS `ExtendingOrg`, orgType.type AS orgType
	FROM ".ACTIVITY_TABLE." a
	JOIN activityclassifications as c ON a.classificationCode = c.classificationCode
	JOIN activitysubclassifications as s  ON  a.subclassification_id = s.subclassification_id 
	LEFT JOIN activitycoverage v ON v.activity_Id = a.activity_Id 
	JOIN regions AS r ON v.regHasc = r.regHasc
	LEFT JOIN districts AS d ON v.distHasc !='' AND v.distHasc = d.distHasc 
	LEFT JOIN activityorganisations o ON o.activity_id = a.activity_Id
	JOIN organisations as org ON o.organisation_id = org.organisation_id
	JOIN organisationtypes as orgType ON orgType.type_id = org.type_id"; */
	
	//echo $sql;
	
	//die();
	
	$fp = fopen('../data/data.js', 'w');	 

	fputs($fp,'var data = [');
	if($result = $conn->query($sql)){
		while ($row = $result->fetch_assoc()) {
			fputs($fp,json_encode($row). ",");
		}  		
		$result->free();
	}	
	fputs($fp, '];');
	fclose($fp);
	$conn->close();
}

 function getDataJavaScriptString(){
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	/*$sql = "SELECT id, Region, District, Ward, fOrganisation as Organisation, ActivityTitle, ActivityDesc, y.classification as ActivityClassification, ActivitySubClassification, `fOrg_type` as `orgtype` FROM $tableName AS x,  _metaactivityclassifications AS y WHERE x.ActivityClassification=y.classificationId";*/
	
	/* $sql = "SELECT a.activity_Id AS `Pid`, r.name AS `Region`, IFNULL(d.name, \"\") AS `District`, ward_id AS `Ward`, a.title AS `ActivityTitle`, a.description AS `ActivityDesc`, a.startDate AS `StartDate`, a.endDate AS `EndDate`, a.pCode AS `ActivityCode`, a.activityStatus AS `ActivityStatus`, a.activityType AS `ActivityType`, a.fundingStatus AS `FundingStatus`, a.fundingType AS `FundingType`, a.currencyCode AS `FundingCurrency`, a.fundingAmount AS `FundingAmount`, s.subclassification AS `ActivitySubClassification`, a.activityThemes_id AS `ActivityTheme`, c.classification AS `ActivityClassification`, IF(o.organisation_role='Funding', org.name, '')  AS `FundingOrg`, IF(o.organisation_role='Accountable', org.name, '')  AS `AccountableOrg`, IF(o.organisation_role='Implementing', org.name, '')  AS `ImplementingOrg`, IF(o.organisation_role='Extending', org.name, '')  AS `ExtendingOrg`, orgType.type AS OrgType
	FROM ".ACTIVITY_TABLE." a
	JOIN activityclassifications as c ON a.classificationCode = c.classificationCode
	JOIN activitysubclassifications as s  ON  a.subclassification_id = s.subclassification_id 
	LEFT JOIN activitycoverage v ON v.activity_Id = a.activity_Id 
	JOIN regions AS r ON v.regHasc = r.regHasc
	LEFT JOIN districts AS d ON v.distHasc !='' AND v.distHasc = d.distHasc 
	LEFT JOIN activityorganisations o ON o.activity_id = a.activity_Id
	JOIN organisations as org ON o.organisation_id = org.organisation_id
	JOIN organisationtypes as orgType ON orgType.type_id = org.type_id"; */
	
	$sql = "SELECT a.activity_Id AS `Pid`, group_concat(DISTINCT(r.name) separator ', ') AS `Regions`, group_concat(DISTINCT(d.name) separator ', ') AS `Districts`, ward_id AS `Ward`, a.title AS `ActivityTitle`, a.description AS `ActivityDesc`, a.startDate AS `StartDate`, a.endDate AS `EndDate`, a.pCode AS `ActivityCode`, a.activityStatus AS `ActivityStatus`, a.activityType AS `ActivityType`, a.fundingStatus AS `FundingStatus`, a.fundingType AS `FundingType`, a.currencyCode AS `FundingCurrency`, a.fundingAmount AS `FundingAmount`, s.subclassification AS `ActivitySubClassification`, a.activityThemes_id AS `ActivityTheme`, c.classification AS `ActivityClassification`, IF(o.organisation_role='Funding', group_concat(DISTINCT(org.name) separator ', '), '')  AS `FundingOrg`, IF(o.organisation_role='Accountable', group_concat(DISTINCT(org.name) separator ', '), '')  AS `AccountableOrg`, IF(o.organisation_role='Implementing', group_concat(DISTINCT(org.name) separator ', '), '')  AS `ImplementingOrg`, IF(o.organisation_role='Extending', group_concat(DISTINCT(org.name) separator ', '), '')  AS `ExtendingOrg`, group_concat(DISTINCT(orgType.type) separator ', ')  AS `OrgType`
	FROM ".ACTIVITY_TABLE." a
	JOIN activityclassifications as c ON a.classificationCode = c.classificationCode
	JOIN activitysubclassifications as s  ON  a.subclassification_id = s.subclassification_id 
	LEFT JOIN activitycoverage v ON v.activity_Id = a.activity_Id 
	JOIN regions AS r ON v.regHasc = r.regHasc
	LEFT JOIN districts AS d ON v.distHasc !='' AND v.distHasc = d.distHasc 
	LEFT JOIN activityorganisations o ON o.activity_id = a.activity_Id
	JOIN organisations as org ON o.organisation_id = org.organisation_id
	JOIN organisationtypes as orgType ON orgType.type_id = org.type_id
	GROUP BY a.pCode";
	
	/*$sql = "SELECT a.activity_Id AS `pid`, r.name AS `Region`, IFNULL(d.name, \"\") AS `District`, ward_id AS `Ward`, a.title AS `ActivityTitle`, a.description AS `ActivityDesc`, a.startDate, a.endDate, a.pCode, a.activityStatus, a.activityType, a.fundingStatus, a.fundingType, a.currencyCode, a.fundingAmount, s.subclassification AS `ActivitySubClassification`, a.activityThemes_id, c.classification AS `ActivityClassification`, IF(o.organisation_role='Funding', org.name, '')  AS `FundingOrg`, IF(o.organisation_role='Accountable', org.name, '')  AS `AccountableOrg`, IF(o.organisation_role='Implementing', org.name, '')  AS `ImplementingOrg`, IF(o.organisation_role='Extending', org.name, '')  AS `ExtendingOrg`, orgType.type AS orgType
	FROM ".ACTIVITY_TABLE." a
	JOIN activityclassifications as c ON a.classificationCode = c.classificationCode
	JOIN activitysubclassifications as s  ON  a.subclassification_id = s.subclassification_id 
	LEFT JOIN activitycoverage v ON v.activity_Id = a.activity_Id 
	JOIN regions AS r ON v.regHasc = r.regHasc
	LEFT JOIN districts AS d ON v.distHasc !='' AND v.distHasc = d.distHasc 
	LEFT JOIN activityorganisations o ON o.activity_id = a.activity_Id
	JOIN organisations as org ON o.organisation_id = org.organisation_id
	JOIN organisationtypes as orgType ON orgType.type_id = org.type_id";*/
	
	//echo $sql;
	
	//die();
	
	$fp = fopen('../data/data.json', 'w');	 

	if($result = $conn->query($sql)){
		while ($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}  		
		$result->free();
	}	
	fputs($fp,json_encode($rows));
	fclose($fp);
	$conn->close();
}



/**
 * Function to populate activity data into DB (activity data in one table, coverage data in another). Starts by  
 * storing activity data then checks if organisation (implementing or funding) entered is new, and if so, store 
 * org information into the org * table. Then populates activity coverage and activity organisations association
 * tables  
 *
 * @param  None
 * @return Nothing.
 */
/*function insertActivity ($activityTitle, $activityDesc, $activitysDate, $activityeDate, $activityClassification, $activitySubClassification, $activityTheme, $code, $iOrganization, $iOrgType, $fOrganization, $fOrgType, $activityStatus, $activityType, $fundingStatus, $fundingType, $fundingCurrency, $fundingAmount, $focalPoint, $regions, $districts, $wards)*/
function insertActivity ($activityTitle, $activityDesc, $activitysDate, $activityeDate, $activityClassification, $activitySubClassification, $activityTheme, $code, $orgs, $activityStatus, $activityType, $fundingStatus, $fundingType, $fundingCurrency, $fundingAmount, $focalPoint, $regions, $districts, $wards) {
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$activity_id = $organisation_id = '';
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	
	//Begin Transaction 
	//$conn->begin_transaction();
	
	/*$sql = "INSERT INTO " . $tableName . " (ActivityTitle, ActivityClassification, ActivitySubClassification, ActivityDesc, Region, fOrganisation, iOrganisation, Pcode, fOrg_type, iOrg_type, reg_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"; 
	
	$stmt = $conn->prepare($sql);
	foreach ($regions as $region) {
		$stmt->bind_param("siisssssss", $activityTitle, $activityClassification, $activitySubClassification, $activityDesc, $region, $fOrganization, $iOrganization, $code, $fOrgType, $iOrgType);
		if ($stmt->execute() === TRUE) {
			$message = "New record(s) created successfully.";
		} else {
			$message = "Error: <br>" . $stmt->error;
		}
	}*/
	
	/*$sql = "INSERT INTO " . $tableName . " (ActivityTitle, ActivityClassification, ActivitySubClassification, ActivityDesc, Region, fOrganisation, iOrganisation, Pcode, fOrg_type, iOrg_type, reg_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";//.str_repeat(",(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())", ($insertRegionReps-1));*/
	
	//Store activity metadata
	$activitySql = "INSERT INTO ".ACTIVITY_TABLE." (title, description, startDate, endDate, pCode, activityStatus, activityType, fundingStatus, fundingType, fundingAmount, focalPoint, classificationCode, subclassification_id, activityThemes_id, currencyCode, regDate ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
	
	$activityStmt = $conn->prepare($activitySql);
	$activityStmt->bind_param("ssssssssssssiis", $activityTitle, $activityDesc, $activitysDate, $activityeDate, 
	$code, $activityStatus, $activityType, $fundingStatus, $fundingType, $fundingAmount, $focalPoint, $activityClassification, $activitySubClassification, $activityTheme, $fundingCurrency);
	if ($activityStmt->execute() === TRUE) {
		$message = "Activity record created successfully.";
		//get inserted activity id
		$activity_id = $activityStmt->insert_id;
	} else {
		$message = "Error: <br>" . $activityStmt->error;
		$conn->rollback();
		// exit or throw an exception
		//die();
	}
	$activityStmt->close();
	
	//Store activity organisation associations
	//For each organisation entered (in cloned form elements)
	
	$orgs_len = count($orgs[Organization]);

	for ($idx = 0; $idx < $orgs_len; $idx++) {
		//echo $orgs[Organization][$idx]."->".$orgs[OrgType][$idx]."->".$orgs[OrgRole][$idx]. "<br>";
		
		$org_id = organisationExists($orgs[Organization][$idx]);
		//echo $org_id." wow ".$fOrganization;
		//die();
		if ($org_id == -1){ //Organisation doesn't exist. Insert new organisation into org table
			$orgSql = "INSERT INTO organisations (name, type_id) VALUES (?, ?)";
			$orgStmt = $conn->prepare($orgSql);
			$orgStmt->bind_param("si", $orgs[Organization][$idx], $orgs[OrgType][$idx]);
			if ($orgStmt->execute() === TRUE) {
				$message = "New Organisation Added.";
			} else {
				$message = "Error: <br>" . $orgStmt->error;
				$conn->rollback();
				// exit or throw an exception
				//exit();
			}
			$organisation_id = $orgStmt->insert_id;
			$orgStmt->close();
		}
		else
		{
			$organisation_id = $org_id;
		}
	
		$activityOrgSql = "INSERT INTO ".ACTIVITY_ORG_TABLE." (activity_id, organisation_id, organisation_role) VALUES (?, ?, ?)";
		
		$activityOrgStmt = $conn->prepare($activityOrgSql);
		$activityOrgStmt->bind_param("iis", $activity_id, $organisation_id, $orgs[OrgRole][$idx]);
		if ($activityOrgStmt->execute() === TRUE) {
			$message = "Organisation information successfully stored for Activity. <br />";
		} else {
			$message = "Error: <br>" . $activityOrgStmt->error;
			$conn->rollback();
			// exit or throw an exception
			//exit();
		}
		$activityOrgStmt->close();
	}//End of loop
	
	//Store activity coverage associations
	$activityCoverageSql = "INSERT INTO ".ACTIVITY_COVERAGE_TABLE." (activity_id, regHasc, distHasc, ward_id) VALUES (?, ?, ?, ?)";
		
	$activityCoverageStmt = $conn->prepare($activityCoverageSql);
	
	/*$insertRegionReps = count($regions);
	$insertDistrictReps = count($districts);
	$inserWardReps = count($wards);
	$totalReps = $insertRegionReps + $insertDistrictReps + $inserWardReps;*/
	
	$blank = '';
	foreach ($regions as $region) {
		$activityCoverageStmt->bind_param("isss", $activity_id, $region, $blank, $blank);
		if ($activityCoverageStmt->execute() === TRUE) {
			$message .= "Activity coverage record for $region created successfully. <br />";
		} else {
			$message = "<br>Error: <br>" . $activityCoverageStmt->error;
			$conn->rollback();
			// exit or throw an exception
			//exit();
		}
	}
		
	foreach ($districts as $district) {
		$reg = getDistrictRegion($district);
		$activityCoverageStmt->bind_param("isss", $activity_id, $reg, $district, $blank);
		if ($activityCoverageStmt->execute() === TRUE) {
			$message .= "Activity coverage record for $district created successfully. <br />";
		} else {
			$message .= "<br>Error: <br>" . $activityCoverageStmt->error;
			$conn->rollback();
			// exit or throw an exception
			//exit();
		}
	}
	
	foreach ($wards as $ward) {
		$reg = getWardRegion($ward);
		$dist = getWardDistrict($ward);
		$activityCoverageStmt->bind_param("isss", $activity_id, $reg, $dist, $ward);
		if ($activityCoverageStmt->execute() === TRUE) {
					$message .= "Activity coverage record for $ward created successfully. <br />";
		} else {
			$message .= "Error: <br>" . $activityCoverageStmt->error;
			$conn->rollback();
			// exit or throw an exception
			//exit();
		}
	}
	
	$activityCoverageStmt->close();
	
	/* $sql = "";
	foreach ($regions as $region) {
		$sql .= "INSERT INTO " . $tableName . " (ActivityTitle, ActivityClassification, ActivitySubClassification, ActivityDesc, Region, fOrganisation, iOrganisation, Pcode, `fOrg_type`, `iOrg_type`, reg_date) VALUES ('". $activityTitle."','". $activityClassification."','". $activitySubClassification."','". $activityDesc."','". $region ."', '". $fOrganization ."', '".$iOrganization ."', '". $code ."', '". $fOrgType ."', '". $iOrgType ."', NOW() );";
	}
	
	foreach ($districts as $district) {
		$sql .= "INSERT INTO " . $tableName . " (ActivityTitle, ActivityClassification, ActivitySubClassification, ActivityDesc, Region, District, fOrganisation, iOrganisation, Pcode, `fOrg_type`, `iOrg_Type`, reg_date) VALUES ('". $activityTitle."','". $activityClassification."','". $activitySubClassification."','". $activityDesc."','". getDistrictRegion($district) ."','". $district ."', '". $fOrganization ."', '". $iOrganization ."', '". $code ."', '". $fOrgType ."','". $iOrgType  ."', NOW() );";
	}
	
	//This needs updating to insert region and district with each data;
	foreach ($wards as $ward) {
		$sql .= "INSERT INTO " . $tableName . " (ActivityTitle, ActivityClassification, ActivitySubClassification, ActivityDesc, Ward, fOrganisation, iOrganisation, Pcode, `fOrg_type`, `iOrg_type`, reg_date) VALUES ('". $activityTitle."','". $activityClassification."','". $activitySubClassification."','". $activityDesc."','". $ward ."', '". $fOrganization ."', '". $iOrganization ."', '". $code ."', '". $fOrgType ."', '". $iOrgType  ."', NOW() );";
	} */
	/*echo $sql;
	die ();
	*/
	
	/*if ($conn->multi_query($sql) === TRUE) {
		$message = "New record(s) created successfully.";
	} else {
		$message = "Error: <br>" . $conn->error;
	}
	*/
	
	/* commit transaction */
	//$conn->commit();
	
	$conn->close();
	echo $message;
}

/**
 * Function to fetch regions from DB and create an HTML dropdown select 
 *
 * @param  None
 * @return Regions as dropdown options.
 */
function getRegionsDropdownOptions(){
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	$sql = "SELECT regHasc, name FROM ".REGION_TABLE." ORDER BY name";	
	$regoptions =  '';
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$regoptions .= '<option value="'.$row["regHasc"].'">'.$row["name"].'</option>';
		}	  
		$result->free();
	}
	$conn->close();
	return $regoptions;
}

/**
 * Function to fetch districts from DB and create an HTML dropdown select 
 *
 * @param  regname: Name of region(s) whose districts to return
 * @return districts as dropdown options.
 */
function getDistrictDropdownOptions($regname){
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	
	//where clause to be used in the SQL query
	$where_clause = '(';
	foreach ($regname as $region) {
		$where_clause .= "r.regHasc LIKE '$region' OR " ;
	}
	$where_clause = rtrim($where_clause, " OR "); //remove the last OR from where clause
	$where_clause .= ')';

	$sql = "SELECT d.distHasc, d.name FROM ".DISTRICT_TABLE." AS d, ".REGION_TABLE." AS r WHERE $where_clause AND d.regHasc = r.regHasc ORDER BY distHasc"; //Ordering to bundle districts from same region

	$distoptions = '';
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$distoptions .= '<option value="'.$row["distHasc"].'">'.$row["name"].'</option>';
		}	  
		$result->free();
	}
	$conn->close();
	return $distoptions;
}


/**
 * Function to fetch wards from DB and create an HTML dropdown select 
 *
 * @param  distname: Name of district whose wards to return
 * @return wards as dropdown options.
 */
function getWardDropdownOptions($distname){ 
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	$sql = "SELECT w.wardcode, w.name FROM ".WARD_TABLE." AS w, ".DISTRICT_TABLE." AS d WHERE d.name LIKE '$distname' AND d.distcode = w.distcode ORDER BY w.name";
	
	
	$wardoptions = '';
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$wardoptions .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
		}	  
		$result->free();
	}
	$conn->close();
	return $wardoptions;
}


/**
 * Function to lookup and return region from DB, given district name 
 * Used when loading activity data into DB, given district data with the old schema. 
 * @param  distname: Name of district whose region to return
 * @return region.
 */
function getDistrictRegion($distHasc){
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	
	$sql = "SELECT d.regHasc FROM ".DISTRICT_TABLE." AS d WHERE d.distHasc='$distHasc' LIMIT 1";
	$region = '';
	
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$region .= $row["regHasc"];
		}	  
		$result->free();
	}
	$conn->close();
	return $region;
}

function getWardRegion($ward) {
	return 'TZ.XX';
}

function getWardDistrict($ward){
	return 'TZ.XX.XX';
}
/**
 * Function to get activity classifications from DB, and create an HTML dropdown select 
 *  
 * @param None
 * @return Classifications as dropdown options.
 */
function getClassificationDropdownOptions(){
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	$sql = "SELECT classification_id, classificationCode, classification FROM activityclassifications AS x ORDER BY classification";
	$classificationoptions = '<option selected disabled value="">Select Intervention Area</option>';
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$classificationoptions .= '<option value="'.$row["classificationCode"].'">'.$row["classification"].'</option>';
		}	  
		$result->free();
	}
	$conn->close();
	return '<option value="0">Juma</option>';
	return $classificationoptions;
}

/**
 * Function to lookup activity classifications code from DB. No longer needed
 *  
 * @param classification
 * @return Classifications code as a string.
 */
function getClassificationCode($classification){
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	$sql = "SELECT classificationCode FROM activityclassifications WHERE classification_Id='$classification'";
	$classificationCode = "";
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$classificationCode = $row["classificationCode"];
		}	  
		$result->free();
	}
	$conn->close();
	return $classificationCode;
}

/**
 * Function to get activity subclassifications from DB, and create an HTML dropdown select   
 *  
 * @param classification
 * @return Sub Classifications as dropdown options.
 */
function getSubClassificationDropdownOptions($classification){
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	$sql = "SELECT x.subclassification_id, x.subclassification FROM activitysubclassifications AS x, activityclassifications AS y WHERE x.classificationCode = y.classificationCode AND y.classificationCode LIKE '$classification' ORDER BY subclassification";
	//echo $sql;
	$subclassificationoptions = '<option selected disabled value="">Select Activity Subcategory</option>';
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$subclassificationoptions .= '<option value="'.$row["subclassification_id"].'">'.$row["subclassification"].'</option>';
		}	  
		$result->free();
	}
	$conn->close();
	return $subclassificationoptions;
}

/**
 * Function to get organisation types (classifications) from DB, and create an HTML dropdown select 
 *  
 * @param None
 * @return Organisation Classifications as dropdown options.
 */
function getOrganisationTypeDropdownOptions() {
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	$sql = "SELECT type_id, type FROM organisationtypes AS x ORDER BY type";
	$orgtypeoptions = '<option selected disabled value="">Select Organisation Type</option>';
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$orgtypeoptions .= '<option value="'.$row["type_id"].'">'.$row["type"].'</option>';
		}	  
		$result->free();
	}
	$conn->close();
	return $orgtypeoptions;
}

function getOrganisationsJSON ($search) {
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	$retArr = array();
	$sql = "SELECT `organisation_id` AS id, `name` AS value, `type_id` AS type FROM organisations WHERE `name` LIKE '$search%'";
	
	if($result = $conn->query($sql)){
		while ($row = $result->fetch_assoc()) {
			$retArr[] = $row;
		}
		$result->free();
	}	
	$conn->close();
	return json_encode($retArr);
}


/**
 * Function to get currencies from DB, and create an HTML dropdown select 
 *  
 * @param None
 * @return Currencies as dropdown options.
 */
function getCurrencyDropdownOptions() {
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	$sql = "SELECT currencyCode, currency FROM fundingcurrencies AS x ORDER BY currency";
	$currencyoptions = '<option selected disabled value="">Select Currency</option>';
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$currencyoptions .= '<option value="'.$row["currencyCode"].'">'.$row["currency"].'</option>';
		}	  
		$result->free();
	}
	$conn->close();
	return $currencyoptions;
}

/**
 * Function to get Activity themes from DB, and create an HTML dropdown select 
 *  
 * @param None
 * @return Themes as dropdown options.
 */
function getThemeDropdownOptions() {
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	$sql = "SELECT activityThemes_id, theme FROM activitythemes AS x ORDER BY theme";
	$themeoptions = '<option selected disabled value="">Select Theme</option>';
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$themeoptions .= '<option value="'.$row["activityThemes_id"].'">'.$row["theme"].'</option>';
		}	  
		$result->free();
	}
	$conn->close();
	return $themeoptions;
}

/**
 * Function to check if entered organisations already exists in database, and return its id if so. 
 * Otherwise, return -1
 *  
 * @param orgname: name of the organisarion
 * @return -1 or Organisation_id.
 */
function organisationExists($orgname)
{
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: ". $conn->connect_error);	
	}
	$org_id = -1;
	$sql = "SELECT organisation_id FROM organisations AS x WHERE x.name='$orgname' LIMIT 1";
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$org_id = $row["organisation_id"];
		}	  
		$result->free();
	}

	$conn->close();
	return $org_id;
}

function fetchExchangeRates() {
	$access_key = '52680fb527f724ef75847d0d17eae3a6';

	// Initialize CURL:
	$ch = curl_init('http://apilayer.net/api/live?access_key='.$access_key.'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Store the data:
	$json = curl_exec($ch);
	curl_close($ch);
	
	// Decode JSON response:
	$results = json_decode($json, true);
	
	// Access the exchange rate values, e.g. GBP:
	//echo $results['quotes']['USDGBP'];
	
	//write to file if successful
	if($results['success']){
		$fp = fopen('../data/fxrates.json', 'w');	 
		fputs($fp, $json);
		fclose($fp);
	}
}
function exportAllActivities () {
	
	global $servername, $username, $password, $dbname, $tableName;
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	// Check connection
	if ($conn->connect_error) {
		echo "Connection failed <br>";
		die("Connection failed: " . $conn->connect_error);
		
	}
	$sql = "SELECT ActivityTitle, ActivityClassification, ActivitySubClassification, ActivityDesc, Region, District, Ward, Organisation, `Org_type`, reg_date FROM ". $tableName;
	if($result = $conn->query($sql)){
		$fp = fopen('exports/output.csv', 'w');	
		while ($row = $result->fetch_assoc()) {
			fputcsv($fp,$row);
		}
		fclose($fp);  
		$result->free();
	}
	$conn->close();
	return "<a href='exports/output.csv'>Get it Here</a>";
}

/*
	Source: http://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string/13733588#13733588
*/

function crypto_rand_secure($min, $max) {
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}


/*
	Function to generate unique activity codes
*/
function getToken($length) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet) - 1;
    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max)];
		//$token .= $codeAlphabet[random_int(0, $max)]; //works with php7 and does not use function crypto_rand_secure abive
    }
    return $token;
}

?>