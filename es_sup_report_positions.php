<?php
/*********************************************************
	File: es_sup_report_positions.php
	Project: Employee Scheduler
	
	Comments:
		
	
**********************************************************/

require "es_functions.php";
dbconnect();

//-- only allow supervisors to view page
$user = auth_supervisor();
print_header($es_lang["pos_report"]);
print '<br><br><span class="pagetitle">'.$es_lang["pos_report"].'</span><br><img src="images/bar_1.gif" width="85%" height="2">';

if (empty($action)) $action="form";
if (empty($s_starttime)) $s_starttime = starttime_to_sunday(time()-(60*60*24*30));
else $s_starttime = starttime_to_sunday(strtotime($s_startdate));
if (empty($s_enddate)) $s_enddate = exptime_to_saturday(time());
else $s_enddate = exptime_to_saturday(strtotime($s_enddate));
if ($view!="print") {
	?>
	<SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
	<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
	<form name="scheduleform" method="get" action="es_sup_report_positions.php">
	<input type="hidden" name="action" value="report" />
	<?php print $es_lang["pos_report_descr"]; ?><br /><br />
		<table>
		<tr><td align="right" class="text"><?php print $es_lang["start_date"]; ?></td><td><input type="text" name="s_startdate" size="10" value="<?php print date("m/d/Y", $s_starttime)?>" >
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
//-- get supervisor areas as an array of areas
$areas = get_supervisor_areas($user);
//-- if not in print-preview mode then show the add new area link
if ($view!="print") {
	print "<br><a href=\"es_sup_edit_area.php\">".$es_lang["add_area"]."</a>";
}
print "<br>";
//-- loop through the areas and print out a list
foreach($areas as $area) {
	print '<br><table width="85%" cellpadding="0" cellspacing="0"><tr><td><span class="sectitle">'.$area["a_name"].'</span></td><td align="right" class="text">';
	print '</td></tr>';
	print '<tr><td colspan="2"><img src="images/bar_1.gif" width="100%" height="2"></td></tr>';
	print "<tr><td colspan=\"2\"><div class=\"text\" style=\"padding-left: 15px;\">";
	print $area["a_description"]."<br>";
	if (count($area["a_positions"])>0) {
		print "<table cellspacing=\"3\">\n";
		print "<tr><td class=\"text\"><b>".$es_lang["position"]."</b></td>\n";
		print "<td class=\"text\"><b>".$es_lang["total"]."</b></td>\n";
		print "<td class=\"text\"></td>\n";
		print "</tr>\n";
		$total_hours = 0;
		foreach($area["a_positions"] as $position) {
			$schedules = get_timed_position_schedules($position, $s_starttime, $s_enddate);
			$hours = 0;
			foreach($schedules as $schedule) {
				foreach($schedule["s_assignments"] as $pa) {
					$hours += count($pa)/4;
				}
			}
			$total_hours+=$hours;
			print "<tr><td class=text>".$position["p_name"]."</td>\n";
			print "<td class=\"text\">$hours</td>\n";
			print "<td class=\"text\"></td>\n";
			print "</tr>\n";
		}
		print "<tr><td class=\"text\"></td>\n";
		print "<td class=\"text\"></td>\n";
		print "<td class=\"text\"><b>".$es_lang["total_for_area"]." - $total_hours</b></td>\n";
		print "</tr>\n";
		print "</table>\n";
	}
	print "</div>\n</td></tr></table>";
}
}
print_footer();
?>
