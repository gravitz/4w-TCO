<?php
require_once ("config.inc.php");

function getDataJavaScriptString(){
	//echo "Called <br>";
	global $servername, $username, $password, $dbname;
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		echo "connection failed <br>";
		die("Connection failed: " . $conn->connect_error);
		
	}
	$sql = "SELECT Country, Region, District, Ward, Organisation, Activity, Pcode, `Org_type` FROM " . $tableName;
	
	$jsonString = '<script type="text/javascript"> var data = [';
	if($result = $conn->query($sql)){			
		while ($row = $result->fetch_assoc()) {
			$jsonString .= json_encode($row) . ",";
		}
		$jsonString .= ']; </script>';	  
		$result->free();
	}
	$conn->close();
	return $jsonString;
}


function exportAllActivities () {
	//echo 'Calleddddddd<br>';
	global $servername, $username, $password, $dbname;
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		echo "connection failed <br>";
		die("Connection failed: " . $conn->connect_error);
		
	}
	$sql = "SELECT Country, Region, District, Ward, Organisation, Activity, Pcode, `Org_type` FROM Activity" //. $tableName;
	if($result = $conn->query($sql)){
		$fp = fopen('output.csv', 'w');	
		while ($row = $result->fetch_assoc()) {
			fputcsv($fp,$row);
		}
		fclose($fp);  
		$result->free();
	}
	$conn->close();
	return $sql." <a href='output.csv'>Get your Export Here</a>";
}

?>