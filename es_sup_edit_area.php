<?php
/*********************************************************
	File: es_sup_edit_area.php
	Project: Employee Scheduler
	
	Comments:
		Allows a supervisor to edit areas
		
	
**********************************************************/

require "es_functions.php";
dbconnect();
$user = auth_supervisor();

if (!isset($action)) $action="";
if (!isset($u_ids)) $u_ids = array($user["user_id"]);

if ($action=="update") {
	if (empty($a_id)) {
		$sql  = "INSERT INTO es_area VALUES (NULL, '".addslashes($a_name)."', '".addslashes($a_description)."')";
		$res = dbquery($sql);
		$a_id = mysql_insert_id();
	}
	else {
		$sql  = "UPDATE es_area SET a_name='".addslashes($a_name)."', a_description='".addslashes($a_description)."' WHERE a_id=$a_id";
		$res = dbquery($sql);
		$sql = "DELETE FROM es_area_sups WHERE as_a_id=$a_id";
		$res = dbquery($sql);
	}
	if (!is_array($u_ids)) $u_ids = array($user["user_id"]);
	foreach($u_ids as $u_id) {
		$sql = "INSERT INTO es_area_sups VALUES(NULL, $a_id, $u_id)";
		$res = dbquery($sql);
	}
	if ($res) header("Location: es_sup_index.php?".session_name()."=".session_id());
}

print_header($es_lang["edit_area"]);

print '<br><br><span class="pagetitle">Edit Area</span><br><img src="images/bar_1.gif" width="75%" height="2">';
print "<br><br>";

if (!empty($a_id)) {
	$area = get_area($a_id);
	$asups = get_area_supervisors($a_id);
}
else {
	$a_id="";
	$area = array();
	$area["a_id"] = "";
	$area["a_name"] = "";
	$area["a_description"] = "";
	$asups = array($user);
}
?>
<form method="post">
<input type="hidden" name="action" value="update">
<input type="hidden" name="a_id" value="<?php print $a_id?>">
<table>
<tr><td align="right" class="text"><?php print $es_lang["area_name"]; ?></td><td><input type="text" name="a_name" value="<?php print $area["a_name"]?>" size="30" maxlength="50"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["description"]; ?></td><td><textarea name="a_description" rows="5" cols="50"><?php print $area["a_description"]?></textarea></td></tr>
<tr><td colspan="2"><br></td></tr>
<tr><td align="right" class="text"></td><td class="text"><?php print $es_lang["area_sups"]; ?><br>
<select name="u_ids[]" size="10" multiple>
<?php
	$supervisors = get_supervisors();
	$sups = get_area_supervisors($area["a_id"]);
	foreach($supervisors as $supervisor) {
		print "<option value=\"".$supervisor["user_id"]."\"";
		foreach($sups as $sup) {
			if ($sup["user_id"]==$supervisor["user_id"]) {
				print " selected";
				break;
			}
		}
		print ">".$supervisor["user_name"]."</option>\n";
	}
?>
</select>
</td></tr>
<tr><td colspan="2"><br></td></tr>
<tr><td></td><td><input type="submit" value="<?php print $es_lang["save"]; ?>"></td></tr>
</table>
<?php
print_footer();
?>
