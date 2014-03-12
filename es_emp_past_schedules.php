<?php
/*********************************************************
	File: es_emp_past_schedules.php
	Project: Employee Scheduler
	Comments:
		Shows an employee their past schedules.
		
	
**********************************************************/

require "es_functions.php";
dbconnect();
//-- only allow authenticated users to use this page
$user = auth_user();

//-- set the default action view
if (empty($action)) $action="week";
if (!isset($gotoedit)) $gotoedit = false;
if (!isset($deleteschedule)) $deleteschedule = false;

if (isset($schedule)) {
	$temp = preg_split('/\|/', $schedule);
	if (count($temp)==2) {
		$s_group = $temp[0];
		$s_exptime = $temp[1];
	}
}

if ($gotoedit) {
	if (!empty($s_group) && !empty($s_exptime)) {
		header("Location: es_emp_edit_schedule.php?s_group=$s_group&s_exptime=$s_exptime&".session_name()."=".session_id());
		exit;
	}
}

if ($deleteschedule) {
	delete_user_schedule_group($s_group, $s_exptime, $user);
	$s_group = "";
	$s_exptime = "";
}

print_header($es_lang["my_past_schedule"]);
print '<br><br><span class="pagetitle">'.$es_lang["my_past_schedule"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';
//-- get the schedules as an array of schedules for this user
$schedules = get_past_user_schedules($user);
//-- if the schedule hasn't been created yet then provide a link to create a new one and a link to the online help pages
if (count($schedules)==0) {
	print "<br><br><br>".$es_lang["have_note_created"]."  <br><br>\n";
	print_footer();
	exit;
}
$group = array();
if (!isset($s_group)) $s_group=$schedules[0]["s_group"];
if (!isset($s_exptime)) $s_exptime=$schedules[0]["s_exptime"];
foreach($schedules as $schedule) {
	if (($s_group==$schedule["s_group"])&&($s_exptime==$schedule["s_exptime"])) $group[] = $schedule;
}
if (count($group)<=0) {
	for($i=count($group); $i<7; $i++) {
		$temp = array();
		$temp["s_repeat"] = "";
		$group[] = $temp;
	}
}

if ($view!="print") {
?>
	<script language="JavaScript">
	function goedit() {
		document.scheduleform.gotoedit.value='1';
		document.scheduleform.submit();
	}
	function godelete() {
		if (confirm('<?php print $es_lang["confirm_schedule"]; ?>')) {
			document.scheduleform.deleteschedule.value='1';
			document.scheduleform.submit();
		}
	}
	</script>
<?php
	print "<table><tr><td>";
	print "<form name=\"scheduleform\" method=\"get\" action=\"es_emp_past_schedules.php\">\n";
	print "<input type=\"hidden\" name=\"gotoedit\" value=\"0\">\n";
	print "<input type=\"hidden\" name=\"deleteschedule\" value=\"0\">\n";
	print $es_lang["select_schedule"]."<br>\n";
	print "<select name=\"schedule\">\n";
	$old_group="";
	$old_time="";
	foreach($schedules as $schedule) {
		if (($schedule["s_group"]!=$old_group)||($schedule["s_exptime"]!=$old_time)) {
			print "<option value=\"".$schedule["s_group"]."|".$schedule["s_exptime"]."\"";
			if (($schedule["s_group"]==$s_group)&&($schedule["s_exptime"]==$s_exptime)) print " selected";
			print ">".$schedule["s_group"]." from ".date("j M Y", $schedule["s_starttime"])." to ".date("j M Y", $schedule["s_exptime"])."</option>\n";
			$old_group = $schedule["s_group"];
			$old_time = $schedule["s_exptime"];
		}
	}
	print "</select><br>\n";
	print "<input type=\"submit\" value=\"".$es_lang["view"]."\"> <input type=\"button\" value=\"".$es_lang["edit"]."\" onclick=\"goedit();\"> ";
	print "<input type=\"button\" value=\"".$es_lang["delete"]."\" onclick=\"godelete();\"> ";
	print "<input type=\"button\" value=\"".$es_lang["new"]."\" onclick=\"window.location='es_emp_edit_schedule.php?create=create&".session_name()."=".session_id()."';\">\n";
	print "</form>\n";
	print "</td></tr></table>\n";
}

$total_hours = 0;
$total_assigned = 0;
$j=0;
//-- add up the total hours and check if there are any changes in the hours before 6am and force the first hour to be midnight
foreach($group as $schedule) {
	for($i=0; $i<96; $i++) {
		if ($schedule["s_hours"]{$i}>0) {
			$total_hours++;
		}
		if (isset($schedule["s_assignments"][$i])) {
			$total_assigned++;
			if (($j==0)||($j==6)) if (!isset($show_weekends)) $show_weekends = true;
		}
	}
	$j++;
}
$total_hours = $total_hours / 4;
$total_assigned = $total_assigned / 4;
//-- set the default to hide weekends
if (!isset($show_weekends)) $show_weekends = false;	
$firstday = 0;
$lastday = 7;
if (!$show_weekends) {
	$firstday = 1;
	$lastday = 6;
}

//-- show schedule as a week view
if ($action=="week") {
if ($view!="print") {
?>
<table>
<tr>
<td>
	<table border=0 cellspacing=1 bgcolor="black">
		<tr bgcolor="<?php print $PRIORITY[0]?>"><td><?php print $es_lang["unavailable"];?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[1]?>"><td><?php print $es_lang["pref1"];?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[2]?>"><td><?php print $es_lang["pref2"];?></td></tr>
		<tr bgcolor="<?php print $PRIORITY[3]?>"><td><?php print $es_lang["pref3"];?></td></tr>
	</table>
</td>
<td width="20"><br></td>
<td>
	<table>
	<tr><td align="right"><?php print $es_lang["min_left"];?></td><td> <?php print $user["user_min"];?></td></tr>
	<tr><td align="right"><?php print $es_lang["max_left"];?></td><td> <?php print $user["user_max"];?></td></tr>
	<tr><td align="right"><b><?php print $es_lang["total_hours"];?></b></td><td><?php print $total_hours;?></td></tr>
	<tr><td align="right"><b><?php print $es_lang["hours_desired"];?></b></td><td><?php print $user["user_hours"];?></td></tr>
	<tr><td align="right"><b><?php print $es_lang["total_scheduled"];?></b></td><td><?php print $total_assigned;?></td></tr>
	</table>
</td>
<td width="10"><br></td>
<td valign="top">
	<?php
		print "Repeat ".$REPEAT[$group[0]["s_repeat"]]." <br>from ".date("j M Y", $group[0]["s_starttime"])." to ".date("j M Y", $group[0]["s_exptime"])."<br>\n";
	?>
</td>
</tr>
</table>

	<br>
	<form name="timeform" method="get">
	<input type="hidden" name="s_exptime" value="<?php print $s_exptime; ?>" />
	<input type="hidden" name="s_group" value="<?php print $s_group; ?>" />
	<?php print $es_lang["first_hour"]; ?> <select name="first_hour" onchange="document.timeform.submit();">
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
	<?php print $es_lang["last_hour"]; ?> <select name="last_hour" onchange="document.timeform.submit();">
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
	<?php print $es_lang["resolution"]; ?> <select name="sections_in_day" onchange="document.timeform.submit();">
	<option value="24"<?php if ($sections_in_day==24) print " selected"; ?>>1 <?php print $es_lang["hour"]; ?></option>
	<option value="48"<?php if ($sections_in_day==48) print " selected"; ?>>1/2 <?php print $es_lang["hour"]; ?></option>
	<option value="96"<?php if ($sections_in_day==96) print " selected"; ?>>15 <?php print $es_lang["min"]; ?></option>
	</select>
	</form>
<?php 
}
?>
	<table border="1" cellspacing="0" bordercolor="black" style="border-collapse: collapse; empty-cells: show;">
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
		?>
	</tr>
<?php
	$firstday = 0;
	$lastday = 7;
	$htime = mktime($first_hour, 0, 0, 1, 1, 2003);
	//-- loop through 24 hours in a day
	for ($i=($first_hour*4); $i<($last_hour*4); $i+=(4/$sections_in_hour)) {
		print "<tr>\n";
		print "\t<td class=\"timecell\">". date($TIME_FORMAT, $htime). "</td>\n";
		//-- loop through the days a week
		$j = $WEEKSTART;
		for ($d=$firstday; $d<$lastday; $d++) {
			$hours = $group[$j]["s_hours"];
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
			print "\t<td bgcolor=\"$bgcolor\" class=\"schedulecell\">";
			//-- check for schedule assignments and show them
			if (isset($group[$j]["s_assignments"][$i])) {
				$assignment = $group[$j]["s_assignments"][$i];
				$position = get_position($assignment["pa_p_id"]);
				print "<b>".$position["p_name"]."</b><br>";
				$pschedule = get_schedule($assignment["pa_s_id"]);
				if ($pschedule["s_repeat"]!=$REPEAT[1]) {
					print $pschedule["s_group"]."<br>\n";
					$nextdate = get_next_date($pschedule, $j);
					if ($nextdate > $pschedule["s_exptime"]) print $es_lang["finished"];
					else {
						print date("m/d/Y", $nextdate);
					}
				}
			}
			//-- if we are in print-preview mode then show the preference level by number
			if (($view=="print")&&($hours[$i]>0)) print "<b>P-".$hours[$i]."</b>";
			$coursedisp = "none";
			$commentdisp = "none";
			//-- check if the comment or course layers should be shown
			if (isset($group[$j]["s_comments"][$i])) {
				$comment = $group[$j]["s_comments"][$i];
				if (!empty($comment["sc_course"])) {
					$coursedisp = "block";
				}
				if (!empty($comment["sc_comment"])) {
					$commentdisp = "block";
				}
			}
			else {
				$comment = array();
			}
			//-- print out any courses for this hour
			if ($coursedisp!="none") {
				print "<div id=\"div-course-$i-$j\" style=\"display: $coursedisp;\">\n";
				print $es_lang["course"].": ".$comment["sc_course"]."<br>\n";
				print $es_lang["building"]." ".$comment["sc_building"]."<br>\n";
				print "</div>\n";
			}
			//-- print out any commnets for this hour
			if ($commentdisp!="none") {
				print "<div id=\"div-comment-$i-$j\" style=\"display: $commentdisp;\">\n";
				print $es_lang["comment"].": ".$comment["sc_comment"]."\n";
				print "</div>\n";
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
<?php
}
if ($view!="print") {
	print '<input type="button" name="edit" value="'.$es_lang["edit_schedule"].'" onclick="window.location = \'es_emp_edit_schedule.php?s_group='.$s_group.'&s_exptime='.$s_exptime.'&'.session_name()."=".session_id().'\';">';
}

print_footer();

global $user;
		if ($user) {
			print '<td width="20%" height="100%" valign="top">';
			if (preg_match("/(Supervisor)|(Admin)/i", $user["user_type"])>0) {
				print_supervisor_menu();
			}
			else {
				print_employee_menu();
			}
		}else {
	}
	print "<td valign=\"top\" class=\"text\">";
?>
