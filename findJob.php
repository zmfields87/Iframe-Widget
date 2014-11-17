<?php 


$state = $_REQUEST['state'];
$city = $_REQUEST['city'];

function showerror() {
	die("Error " . mysql_errno() . " : " . mysql_error());
}

// database connection
global $wpdb;
$connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error()); 
mysql_select_db(DB_NAME, $connection) or die(mysql_error());

$query = "SELECT DISTINCT 
	OCC_TITLE 
	FROM EDU_LOCAL 
	WHERE PRIM_STATE='".mysql_real_escape_string($state)."' 
	AND AREA_TITLE='".mysql_real_escape_string($city)."' ORDER BY OCC_TITLE ASC";

if (!($getPosts = mysql_query ($query, $connection))) {
	showerror();
}

?>

<option value="">Select a Job</option>
<? while($rows = mysql_fetch_array($getPosts)) {  ?>
<option value='<?=$rows['OCC_TITLE']?>'><?=$rows['OCC_TITLE']?></option>
<? }  ?>		  

