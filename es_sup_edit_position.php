<?php
/*********************************************************
	File: es_sup_edit_position.php
	Project: Employee Scheduler
	Comments:
		Allows a supervisor to edit positions
	
	
**********************************************************/

require "es_functions.php";
dbconnect();
$user = auth_supervisor();

if (!isset($action)) $action="";

if ($action=="update") {
	if (empty($p_id)) {
		$sql  = "INSERT INTO es_position VALUES (NULL, '".addslashes($p_name)."', '".addslashes($p_description)."', $p_a_id)";
		$res = dbquery($sql);
	}
	else {
		$sql  = "UPDATE es_position SET p_name='".addslashes($p_name)."', p_description='".addslashes($p_description)."', p_a_id=$p_a_id WHERE p_id=$p_id";
		$res = dbquery($sql);
	}
	if ($res) header("Location: es_sup_index.php?".session_name()."=".session_id());
}

print_header($es_lang["edit_position"]);

print '<br><br><span class="pagetitle">'.$es_lang["edit_position"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';
print "<br><br>";

if (!empty($p_id)) {
	$position = get_position($p_id, true);
}
else {
	$p_id="";
	$position = array();
	$position["p_name"] = "";
	$position["p_description"] = "";
	if (!empty($a_id)) $area = get_area($a_id);
	else $area = array();
	$position["p_area"] = $area;
}
?>
<form method="post">
<input type="hidden" name="action" value="update">
<input type="hidden" name="p_id" value="<?php print $p_id?>">
<table>
<tr><td align="right" class="text"><?php print $es_lang["pos_name"]; ?></td><td><input type="text" name="p_name" value="<?php print $position["p_name"]?>" size="20" maxlength="20"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["description"]; ?></td><td><textarea name="p_description" rows="5" cols="50"><?php print $position["p_description"]?></textarea></td></tr>
<tr><td colspan="2"><br></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["area"]; ?></td><td class="text"><select name="p_a_id">
<?php
	$areas = get_supervisor_areas($user);
	foreach($areas as $area) {
		print "<option value=\"".$area["a_id"]."\"";
		if ($position["p_area"]["a_id"]==$area["a_id"]) print " selected";
		print ">".$area["a_name"]."</a>\n";
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
