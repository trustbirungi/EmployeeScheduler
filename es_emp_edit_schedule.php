<?php
/*********************************************************
	File: es_emp_edit_schedule.php
	Project: Employee Scheduler
	
	Comments:
		Allows an employee to edit their schedule.
		
	
**********************************************************/

require "es_functions.php";
dbconnect();

//-- only allow authenticated users to use this page
$user = auth_user();
if (empty($s_group) && empty($s_exptime)) {
	if (empty($create)) {
		//-- get the schedules as an array of schedules for this user
		$schedules = get_user_schedules($user);
		if (!isset($schedules[0]["s_id"])) {
			$start = get_next_user_starttime($user);
			$schedules[0]["s_starttime"]=starttime_to_sunday($start);
			$schedules[0]["s_exptime"]=exptime_to_saturday($start+(60*60*24*40));
		}
	}
	else {
		$schedules = array();
		for($i=0; $i<7; $i++) {
			$schedules[$i] = array();
			$schedules[$i]["s_assignments"] = array();
			$schedules[$i]["s_hours"] = "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
			$schedules[$i]["s_comments"] = array();
			$schedules[$i]["s_repeat"] = 60*60*24*7;
			$schedules[$i]["s_starttime"] = starttime_to_sunday(get_next_user_starttime($user));
			$schedules[$i]["s_exptime"] = exptime_to_saturday($schedules[$i]["s_starttime"]+(60*60*24*40));
			$schedules[$i]["s_group"] = $user["user_name"]."|".$schedules[0]["s_starttime"];
		}
	}
}
else {
	$schedules = get_user_schedule_group($s_group, $s_exptime, $user);
}

if (!isset($action)) $action = "";

//-- persist the hours and comments between page loads
//-- course information is passed in using a different variable for each section of the schedule grid $comment-hour-day
//-- $course-13-1 is the course info for monday at 1pm.  This is accomplished by creating a string
//-- for the name of the variable and then evaluating that string as a variable using the $$
if (!empty($action)) {
	//-- loop through the 7 days in a week
	for($j=0; $j<7; $j++) {
		$schedules[$j]["s_hours"] = $day[$j];
		//-- loop through the 24 hours in a day
		for($i=0; $i<96; $i++) {
			$course = "course-".($i-($i%(4/$old_sections_in_hour)))."-$j";
//			print "[$i $course]";
			if (!empty($$course)) {
//				print $$course;
				if (!isset($schedules[$j]["s_comments"][$i])) $schedules[$j]["s_comments"][$i] = array();
				$schedules[$j]["s_comments"][$i]["sc_course"]=$$course;
			}
			else if (isset($schedules[$j]["s_comments"][$i]["sc_course"])) unset($schedules[$j]["s_comments"][$i]["sc_course"]);
			$building = "building-".($i-($i%(4/$old_sections_in_hour)))."-$j";
			if (!empty($$building)) {
				if (!isset($schedules[$j]["s_comments"][$i])) $schedules[$j]["s_comments"][$i] = array();
				$schedules[$j]["s_comments"][$i]["sc_building"]=$$building;
			}
			else if (isset($schedules[$j]["s_comments"][$i]["sc_building"])) unset($schedules[$j]["s_comments"][$i]["sc_building"]);
			$comment = "comment-".($i-($i%(4/$old_sections_in_hour)))."-$j";
			if (!empty($$comment)) {
				if (!isset($schedules[$j]["s_comments"][$i])) $schedules[$j]["s_comments"][$i] = array();
				$schedules[$j]["s_comments"][$i]["sc_comment"]=$$comment;
			}
			else if (isset($schedules[$j]["s_comments"][$i]["sc_comment"])) unset($schedules[$j]["s_comments"][$i]["sc_comment"]);
		}
	}
}
$error = "";
//-- if the save button was pressed then save the schedules in the database
if ($action=="save") {
	$j=0;
	$starttime = starttime_to_sunday(strtotime($s_startdate));
	$exptime = strtotime($s_enddate);
	if (!check_user_schedules($starttime, $exptime, $schedules[0]["s_group"], $user)) {
		$error = $es_lang["schedule_conflict"];
	}
	else {
		foreach($schedules as $schedule) {
			$starttime = starttime_to_sunday(strtotime($s_startdate))+(60*60*24*$j);
			//-- check if we are creating a new schedule or updating an old one and do the appropriate insert or update sql
			if (empty($schedule["s_id"])) {
				$repeat = 7*24*60*60;					//-- repeat weekly seconds
				$sql = "INSERT INTO es_schedule VALUES (NULL, ".$user["user_id"].", 0, '".addslashes($user["user_name"])."', $starttime, '".$schedule["s_hours"]."', $repeat, $exptime, '', NULL)";
				$res = dbquery($sql);
				$s_id = mysql_insert_id();
				//-- insert any comments
				if (is_array($schedule["s_comments"])) {
					foreach($schedule["s_comments"] as $key=>$comment) {
						$sql = "INSERT INTO es_schedule_comment VALUES(NULL, $s_id, $key, '".addslashes($comment["sc_course"])."', '".addslashes($comment["sc_building"])."', '".addslashes($comment["sc_comment"])."')";
						$res = dbquery($sql);
					}
				}
			}
			else {
				$s_id = $schedule["s_id"];
				$sql = "UPDATE es_schedule SET s_hours='".$schedule["s_hours"]."', s_starttime=$starttime, s_exptime=$exptime WHERE s_id=$s_id";
				$res = dbquery($sql);
				//-- delete old comments
				$sql = "DELETE FROM es_schedule_comment WHERE sc_s_id=$s_id";
				$res = dbquery($sql);
				//-- insert any comments
				if (is_array($schedule["s_comments"])) {
					foreach($schedule["s_comments"] as $key=>$comment) {
						if (!isset($comment["sc_course"])) $comment["sc_course"]="";
						if (!isset($comment["sc_building"])) $comment["sc_building"]="";
						if (!isset($comment["sc_comment"])) $comment["sc_comment"]="";
						$sql = "INSERT INTO es_schedule_comment VALUES(NULL, $s_id, $key, '".addslashes($comment["sc_course"])."', '".addslashes($comment["sc_building"])."', '".addslashes($comment["sc_comment"])."')";
						$res = dbquery($sql);
					}
				}
			}
			$j++;
		}
		//-- send emails
		if ((!empty($sendemails))&&($sendemails=="yes")) {
			$supervisors = get_employee_supervisors($user);
			foreach($supervisors as $supervisor) {
				if ((!empty($supervisor["user_email"]))&&(strpos($supervisor["user_email"], "@")!==false)) {
					$headers="";
					$message = $es_lang["hello"]." ".$supervisor["user_name"].",\n\n".$es_lang["email_msg1"].$user["user_name"].$es_lang["email_msg2"]."\n".$SITE_URL."es_sup_employee_schedule.php?user_id=".$user["user_id"]."\n\n".$es_lang["email_msg3"]." ".$user["user_name"].".\n\n";
					$subject = $user["user_name"]." ".$es_lang["email_subject"];
					if (!empty($user["user_email"])) {
						if ($ES_FULL_MAIL_TO) $headers = "From: ".$user["user_name"]." <".$user["user_email"].">\r\n";
						else $headers = "From: ".$user["user_email"]."\r\n";
					}
					if ($ES_FULL_MAIL_TO) $to = $supervisor["user_name"]." <".$supervisor["user_email"].">";
					else $to = $supervisor["user_email"];
					$res = mail($to, $subject, $message, $headers);
				}
			}
		}
		header("Location: es_emp_index.php?".session_name()."=".session_id());
	}
}

//-- add up the total hours and check if there are any changes in the hours before 6am and force the first hour to be midnight
$total_hours = 0;
$total_assigned = 0;
foreach($schedules as $schedule) {
	for($i=0; $i<96; $i++) {
		if ($schedule["s_hours"]{$i}>0) {
			$total_hours++;
		}
		if (isset($schedule["s_assignments"][$i])) {
			$total_assigned++;
		}
	}
}
$total_hours = $total_hours / 4;
$total_assigned = $total_assigned / 4;
$sections_in_hour = $sections_in_day / 24;

print_header($es_lang["edit_my_schedule"]);
if (!empty($error)) print "<span class=\"error\">$error</span><br /><br />\n";
?>
<SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<script language="JavaScript" type="text/javascript">
	var lastclosed = "";		//-- the last layer that was closed using the expand/collapse layer functions
	
	//-- Javascript function to show a layer by setting the CSS display element to 'block'
	function expand_layer(sid) {
		var sbox = document.getElementById(sid);
		var sbox_style = sbox.style;
		sbox_style.display='block';
		return false;
	}
	
	//-- Javascript function to hide a layer by setting the CSS display element to 'none'
	function collapse_layer(sid) {
		var sbox = document.getElementById(sid);
		var sbox_style = sbox.style;
		sbox_style.display='none';
		return false;
	}
	
	//-- Javascript function to delete a comment.  If the comment has a value, it first confirms the user action
	function deletecomment(i, j) {
		comment = document.getElementById("comment-"+i+"-"+j);
		if (comment.value!="") {
			if (confirm('<?php print $es_lang["confirm_comment"]; ?>')) {
				comment.value="";
				collapse_layer("div-comment-"+i+"-"+j);
				lastclosed = "div-comment-"+i+"-"+j;
			}
		}
		else {
			collapse_layer("div-comment-"+i+"-"+j);
			lastclosed = "div-comment-"+i+"-"+j;
		}
		return false;
	}
	
	//-- Javascript function to delete a course.  If the course has a value, it first confirms the user action
	function deletecourse(i, j) {
		course = document.getElementById("course-"+i+"-"+j);
		building = document.getElementById("building-"+i+"-"+j);
		if (course.value!="") {
			if (confirm('<?php print $es_lang["confirm_course"]; ?>')) {
				course.value="";
				building.value="";
				collapse_layer("div-course-"+i+"-"+j);
				lastclosed = "div-course-"+i+"-"+j;
			}
		}
		else {
			building.value="";
			collapse_layer("div-course-"+i+"-"+j);
			lastclosed = "div-course-"+i+"-"+j;
		}
		return false;
	}

	//-- an array of colors for setting cell backgrounds during highlites and selects
	//-- these correspond to the colors in the $PRIORITY array
	<?php 
	$colors = "";
	foreach($PRIORITY as $c=>$color) {
		if ($c!=0) $colors.=",";
		$colors .= "\"$color\"";
	}
	print "colors = Array($colors);";
	?>

	var oldcolor;		//-- variable to hold the old color, while the mouse is over a cell, so that the old color can be restored when the mouse leaves the cell
	
	//-- highlite a table cell when the mouse is over it
	function hilitecell(cell) {
		oldcolor = cell.style.backgroundColor;
		colorIndex = 0;
		for(i=0; i<document.scheduleform.priority.length; i++) {
			if (document.scheduleform.priority[i].checked) colorIndex=i;
		}
		cell.style.backgroundColor = colors[document.scheduleform.priority[colorIndex].value];
	}
	
	//-- restore the original color when the mouse leaves the cell
	function unhilitecell(cell) {
		cell.style.backgroundColor = oldcolor;
	}
	
	//-- function called when the use clicks in a cell
	//-- cell is the javascript object for the cell the mouse was clicked in
	//-- i is the hour
	//-- j is the day
	function setcell(cell, i, j) {
		//-- get the hours array for the selected day
		hour = document.getElementById('day'+j);
		//-- get the selected color

		colorIndex = 0;
		for(k=0; k<document.scheduleform.priority.length; k++) {
			if (document.scheduleform.priority[k].checked) colorIndex=k;
		}
		//-- newval is the priority the user wants to place
		//-- 0 is unavailable
		//-- 1-3 are preference levels
		//-- 4-5 are unused preference levels
		//-- 6 is for courses
		//-- 7 is for comments
		newval = document.scheduleform.priority[colorIndex].value;
		
		//-- expand the course
		if (newval==6) {
			if (lastclosed!='div-course-'+i+'-'+j) expand_layer('div-course-'+i+'-'+j);
			lastclosed="";
			newval = 0;
		}
		//-- expand the comment
		if (newval==7) {
			if (lastclosed!='div-comment-'+i+'-'+j) expand_layer('div-comment-'+i+'-'+j);
			lastclosed="";
			return false;
		}
		//-- update the hours array
		if (hour) {
			curval = hour.value.charAt(i);
			cell.style.backgroundColor = colors[document.scheduleform.priority[colorIndex].value];
			oldcolor = cell.style.backgroundColor;
			for(k=0; k<(4/<?php print $sections_in_hour; ?>); k++) hour.value = hour.value.substring(0,(i+k)) + newval + hour.value.substring(i+k+1);
			//-- if the old value was unavailable and the new value is above 0 then subtract from the hours list
			if ((curval == 0)&&(newval>0)) {
				document.scheduleform.maxleft.value = parseFloat(document.scheduleform.maxleft.value) - 1/<?php print $sections_in_hour; ?>;
				document.scheduleform.totalhours.value = parseFloat(document.scheduleform.totalhours.value) + 1/<?php print $sections_in_hour; ?>;
				if (document.scheduleform.minleft.value>0) {
					document.scheduleform.minleft.value = parseFloat(document.scheduleform.minleft.value) - 1/<?php print $sections_in_hour; ?>;
				}
			}
			//-- if the old value was a preference level and the new value is unavailable then add to the hours list
			else if ((curval > 0) && (newval==0)) {
				document.scheduleform.maxleft.value = parseFloat(document.scheduleform.maxleft.value) + 1/<?php print $sections_in_hour; ?>;
				document.scheduleform.totalhours.value = parseFloat(document.scheduleform.totalhours.value) - 1/<?php print $sections_in_hour; ?>;
				if (document.scheduleform.totalhours.value < <?php print $user["user_min"]?>) document.scheduleform.minleft.value = parseFloat(document.scheduleform.minleft.value) + 1/<?php print $sections_in_hour; ?>;
			}
		}
	}
	
	//-- function reload the page and save the schedule
	function saveschedule() {
		document.scheduleform.elements["action"].value="save";
		document.scheduleform.submit();
		return false;
	}
	
	//-- function to fill in a day with a certain color
	function fillDay(j) {
		for(i=<?php print $first_hour*$sections_in_hour; ?>; i<96; i++) {
			cell = document.getElementById('cell-'+i+'-'+j);
			if (cell) {
				if (cell.name!="assigned") setcell(cell, i, j);
			}
		}
		return false;
	}
</script>
<br /><br /><span class="pagetitle">Edit Instructions</span><br />
<img src="images/bar_1.gif" width="75%" height="2"><br /><br />
<span class="pagetitle"><?php print $es_lang["edit_schedule"]; ?></span><br />
<img src="images/bar_1.gif" width="75%" height="2"><br /><br />
<form method="post" name="scheduleform">
	<input type="hidden" name="action" value="reload">
	<input type="hidden" name="old_sections_in_hour" value="<?php print $sections_in_hour;?>">
<?php
	//-- setup hidden variables for each days hours array
	for($i=0; $i<7; $i++) {
		print "<input type=\"hidden\" id=\"day$i\" size=\"108\" name=\"day[]\" value=\"".$schedules[$i]["s_hours"]."\">\n";
	}
	if (!empty($s_group)) print "<input type=\"hidden\" name=\"s_group\" value=\"".$s_group."\">\n";
	if (!empty($s_exptime)) print "<input type=\"hidden\" name=\"s_exptime\" value=\"".$s_exptime."\">\n";
	if (!empty($create)) print "<input type=\"hidden\" name=\"create\" value=\"".$create."\">\n";
	if (!empty($error)) print "<span class=\"error\">$error</span>";
?>
<table>
<tr>
<td valign="top">
	<table>
		<tr><td align="right" class="text"><?php print $es_lang["start_date"]; ?></td><td><input type="text" name="s_startdate" size="10" value="<?php print date("m/d/Y", $schedules[0]["s_starttime"])?>" <?php if ($total_assigned>0) print "readonly=\"readonly\">"; else { ?> >
		<SCRIPT LANGUAGE="JavaScript">var cal1x = new CalendarPopup("caldiv1"); cal1x.showYearNavigation(); cal1x.showYearNavigationInput(); cal1x.setDisabledWeekDays(1,2,3,4,5,6);</SCRIPT>
		<A HREF="#" onClick="cal1x.select(document.scheduleform.s_startdate,'anchor1x','MM/dd/yyyy'); return false;"><img src="images/es_calendar.gif" width="20" height="20" border="0" NAME="anchor1x" ID="anchor1x"></A>
		<DIV ID="caldiv1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV><?php } ?>
		</td></tr>
		<tr><td align="right" class="text"><?php print $es_lang["end_date"]; ?></td><td><input type="text" name="s_enddate" size="10" value="<?php print date("m/d/Y", $schedules[0]["s_exptime"])?>"<?php if ($total_assigned>0) print "readonly=\"readonly\">"; else { ?> >
		<SCRIPT LANGUAGE="JavaScript">var cal2x = new CalendarPopup("caldiv2"); cal2x.showYearNavigation(); cal2x.showYearNavigationInput(); cal2x.setDisabledWeekDays(0,1,2,3,4,5);</SCRIPT>
		<A HREF="#" onClick="cal2x.select(document.scheduleform.s_enddate,'anchor2x','MM/dd/yyyy'); return false;" NAME="anchor2x" ID="anchor2x"><img src="images/es_calendar.gif" width="20" height="20" border="0"></A>
		<DIV ID="caldiv2" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV><?php } ?>
		</td></tr>
		<tr><td colspan="2"><input type="submit" name="save" value="<?php print $es_lang["save_schedule"]; ?>" onclick="return saveschedule();"></td></tr>
	</table>
</td>
<td width="10"><br /></td>
<td valign="top">
	<table border=0 cellspacing=1 bgcolor="black">
		<tr bgcolor="<?php print $PRIORITY[6]?>"><td><input type="radio" name="priority" value="6"></td><td><?php print $es_lang["course"]; ?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[0]?>"><td><input type="radio" name="priority" value="0"></td><td><?php print $es_lang["unavailable"]; ?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[1]?>"><td><input type="radio" name="priority" value="1"></td><td><?php print $es_lang["pref1"]; ?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[2]?>"><td><input type="radio" name="priority" value="2"></td><td><?php print $es_lang["pref2"]; ?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[3]?>"><td><input type="radio" name="priority" value="3" checked></td><td><?php print $es_lang["pref3"]; ?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[7]?>"><td><input type="radio" name="priority" value="7"></td><td><?php print $es_lang["comment"]; ?></td></tr>
	</table>
</td>
<td width="10"><br /></td>
<td>
	<table>
	<tr><td align="right"><?php print $es_lang["minimum_hours"]; ?></td><td> <?php print $user["user_min"]?></td></tr>
	<tr><td align="right"><?php print $es_lang["maximum_hours"]; ?></td><td> <?php print $user["user_max"]?></td></tr>
	<tr><td align="right"><?php print $es_lang["min_left"]; ?></td><td><input type="text" name="minleft" value="<?php if ($user["user_min"]-$total_hours < 0) print "0"; else print ($user["user_min"]-$total_hours);?>" readonly size="2" style="border: none; font-size: 12pt;"></td></tr>
	<tr><td align="right"><?php print $es_lang["max_left"]; ?></td><td><input type="text" name="maxleft" value="<?php print $user["user_max"]-$total_hours?>" readonly size="2" style="border: none; font-size: 12pt;"></td></tr>
	<tr><td align="right"><b><?php print $es_lang["total_hours"]; ?></b></td><td><input type="text" name="totalhours" value="<?php print $total_hours?>" readonly size="2" style="border: none; font-size: 12pt; font-weight: bold;"></td></tr>
	<tr><td align="right"><b><?php print $es_lang["hours_desired"]; ?></b></td><td><input type="text" name="totaldesired" value="<?php print $user["user_hours"]?>" readonly size="2" style="border: none; font-size: 12pt; font-weight: bold;"></td></tr>
	<tr><td align="right"><b><?php print $es_lang["total_scheduled"]; ?></b></td><td><input type="text" name="totalassigned" value="<?php print $total_assigned?>" readonly size="2" style="border: none; font-size: 12pt; font-weight: bold;"></td></tr>
	</table>
</td>
</tr>
</table>

	<br />
	<div id="resolutiondiv" style="display: block;">
	<?php print $es_lang["first_hour"]; ?> <select name="first_hour" onchange="document.scheduleform.submit();">
	<?php 
		$stime = mktime($START_HOUR, 0, 0, 1,1,2003);
		for($i=$START_HOUR; $i<$END_HOUR-2; $i++) {
			print "<option value=\"$i\"";
			if ($first_hour==$i) print " selected";
			print ">".date($TIME_FORMAT, $stime)."</option>\n";
			$stime+=(60*60);
		}
	?>
	</select>
	<?php print $es_lang["last_hour"]; ?> <select name="last_hour" onchange="document.scheduleform.submit();">
	<?php 
		$stime = mktime($START_HOUR, 0, 0, 1,1,2003);
		for($i=$START_HOUR; $i<=$END_HOUR; $i++) {
			print "<option value=\"$i\"";
			if ($last_hour==$i) print " selected";
			print ">".date($TIME_FORMAT, $stime)."</option>\n";
			$stime+=(60*60);
		}
	?>
	</select>
	<?php print $es_lang["resolution"]; ?> <select name="sections_in_day" onchange="document.scheduleform.submit();">
	<option value="24"<?php if ($sections_in_day==24) print " selected"; ?>>1 <?php print $es_lang["hour"]; ?></option>
	<option value="48"<?php if ($sections_in_day==48) print " selected"; ?>>1/2 <?php print $es_lang["hour"]; ?></option>
	<option value="96"<?php if ($sections_in_day==96) print " selected"; ?>>15 <?php print $es_lang["min"]; ?></option>
	</select>
	</div>
	<table border=1 cellspacing=0 bordercolor="black" style="border-collapse: collapse; empty-cells: show;">
	<tr bgcolor="#F0F0E2">
<?php 
	print "<th class=\"daycell\">".$es_lang["hours"]."</th>\n";
	$j = $WEEKSTART;
	for($d=0; $d<7; $d++) {
		print "<th class=\"daycell\">".$DAYS[$j]["short"]."</th>\n";
		$j++;
		if ($j>6) $j=0;
	}
	print "</tr>\n";
	$htime = mktime($first_hour, 0, 0, 1, 1, 2003);
	//-- loop through 24 hours in a day
	for ($i=($first_hour*4); $i<($last_hour*4); $i+=(4/$sections_in_hour)) {
		print "<tr>\n";
		print "\t<td class=\"timecell\">". date($TIME_FORMAT, $htime). "</td>\n";
		//-- loop through 7 days in a week
		$j = $WEEKSTART;
		for ($d=0; $d<7; $d++) {
			$hours = $schedules[$j]["s_hours"];
			$pry = 0;
			//-- get the background color based on the priority value
			for ($k=0; $k<4/$sections_in_hour; $k++) {
				if ($pry < $hours[$i+$k]) $pry = $hours[$i+$k];
			}
			for ($k=0; $k<4/$sections_in_hour; $k++) {
				if (isset($schedules[$j]["s_assignments"][$i+$k])) {
					$pry=7;
				}
			}
			$bgcolor = $PRIORITY[$pry];
			//-- set default unavailable background color
			if (empty($bgcolor)) $bgcolor = $PRIORITY[0];
			print "\t<td id=\"cell-$i-$j\" bgcolor=\"$bgcolor\" class=\"schedulecell\"";
			//-- check for assignments at this day/hour
			if (!isset($schedules[$j]["s_assignments"][$i])) {
				print " onmouseover=\"hilitecell(this);\"  onmouseout=\"unhilitecell(this);\" onclick=\"setcell(this,$i,$j);\">";
			}
			else {
				//-- show assignments on schedule prevent changing this hour
				print " name=\"assigned\">";
				$assignment = $schedules[$j]["s_assignments"][$i];
				$position = get_position($assignment["pa_p_id"]);
				print "<b>".$position["p_name"]."</b><br />";
				$pschedule = get_schedule($assignment["pa_s_id"]);
				if ($pschedule["s_repeat"]!=$REPEAT[1]) {
					$nextdate = get_next_date($pschedule, $j);
					if ($nextdate > $pschedule["s_exptime"]) print $es_lang["finished"];
					else {
						print date("m/d/Y", $nextdate);
					}
				}
			}
			//-- check if the course and comment layers should be displayed
			$coursedisp = "none";
			$commentdisp = "none";
			if (isset($schedules[$j]["s_comments"][$i])) {
				$comment = $schedules[$j]["s_comments"][$i];
				if (!empty($comment["sc_course"])) {
					$coursedisp = "block";
				}
				if (!empty($comment["sc_comment"])) {
					$commentdisp = "block";
				}
			}
			else {
				$comment = array();
				$comment["sc_course"] = "";
				$comment["sc_building"] = "";
				$comment["sc_comment"] = "";
			}
			print "<div id=\"div-course-$i-$j\" style=\"display: $coursedisp;\">\n";
			print $es_lang["course"].": <a href=\"#\" onclick=\"return deletecourse($i, $j);\">remove</a><input type=\"text\" class=\"coursetextbox\" name=\"course-$i-$j\" value=\"".$comment["sc_course"]."\"><br />\n";
			print $es_lang["building"]." <input type=\"text\" class=\"buildingtextbox\" name=\"building-$i-$j\" value=\"".$comment["sc_building"]."\"><br />\n";
			print "</div>\n";
			print "<div id=\"div-comment-$i-$j\" style=\"display: $commentdisp;\">\n";
			print $es_lang["comment"].": <a href=\"#\" onclick=\"return deletecomment($i, $j);\">x</a><textarea class=\"commenttextbox\" name=\"comment-$i-$j\">".$comment["sc_comment"]."</textarea>\n";
			print "</div>\n";
			print "</td>\n";
			$j++;
			if ($j>6) $j=0;
		}
		print "</tr>\n";
		$htime += (60/$sections_in_hour)*60;
	}
?>	
	<tr bgcolor="#F0F0E2">
		<td></td>
		<td><a href="#" onclick="return fillDay(0);" align="center" style="font-size: 10px; color: black; "><?php print $es_lang["fill"]; ?></a></td>
		<td><a href="#" onclick="return fillDay(1);" align="center" style="font-size: 10px; color: black; "><?php print $es_lang["fill"]; ?></a></td>
		<td><a href="#" onclick="return fillDay(2);" align="center" style="font-size: 10px; color: black; "><?php print $es_lang["fill"]; ?></a></td>
		<td><a href="#" onclick="return fillDay(3);" align="center" style="font-size: 10px; color: black; "><?php print $es_lang["fill"]; ?></a></td>
		<td><a href="#" onclick="return fillDay(4);" align="center" style="font-size: 10px; color: black; "><?php print $es_lang["fill"]; ?></a></td>
		<td><a href="#" onclick="return fillDay(5);" align="center" style="font-size: 10px; color: black; "><?php print $es_lang["fill"]; ?></a></td>
		<td><a href="#" onclick="return fillDay(6);" align="center" style="font-size: 10px; color: black; "><?php print $es_lang["fill"]; ?></a></td>
	</tr>
	</table>
	<br />
	<input type="checkbox" name="sendemails" value="yes" checked> <?php print $es_lang["email_sup"]; ?><br />
	<input type="submit" name="save" value="<?php print $es_lang["save_schedule"]; ?>" onclick="return saveschedule();">
</form>
<br /><?php print $es_lang["edit_instructions"];?><br /><br />
<?php
print_footer();

?>
