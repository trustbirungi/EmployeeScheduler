<?php
/*********************************************************
	File: es_sup_position_past.php
	Project: Employee Scheduler
	
	Comments:
		Allows a supervisor to view/edit a position schedule
		
	
**********************************************************/

require "es_functions.php";
dbconnect();

$user = auth_supervisor();
if (!empty($p_id_schedule)) {
	$vals = preg_split("/-/", $p_id_schedule);
	$p_id = $vals[0];
	$s_group = $vals[1];
}
if (!isset($gotoedit)) $gotoedit = false;
if (!isset($deleteschedule)) $deleteschedule = false;

if ($gotoedit) {
	header("Location: es_sup_edit_position_schedule.php?p_id=$p_id&s_group=$s_group&".session_name()."=".session_id());
	exit;
}

if ($deleteschedule) {
	delete_schedule_group($s_group, $p_id);
	$s_group="";
}
$position = get_position($p_id);
print_header($position["p_name"]." ".$es_lang["schedule"]);

print '<br><br><span class="pagetitle">'.$position["p_name"].' '.$es_lang["past_schedules"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';
$schedules = get_past_position_schedules($position);

if ($schedules[0]["s_group"]=="") {
	print "<br><br><br>".$es_lang["no_schedule"]."<br><br>\n";
	print_footer();
	exit;
}

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
	print "<form name=\"scheduleform\" method=\"get\" action=\"es_sup_position.php\">\n";
	print "<input type=\"hidden\" name=\"gotoedit\" value=\"0\">\n";
	print "<input type=\"hidden\" name=\"p_id\" value=\"$p_id\">\n";
	print "<input type=\"hidden\" name=\"deleteschedule\" value=\"0\">\n";
	print $es_lang["select_schedule"]."<br>\n";
	print "<select name=\"p_id_schedule\">\n";

	$areas = get_supervisor_areas($user);
	foreach($areas as $area) {
		foreach($area["a_positions"] as $position) {
			$groups = get_past_position_schedules($position);
			$old_group = "";
			foreach($groups as $schedule) {
				if ($schedule["s_group"]!=$old_group) {
					print "<option value=\"".$position["p_id"]."-".$schedule["s_group"]."\"";
					if (($schedule["s_group"]==$s_group)&&($position["p_id"]==$p_id)) print " selected";
					print ">".$area["a_name"]."-".$position["p_name"]."-".$schedule["s_group"]." from ".date("j M Y", $group[0]["s_starttime"])." to ".date("j M Y", $group[0]["s_exptime"])."</option>\n";
					$old_group = $schedule["s_group"];
				}
			}
		}
	}
	print "</select><br>\n";
	print "<input type=\"submit\" value=\"".$es_lang["view"]."\"> <input type=\"button\" value=\"".$es_lang["edit"]."\" onclick=\"goedit();\"> ";
	print "<input type=\"button\" value=\"".$es_lang["delete"]."\" onclick=\"godelete();\"> ";
	print "<input type=\"button\" value=\"".$es_lang["new"]."\" onclick=\"window.location='es_sup_edit_position_schedule.php?p_id=$p_id&".session_name()."=".session_id()."';\">\n";
	print "</form>\n";
	print "</td><td width=\"15\"><br></td><td class=\"text\">";
	print "<b>".$group[0]["s_group"]."</b><br>\n";
	if (!isset($group[0]["s_repeat"])) $group[0]["s_repeat"] = 604800;
	print "Repeat ".$REPEAT[$group[0]["s_repeat"]]." <br>from ".date("j M Y", $group[0]["s_starttime"])." to ".date("j M Y", $group[0]["s_exptime"])."<br>\n";
	print "</td></tr></table>\n";
}


if (!isset($first_hour)) $first_hour=6;
$total_hours = 0;
foreach($group as $schedule) {
	for($i=0; $i<24; $i++) {
		if ($schedule["s_hours"]{$i}>0) {
			$total_hours++;
			if ($i<6) {
				$first_hour=0;
				$nolink = true;
			}
		}
	}
}

if ($view!="print") {
	if (empty($nolink)) {
		if ($first_hour!=0) print '<a href="es_sup_position.php?p_id='.$p_id.'&s_group='.$s_group.'&first_hour=0">'.$es_lang["view24"].'</a>'; else print '<a href="es_sup_position.php?p_id='.$p_id.'&s_group='.$s_group.'&first_hour=6">'.$es_lang["view18"].'</a>'; 
	}
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
	for ($i=$first_hour; $i<24; $i++) {
		print "<tr>\n";
		$htime = mktime($i, 0, 0, 1, 1, 2003);
		print "\t<td bgcolor=\"#F0F0E2\">". date($TIME_FORMAT, $htime). "</td>\n";
		$j = $WEEKSTART;
		for($d=0; $d<7; $d++) {
			$hours = $group[$j]["s_hours"];
			//-- get the background color based on the priority value
			if ((isset($group[$j]["s_assignments"][$i]))&&(count($group[$j]["s_assignments"][$i])>0)) $bgcolor = $PRIORITY[3];
			else $bgcolor = $PRIORITY[0];
			print "\t<td bgcolor=\"$bgcolor\" width=70 height=25 style=\"border: solid black 1px; empty-cells: show; font-size: 9px;\" valign=\"top\">";
			if (isset($group[$j]["s_assignments"][$i])) {
				foreach($group[$j]["s_assignments"][$i] as $assignment) {
					$employee = get_user($assignment["pa_u_id"]);
					print "<div style=\"white-space: nowrap; color: black;\">";
					print $employee["user_name"]."</div>\n";
				}
			}
			print "</td>\n";
			$j++;
			if ($j>6) $j=0;
		}
		print "</tr>\n";
	}
print "</table>";
print_footer();
?>
