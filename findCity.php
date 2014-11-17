<? 

$state=$_REQUEST['state'];

//$state = mysql_real_escape_string($state);

function showerror() {
	die("Error " . mysql_errno() . " : " . mysql_error());
}

// database connection
global $wpdb;
$connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error()); 
mysql_select_db(DB_NAME, $connection) or die(mysql_error());

$query = "SELECT DISTINCT AREA_TITLE 
		FROM EDU_LOCAL 
		WHERE PRIM_STATE='".mysql_real_escape_string($state)."'
		ORDER BY AREA_TITLE ASC";

if (!($getPosts = mysql_query ($query, $connection))) {
	showerror();
}
?>

<option value="">Select City</option>
<? while($rows = mysql_fetch_array($getPosts)) { ?>
<option value='<?=$rows['AREA_TITLE']?>'><?=$rows['AREA_TITLE']?></option>
<? } ?>

