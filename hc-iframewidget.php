<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 'on');

$headReferer = $_SERVER['HTTP_REFERER'];

$Referer = parse_url($headReferer);

$cleanReferer = $Referer['host'];

$todayDate = date("Y-m-d");

$connection = mysql_connect('127.0.0.1', 'censored', 'censored') or die(mysql_error()); 
mysql_select_db('censored', $connection) or die(mysql_error());


//Check referring domain, authenticate and serve appropriate response

$query = "SELECT * 
		FROM PubTable
		WHERE Hostname = '$cleanReferer'";
		
		if (!($stmt = mysql_query($query, $connection))) {
			showerror();
		}	
		
		$row0 = mysql_fetch_array($stmt);
		
		if ($cleanReferer !== $row0['Hostname'])
		{
			echo "Sorry, this domain is not authorized to use this widget.";
			exit;
		}
		
	
	
		
		$getExpDate = "SELECT PayEnd FROM PubTable WHERE Hostname = '$cleanReferer'";
		
		if (!($stmt3 = mysql_query($getExpDate, $connection))) {
			showerror();
		}	
		
		$expDateFetch = mysql_fetch_array($stmt3);
		
		$expDate = $expDateFetch[0];
		
		$today = strtotime($todayDate);
		
		$expiration_date = strtotime($expDate);
		
		
		
		
		if ($expiration_date < $today)
		{
			
			$sendTo = "zander.fields@hotchalk.com";
			$sendSubject = "Expired Widget";
			$sendMessage = "The widget for domain:\n".$cleanReferer."\nhas expired, please notify them.";
			$sendFrom = "From: Hotchalk_Widgets";
			
			
			echo "Sorry, the owner of this domain has not renewed their subscription to this product.";
			mail($sendTo, $sendSubject, $sendMessage, $sendFrom);
			exit;
		}

		
		//Check referring domain and serve appropriate stylesheet
	
		$styleCheck = "SELECT StyleCSS FROM PubTable WHERE Hostname = '$cleanReferer'";
		
		if (!($stmt4 = mysql_query($styleCheck, $connection))) {
			showerror();
		}
		
		$fetchStyle = mysql_fetch_array($stmt4);
		
		if (is_null($cleanReferer))
		{
			$style = "hc-iframewidget-01.css";
		}
		else
		{	
			$style = $fetchStyle[0];
		}
		
		
	
	


$insertDate = "INSERT INTO PubRecords (Hostname, Date_Recorded)
				VALUES ('$cleanReferer', '$todayDate')";

if (!($stmt2 = mysql_query($insertDate, $connection))) {
	showerror();
}	


/*
Plugin Name: HotChalk - IframeWidget
Plugin URI: http://www.hotchalk.com/
Version: 09/08/2014 (replace with date of your latest revision)
Author: Zander Fields
Description: This is the Iframe Version of the Salary Widget

This plugin will allow for implementation of the Iframe version of the salary widget tool. It will act as the placeholder for testing the new implementation.


/* Follow the steps below to create a new plugin from this template */

/* In this file:
/* Replace Plugin with Nameofyourplugin
/* Replace HC_PLUGIN_ with HC_NAMEOFYOURPLUGIN_ throughout */
/* Replace hc-plugin with hc-nameofyourplugin throughout */
/* Replace HC_Plugin with HC_Nameofyourplugin throughout */
/* Replace hcPlugin with hcNameofyourplugin throughout */
/* Replace hc_plugin with hc_nameofyourplugin throughout */

/* 
	Change the directory name from hc-plugin to hc-nameofyourplugin.
	Change the file name hc-plugin.php to hc-nameofyourplugin.php
	Change the file name hc-plugin.css to hc-nameofyourplugin.css
*/

define ('HC_IFRAMEWIDGET_VERSION', '06/09/2014');
define ('HC_IFRAMEWIDGET_PLUGIN_URL', plugin_dir_url(__FILE__));
define ('HC_IFRAMEWIDGET_PLUGIN_DIR', plugin_dir_path(__FILE__));
define ('HC_IFRAMEWIDGET_SETTINGS_LINK', '<a href="'.home_url().'/wp-admin/admin.php?page=hc-iframewidget">Settings</a>');

class HC_IframeWidget {
	/* define any localized variables here */

	private $myPrivateVars;
	private $opt; /* points to any options defined and used in the admin */

	function __construct() {
		/* Best practice is to save all your settings in 1 array */
		/*   Get this array once and reference throughout plugin */

		$this->opt = get_option('hcIframeWidget');
		
		/* You can do things once here when activating / deactivating, such as creating
		     database tables and deleting them. */

		register_activation_hook(__FILE__,array($this,'activate'));
		register_deactivation_hook( __FILE__,array($this,'deactivate'));
		
		/* Enqueue any scripts needed on the front-end */

		add_action('wp_enqueue_scripts', array($this,'frontScriptEnqueue'));
		
		/* Create all the necessary administration menus. */
		/* Also enqueues scripts and styles used only in the admin */

		add_action('admin_menu', array($this,'adminMenu'));
		
		/* adminInit handles all of the administartion settings  */ 

		add_action('admin_init', array($this,'adminInit'));
		
		// if you need anything in the footer, define it here
		add_action('wp_footer', array($this,'footerScript'));
		$ga_plugin = plugin_basename(__FILE__); 
		
		// this code creates the settings link on the plugins page
		add_filter("plugin_action_links_$ga_plugin", array($this,'pluginSettingsLink'));
		
		// create any shortcodes needed
		add_shortcode( 'hc_iframewidget', array($this,'shortcode'));
    }
	
	// Enqueue any front-end scripts here
	function frontScriptEnqueue() {
		//wp_enqueue_script('swaplogo',HC_PLUGIN_PLUGIN_URL.'js/swaplogo.js',false,null);
		global $style;
		wp_enqueue_style('my_style',HC_IFRAMEWIDGET_PLUGIN_URL.$style);
	}

    /* these admin styles are only loaded when the admin settings page is displayed */
	
	function adminEnqueue() {
		// wp_enqueue_style('hc-plugin-style',HC_PLUGIN_PLUGIN_URL.'css/hc_plugin.css');
	}
	
	// Enqueue any scripts needed in the admin here 
	function adminEnqueueScripts() {
		// wp_enqueue_script('jquery-ui-sortable');
		// wp_enqueue_script('jquery-ui-datepicker');
	}
	
	// code that gets run on plugin activation.
	// create any needed database tables or similar here
	function activate() {
	}

	// code the gets run on plugin de-activation
	// remove any database tables or other settings here
	function deactivate() {
	}
	
	// Setup the admin menu here.  Also enqueues backend styles/scripts
	// images/icon.png is the icon that appears on the admin menu
	function adminMenu() {
		add_menu_page('HotChalk','HotChalk','manage_options','hc_top_menu','',plugin_dir_url(__FILE__).'/images/icon.png', 88.8 ); 
		
		$page = add_submenu_page('hc_top_menu','IframeWidget','IframeWidget','manage_options','hc-iframewidget',array($this,'adminOptionsPage'));
		
		remove_submenu_page('hc_top_menu','hc_top_menu'); // remove extra top level menu item if there
		
		 /* Using registered $page handle to hook stylesheet loading */
	
		add_action( 'admin_print_styles-' . $page, array($this,'adminEnqueue'));
		add_action( 'admin_print_scripts-' . $page, array($this,'adminEnqueueScripts'));
	}
	
	// settings link on plugins page
	function pluginSettingsLink($links) { 
	  $settings_link = HC_IFRAMEWIDGET_SETTINGS_LINK; 
	  array_unshift($links, $settings_link); 
	  return $links; 
	}
	
	/* Define the settings for your plugin here */ 
	/* Create as many sections as needed */ 

	function adminInit(){
		register_setting( 'hcIframeWidgetOptions', 'hcIframeWidgetOptions', array($this,'optionsValidate'));
		add_settings_section('hcIframeWidgetSection1', 'Plugin Settings Section 1', array($this,'sectionText1'), 'hc-iframewidget');
		add_settings_field('hcIframeWidgetSection1', '', array($this,'section1settings'), 'hc-iframewidget', 'hcIframeWidgetSection1');
	}
	

		
	// You can validate input here on saving
	// This gets called when click 'Save Changes' from the admin settings.
	// Process input and then return it
	function optionsValidate($input) {
		return $input;
	}
	
	// Settings section description
	function sectionText1() {
		?>
        <p>This plugin will allow for implementation of the salary widget tool. The salary widget tool allows users to select a local area, state, and job title, and be returned a series of employment statistics based on their selections. All returned data is provided by data downloaded from the Bureau of Labor Statistics.</p>
        <?php
	}
	
	// Example setting in admin
	/*
	function section1settings() {
		echo '<div class="section1">';
	    echo '<label>Setting 1 </label><input type="text" name="hcSalaryWidgetOptions[setting1]" value="'.$this->opt['setting1'].'" />';
		echo '</div>';
	}
	*/
	// Example shortcode
	// [hc_plugin parm1="parm1_setting"]

	function shortcode( ) {
		
		ob_start(); ?>
	
		<script language="javascript" type="text/javascript">

			function getXMLHTTP() { //function to return the xml http object
				var xmlhttp=false;	
				try{
					xmlhttp=new XMLHttpRequest();
				}
				catch(e)	{		
					try{			
						xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch(e){
						try{
						xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
						}
						catch(e1){
							xmlhttp=false;
						}
					}
				}
		 	
				return xmlhttp;
		    }
	
				function getCity(stateId) 
			{		
				//Clean out data section on new select
					document.getElementById('jobdatadiv').innerHTML="";
					document.getElementById('MSAdatadiv').innerHTML="";
					document.getElementById('NMSAdatadiv').innerHTML="";
					document.getElementById('statedatadiv').innerHTML="";
					document.getElementById('natdatadiv').innerHTML="";
				
					var strURL="<?php echo HC_IFRAMEWIDGET_PLUGIN_URL ?>findCity.php?state="+stateId;
					var req = getXMLHTTP();
			
					if (req) {
				
						req.onreadystatechange = function() {
							if (req.readyState == 4) {
								// only if "OK"
								if (req.status == 200) {	
													
									document.getElementById('citydiv').innerHTML=req.responseText;											
								} else {
									alert("There was a problem while using XMLHTTP:\n" + req.statusText);
								}
							}				
						}			
						req.open("GET", strURL, true);
						req.send(null);
				
				}
				// Reset 'city' and 'job' selects on new select in order to clean out data section
				document.getElementById('citydiv').value="";
				document.getElementById('jobdiv').value="";
			
		
			}
				function getJob() 
					{		
						//Clean out data section on new select
						document.getElementById('jobdatadiv').innerHTML="";
						document.getElementById('MSAdatadiv').innerHTML="";
						document.getElementById('NMSAdatadiv').innerHTML="";
						document.getElementById('statedatadiv').innerHTML="";
						document.getElementById('natdatadiv').innerHTML="";
						
						var stateId = document.getElementById('selectstate').value;
						var cityId = document.getElementById('citydiv').value;
						var strURL="<?php echo HC_IFRAMEWIDGET_PLUGIN_URL ?>findJob.php?state="+stateId+"&city="+cityId;
						var req = getXMLHTTP();
				
						if (req) {
					
							req.onreadystatechange = function() {
								if (req.readyState == 4) {
									// only if "OK"
									if (req.status == 200) {						
										document.getElementById('jobdiv').innerHTML=req.responseText;							
									} else {
										alert("There was a problem while using XMLHTTP:\n" + req.statusText);
									}
								}				
							}			
							req.open("GET", strURL, true);
							req.send(null);
						}
						// Reset 'job' select on new select in order to clean out data section
						document.getElementById('jobdiv').value="";
					}
			function getJobData() 
			{	
				//Clean out data section on new select
				document.getElementById('jobdatadiv').innerHTML="";
				document.getElementById('MSAdatadiv').innerHTML="";
				document.getElementById('NMSAdatadiv').innerHTML="";
				document.getElementById('statedatadiv').innerHTML="";
				document.getElementById('natdatadiv').innerHTML="";
				var stateId = document.getElementById('selectstate').value;
				var cityId = document.getElementById('citydiv').value;
				var jobId = document.getElementById('jobdiv').value;
				var strURL="<?php echo HC_IFRAMEWIDGET_PLUGIN_URL ?>getJobData.php?state="+stateId+"&city="+cityId+"&job="+jobId;
				var req = getXMLHTTP();
	
				if (req) 
				{
		
					req.onreadystatechange = function() {
						if (req.readyState == 4) {
							// only if "OK"
							if (req.status == 200) {						
								document.getElementById('jobdatadiv').innerHTML=req.responseText;						
							} else {
								alert("There was a problem while using XMLHTTP:\n" + req.statusText);
							}
						}				
					}			
					req.open("GET", strURL, true);
					req.send(null);
				}
		
			}
			function getStateData(stateId,jobId) 
			{	
				//Clean out data section on new select
				document.getElementById('jobdatadiv').innerHTML="";
				document.getElementById('MSAdatadiv').innerHTML="";
				document.getElementById('NMSAdatadiv').innerHTML="";
				document.getElementById('statedatadiv').innerHTML="";
				document.getElementById('natdatadiv').innerHTML="";
				var stateId = document.getElementById('selectstate').value;
				var jobId = document.getElementById('jobdiv').value;
				var strURL="<?php echo HC_IFRAMEWIDGET_PLUGIN_URL ?>getStateData.php?state="+stateId+"&job="+jobId;
				var req = getXMLHTTP();
	
				if (req) 
				{
		
					req.onreadystatechange = function() {
						if (req.readyState == 4) {
							// only if "OK"
							if (req.status == 200) {						
								document.getElementById('statedatadiv').innerHTML=req.responseText;						
							} else {
								alert("There was a problem while using XMLHTTP:\n" + req.statusText);
							}
						}				
					}			
					req.open("GET", strURL, true);
					req.send(null);
				}
		
			}
			function getNatData(jobId) 
			{	
				//Clean out data section on new select
				document.getElementById('jobdatadiv').innerHTML="";
				document.getElementById('MSAdatadiv').innerHTML="";
				document.getElementById('NMSAdatadiv').innerHTML="";
				document.getElementById('statedatadiv').innerHTML="";
				document.getElementById('natdatadiv').innerHTML="";
				var jobId = document.getElementById('jobdiv').value;
				var strURL="<?php echo HC_IFRAMEWIDGET_PLUGIN_URL ?>getNatData.php?job="+jobId;
				var req = getXMLHTTP();
	
				if (req) 
				{
		
					req.onreadystatechange = function() {
						if (req.readyState == 4) {
							// only if "OK"
							if (req.status == 200) {						
								document.getElementById('natdatadiv').innerHTML=req.responseText;						
							} else {
								alert("There was a problem while using XMLHTTP:\n" + req.statusText);
							}
						}				
					}			
					req.open("GET", strURL, true);
					req.send(null);
				}
		
			}	
			function getMSAdata(stateId,jobId) 
			{	
				//Clean out data section on new select
				document.getElementById('jobdatadiv').innerHTML="";
				document.getElementById('MSAdatadiv').innerHTML="";
				document.getElementById('NMSAdatadiv').innerHTML="";
				document.getElementById('statedatadiv').innerHTML="";
				document.getElementById('natdatadiv').innerHTML="";
				var stateId = document.getElementById('selectstate').value;
				var jobId = document.getElementById('jobdiv').value;
				var strURL="<?php echo HC_IFRAMEWIDGET_PLUGIN_URL ?>getMSAdata.php?state="+stateId+"&job="+jobId;
				var req = getXMLHTTP();
	
				if (req) 
				{
		
					req.onreadystatechange = function() {
						if (req.readyState == 4) {
							// only if "OK"
							if (req.status == 200) {						
								document.getElementById('MSAdatadiv').innerHTML=req.responseText;						
							} else {
								alert("There was a problem while using XMLHTTP:\n" + req.statusText);
							}
						}				
					}			
					req.open("GET", strURL, true);
					req.send(null);
				}
		
			}
			function getNMSAdata(stateId,jobId) 
			{	
				//Clean out data section on new select
				document.getElementById('jobdatadiv').innerHTML="";
				document.getElementById('MSAdatadiv').innerHTML="";
				document.getElementById('NMSAdatadiv').innerHTML="";
				document.getElementById('statedatadiv').innerHTML="";
				document.getElementById('natdatadiv').innerHTML="";
				var stateId = document.getElementById('selectstate').value;
				var jobId = document.getElementById('jobdiv').value;
				var strURL="<?php echo HC_IFRAMEWIDGET_PLUGIN_URL ?>getNMSAdata.php?state="+stateId+"&job="+jobId;
				var req = getXMLHTTP();
	
				if (req) 
				{
		
					req.onreadystatechange = function() {
						if (req.readyState == 4) {
							// only if "OK"
							if (req.status == 200) {						
								document.getElementById('NMSAdatadiv').innerHTML=req.responseText;						
							} else {
								alert("There was a problem while using XMLHTTP:\n" + req.statusText);
							}
						}				
					}			
					req.open("GET", strURL, true);
					req.send(null);
				}
		
			}



		</script>
		<?php
		
		
	
		// database connection
		global $wpdb;
		$connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error()); 
		mysql_select_db(DB_NAME, $connection) or die(mysql_error());
		
		$query = "SELECT DISTINCT PRIM_STATE 
				FROM EDU_LOCAL
				ORDER BY PRIM_STATE ASC";
				
				if (!($stmt = mysql_query ($query, $connection))) {
					showerror();
				}	
	
	
		?>

					<div id="salary-widget"><h2>Teacher Salary and Employment Data</h2>
				    <p>Quickly compare salary and job statistics in your area</p>
					<form>
					    <div class="selection"><p class="title">1. Select a State</p>
							<select id="selectstate" class="required" name="state" onChange="getCity(this.value); getJob(this.value,'');getJobData(this.value,'','');getStateData(this.value,'');getNatData('');getMSAdata(this.value,'');getNMSAdata(this.value,'')">
						
						<option value="">Select State</option>						
						<? while ($row = mysql_fetch_array($stmt)) { ?>
						<option value='<?=$row['PRIM_STATE']?>'><?=$row['PRIM_STATE']?></option>
						<? } ?>
						</select>
					
					</div>
					
												
			            <div class="selection"><p class="title">2. Select a City</p>
			            <div >
			                <select id="citydiv" name="city" onChange="getJob(); getJobData();getStateData();getNatData();getMSAdata();getNMSAdata()">
			                    <option></option>
			                </select>
			            </div>
			            </div>					

			            <div class="selection"><p class="title">3. Select a Job Title</p>
			            <div >
			                <select id="jobdiv" name="job" onChange=" getJobData();getStateData();getNatData();getMSAdata();getNMSAdata()">
			            		<option></option>
			                </select>
			            </div>	
			            </div>

			            <div class="output">
			                <div id="jobdatadiv"></div>
			                <div id="MSAdatadiv"></div>
			                <div id="NMSAdatadiv"></div>
			                <div id="statedatadiv"></div>
			                <div id="natdatadiv"></div>
			            </div>
			        </form>
					<p class="source"><i>Source: BLS Wage Data by Area and Occupation. * or ** indicate insignificant or unavailable data.</i></p>
		<?php 
	
		return ob_get_clean(); 
	}
	
	// footer scripts		
	function footerScript () {
		?>
		<script type="text/javascript">
		// any needed javascript code here - goes in footer
        </script>
        <?php
	}
	
	/* the Settings page for this plugin */
	
	function adminOptionsPage() { ?>
		<div id="hc_iframewidget">
		<h2>(NurseWidget) - HotChalk, Inc. v<?php echo HC_IFRAMEWIDGET_VERSION; ?></h2>
		<form method="post" action="options.php">
		<?php settings_fields('hcIframeWidgetOptions'); ?>
		<?php do_settings_sections('hc-iframewidget'); ?>
		</form></div>
		<?php
	}
}

$hcPlugin = new HC_IframeWidget();
?>