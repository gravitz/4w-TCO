<?php
	require ("functions.inc.php");
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
		echo "connection failed <br>";
		die("Connection failed: " . $conn->connect_error);
		
	}
	$rpt = $_POST['rpt'];
	switch ($rpt) {
		case "abr":
        //Activity count by Region by ActivityClassification
		$sql = "SELECT Region, ActivityClassification, count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, ActivityClassification";
        break;
    case "abd":
        //Activity count by District by ActivityClassification
		$sql = "SELECT District, ActivityClassification, count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, District, ActivityClassification";
        break;
    case "abor":
        //Activity count by Organisation by Region
		$sql = "SELECT Organisation, Region,  count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, Organisation ORDER BY count(id) desc";
        break;
	case "abod":
		//Activity count by Organisation by District
		$sql = "SELECT Organisation, Region, District,  count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region,  Organisation ORDER BY count(id) desc";
		break;
	case "ars":
		//Activity count by Region by ActivitySubClassification
		$sql = "SELECT Region, ActivitySubClassification, count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, ActivitySubClassification";
		break;
	case "ads":
		//Activity count by District by ActivitySubClassification
		$sql = "SELECT Region, District, ActivitySubClassification, count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, District, ActivitySubClassification";
		break;
		
		//$sql = "SELECT Region, ActivityClassification, count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, ActivityClassification";
		
		//$sql = "SELECT Region, ActivityClassification, count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, ActivityClassification";
		
		//$sql = "SELECT Region, ActivityClassification, count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, ActivityClassification";
		
		//$sql = "SELECT Region, ActivityClassification, count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, ActivityClassification";
		
		//$sql = "SELECT Region, ActivityClassification, count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, ActivityClassification";
    default:
        $sql = "SELECT Region, ActivityClassification, count(id) as `# of Activities` FROM ". $tableName . " GROUP BY  Region, ActivityClassification";
	}
	
	
	if($result = $conn->query($sql)){
		$recordsTotal = $result->num_rows;
		
		$output = array(
			"draw" => 1,
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsTotal,
			"columns" => array(),
			"data" => array()
		);
		
		while ($fieldinfo=$result->fetch_field()) {
			$output['columns'][] = array("title"=>$fieldinfo->name);
		}
		//var_dump($output['columns']);
		
		while ($row = $result->fetch_array()) {
			 $output['data'][] = $row;
		} 		
		$result->free();
	}
	$conn->close();
	

	
	echo json_encode( $output );
	//echo $output;
?>