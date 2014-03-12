<?php
/*********************************************************
	File: es_emp_edit_info.php
	Project: Employee Scheduler
	
	Comments:
		Edit an employee's information.
		
	
**********************************************************/

require "es_functions.php";
dbconnect();

$user = auth_user();

if (!empty($u_language)) {
	if ($user["user_language"]!=$u_language) {
		$_SESSION["CLANGUAGE"] = $u_language;
		$LANGUAGE = $u_language;
		if (isset($es_language[$LANGUAGE])) require($es_language[$LANGUAGE]);
	}
}
print_header($es_lang["my_info"]);

if (!isset($action)) $action = "";
if (!isset($deletepic)) $deletepic = "";

if ($action=="update") {
	$picture="";
	if ($deletepic) if ((!empty($user["user_picture"]))&&(file_exists("./photos/".$user["user_picture"]))) unlink("./photos/".$user["user_picture"]);
	if ($_FILES["user_picture"]["error"]==0) {
                if ((!empty($user["user_picture"]))&&(file_exists("./photos/".$user["user_picture"]))) unlink("./photos/".$user["user_picture"]);
                move_uploaded_file($_FILES["user_picture"]["tmp_name"], "./photos/".$user["user_id"]."-".$_FILES["user_picture"]["name"]);
                $picture = $user["user_id"]."-".$_FILES["user_picture"]["name"];
        }
	$sql = "UPDATE es_user SET 
		user_name='".addslashes($u_name)."', 
		user_major='".addslashes($u_major)."',
		user_workphone='".addslashes($u_workphone)."',
		user_homephone='".addslashes($u_homephone)."',
		user_location='".addslashes($u_location)."',
		user_email='".addslashes($u_email)."',
		user_hours='".addslashes($u_hours)."',
		user_notes='".addslashes($u_notes)."',
		user_language='".addslashes($u_language)."'";
	if ((!empty($picture))||($deletepic)) $sql .= ",user_picture='".addslashes($picture)."'";
	if (!empty($pass1)) {
		if ($pass1==$pass2) $sql .= ", user_password='".addslashes(crypt($pass1))."'";
	}
	$sql .= " WHERE user_id=".$user["user_id"];
	$res = dbquery($sql);
	$user = get_user($user["user_id"]);
}

print '<br><br><span class="pagetitle">'.$es_lang["my_info"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';
?>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="update">
<table>
<tr><td align="right" class="text"><?php print $es_lang["username"]; ?></td><td><?php print $user["user_netid"]?></td>
<td rowspan="9">
<?php if (!empty($user["user_picture"])) print '<img align="right" src="photos/'.$user["user_picture"].'" height="200">'; ?>
</td></tr>
<?php
if ($user["user_type"]=="Employee") {
	print '<tr><td align="right" class="text">'.$es_lang["password"].'</td><td><input type="password" name="pass1"></td></tr>';
	print '<tr><td align="right" class="text">'.$es_lang["confirm_password"].'</td><td><input type="password" name="pass2"></td></tr>';
}
?>
<tr><td align="right" class="text"><?php print $es_lang["full_name"]; ?></td><td><input type="text" name="user_name" value="<?php print $user["user_name"]?>"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["major"]; ?></td><td><input type="text" name="user_major" value="<?php print $user["user_major"]?>"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["work_phone"]; ?></td><td><input type="text" name="user_workphone" value="<?php print $user["user_workphone"]?>"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["home_phone"]; ?></td><td><input type="text" name="user_homephone" value="<?php print $user["user_homephone"]?>"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["location"]; ?></td><td><input type="text" name="user_location" value="<?php print $user["user_location"]?>"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["email"]; ?></td><td><input type="text" name="user_email" value="<?php print $user["user_email"]?>"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["minimum_hours"]; ?></td><td><?php print $user["user_min"]?></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["maximum_hours"]; ?></td><td><?php print $user["user_max"]?></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["desired_hours"]; ?></td><td><input type="text" name="user_hours" value="<?php print $user["user_hours"]?>" size="5"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["preferred_language"]; ?></td><td><select name="user_language">
<?php
foreach($es_language as $language=>$langfile) {
	print "<option value=\"".$language."\"";
	if ($user["user_language"]==$language) print " selected=\"selected\"";
	print ">".$es_lang[$language]."</option>\n";
}
?>
</select>
</td></tr>
<tr><td align="right" class="text" valign="top"><?php print $es_lang["notes"]; ?></td><td colspan="2"><textarea name="user_notes" rows=5 cols=50><?php print $user["user_notes"]?></textarea></td></tr>
<tr><td colspan="3"><br></td></tr>
<tr><td align="right" class="text" valign="top"><?php print $es_lang["upload_picture"]; ?></td><td colspan="2" class="text"><input type="hidden" name="MAX_FILE_SIZE" value="150000"><input type="file" name="user_picture"><br>
<?php if (!empty($user["user_picture"])) print '<input type="checkbox" name="deletepic" value="1"> Delete Picture'; ?>
</td></tr>
<tr><td colspan="3"><br></td></tr>
<tr><td></td><td><input type="submit" value="<?php print $es_lang["update"]; ?>"></td></tr>
</table>
</form>
<?php
	
print_footer();
?>
