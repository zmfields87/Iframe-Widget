<? 

$state = $_REQUEST['state'];
$city = $_REQUEST['city'];
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
	FROM EDU_LOCAL 
	WHERE PRIM_STATE='" . mysql_real_escape_string($state) . "' 
	AND AREA_TITLE='" . mysql_real_escape_string($city) . "' 
	AND OCC_TITLE='" . mysql_real_escape_string($jobTitle) . "'";
	
	

if (!($getPosts = mysql_query ($query, $connection))) {
	showerror();
}


		while($rows = mysql_fetch_array($getPosts)) 
		{		
			
			echo "<h2>" . $jobTitle . " <i>in</i> " . $city . ", " . $state . "</h2>";
			
			echo "<ul class='lists'>
				
				<li class='even'>
					<span class='area cats'>&nbsp;</span>
					
					<span class='total cats'>Total <br />
						Employment</span>
					<span class='per cats'>Jobs<br />
						Per 1000</span>
					<span class='average cats'>Average<br />
						Salary</span>
					<span class='top10 cats'>Salary, Top<br />
						10 Percent</span>
				</li>";
					
			echo "<li class='odd'>";	
				
			echo "<span class='area'>Local</span>";	
				
			echo "<span class='total'>" . $rows['TOT_EMP'] . " jobs" . "</span>";	
			
			echo "<span class='per'>" . $rows['JOBS_1000'] . "</span>";
			
			echo "<span class='average'>" . "$" . $rows['A_MEAN'] . "</span>";
			
			echo "<span class='top10'>" . "$" . $rows['A_PCT90'] . "</span>";
			
			echo "</li>";
				
			echo "</ul>";

	}


	
?>