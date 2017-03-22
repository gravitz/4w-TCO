<?php
require ("functions.inc.php");
	//echo 'hello world!';
	//echo "<script type='text/javascript'>alert('".$_POST['class']."');</script>";
	echo getSubClassificationDropdownOptions($_POST['class']);
?>
