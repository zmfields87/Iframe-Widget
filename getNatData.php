<?php
	
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
	FROM EDU_NAT 
	WHERE OCC_TITLE='" . $jobTitle . "'";

if (!($getPosts = mysql_query ($query, $connection))) {
	showerror();
}
	

		
		while($rows = mysql_fetch_array($getPosts))
		{
			
			echo "<ul class='lists'>";
			
			echo "<li class='odd'>";
			
			echo "<span class='area'>" . "USA" . "</span>";
			
			echo "<span class='total'>" . $rows['TOT_EMP'] . " jobs" . "</span>";
			
			echo "<span class='per'>" . $rows['JOBS_1000'] . "</span>";

			echo "<span class='average'>" . "$" . $rows['A_MEAN'] . "</span>";

			echo "<span class='top10'>" . "$" . $rows['A_PCT90'] . "</span>";
			
			echo "</li>";

			echo "</ul>";
	
		}
	
	
?>