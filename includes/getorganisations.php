<?php
require ("functions.inc.php");
	//echo 'hello world '.$_POST['reg'].' -- '.$_REQUEST['reg'] ;
	echo getOrganisationsJSON($_REQUEST['org']);
?>
