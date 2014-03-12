<?php
/*********************************************************
	File: es_admin_settings.php
	Project: Employee Scheduler
	
	Comments:
		Edit an employee's information.
		
	
**********************************************************/

require "es_functions.php";

$user = auth_admin();
print_header($es_lang["edit_settings"]);
print '<br><br><span class="pagetitle">'.$es_lang["edit_settings"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';

if (!isset($action)) $action="";

if ($action=="update") {
	$sql = "SELECT * FROM es_settings";
	$res = dbquery($sql);
	if (mysql_num_rows($res)==0) {
		$sql = "INSERT INTO es_settings (se_version) VALUES ('$VERSION')";
		$res = dbquery($sql);
	}
	$sql = "UPDATE es_settings SET ";
	$sql .= "se_SITE_URL='".addslashes($_POST["se_SITE_URL"])."',";
	$sql .= "se_SESSION_COOKIE_TIMEOUT='".addslashes($_POST["se_SESSION_COOKIE_TIMEOUT"])."',";
	$sql .= "se_CHARACTER_SET='".addslashes($_POST["se_CHARACTER_SET"])."',";
	$sql .= "se_SITE_ADMIN_EMAIL='".addslashes($_POST["se_SITE_ADMIN_EMAIL"])."',";
	$sql .= "se_COMPANY_URL='".addslashes($_POST["se_COMPANY_URL"])."',";
	$sql .= "se_COMPANY_NAME='".addslashes($_POST["se_COMPANY_NAME"])."',";
	$sql .= "se_START_HOUR='".addslashes($_POST["se_START_HOUR"])."',";
	$sql .= "se_END_HOUR='".addslashes($_POST["se_END_HOUR"])."',";
	$sql .= "se_DEFAULT_TIME_BLOCKS='".addslashes($_POST["se_DEFAULT_TIME_BLOCKS"])."',";
	$sql .= "se_ES_SHOW_STATS='".addslashes($_POST["se_ES_SHOW_STATS"])."',";
	$sql .= "se_PRIORITY_0='".addslashes($_POST["se_PRIORITY_0"])."',";
	$sql .= "se_PRIORITY_1='".addslashes($_POST["se_PRIORITY_1"])."',";
	$sql .= "se_PRIORITY_2='".addslashes($_POST["se_PRIORITY_2"])."',";
	$sql .= "se_PRIORITY_3='".addslashes($_POST["se_PRIORITY_3"])."',";
	//$sql .= "se_PRIORITY_4='".addslashes($_POST["se_PRIORITY_4"])."',";
	//$sql .= "se_PRIORITY_5='".addslashes($_POST["se_PRIORITY_5"])."',";
	$sql .= "se_PRIORITY_6='".addslashes($_POST["se_PRIORITY_6"])."',";
	$sql .= "se_PRIORITY_7='".addslashes($_POST["se_PRIORITY_7"])."',";
	$sql .= "se_PRIORITY_8='".addslashes($_POST["se_PRIORITY_8"])."'";
	$res = dbquery($sql);
	$SITE_URL=$_POST["se_SITE_URL"];
	$SESSION_COOKIE_TIMEOUT=$_POST["se_SESSION_COOKIE_TIMEOUT"];
	$CHARACTER_SET=$_POST["se_CHARACTER_SET"];
	$SITE_ADMIN_EMAIL=$_POST["se_SITE_ADMIN_EMAIL"];
	$COMPANY_URL=$_POST["se_COMPANY_URL"];
	$COMPANY_NAME=$_POST["se_COMPANY_NAME"];
	$START_HOUR=$_POST["se_START_HOUR"];
	$END_HOUR=$_POST["se_END_HOUR"];
	$DEFAULT_TIME_BLOCKS=$_POST["se_DEFAULT_TIME_BLOCKS"];
	$ES_SHOW_STATS=$_POST["se_ES_SHOW_STATS"];
	$ES_FULL_MAIL_TO=$_POST["se_ES_FULL_MAIL_TO"];
	$PRIORITY[0]=$_POST["se_PRIORITY_0"];
	$PRIORITY[1]=$_POST["se_PRIORITY_1"];
	$PRIORITY[2]=$_POST["se_PRIORITY_2"];
	$PRIORITY[3]=$_POST["se_PRIORITY_3"];
	//$PRIORITY[4]=$_POST["se_PRIORITY_4"];
	//$PRIORITY[5]=$_POST["se_PRIORITY_5"];
	$PRIORITY[6]=$_POST["se_PRIORITY_6"];
	$PRIORITY[7]=$_POST["se_PRIORITY_7"];
	$PRIORITY[8]=$_POST["se_PRIORITY_8"];
	print "<br /><b>".$es_lang["settings_saved"]."</b><br /><br />";
}

?>
<SCRIPT LANGUAGE="JavaScript" SRC="ColorSelector.js"></SCRIPT>
<script language="JavaScript">
	function change_background(colorbox) {
		colorbox.style.backgroundColor = colorbox.value;
	}
</script>
<form method="post" name="settingsform" action="es_admin_settings.php">
<input type="hidden" name="action" value="update" />
<table>
<tr><td align="right" class="text"><?php print $es_lang["se_SITE_URL"];?></td><td><input type="text" name="se_SITE_URL" value="<?php print $SITE_URL?>" maxlength="255" size="50"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_SESSION_COOKIE_TIMEOUT"];?></td><td><input type="text" name="se_SESSION_COOKIE_TIMEOUT" value="<?php print $SESSION_COOKIE_TIMEOUT?>" maxlength="10" size="5"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_CHARACTER_SET"];?></td><td><input type="text" name="se_CHARACTER_SET" value="<?php print $CHARACTER_SET?>" maxlength="255" size="10"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_SITE_ADMIN_EMAIL"];?></td><td><input type="text" name="se_SITE_ADMIN_EMAIL" value="<?php print $SITE_ADMIN_EMAIL?>" maxlength="255" size="50"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_COMPANY_URL"];?></td><td><input type="text" name="se_COMPANY_URL" value="<?php print $COMPANY_URL?>" maxlength="255" size="50"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_COMPANY_NAME"];?></td><td><input type="text" name="se_COMPANY_NAME" value="<?php print $COMPANY_NAME?>" maxlength="255" size="50"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_START_HOUR"];?></td><td><select name="se_START_HOUR">
<?php 
		$stime = mktime(0, 0, 0, 1,1,2003);
		for($i=0; $i<24; $i++) {
			print "<option value=\"$i\"";
			if ($START_HOUR==$i) print " selected";
			print ">".date($TIME_FORMAT, $stime)."</option>\n";
			$stime+=(60*60);
		}
	?>
</select></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_END_HOUR"];?></td><td><select name="se_END_HOUR">
<?php 
		$stime = mktime(0, 0, 0, 1,1,2003);
		for($i=0; $i<=24; $i++) {
			print "<option value=\"$i\"";
			if ($END_HOUR==$i) print " selected";
			print ">".date($TIME_FORMAT, $stime)."</option>\n";
			$stime+=(60*60);
		}
	?>
</select></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_DEFAULT_TIME_BLOCKS"];?></td><td><select name="se_DEFAULT_TIME_BLOCKS">
<option value="24"<?php if ($DEFAULT_TIME_BLOCKS==24) print " selected"; ?>>1 <?php print $es_lang["hour"]; ?></option>
	<option value="48"<?php if ($DEFAULT_TIME_BLOCKS==48) print " selected"; ?>>1/2 <?php print $es_lang["hour"]; ?></option>
	<option value="96"<?php if ($DEFAULT_TIME_BLOCKS==96) print " selected"; ?>>15 <?php print $es_lang["min"]; ?></option>
</select></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_ES_SHOW_STATS"];?></td><td><select name="se_ES_SHOW_STATS">
	<option value="0"<?php if ($ES_SHOW_STATS==0) print " selected"; ?>><?php print $es_lang["no"]; ?></option>
	<option value="1"<?php if ($ES_SHOW_STATS==1) print " selected"; ?>><?php print $es_lang["yes"]; ?></option>
</select></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_ES_FULL_MAIL_TO"];?></td><td><select name="se_ES_FULL_MAIL_TO">
	<option value="0"<?php if ($ES_FULL_MAIL_TO==0) print " selected"; ?>><?php print $es_lang["no"]; ?></option>
	<option value="1"<?php if ($ES_FULL_MAIL_TO==1) print " selected"; ?>><?php print $es_lang["yes"]; ?></option>
</select></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["unavailable"];?></td><td><input type="text" name="se_PRIORITY_0" value="<?php print $PRIORITY[0]?>" size="10" maxlength="10" style="background-color: <?php print $PRIORITY[0]?>;" onchange="change_background(this);">
	<script language="JavaScript" type="text/javascript">selector0 = new ColorSelector(document.settingsform.se_PRIORITY_0, true); selector0.writeSelector();</script>
	<a href="#" onclick="selector0.show(); return false;"><img src="images/es_color.png" alt="Choose Color" border="0" width="30" height="30"></a></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["pref1"];?></td><td><input type="text" name="se_PRIORITY_1" value="<?php print $PRIORITY[1]?>" size="10" maxlength="10" style="background-color: <?php print $PRIORITY[1]?>;" onchange="change_background(this);">
	<script language="JavaScript" type="text/javascript">selector1 = new ColorSelector(document.settingsform.se_PRIORITY_1, true); selector1.writeSelector();</script>
	<a href="#" onclick="selector1.show(); return false;"><img src="images/es_color.png" alt="Choose Color" border="0" width="30" height="30"></a></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["pref2"];?></td><td><input type="text" name="se_PRIORITY_2" value="<?php print $PRIORITY[2]?>" size="10" maxlength="10" style="background-color: <?php print $PRIORITY[2]?>;" onchange="change_background(this);">
	<script language="JavaScript" type="text/javascript">selector2 = new ColorSelector(document.settingsform.se_PRIORITY_2, true); selector2.writeSelector();</script>
	<a href="#" onclick="selector2.show(); return false;"><img src="images/es_color.png" alt="Choose Color" border="0" width="30" height="30"></a></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["pref3"];?></td><td><input type="text" name="se_PRIORITY_3" value="<?php print $PRIORITY[3]?>" size="10" maxlength="10" style="background-color: <?php print $PRIORITY[3]?>;" onchange="change_background(this);">
	<script language="JavaScript" type="text/javascript">selector3 = new ColorSelector(document.settingsform.se_PRIORITY_3, true); selector3.writeSelector();</script>
	<a href="#" onclick="selector3.show(); return false;"><img src="images/es_color.png" alt="Choose Color" border="0" width="30" height="30"></a></td></tr>
<?php /* -- these are unused priorities that may be used in future versions
<tr><td align="right" class="text"><?php print $es_lang["se_PRIORITY_4"];?></td><td><input type="text" name="se_PRIORITY_4" value="<?php print $PRIORITY[4]?>" size="10" maxlength="10" style="background-color: <?php print $PRIORITY[4]?>;" onchange="change_background(this);"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["se_PRIORITY_5"];?></td><td><input type="text" name="se_PRIORITY_5" value="<?php print $PRIORITY[5]?>" size="10" maxlength="10" style="background-color: <?php print $PRIORITY[5]?>;" onchange="change_background(this);"></td></tr>
*/ ?>
<tr><td align="right" class="text"><?php print $es_lang["course"];?></td><td><input type="text" name="se_PRIORITY_6" value="<?php print $PRIORITY[6]?>" size="10" maxlength="10" style="background-color: <?php print $PRIORITY[6]?>;" onchange="change_background(this);">
<script language="JavaScript" type="text/javascript">selector6 = new ColorSelector(document.settingsform.se_PRIORITY_6, true); selector6.writeSelector();</script>
	<a href="#" onclick="selector6.show(); return false;"><img src="images/es_color.png" alt="Choose Color" border="0" width="30" height="30"></a></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["comment"];?></td><td><input type="text" name="se_PRIORITY_7" value="<?php print $PRIORITY[7]?>" size="10" maxlength="10" style="background-color: <?php print $PRIORITY[7]?>;" onchange="change_background(this);">
	<script language="JavaScript" type="text/javascript">selector7 = new ColorSelector(document.settingsform.se_PRIORITY_7, true); selector7.writeSelector();</script>
	<a href="#" onclick="selector7.show(); return false;"><img src="images/es_color.png" alt="Choose Color" border="0" width="30" height="30"></a></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["position_assignments"];?></td><td><input type="text" name="se_PRIORITY_8" value="<?php print $PRIORITY[8]?>" size="10" maxlength="10" style="background-color: <?php print $PRIORITY[8]?>;" onchange="change_background(this);">
	<script language="JavaScript" type="text/javascript">selector8 = new ColorSelector(document.settingsform.se_PRIORITY_8, true); selector8.writeSelector();</script>
	<a href="#" onclick="selector8.show(); return false;"><img src="images/es_color.png" alt="Choose Color" border="0" width="30" height="30"></a></td></tr>
</table>
<input type="submit" value="<?php print $es_lang["save"];?>" />
</form>
<?php

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