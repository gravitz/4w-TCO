<?php     

/*
 * Based on EditableGrid code (http://editablegrid.net)
 * Extended in accordance to http://editablegrid.net/license
 * Used for the Activity Edit scripts 
 */
                              

/*
 * Script loads data from the database and returns it to the js
 *
 */
       
require_once('config.inc.php');      
require_once('editableGrid.php');            

/*
 * This function transforms a mysqli_result object into an array, 
 * used to generate dropdown options values for Region, District, Wards, 
 * ActivityClassification, and ActivitySubClassification columns.
*/
function fetch_pairs($mysqli,$query){
	if (!($res = $mysqli->query($query)))return FALSE;
	$rows = array();
	while ($row = $res->fetch_assoc()) {
		$first = true;
		$key = $value = null;
		foreach ($row as $val) {
			if ($first) { $key = $val; $first = false; }
			else { $value = $val; break; } 
		}
		$rows[$key] = $value;
	}
	return $rows;
}


// Open a DB connection
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($servername, $username, $password, $dbname); 
                    
// EditableGrid object
$grid = new EditableGrid();

//$grid->setPaginator($pageCount, $totalRowCount, $unfilteredRowCount, $customAttributes = NULL)

/* 
*  Add grid columns. The first argument of addColumn is the name of the field in the databse. 
*  The second argument is the label that will be displayed in the header
*/
$grid->addColumn('id', 'ID', 'integer', NULL, false, NULL, false, true); 
//addColumn($name, $label, $type, $values = NULL, $editable = true, $field = NULL, $bar = true, $hidden = false)
$grid->addColumn('ActivityTitle', '    Title    ', 'string'); 
$grid->addColumn('ActivityClassification', '    Classification    ', 'string', fetch_pairs($mysqli,'SELECT classificationId, classification FROM _metaactivityclassifications'),true);
$grid->addColumn('ActivitySubClassification', '    Subclassification   ', 'string', fetch_pairs($mysqli,'SELECT subClassificationId, subClassification FROM _metaactivitysubclassifications'),true);
$grid->addColumn('Region', '    Region    ', 'string', fetch_pairs($mysqli,'SELECT regname as optval, regname as opttext FROM regions'),true ); 
$grid->addColumn('District', '    District    ', 'string', fetch_pairs($mysqli,'SELECT distname as optval, distname as opttext FROM districts'),true );
$grid->addColumn('Ward', '    Ward    ', 'string');	
$grid->addColumn('fOrganisation', '    Funding Org    ', 'string');	
$grid->addColumn('fOrg_type', '    Fund. Org Type    ', 'string', array(0 => 'UN', 1 => 'CSO', 2 => 'NGO', 3 => 'Gov', 4 => 'Bilateral Agency'));	
$grid->addColumn('iOrganisation', '    Implementing Organisation    ', 'string');	
$grid->addColumn('iOrg_type', '    Imp. Org Type    ', 'string', array(0 => 'UN', 1 => 'CSO', 2 => 'NGO', 3 => 'Gov', 4 => 'Bilateral Agency'));
$grid->addColumn('ActivityDesc', '    Activity Description    ', 'string');	
$grid->addColumn('action', '   Action   ', 'html', NULL, false, 'id'); 

$result = $mysqli->query('SELECT * FROM '.$tableName );
$mysqli->close();

// send data to the browser
$grid->renderJSON($result);

