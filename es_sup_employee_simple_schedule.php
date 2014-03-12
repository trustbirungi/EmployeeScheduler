<?php
/*********************************************************
	File: es_sup_employee_simple_schedule.php
	Project: Employee Scheduler
	
	Comments:
		Shows the employee's schedule to supervisors.
		
	
**********************************************************/

require "es_functions.php";

//-- set the default action view
if (empty($action)) $action="week";

$user = auth_supervisor();
$employee = get_user($u_id);
print_simple_header($es_lang["emp_schedule"]." - ".$employee["user_name"]);

print '<br><br><span class="pagetitle">'.$es_lang["schedule"].' - '.$employee["user_name"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';

$schedules = get_user_schedules($employee);
if (!isset($schedules[0]["s_id"])) {
	$start = get_next_user_starttime($user);
	$schedules[0]["s_starttime"]=starttime_to_sunday($start);
	$schedules[0]["s_exptime"]=exptime_to_saturday($start+(60*60*24*40));
}

if (count($schedules)==0) {
	print "<br>".$employee["user_name"]." ">$es_lang["not_created"]."<br>\n";
	for($j=0; $j<7; $j++) {
		$schedules[$j] = array();
		$schedules[$j]["s_hours"] = "000000000000000000000000";
	}
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

if ($action=="week") {
if ($view!="print") {
	print '<br>'.$es_lang["week"].' | <a href="es_sup_employee_simple_schedule.php?user_id='.$u_id.'&action=list">'.$es_lang["list"].'</a> ';
	//print '| <a href="es_emp_index.php?action=calendar">Calendar</a>';
	print '<br><br>';
?>

	<form name="timeform" method="get">
	<input type="hidden" name="user_id" value="<?php print $u_id; ?>" />
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
print "Repeat ".$REPEAT[$group[0]["s_repeat"]]." from ".date("j M Y", $group[0]["s_starttime"])." to ".date("j M Y", $group[0]["s_exptime"])."<br>\n";
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
		for($d=0; $d<7; $d++) {
			$hours = $group[$j]["s_hours"];
			$pry = 0;
			//-- get the background color based on the priority value
			for ($k=0; $k<4/$sections_in_hour; $k++) {
				if ($pry < $hours[$i+$k]) $pry = $hours[$i+$k];
			}
			for ($k=0; $k<4/$sections_in_hour; $k++) {
				if (isset($group[$j]["s_assignments"][$i+$k])) {
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
//-- show the schedule in a list view
else if ($action=="list") {
	if ($view!="print") {
		print '<br><a href="es_sup_employee_simple_schedule.php?user_id='.$u_id.'&action=week">'.$es_lang["week"].'</a> | '.$es_lang["list"].' ';
		//print '| <a href="es_emp_index.php?action=calendar">Calendar</a>';
		print '<br>';
	}
	$curj = -1;
	//-- loop through the days
	for ($j=0; $j<7; $j++) {
		$htime = mktime(0, 0, 0, 1, 1, 2003);
		//-- loop through the hours in a day
		for ($i=0; $i<96; $i++) {
			$hours = $group[$j]["s_hours"];
			//-- if the schedule has something set at this hour the show it
			if (($hours[$i]>0)||(isset($group[$j]["s_comments"][$i]))) {
				$i1 = $i;
				//-- if we are at a new day then close the table for the old day and start a new day
				if ($curj!=$j) {
					if ($curj!=-1) print "</table>\n";
					print '<br><span class="sectitle">'.$DAYS[$j]["long"].'</span><br><img src="images/bar_1.gif" width="75%" height="2"><br><table cellspacing=4 cellpadding=1>';
					$curj=$j;
				}
				print "<tr><td valign=\"top\" align=\"right\"><span class='navtitle'>".date($TIME_FORMAT, $htime)." - ";
				//-- get the positition schedule id for any assignments at this hour
				$sid=0;
				if (isset($group[$j]["s_assignments"][$i])) {
					$sid = $group[$j]["s_assignments"][$i]["pa_s_id"];
				}
				$sid2=$sid;
				//-- loop through the following hours until a change is detected, this will tell us how long the current assignment or level lasts
				while(($i<96)&&($hours[$i]==$hours[$i1])&&($sid2==$sid)) {
					$i++;
					$htime += 60*15;
					if (isset($group[$j]["s_assignments"][$i])) {
						$sid2 = $group[$j]["s_assignments"][$i]["pa_s_id"];
					}
				}
				$i2 = $i;
				//-- decrement $i so that we will be on the correct hour on the next iteration of the for loop
				$i--;
				print date($TIME_FORMAT, $htime)."</span></td><td class=\"text\" valign=\"top\" bgcolor=\"".$PRIORITY[$hours[$i1]]."\">&nbsp;</td><td class=\"text\" valign=\"top\">";
				$htime -= 60*15;
				//-- if there is an assignment out this hour then print out the position name
				if (isset($group[$j]["s_assignments"][$i1])) {
					$assignment = $group[$j]["s_assignments"][$i1];
					$position = get_position($assignment["pa_p_id"]);
					print "<b>".$position["p_name"]."</b><br>";
					//-- check the position schedul to see if the assignment is on a rotating schedule
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
				//-- there is no assignment so print out the preference level
				else {
				if ($hours[$i1] > 0) print $es_lang["pref_level"]." ".$hours[$i1]."<br>";
				}
				//-- loop through the hours for the current list item and print any courses or comments in them
				while ($i1<$i2) {
					if (isset($group[$j]["s_comments"][$i1])) {
						$comment = $group[$j]["s_comments"][$i1];
						if (!empty($comment["sc_course"])) print $es_lang["course"].": ".$comment["sc_course"]."<br>\n";
						if (!empty($comment["sc_building"])) print $es_lang["building"]." ".$comment["sc_building"]."<br>\n";
						if (!empty($comment["sc_comment"])) print $es_lang["comment"].": ".$comment["sc_comment"]."\n";
					}
					$i1++;
				}
				print "</td></tr>";
			}
			//-- create a timestamp and print out the start time
			$htime += 60*15;
		}
	}
	if ($curj!=-1) print "</table>";
	print "<br><br>";
}

print_footer();
?>