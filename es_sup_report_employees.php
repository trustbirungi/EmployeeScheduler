<?php
/*********************************************************
	File: es_sup_report_employees.php
	Project: Employee Scheduler
	
	
	Comments:
		Employee hourly report
		
	
**********************************************************/

require "es_functions.php";

$user = auth_supervisor();
print_header($es_lang["emp_report"]);

print '<br /><br /><span class="pagetitle">'.$es_lang["emp_report"].'</span><br /><img src="images/bar_1.gif" width="75%" height="2">';

if (empty($action)) $action="form";
if (empty($s_starttime)) $s_starttime = starttime_to_sunday(time()-(60*60*24*30));
else $s_starttime = starttime_to_sunday(strtotime($s_startdate));
if (empty($s_enddate)) $s_enddate = exptime_to_saturday(time());
else $s_enddate = exptime_to_saturday(strtotime($s_enddate));

if ($view!="print") {
	?>
	<SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
	<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
	<form name="scheduleform" method="get" action="es_sup_report_employees.php">
	<input type="hidden" name="action" value="report" />
	<?php print $es_lang["emp_report_descr"]; ?><br /><br />
		<table>
		<tr><td align="right" class="text"><?php print $es_lang["start_date"]; ?></td><td><input type="text" name="s_startdate" size="10" value="<?php print date("m/d/Y", $s_starttime)?>">
		<SCRIPT LANGUAGE="JavaScript">var cal1x = new CalendarPopup("caldiv1"); cal1x.showYearNavigation(); cal1x.showYearNavigationInput(); cal1x.setDisabledWeekDays(1,2,3,4,5,6);</SCRIPT>
		<A HREF="#" onClick="cal1x.select(document.scheduleform.s_startdate,'anchor1x','MM/dd/yyyy'); return false;"><img src="images/es_calendar.gif" width="20" height="20" border="0" NAME="anchor1x" ID="anchor1x"></A>
		<DIV ID="caldiv1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
		</td></tr>
		<tr><td align="right" class="text"><?php print $es_lang["end_date"]; ?></td><td><input type="text" name="s_enddate" size="10" value="<?php print date("m/d/Y", $s_enddate)?>">
		<SCRIPT LANGUAGE="JavaScript">var cal2x = new CalendarPopup("caldiv2"); cal2x.showYearNavigation(); cal2x.showYearNavigationInput(); cal2x.setDisabledWeekDays(0,1,2,3,4,5);</SCRIPT>
		<A HREF="#" onClick="cal2x.select(document.scheduleform.s_enddate,'anchor2x','MM/dd/yyyy'); return false;" NAME="anchor2x" ID="anchor2x"><img src="images/es_calendar.gif" width="20" height="20" border="0"></A>
		<DIV ID="caldiv2" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
		</td></tr>
		<tr><td colspan="2"><input type="submit" value="<?php print $es_lang["view_report"]; ?>"></td></tr>
		</table>
	</form>
	
	<?php
}
if ($action=="report") {
$employees = get_supervisor_employees($user);

print "<br />";

foreach($employees as $employee) {
	print '<br /><table width="85%" cellpadding="0" cellspacing="0"><tr><td width="50%"><span class="sectitle">';
	//if (!empty($employee["u_picture"])) print '<img align="bottom" src="photos/'.$employee["u_picture"].'" height="50" style="border: solid '.$employee["u_color"].' 5px;" />';
	print $employee["user_name"].'</span></td><td width="50%" valign="bottom" align="right" class="text">';
	print '</td></tr>';
	print '<tr><td colspan="2"><img src="images/bar_1.gif" width="100%" height="2"></td></tr>';
	print "<tr>";
	print "<td class=\"text\">";
	$schedules = get_timed_user_schedules($user, $s_starttime, $s_enddate);
	$positions = array();
	foreach($schedules as $schedule) {
		foreach($schedule["s_assignments"] as $assignment) {
			if (isset($positions[$assignment["pa_p_id"]])) $positions[$assignment["pa_p_id"]]+=.25;
			else $positions[$assignment["pa_p_id"]] = .25;
		}
	}
	$total_hours = 0;
	if (count($positions)>0) {
		print "<b>".$es_lang["position_assignments"]."</b><br />";
		foreach($positions as $key=>$value) {
			$total_hours += $value;
			$position = get_position($key);
			print $position["p_name"].": ".$value." ".$es_lang["hours"]."<br />\n";
		}
		print "<b>".$es_lang["total_scheduled"]." $total_hours ".$es_lang["hours"]."</b><br />\n";
	}
	print "</td>\n";
	print "</tr></table>";
}
}
print_footer();
?>
