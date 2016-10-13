<?php

//start the session
session_start();

//include configuration and functions files
include("include/configuration.php");

//link to database
$link = mysql_connect(CONF_LOCATION, CONF_ADMINID, CONF_ADMINPASS) or die("poop"); mysql_select_db(CONF_DATABASE) or die("poop");

?>

<!DOCTYPE HTML>
<!--
	Stellar by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title> CSV Calendar Creator - Newtown High School </title>
  <meta name="Author" content="Devin Matte & Charles Dumais">
<link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
        <script type="text/javascript">
<!--
function SetAllCheckBoxes(FormName, FieldName, CheckValue)
{
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}
// -->
</script>
	</head>
	<body>
    <?

if (isset($_POST['submitschedule'])){
	//let's record the access information
	//log access
	$ip=$_SERVER['REMOTE_ADDR'];
	$id = session_id();
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$scheduleraw = $_POST['period1'].":".$_POST['period2'].":".$_POST['period3'].":".$_POST['period4'].":".$_POST['period5'].":".$_POST['period6'].":".$_POST['period7'].":".$_POST['period8'];
	$scheduleraw = mysql_real_escape_string(strip_tags(trim($scheduleraw)));
	$logquery="INSERT into access_log values (NULL, DATE_ADD(NOW(), INTERVAL 2 HOUR), \"".$id."\",\"".$ip."\",\"".$hostname."\",\"".$scheduleraw."\")";
	mysql_query($logquery);

	//run through POST variable and get info from database
	//first get active dates
	$date_query="select * from Days where Date between \"".$_POST['startdate']."\" and \"".$_POST['enddate']."\"";
	$activedates=mysql_query($date_query);

/*

This is the only section that needs to be edited for the program to work. You simply need to list out all the special day IDs. So anything that isn't A,B,C,D...

*/
	$special_days = array("AS","BS","CS","DS","ES","FS","GS","HS","S","SS","M1","M2","M3","M4","Y1","Y2","Y3","Y4","EAA","EAB","EAC","EAD","EAE","EAF","EAG","EAH","APLC","BPLC","CPLC","DPLC","EPLC","FPLC","GPLC","HPLC","ATD","HTD","BTD","DTD");

	//Stop editing
	$export_text = "";

	$export_text = "Subject,Start Date,Start Time,End Date,End Time\r\n";

	while ($single_date = mysql_fetch_assoc($activedates)) {
		for ($i=1;$i<9;$i++){
			$period = "period".$i;
			$day = "day".$i;
			if (isset($_POST[$period]) && (strlen($_POST[$period]) > 0)) {
				$_POST[$period] = mysql_real_escape_string(strip_tags(trim($_POST[$period])));
				$_POST[$period] = str_replace(",","-",$_POST[$period]);
				if (in_array($single_date['Day'],$special_days)){
				//if ($single_date['Day'] == "X"){
					//get times from database
					$specialget_query="Select * from Bells where Day=\"".$single_date['Day']."\" AND Type=\"".$single_date['Type']."\" AND Period=\"".$i."\"";
					$specialget = mysql_query($specialget_query);
					$specialgetrow = mysql_fetch_array($specialget);
					if (!is_null($specialgetrow['Start'])){
						$export_text .= $_POST[$period].",".$single_date['Date'].",".$specialgetrow['Start'].",".$single_date['Date'].",".$specialgetrow['End']."\r\n";
					}
				} else {
					for ($j=0;$j<10;$j++){
						if (isset($_POST[$day][$j]) && (strlen($_POST[$day][$j]) > 0) && ($_POST[$day][$j] == $single_date['Day'])) {
							$specialget_query2="Select * from Bells where Type=\"".$single_date['Type']."\" AND Period=\"".$i."\" AND Day=\"".$_POST[$day][$j]."\"";
							$specialget2 = mysql_query($specialget_query2);
							$specialgetrow2 = mysql_fetch_array($specialget2);
							if (!is_null($specialgetrow2['Start'])){
								$export_text .= $_POST[$period].",".$single_date['Date'].",".$specialgetrow2['Start'].",".$single_date['Date'].",".$specialgetrow2['End']."\r\n";
							}
						}
					}
				}
			}
		}
	}
	//write to file
	$calfile = fopen("calendar.csv","w");
	fwrite($calfile, $export_text);
	fclose($calfile);

?>
<div id="wrapper">

				<!-- Main -->
					<div id="main">

						<!-- Introduction -->
							<section id="intro" class="main">
								<div class="spotlight">
									<div class="content">
										<header class="major">
											<h2>Thanks for using the Newtown High School Calendar Generator</h2>
										</header>
                                        <ul class="actions">
											<li><a href="http://nhstech.us/calendar/calendar.csv" class="button special icon fa-download">Download File</a></li>
										</ul>
                                        </br></br>

	And here are instructions for creating a new calendar in Gmail and importing the CSV file:
	<ul>
	<li>Open Gmail and access the calendar tab</li>
	<li>Click on the "gear" in the upper right corner and access "settings" </br> <img src="images/settings.PNG" width="35%" border="0" alt=""></li>
	<li>Click on the "Calendars" tab </br> <img src="images/calendartab.PNG" width="50%" border="0" alt=""></li>
	<li>Click on the "create new calendar" button </br> <img src="images/createnewcalendar.PNG" width="50%" border="0" alt=""></li>
	<li>Create a new calendar by entering a calendar name and clicking on "Create Calendar" (you can import the file directly into your main calendar <em>but</em> if you create a new calendar you can always delete it or share it without affecting your other events) </br> <img src="images/createnewcalendar2.PNG" width="50%" border="0" alt=""></li>
	<li>Return to calendar, then settings (under the gear), then calendar, and "import calendar" </br> <img src="images/import.PNG" width="50%" border="0" alt=""></li>
	<li>Select the CSV file that you downloaded, select your new calendar, and then click "Import" </br> <img src="images/import2.PNG" width="50%" border="0" alt=""></li>
	</ul>
									</div>
								</div>
							</section>
					</div>
			</div>
<?

} else {

?>
	<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header" class="alt">
						<span class="logo"><img src="images/logo.svg" width="10%" alt="" /></span>
						<h1>Welcome to the Newtown High School Calendar Generator</h1>
						<p>Maintained by NHS Technology Team</p>
					</header>

				<!-- Nav -->
					<nav id="nav">
						<ul>
							<li><a href="#intro" class="active">Instructions</a></li>
							<li><a href="#cta">Build Schedule</a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">

						<!-- Introduction -->
							<section id="intro" class="main">
								<div class="spotlight">
									<div class="content">
										<header class="major">
											<h2>Welcome to the Newtown High School Calendar Generator</h2>
										</header>
										<p>This program will generate a CSV file (suitable for import to Google Calendar) that recognizes Letter Day Designations, Half Days and even Finals.</br></br>
	How to use it:
	<ul>
	<li>Be creative.  Do it in pieces and establish a calendar for each course or section to be shared with others.</li>
	<li>In the "Start Date" field, enter the date you would like the calendar information to begin on.  Appropriate format is YYYY-MM-DD.</li>
	<li>In the "End Date" field, enter the date you would like the calendar information to end on.  Appropriate format is YYYY-MM-DD. (Perhaps you have semester courses and need to run this twice.)</li>
	<li>In the "Period # Class" field, enter the name of the course, the duty, or whatever other "period regular" activity you have (do not include commas - commas will be replaced with dashes).</li>
	<li>Leave all of the Letter boxes checked if this event occurs on every letter day (don't worry about days that drop - the program knows which letter day it is).  If an event occurs only on specific Letter days (like science lab), just leave those boxes checked.</li>
	<li>Click "Submit Schedule".  Your CSV file will be created and you will see instructions on how to set up your Google calendar</li>
	</ul></p>
									</div>
								</div>
							</section>

						<!-- Get Started -->
							<section id="cta" class="main special">
<form name="schedule" id="schedule" action=<?php echo $_SERVER['PHP_SELF']?> method="post">
		<div class="row uniform">
		<div class="6u 12u$(small)">Start Date: <input type=text name='startdate' id='startdate' size=50% value='2016-08-25' tabindex=1></div> 
        <div class="6u$ 12u$(small)">End Date: <input type=text name='enddate' id='enddate' size=50% value='2017-06-30' tabindex=2></div>
        </div>
        </br>
		<h2><strong>Period 1 Class:</strong> </h2>
        <input type=text name='period1' id='period1' placeholder="Period 1 Class" size=25 tabindex=3/></br>
        <input type="checkbox" id="A-1" checked name="day1[]" value="A" > <label for="A-1">A</label> 
        <input type="checkbox" id="B-1" checked name="day1[]" value="B" > <label for="B-1">B</label>  
        <input type="checkbox" id="C-1" checked name="day1[]" value="C" > <label for="C-1">C</label>  
        <input type="checkbox" id="D-1" checked name="day1[]" value="D" > <label for="D-1">D</label>  
        <input type="checkbox" id="E-1" checked name="day1[]" value="E" > <label for="E-1">E</label> 
        <input type="checkbox" id="F-1" checked name="day1[]" value="F" > <label for="F-1">F</label> 
        <input type="checkbox" id="G-1" checked name="day1[]" value="G" > <label for="G-1">G</label> 
        <input type="checkbox" id="H-1" checked name="day1[]" value="H" > <label for="H-1">H</label> 
		</br>
        <h2><strong>Period 2 Class: </strong></h2>
        <input type=text name='period2' id='period2' placeholder="Period 2 Class" size=25 tabindex=4></br>
        <input type="checkbox" id="A-2" checked name="day2[]" value="A" > <label for="A-2">A</label> 
        <input type="checkbox" id="B-2" checked name="day2[]" value="B" > <label for="B-2">B</label>  
        <input type="checkbox" id="C-2" checked name="day2[]" value="C" > <label for="C-2">C</label>  
        <input type="checkbox" id="D-2" checked name="day2[]" value="D" > <label for="D-2">D</label>  
        <input type="checkbox" id="E-2" checked name="day2[]" value="E" > <label for="E-2">E</label> 
        <input type="checkbox" id="F-2" checked name="day2[]" value="F" > <label for="F-2">F</label> 
        <input type="checkbox" id="G-2" checked name="day2[]" value="G" > <label for="G-2">G</label> 
        <input type="checkbox" id="H-2" checked name="day2[]" value="H" > <label for="H-2">H</label> 
		</br>
        <h2><strong>Period 3 Class: </strong></h2>
        <input type=text name='period3' id='period3' placeholder="Period 3 Class" size=25 tabindex=5></br>
        <input type="checkbox" id="A-3" checked name="day3[]" value="A" > <label for="A-3">A</label> 
        <input type="checkbox" id="B-3" checked name="day3[]" value="B" > <label for="B-3">B</label>  
        <input type="checkbox" id="C-3" checked name="day3[]" value="C" > <label for="C-3">C</label>  
        <input type="checkbox" id="D-3" checked name="day3[]" value="D" > <label for="D-3">D</label>  
        <input type="checkbox" id="E-3" checked name="day3[]" value="E" > <label for="E-3">E</label> 
        <input type="checkbox" id="F-3" checked name="day3[]" value="F" > <label for="F-3">F</label> 
        <input type="checkbox" id="G-3" checked name="day3[]" value="G" > <label for="G-3">G</label> 
        <input type="checkbox" id="H-3" checked name="day3[]" value="H" > <label for="H-3">H</label> 
		</br>
        <h2><strong>Period 4 Class: </strong></h2>
        <input type=text name='period4' id='period4' placeholder="Period 4 Class" size=25 tabindex=6></br>
        <input type="checkbox" id="A-4" checked name="day4[]" value="A" > <label for="A-4">A</label> 
        <input type="checkbox" id="B-4" checked name="day4[]" value="B" > <label for="B-4">B</label>  
        <input type="checkbox" id="C-4" checked name="day4[]" value="C" > <label for="C-4">C</label>  
        <input type="checkbox" id="D-4" checked name="day4[]" value="D" > <label for="D-4">D</label>  
        <input type="checkbox" id="E-4" checked name="day4[]" value="E" > <label for="E-4">E</label> 
        <input type="checkbox" id="F-4" checked name="day4[]" value="F" > <label for="F-4">F</label> 
        <input type="checkbox" id="G-4" checked name="day4[]" value="G" > <label for="G-4">G</label> 
        <input type="checkbox" id="H-4" checked name="day4[]" value="H" > <label for="H-4">H</label> 
		</br>
        <h2><strong>Period 5 Class: </strong></h2>
        <input type=text name='period5' id='period5' placeholder="Period 5 Class" size=25 tabindex=7></br>
        <input type="checkbox" id="A-5" checked name="day5[]" value="A" > <label for="A-5">A</label> 
        <input type="checkbox" id="B-5" checked name="day5[]" value="B" > <label for="B-5">B</label>  
        <input type="checkbox" id="C-5" checked name="day5[]" value="C" > <label for="C-5">C</label>  
        <input type="checkbox" id="D-5" checked name="day5[]" value="D" > <label for="D-5">D</label>  
        <input type="checkbox" id="E-5" checked name="day5[]" value="E" > <label for="E-5">E</label> 
        <input type="checkbox" id="F-5" checked name="day5[]" value="F" > <label for="F-5">F</label> 
        <input type="checkbox" id="G-5" checked name="day5[]" value="G" > <label for="G-5">G</label> 
        <input type="checkbox" id="H-5" checked name="day5[]" value="H" > <label for="H-5">H</label> 
		</br>
        <h2><strong>Period 6 Class: </strong></h2>
        <input type=text name='period6' id='period6' placeholder="Period 6 Class" size=25 tabindex=8> </br>
        <input type="checkbox" id="A-6" checked name="day6[]" value="A" > <label for="A-6">A</label> 
        <input type="checkbox" id="B-6" checked name="day6[]" value="B" > <label for="B-6">B</label>  
        <input type="checkbox" id="C-6" checked name="day6[]" value="C" > <label for="C-6">C</label>  
        <input type="checkbox" id="D-6" checked name="day6[]" value="D" > <label for="D-6">D</label>  
        <input type="checkbox" id="E-6" checked name="day6[]" value="E" > <label for="E-6">E</label> 
        <input type="checkbox" id="F-6" checked name="day6[]" value="F" > <label for="F-6">F</label> 
        <input type="checkbox" id="G-6" checked name="day6[]" value="G" > <label for="G-6">G</label> 
        <input type="checkbox" id="H-6" checked name="day6[]" value="H" > <label for="H-6">H</label> 
		</br>
        <h2><strong>Period 7 Class: </strong></h2>
        <input type=text name='period7' id='period7' placeholder="Period 7 Class" size=25 tabindex=9></br>
        <input type="checkbox" id="A-7" checked name="day7[]" value="A" > <label for="A-7">A</label> 
        <input type="checkbox" id="B-7" checked name="day7[]" value="B" > <label for="B-7">B</label>  
        <input type="checkbox" id="C-7" checked name="day7[]" value="C" > <label for="C-7">C</label>  
        <input type="checkbox" id="D-7" checked name="day7[]" value="D" > <label for="D-7">D</label>  
        <input type="checkbox" id="E-7" checked name="day7[]" value="E" > <label for="E-7">E</label> 
        <input type="checkbox" id="F-7" checked name="day7[]" value="F" > <label for="F-7">F</label> 
        <input type="checkbox" id="G-7" checked name="day7[]" value="G" > <label for="G-7">G</label> 
        <input type="checkbox" id="H-7" checked name="day7[]" value="H" > <label for="H-7">H</label> 
		</br>
        <h2><strong>Period 8 Class: </strong></h2>
        <input type=text name='period8' id='period8' placeholder="Period 8 Class" size=25 tabindex=10></br>
        <input type="checkbox" id="A-8" checked name="day8[]" value="A" > <label for="A-8">A</label> 
        <input type="checkbox" id="B-8" checked name="day8[]" value="B" > <label for="B-8">B</label>  
        <input type="checkbox" id="C-8" checked name="day8[]" value="C" > <label for="C-8">C</label>  
        <input type="checkbox" id="D-8" checked name="day8[]" value="D" > <label for="D-8">D</label>  
        <input type="checkbox" id="E-8" checked name="day8[]" value="E" > <label for="E-8">E</label> 
        <input type="checkbox" id="F-8" checked name="day8[]" value="F" > <label for="F-8">F</label> 
        <input type="checkbox" id="G-8" checked name="day8[]" value="G" > <label for="G-8">G</label> 
        <input type="checkbox" id="H-8" checked name="day8[]" value="H" > <label for="H-8">H</label> 
        </br>
		<input type="button" onclick="SetAllCheckBoxes('schedule', 'day1[]', false);SetAllCheckBoxes('schedule', 'day2[]', false);SetAllCheckBoxes('schedule', 'day3[]', false);SetAllCheckBoxes('schedule', 'day4[]', false);SetAllCheckBoxes('schedule', 'day5[]', false);SetAllCheckBoxes('schedule', 'day6[]', false);SetAllCheckBoxes('schedule', 'day7[]', false);SetAllCheckBoxes('schedule', 'day8[]', false);" value="Uncheck All">&nbsp;&nbsp;<input type="button" onclick="SetAllCheckBoxes('schedule', 'day1[]', true);SetAllCheckBoxes('schedule', 'day2[]', true);SetAllCheckBoxes('schedule', 'day3[]', true);SetAllCheckBoxes('schedule', 'day4[]', true);SetAllCheckBoxes('schedule', 'day5[]', true);SetAllCheckBoxes('schedule', 'day6[]', true);SetAllCheckBoxes('schedule', 'day7[]', true);SetAllCheckBoxes('schedule', 'day8[]', true);" value="Check All">&nbsp;&nbsp;
		</br></br>
        <input type=submit id='submitschedule' name='submitschedule' value='Submit Schedule' tabindex=10>
	</form>
    <p>Currently Supporting Schedules for the 2016-2017 School Year</p>
							</section>

					</div>

				<!-- Footer -->
					<footer id="footer">
					<p class="copyright">&copy; 2014-2016 <a href="http://nhstech.us/">Newtown
					High School Technology Team</a>
                    </br>
                    Design: <a href="https://github.com/devinmatte">Devin Matte</a>  -  Initial Design: <a href="https://twitter.com/charlesdumais">Charles Dumais</a>
                    </br>
                    CSS Template: <a href="https://html5up.net">HTML5 UP</a>
                    </p>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>
<?
}
?>