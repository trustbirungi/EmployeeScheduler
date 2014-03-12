<?php
/*********************************************************
	File: es_sup_edit_position_schedule.php
	Project: Employee Scheduler

	Comments:
		Allows a supervisor to view/edit a position schedule
		
	
**********************************************************/

require "es_functions.php";

//-- only allow supervisors to view this page
$user = auth_supervisor();
//-- get the position for this schedule
$position = get_position($p_id);
//-- get the supervisors employees
$employees = get_supervisor_employees($user);
//-- get each employees schedules
for($i=0; $i<count($employees); $i++) {
	$employees[$i]["u_schedules"] = get_user_schedules($employees[$i]);
}
$sections_in_hour = $sections_in_day / 24;

print_header($position["p_name"]."- ".$es_lang["edit_schedule"]);
print '<br><br><span class="pagetitle">'.$position["p_name"].'- '.$es_lang["edit_schedule"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';

if (!isset($action)) $action = "";
if (!isset($s_group)) $s_group = "";


//-- set the old group to the current group to detect group name changes
if (!empty($oldgroup)) $s_group = $oldgroup;
//-- if a group is requested then get that schedule group otherwise create a new group set
if (!empty($s_group)) {
	$schedules = get_schedule_group($s_group, $p_id);
}
else {
	//-- create a new array of schedule arrays with the default values
	$schedules = array();
	for($i=0; $i<7; $i++) {
		$schedules[$i] = array();
		$schedules[$i]["s_id"]="";
		$schedules[$i]["s_group"]=$s_group;
		$schedules[$i]["s_repeat"]=$REPEAT[1];
		$schedules[$i]["s_hours"]="000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
		$schedules[$i]["s_starttime"]=get_next_position_starttime($position)+(60*60*24*$i);
		$schedules[$i]["s_exptime"]=exptime_to_saturday($schedules[0]["s_starttime"]+(60*60*24*60));
		$schedules[$i]["s_assignments"] = array();
	}
}
//-- if we are saving or updating the page (ie. the save button was pressed or the view 24 hour link was hit) then update the information for each of the daily schedules
if (!empty($action)) {
	$starttime = strtotime($s_startdate);
	$endtime = strtotime($s_enddate);
	//-- loop through the incoming variables and update assignments on their schedules
	//-- incoming variables include: 
	//-- $hour-hour-day[] - this is an array of user ids that comes from the checkboxes next to 
	//-- the employee names on the schedule grid.  
	//-- $usid-hour-day is the employee schedule id for the user and day.  This is required so that the
	//-- position assignment can be added to an employees schedule.
	if (!isset($oldsections)) $oldsections = $sections_in_hour;
	if (!isset($oldfirst_hour)) $oldfirst_hour = $first_hour;
	if (!isset($oldlast_hour)) $oldlast_hour = $last_hour;
	for ($i=$oldfirst_hour*4; $i<$oldlast_hour*4; $i+=(4/$oldsections)) {
		for ($j=0; $j<7; $j++) {
			//-- set the new group name
			if (!empty($newgroup)) $schedules[$j]["s_group"]=$newgroup;
			//-- set the repeat interval
			$schedules[$j]["s_repeat"] = $s_repeat;
			// the hour-$i-$j variables hold how many people were scheduled, this is stored in the s_hours database field as a hex value
			$hourtxt = "hour-".$i."-".$j;
			if (isset($$hourtxt)) {
				//-- get the value from the $hour-$i-$j variable using the $$ 
				$hour = $$hourtxt;
				$hex = dechex(count($hour));
				if (count($hour)>15) $hex="F";
				$schedules[$j]["s_hours"][$i]=$hex;
				//-- add the position assignments to the schedule
				$schedules[$j]["s_assignments"][$i] = array();
				for($k=0; $k<count($hour); $k++) {
					for($l=0; $l<4/$oldsections; $l++) {
						$schedules[$j]["s_assignments"][$i+$l][$k] = array();
						$schedules[$j]["s_assignments"][$i+$l][$k]["pa_u_id"] = $hour[$k];
						$schedules[$j]["s_assignments"][$i+$l][$k]["pa_hour"] = $i+$l;
						$ustxt = "usid-".$i."-".$j."-".$hour[$k];
						$schedules[$j]["s_assignments"][$i+$l][$k]["pa_us_id"] = $$ustxt;
					}
				}
			}
			else {
				if (isset($schedules[$j]["s_assignments"][$i])) {
					for($l=0; $l<4/$oldsections; $l++) {
						$schedules[$j]["s_hours"][$i+$l]="0";
						unset($schedules[$j]["s_assignments"][$i+$l]);
					}
				}
			}
		}
	}
}

//-- check if the save button was pushed
if ($action=="save") {
	//-- make sure the group name is unique for each position and schedule
	$sql = "SELECT * FROM es_schedule WHERE s_group='".addslashes($newgroup)."' AND s_p_id=$p_id";
	$res = dbquery($sql);
	$error=false;
	if (mysql_num_rows($res)>0) {
		if ((!empty($schedules[0]["s_id"]))&&($oldgroup!=$newgroup)&&(!empty($oldgroup))) {
			print "<font class=\"error\"><br>".$es_lang["new_name"]."</font><br>\n";
			$error=true;
		}
		if (empty($schedules[0]["s_id"])) {
			print "<font class=\"error\"><br>".$es_lang["new_name"]."</font><br>\n";
			$error=true;
		}
	}
	if (!$error) {
		$i=0;
		//-- loop through the schedules and update the database
		foreach($schedules as $schedule) {
			$starttime = strtotime($s_startdate)+(60*60*24*$i);
			$endtime = strtotime($s_enddate);
			$schedules[$i]["s_starttime"] = $starttime;
			$schedules[$i]["s_exptime"] = $endtime;
			//-- check if this is a new schedule and do the appropriate UPDATE or INSERT sql
			if (!empty($schedule["s_id"])) {
				$s_id = $schedule["s_id"];
				$sql = "UPDATE es_schedule SET s_group='".addslashes($newgroup)."', s_starttime=$starttime, s_hours='".$schedule["s_hours"]."', s_repeat=$s_repeat, s_exptime=$endtime, s_notes='' WHERE s_id=".$schedule["s_id"];
				$res = dbquery($sql);
				//-- for simplicity delete all previous position assignments 
				$sql = "DELETE FROM es_position_assignment WHERE pa_s_id=$s_id";
				$res = dbquery($sql);
			}
			else {
				$sql = "INSERT INTO es_schedule VALUES(NULL, 0, ".$p_id.", '".addslashes($newgroup)."', $starttime, '".$schedule["s_hours"]."', $s_repeat, $endtime, '', NULL)";
				$res = dbquery($sql);
				$s_id = mysql_insert_id();
				$schedule["s_id"]=$s_id;
			}
			//-- loop through all position assignments and insert them into the database
			$assignments = $schedule["s_assignments"];
			if (is_array($assignments)) {
				foreach($assignments as $hour_assignment) {
					foreach($hour_assignment as $assignment) {
						$sql = "INSERT INTO es_position_assignment VALUES(NULL, ".$assignment["pa_u_id"].", $p_id, $s_id, ".$assignment["pa_us_id"].", ".$assignment["pa_hour"].", '')";
						$res = dbquery($sql);
					}
				}
			}
			$i++;
		}
		$s_group = $newgroup;
		print "<br><b>".$es_lang["save_sucess"]."</b><br>";
	}
}

//-- send emails
if ((!empty($sendemails))&&($sendemails=="yes")) {
	//-- setup a new array of employees who are assigned to work on this schedule group
	$email_employees = array();
	//-- loop through each schedule and each of the schedule assignments to 
	for($j=0; $j<count($schedules); $j++) {
		if (count($schedules[$j]["s_assignments"])>0) {
			foreach($schedules[$j]["s_assignments"] as $hour_assignment) {
				foreach($hour_assignment as $assignment) {
					foreach($employees as $employee) {
						if ($assignment["pa_u_id"]==$employee["user_id"]){
							$email_employees[] = $employee;
						}
					}
				}
			}
		}
	}
	//-- run array_unique to only get each employee once
	$email_employees = array_unique($email_employees);
	//-- for every employee in the email_employees list, send them an email
	foreach($email_employees as $employee) {
		if ((!empty($employee["user_email"]))&&(strpos($employee["user_email"], "@")!==false)) {
			$headers="";
			$message = $es_lang["hello"]." ".$employee["user_name"].",\n\n".$es_lang["email_msg6"].$position["p_name"]."\n".$es_lang["email_msg7"]."\n".$SITE_URL."es_emp_position_schedule.php?p_id=".$position["p_id"]."\n\n".$es_lang["email_msg5"]." ".$user["user_name"].".\n\n";
			$subject = $position["p_name"]." ".$es_lang["schedule_update"];
			if (!empty($user["user_email"])) {
				if ($ES_FULL_MAIL_TO) $headers = "From: ".$user["user_name"]." <".$user["user_email"].">\r\n";
				else $headers = "From: ".$user["user_email"]."\r\n";
			}
			if ($ES_FULL_MAIL_TO) $to = $employee["user_name"]." <".$employee["user_email"].">";
			else $to = $employee["user_email"];
			mail($to, $subject, $message, $headers);
			print "<br><b>".$es_lang["email_success"]." ".$employee["user_name"].".</b>\n";
		}
	}
}

$total_hours = 0;
foreach($schedules as $schedule) {
	for($i=0; $i<96; $i++) {
		if ($schedule["s_hours"]{$i}>0) {
			$total_hours++;
		}
	}
}
$total_hours = $total_hours / 4;
print '<SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>';
print '<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>';
?>
<script language="JavaScript">
	//-- function to open a popup window of the given user ids schedule
	function open_employee_schedule(u_id, s_group, s_exptime) {
		window.open('es_sup_employee_simple_schedule.php?user_id='+u_id+'&s_group='+s_group+'&s_exptime='+s_exptime+'&<?php print session_name()."=".session_id(); ?>', '', 'left=50,top=50,width=600,height=600,scrollbars=1,resizable=1,menubar=1');
		return false;
	}
	//-- set the first hour to view and reload the page
	function setfirsthour(hour) {
		document.scheduleform.first_hour.value=hour;
		document.scheduleform.submit();
		return false;
	}
	//-- validate the form
	function checkform(frm) {
		if (frm.newgroup.value=="") {
			alert("<?php print $es_lang["need_name"]; ?>");
			frm.newgroup.focus();
			return false;
		}
		if (frm.newgroup.value.indexOf("-")!=-1) {
			alert("<?php print $es_lang["no_hyphen"]; ?>");
			frm.newgroup.focus();
			return false;
		}
		return true;
	}
	//-- set the action to be "save" so that items are written to the database
	function saveschedule() {
		document.scheduleform.elements["action"].value="save";
		if (checkform(document.scheduleform)) document.scheduleform.submit();
		return false;
	}
	//-- every time a checkbox is pressed then this function is called
	//-- it updates the total hours for each employee and makes sure that
	//-- they will not be scheduled for more than their maximum allowed hours
	//-- the miss count is used to remind the supervisor that there is a reason
	//-- the box isn't being checked and to stop trying to check it
	miss_count = 0;
	function check_hours(u_id, checkbox) {
		totalhours = document.getElementById(u_id+'_total');
		maxhours = document.getElementById(u_id+'_max');
		total = parseInt(totalhours.value);
		max = parseInt(maxhours.value);
		if (checkbox.checked) {
			if (total < max) totalhours.value = parseFloat(totalhours.value) + (1/<?php print $sections_in_hour; ?>);
			else {
				checkbox.checked=false;
				miss_count++;
				if (miss_count>2) {
					alert('<?php print $es_lang["max_reached"]; ?>');
					miss_count=0;
				}
			}
		}
		else {
			if (totalhours.value>0) totalhours.value = parseFloat(totalhours.value) - (1/<?php print $sections_in_hour; ?>);
		}
	}
</script>
<form method="post" name="scheduleform">
	<input type="hidden" name="action" value="reload">
	<input type="hidden" name="oldfirst_hour" value="<?php print $first_hour?>">
	<input type="hidden" name="oldlast_hour" value="<?php print $last_hour?>">
	<input type="hidden" name="p_id" value="<?php print $p_id?>">
	<input type="hidden" name="oldgroup" value="<?php print $s_group?>">
	<input type="hidden" name="oldsections" value="<?php print $sections_in_hour?>">
<?php
	//-- write the hours strings for each schedule
	for($i=0; $i<7; $i++) {
		if (empty($schedules[$i]["s_hours"])) $schedules[$i]["s_hours"]="000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
		print "<input type=\"hidden\" id=\"day$i\" name=\"day[]\" value=\"".$schedules[$i]["s_hours"]."\">\n";
	}
?>
<table>
<tr>
<td align="right">
	<table>
		<tr><td align="right" class="text"><?php print $es_lang["schedule_name"]; ?></td><td><input type="text" name="newgroup" value="<?php print $schedules[0]["s_group"]?>"></td></tr>
		<tr><td align="right" class="text"><?php print $es_lang["repeat_interval"]; ?></td><td>
			<select name="s_repeat">
			<?php
				//-- get the repeat options from the global $REPEAT variable which is set in the configuration file
				foreach($REPEAT as $key=>$value) {
					if ($key>10000) {
						print "<option value=\"$key\"";
						if ($key==$schedules[0]["s_repeat"]) print " selected";
						print ">$value</option>\n";
					}
				}
			?>
			</select>
		</td></tr>
		<tr><td align="right" class="text"><?php print $es_lang["start_date"]; ?></td><td><input type="text" name="s_startdate" size="10" value="<?php print date("m/d/Y", starttime_to_sunday($schedules[0]["s_starttime"]))?>">
		<SCRIPT LANGUAGE="JavaScript">var cal1x = new CalendarPopup("caldiv1"); cal1x.showYearNavigation(); cal1x.showYearNavigationInput(); cal1x.setDisabledWeekDays(1,2,3,4,5,6);</SCRIPT>
		<A HREF="#" onClick="cal1x.select(document.scheduleform.s_startdate,'anchor1x','MM/dd/yyyy'); return false;"><img src="images/es_calendar.gif" width="20" height="20" border="0" NAME="anchor1x" ID="anchor1x"></A>
		<DIV ID="caldiv1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
		</td></tr>
		<tr><td align="right" class="text"><?php print $es_lang["end_date"]; ?></td><td><input type="text" name="s_enddate" size="10" value="<?php print date("m/d/Y", exptime_to_saturday($schedules[0]["s_exptime"]))?>">
		<SCRIPT LANGUAGE="JavaScript">var cal2x = new CalendarPopup("caldiv2"); cal2x.showYearNavigation(); cal2x.showYearNavigationInput(); cal2x.setDisabledWeekDays(0,1,2,3,4,5);</SCRIPT>
		<A HREF="#" onClick="cal2x.select(document.scheduleform.s_enddate,'anchor2x','MM/dd/yyyy'); return false;" NAME="anchor2x" ID="anchor2x"><img src="images/es_calendar.gif" width="20" height="20" border="0"></A>
		<DIV ID="caldiv2" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
		</td></tr>
		<tr><td colspan="2"><input type="submit" name="save" value="<?php print $es_lang["save_schedule"]; ?>" onclick="return saveschedule();"></td></tr>
	</table>
</td>
<td width=20><br></td>
<td>
	<table border=0 cellspacing=1 bgcolor="black">
		<tr bgcolor="<?php print $PRIORITY[0]?>"><td><?php print $es_lang["unavailable"]; ?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[1]?>"><td><?php print $es_lang["pref1"]; ?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[2]?>"><td><?php print $es_lang["pref2"]; ?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[3]?>"><td><?php print $es_lang["pref3"]; ?></td></tr>
	</table>
</td>
<td width=20><br></td>
<td>
	<table cellpadding="0" cellspacing="1" style="border: solid black 1px">
		<tr><td colspan="2" align="center"> <?php print $es_lang["total_scheduled"]; ?> </td></tr>
	<?php
		//-- setup the hours scheduled table with the values for each employee
		for($e=0; $e<count($employees); $e++) {
			$employees[$e]["u_schedules"] = time_limit_schedules($employees[$e]["u_schedules"], $schedules[0]["s_starttime"], $schedules[0]["s_exptime"]);
			$employee = $employees[$e];
			$emp_schedules = $employee["u_schedules"];
			$totaltxt = $employee["user_id"]."_total";
			if (isset($$totaltxt)) {
				$total_assigned = $$totaltxt;
			}
			else {
				$total_assigned = 0;
				foreach($emp_schedules as $emp_schedule) {
					$total_assigned += count($emp_schedule["s_assignments"])/4;
				}
			}
			//-- this sets up hidden fields of the form $userid_total so that the javascript function can reference and update them when a checkbox is checked
			print "<tr><td align=\"right\" class=\"text\" style=\"background-color: ".$employee["user_color"].";\">".$employee["user_name"].": </td><td class=\"text\"><input type=\"text\" name=\"".$employee["user_id"]."_total\" value=\"$total_assigned\" readonly size=\"2\" style=\"border: none; font-size: 10pt; width: 25px;\"> of <input type=\"text\" name=\"".$employee["user_id"]."_max\" value=\"".$employee["user_max"]."\" readonly size=\"2\" style=\"border: none; font-size: 10pt; width: 15px;\"></td></tr>\n";
		}
	?>
	</table>
</td>
</tr>
</table>
	<br />
NOTE: In order for your employees to show up on this schedule, they must have a schedule that does not expire before this position's schedule expires.
<br /><br />
	<div id="resolutiondiv" style="display:block;">
	<?php print $es_lang["first_hour"]; ?> <select name="first_hour" onchange="document.scheduleform.submit();">
	<?php 
		$stime = mktime($START_HOUR, 0, 0, 1,1,2003);
		for($i=$START_HOUR; $i<$last_hour; $i++) {
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
	<table border=2 cellspacing=0 bordercolor="black" style="border-collapse: collapse; empty-cells: show;">
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
		$j = $WEEKSTART;
		for($d=0; $d<7; $d++) {
			$hours = $schedules[$j]["s_hours"];
			//-- get the background color based on the priority value
			$bgcolor = $PRIORITY[0];
			print "\t<td bgcolor=\"$bgcolor\" class=\"schedulecell\" align=\"top\">";
			foreach($employees as $employee) {
				if (isset($employee["u_schedules"][$j])) {
					if (($employee["u_schedules"][$j]["s_hours"][$i]>0)&&($employee["u_schedules"][$j]["s_exptime"]>=$schedules[$j]["s_exptime"])) {
						$pass = true;
						//-- check if the user is already assigned to work this hour
						if ((isset($employee["u_schedules"][$j]["s_assignments"][$i]))&&($employee["u_schedules"][$j]["s_assignments"][$i]["pa_s_id"]!=$schedules[$j]["s_id"])) {
							//-- they are assigned but not to this position
							//-- check if they can be assigned
							$pass = false;
							/*$aschedule = get_schedule($employee["u_schedules"][$j]["s_assignments"][$i]["pa_s_id"]);
							$astart = $aschedule["s_starttime"];
							print "[".$employee["u_name"]." $astart ".$schedules[$j]["s_starttime"]." $REPEAT[1] ".($astart-$schedules[$j]["s_starttime"])."]";
							$count = 0;
							while($astart != $schedules[$j]["s_starttime"]) {
								if ($astart > $schedules[$j]["s_starttime"]) $astart = $astart - $REPEAT[1];
								else $astart = $astart + $REPEAT[1];
								$count++;
								//-- something wrong here
								if ($count>10) break;
							}
							if (($count>0)&&($REPEAT[$count+1]!=$schedules[$j]["s_repeat"])) $pass = true;*/
						}
						if ($pass) {
							print "<div style=\"white-space: nowrap; color: black; background-color: ".$employee["user_color"].";\" onmouseover=\"this.style.backgroundColor='".$PRIORITY[$employee["u_schedules"][$j]["s_hours"][$i]]."';\" onmouseout=\"this.style.backgroundColor='".$employee["u_color"]."';\"><input type=\"checkbox\" name=\"hour-".$i."-".$j."[]\" value=\"".$employee["user_id"]."\"";
							if (isset($schedules[$j]["s_assignments"][$i])) {
								foreach($schedules[$j]["s_assignments"][$i] as $assignment) {
									if ($assignment["pa_u_id"]==$employee["user_id"]) print " checked";
								}
							}
							print " onclick=\"return check_hours(".$employee["user_id"].", this);\">";
							print "<input type=\"hidden\" name=\"usid-".$i."-".$j."-".$employee["u_id"]."\" value=\"".$employee["u_schedules"][$j]["s_id"]."\">\n";
							print "<a href=\"#\" style=\"color: black;\"onclick=\"return open_employee_schedule(".$employee["user_id"].",'".$employee["u_schedules"][0]["s_group"]."','".$employee["u_schedules"][0]["s_exptime"]."');\">".$employee["user_name"]."- P".$employee["u_schedules"][$j]["s_hours"][$i]."</a></div>\n";
						}
					}
				}
			}
			if (isset($schedules[$j]["s_assignments"][$i])) {
				foreach($schedules[$j]["s_assignments"][$i] as $assignment) {
					$found=false;
					foreach($employees as $employee) {
						if ($assignment["pa_u_id"]==$employee["user_id"]){
							$found = true;
							break;
						}
					}
					//-- if the employee was not found then they belong to a different supervisor and we cannot change their schedule, but we should know that they are working
					if (!$found) {
						$employee = get_user($assignment["pa_u_id"]);
						$employee["user_id"] = $assignment["pa_u_id"];
						print "<div style=\"white-space: nowrap; color: black;\">";
						print $employee["user_name"]." (".$employee["user_id"].")</div>\n";
					}
				}
			}
			print "</td>\n";
			$j++;
			if ($j>6) $j=0;
		}
		print "</tr>\n";
		$htime += (60/$sections_in_hour)*60;
	}
?>	
	</table>
	<br>
	<input type="checkbox" name="sendemails" value="yes"> <?php print $es_lang["email_employees"];?><br>
	<input type="submit" name="save" value="<?php print $es_lang["save_schedule"]; ?>" onclick="return saveschedule();">
</form>
<?php

print_footer();
?>
