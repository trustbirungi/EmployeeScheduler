<?php
/*********************************************************
	File: es_sup_position.php
	Project: Employee Scheduler
	Comments:
		Allows a supervisor to view/edit a position schedule
		
	
**********************************************************/

require "es_functions.php";

$user = auth_user();
if (!empty($p_id_schedule)) {
	$vals = preg_split("/-/", $p_id_schedule);
	$p_id = $vals[0];
	$s_group = $vals[1];
}

$position = get_position($p_id);
print_header($position["p_name"]." ".$es_lang["schedule"]);

print '<br><br><span class="pagetitle">'.$position["p_name"].' '.$es_lang["schedule"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';
$schedules = get_position_schedules($position);

if (empty($s_group)) $s_group = $schedules[0]["s_group"];

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
$group = array();
foreach($schedules as $schedule) {
	if ($s_group==$schedule["s_group"]) $group[] = $schedule;
}
if (count($group)<0) {
	for($i=count($group); $i<7; $i++) {
		$temp = array();
		$temp["s_repeat"] = "";
		$group[] = $temp;
	}
}

if ($view!="print") {
	print "<table><tr><td>";
	print "<form name=\"scheduleform\" method=\"get\" action=\"es_emp_position_schedule.php\">\n";
	print "<input type=\"hidden\" name=\"p_id\" value=\"$p_id\">\n";
	print $es_lang["select_schedule"]."<br>\n";
	print "<select name=\"p_id_schedule\">\n";
	$groups = get_position_schedules($position);
	$old_group = "";
	foreach($groups as $schedule) {
		if ($schedule["s_group"]!=$old_group) {
			print "<option value=\"".$position["p_id"]."-".$schedule["s_group"]."\"";
			if (($schedule["s_group"]==$s_group)&&($position["p_id"]==$p_id)) print " selected";
			print ">".$position["p_name"]."-".$schedule["s_group"]."</option>\n";
			$old_group = $schedule["s_group"];
		}
	}
	print "</select><br>\n";
	print "<input type=\"submit\" value=\"".$es_lang["view"]."\"> ";
	print "</form>\n";
	print "</td><td width=\"15\"><br></td><td class=\"text\">";
	print "<b>".$group[0]["s_group"]."</b><br>\n";
	if (!isset($group[0]["s_repeat"])) $group[0]["s_repeat"] = 604800;
	print "Repeat ".$REPEAT[$group[0]["s_repeat"]]." <br>from ".date("j M Y", $group[0]["s_starttime"])." to ".date("j M Y", $group[0]["s_exptime"])."<br>\n";
	print "</td></tr></table>\n";
}

$total_hours = 0;
foreach($group as $schedule) {
	for($i=0; $i<24; $i++) {
		if ($schedule["s_hours"]{$i}>0) {
			$total_hours++;
		}
	}
}

if ($view!="print") {
	?>
	<br>
	<form name="timeform" method="get">
	<input type="hidden" name="s_exptime" value="<?php print $s_exptime; ?>" />
	<input type="hidden" name="s_group" value="<?php print $s_group; ?>" />
	<input type="hidden" name="p_id" value="<?php print $p_id; ?>" />
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
	//-- loop through 24 hours in a day
	for ($i=($first_hour*4); $i<($last_hour*4); $i+=(4/$sections_in_hour)) {
		print "<tr>\n";
		print "\t<td class=\"timecell\">". date($TIME_FORMAT, $htime). "</td>\n";
		$j = $WEEKSTART;
		for($d=0; $d<7; $d++) {
			$hours = $group[$j]["s_hours"];
			//-- get the background color based on the priority value
			$bgcolor = $PRIORITY[0];
			print "\t<td bgcolor=\"$bgcolor\" class=\"schedulecell\" align=\"top\">";
			if (isset($group[$j]["s_assignments"][$i])) {
				foreach($group[$j]["s_assignments"][$i] as $assignment) {
					$employee = get_user($assignment["pa_u_id"]);
					print "<div style=\"white-space: nowrap; color: black; margin: 0px; padding: 0px; width: 100%; background-color: ".$employee["user_color"].";\">";
					print $employee["user_name"]."</div>\n";
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
