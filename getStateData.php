<?php
	
$state = $_REQUEST['state'];
$jobTitle = $_REQUEST['job'];
	
	
	
function showerror() {
	die("Error " . mysql_errno() . " : " . mysql_error());
}

// database connection
global $wpdb;
$connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error()); 
mysql_select_db(DB_NAME, $connection) or die(mysql_error());

$query = "SELECT DISTINCT
	TOT_EMP,  
	JOBS_1000,
	A_MEAN,
	A_PCT90
	FROM EDU_STATE 
	WHERE PRIM_STATE='" . $state . "' 
	AND OCC_TITLE='" . $jobTitle . "'";

if (!($getPosts = mysql_query ($query, $connection))) {
	showerror();
}
		
		while($rows = mysql_fetch_array($getPosts))
		{
			
			echo "<ul class='lists'>";
			
			echo "<li class='even'>";
			
			echo "<span class='area w2'>" . $state . "</span>";
			
			echo "<span class='total b2'>" . $rows['TOT_EMP'] . " jobs" . "</span>";

			echo "<span class='per w2'>" . $rows['JOBS_1000'] . "</span>";

			echo "<span class='average b2'>" . "$" . $rows['A_MEAN'] . "</span>";

			echo "<span class='top10 w2'>" . "$" . $rows['A_PCT90'] . "</span>";

			echo "</li>";
			
			echo "</ul>";
	
		}
	
	
?>