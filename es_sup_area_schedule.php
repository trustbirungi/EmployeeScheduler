<?php
/*********************************************************
	File: es_sup_area_schedule.php
	Project: Employee Scheduler
	Comments:
		Allows a supervisor to view the schedule for an area
		
	
**********************************************************/

require "es_functions.php";

$user = auth_user();
$area = get_area($a_id);
print_header($area["a_name"]." ".$es_lang["schedule"]);

print '<br /><span class="pagetitle">'.$area["a_name"].' '.$es_lang["schedule"].'</span><br /><img src="images/bar_1.gif" width="75%" height="2"><br /><br />';
for($i=0; $i<count($area["a_positions"]); $i++) {
	$past_schedules = get_past_position_schedules($area["a_positions"][$i]);
	$schedules = get_position_schedules($area["a_positions"][$i]);
	$area["a_positions"][$i]["p_schedules"] = array_merge($schedules, $past_schedules);
}

if ($view!="print") {
	?>
	<br />
	<form name="timeform" method="get" action="es_sup_area_schedule.php">
	<input type="hidden" name="a_id" value="<?php print $a_id; ?>" />
	<?php 
	print $es_lang["select_schedule"]."<br />\n";
	$periods = array();
	$lastgroup = "";
	foreach($area["a_positions"] as $position) {
		foreach($position["p_schedules"] as $schedule) {
			if (($lastgroup!=$schedule["s_group"])&&(!empty($schedule["s_group"]))) {
				$lastgroup=$schedule["s_group"];
				$keep = true;
				foreach($periods as $start=>$end) {
					if (($schedule["s_exptime"]>$start)&&($schedule["s_starttime"]<$end)) {
						$keep=false;
						break;
					}
				}
				if ($keep) {
					//print $schedule["s_group"];
					$periods[$schedule["s_starttime"]] = $schedule["s_exptime"];
				}
			}
		}
	}
	print "<select name=\"s_starttime\">\n";
	foreach($periods as $start=>$end) {
		if ((!isset($s_starttime))||(!isset($periods[$s_starttime]))) $s_starttime = $start;
		print "<option value=\"$start\"";
		if ($s_starttime == $start) print " selected=\"selected\"";
		print ">".date("j M Y", $start)." to ".date("j M Y", $end)."</option>\n";
	}
	print "</select> <input type=\"submit\" value=\"".$es_lang["view"]."\" /><br />\n";
	?>
	<br />
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
	for ($i=($first_hour*4); $i<($last_hour*4); $i+=(4/$sections_in_hour)) {
		print "<tr>\n";
		print "\t<td class=\"timecell\">". date($TIME_FORMAT, $htime). "</td>\n";
		$j = $WEEKSTART;
		for($d=0; $d<7; $d++) {
			//-- get the background color based on the priority value
			$bgcolor = $PRIORITY[0];
			print "\t<td bgcolor=\"$bgcolor\" class=\"schedulecell\" valign=\"top\">";
			foreach($area["a_positions"] as $position) {
				for($k=$j; $k<count($position["p_schedules"]); $k+=7) {
					if ($position["p_schedules"][$k]["s_exptime"]>$s_starttime && $position["p_schedules"][$k]["s_starttime"]<$periods[$s_starttime])
					{
						$hours = $position["p_schedules"][$k]["s_hours"];
						if (isset($position["p_schedules"][$k]["s_assignments"][$i])) {
							foreach($position["p_schedules"][$k]["s_assignments"][$i] as $assignment) {
								$employee = get_user($assignment["pa_u_id"]);
								print "<div style=\"white-space: nowrap; background-color: ".$employee["user_color"].";\">";
								print $position["p_name"]."-".$employee["user_name"]."\n";
								if ($position["p_schedules"][$k]["s_repeat"]!=$REPEAT[1]) {
									print "<br />".$position["p_schedules"][$k]["s_group"]."<br />\n";
									$nextdate = get_next_date($position["p_schedules"][$k], $j);
									if ($nextdate > $position["p_schedules"][$k]["s_exptime"]) print $es_lang["finished"];
									else {
										print date("m/d/Y", $nextdate);
									}
								}
								print "</div>\n";
							}
						}
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
print "</table>";
print_footer();
?>
