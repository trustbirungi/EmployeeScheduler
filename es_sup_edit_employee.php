<?php
/*********************************************************
	File: es_sup_edit_employee.php
	Project: Employee Scheduler
	
	Comments:
		Edit an employee's information.
		
	
**********************************************************/


$user_color = "#FFFFFF";
$user_max = "12";
$user_min = "12";
$user_language = "English";

require "es_functions.php";


$user = auth_supervisor();
print_header($es_lang["edit_employee"]);
print '<br><br><span class="pagetitle">'.$es_lang["edit_employee"].' '.$es_lang["information"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';

if (!isset($action)) $action="";
if (!isset($deletepic)) $deletepic="";
if (!isset($u_id)) $u_id = "";

//-- check for the form being submitted
if ($action=="update") {
	//-- make sure there is at least one supervisor in the list unless they are creating a supervisor
	if (!isset($u_supervisors)) {
		$u_supervisors = array();
		if ($user_newtype!="Supervisor") $u_supervisors[]=$user["user_id"];
	}
	$picture="";
	//-- check is the passwords are the same
	if (!isset($pass1)) $pass1="";
	if (!isset($pass2)) $pass2="";
	if ($pass1!=$pass2) {
		print "\n<br><font class=\"error\">".$es_lang["password_mismatch"]."</font><br>\n";
	}
	else {
		//-- check for a unique username
		$sql = "SELECT * FROM es_user WHERE user_netid='".addslashes($user_netid)."'";
		$tuser = get_db_items($sql);
		if ((count($tuser)>0)&&($u_id!=$tuser[0]["user_id"])) {
			$tuser = $tuser[0];
			$tsups = get_employee_supervisors($tuser);
			print "<br><font class=\"error\">".$es_lang["username_exists"]."  ".$es_lang["contact_users_sup"].$tsups[0]["user_name"].$es_lang["have_assigned"]."<br></font>\n";
		}
		//-- add or update the database
		else {
			if (preg_match("/[0-9a-fA-F]{6}/", $user_color)==0) $user_color = "#".dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15));
			//-- if the desired hours is empty then set it to the maximum
			if (empty($user_hours)) $user_hours = $user_max;
			//-- if the u_id is empty then we are adding a new user
			if (empty($u_id)) {
				//Generate the shift
				$days_off = shift_generator();


				//-- create the sql statement
				$sql = "INSERT INTO es_user (user_netid, user_type, user_name, user_major, user_workphone, user_homephone, user_location, user_email, user_min, user_max, user_hours, user_supnotes, user_notes, user_color, user_language, shift_type, days_off";
				if ((!empty($picture))||($deletepic)) $sql .= ", user_picture";
				if (!empty($pass1)) $sql .= ", user_password";
				$sql .= ") VALUES ('".addslashes($user_netid)."', 
					'".addslashes($user_newtype)."', 
					'".addslashes($user_name)."', 
					'".addslashes($user_major)."',
					'".addslashes($user_workphone)."',
					'".addslashes($user_homephone)."',
					'".addslashes($user_location)."',
					'".addslashes($user_email)."',
					'".addslashes($user_min)."',
					'".addslashes($user_max)."',
					'".addslashes($user_hours)."',
					'".addslashes($user_supnotes)."',
					'".addslashes($user_notes)."',
					'".addslashes($user_color)."',
					'".addslashes($user_language)."',
					'".addslashes($_POST["schedule_type"])."',
					'".addslashes($days_off)."'";
				if ((!empty($picture))||($deletepic)) $sql .= ", '".addslashes($picture)."'";
				if (!empty($pass1)) $sql .= ", '".addslashes(crypt($pass1))."'";
				$sql .= ")";
				$res = dbquery($sql);
				$u_id = mysql_insert_id();
				//-- handle the file upload if a picture was sent
				if (($_FILES["user_picture"]["error"]==0)&&(!empty($_FILES["user_picture"]["tmp_name"]))) {
					move_uploaded_file($_FILES["user_picture"]["tmp_name"], "./photos/".$u_id."-".$_FILES["user_picture"]["name"]);
					$picture = $u_id."-".$_FILES["user_picture"]["name"];
					$sql = "UPDATE es_user SET user_picture='".addslashes($picture)."' WHERE user_id=$u_id";
					$res = dbquery($sql);
				}
				//-- update the users supervisors in the list
				foreach($u_supervisors as $sup_id) {	
					$sql = "INSERT INTO es_user_sups VALUES (NULL, ".$sup_id.", $u_id)";
					$res = dbquery($sql);
				}
			}
			//-- update the user in the database
			else {
				//-- get the users information
				$employee = get_user($u_id);
				//-- don't allow changing the type if the employee is an admin and the supervisor is not an admin
				if (strstr($user["user_type"], "Admin")===false) {
					if (strstr($employee["user_type"], "Admin")!==false) $user_newtype = $employee["user_type"];
				}
				//-- if we are deleting a picture then we need to delete it from the photos directory
				if ($deletepic) {
					if ((!empty($employee["user_picture"]))&&(file_exists("./photos/".$employee["user_picture"]))) unlink("./photos/".$employee["user_picture"]);
				}
				//-- check if a valid file was uploaded without errors and delete any old pictures before updating with the new one
				if (is_array($_FILES["user_picture"])) {
					if ((empty($_FILES["user_picture"]["error"]))&&(!empty($_FILES["user_picture"]["tmp_name"]))) {
						if ((!empty($employee["user_picture"]))&&(file_exists("./photos/".$employee["user_picture"]))) unlink("./photos/".$employee["user_picture"]);
						move_uploaded_file($_FILES["user_picture"]["tmp_name"], "./photos/".$employee["user_id"]."-".$_FILES["user_picture"]["name"]);
						$picture = $employee["user_id"]."-".$_FILES["user_picture"]["name"];
					}
				}
				//-- build the sql statement
				$sql = "UPDATE es_user SET 
					user_netid='".addslashes($u_netid)."', 
					user_type='".addslashes($u_newtype)."', 
					user_name='".addslashes($u_name)."', 
					user_major='".addslashes($u_major)."',
					user_workphone='".addslashes($u_workphone)."',
					user_homephone='".addslashes($u_homephone)."',
					user_location='".addslashes($u_location)."',
					user_email='".addslashes($u_email)."',
					user_min='".addslashes($u_min)."',
					user_max='".addslashes($u_max)."',
					user_hours='".addslashes($u_hours)."',
					user_supnotes='".addslashes($u_supnotes)."',
					user_color='".addslashes($u_color)."',
					user_notes='".addslashes($u_notes)."',
					user_language='".addslashes($u_language)."'";
				if ((!empty($picture))||($deletepic)) $sql .= ", user_picture='".addslashes($picture)."'";
				if (!empty($pass1)) $sql .= ", user_password='".addslashes(crypt($pass1))."'";
				$sql .= " WHERE user_id=$u_id";
				$res = dbquery($sql);
				//-- delete any old supervisor links and then create the new ones
				$sql = "DELETE FROM es_user_sups WHERE us_emp_id=$u_id";
				$res = dbquery($sql);
				foreach($u_supervisors as $sup_id) {	
					$sql = "INSERT INTO es_user_sups VALUES (NULL, ".$sup_id.", $u_id)";
					$res = dbquery($sql);
				}
			}
		}
	}
}	//-- end update action

//-- if the u_id is empty we are creating a new user, if not then get the information for the old user we are editing
if (!empty($u_id)) {
	$employee = get_user($u_id, true);
	if (empty($employee["user_color"])) $employee["user_color"] = "#".dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15));
	print "<br />When editing a user leave the password fields blank to keep the old password.<br />\n";
}
else {
	if (empty($u_newtype)) $u_newtype="Employee";
	$employee = array();
	$employee["user_min"]=10;
	$employee["user_max"]=20;
	$employee["user_type"]=$u_newtype;
	$employee["user_netid"]="";
	$employee["user_name"]="";
	$employee["user_major"]="";
	$employee["user_workphone"]="";
	$employee["user_homephone"]="";
	$employee["user_location"] = "";
	$employee["user_email"] = "";
	$employee["user_hours"] = "";
	$employee["user_notes"] = "";
	$employee["user_supnotes"] = "";
	$employee["user_language"] = "";
	$employee["user_color"] = "#".dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15));
}
?>
<SCRIPT LANGUAGE="JavaScript" SRC="ColorSelector.js"></SCRIPT>
<script language="JavaScript">
	//-- the following function will display the fields that allows adminsitrators to enter passwords
	function show_password(type) {
		row1 = document.getElementById("passrow1");
		row2 = document.getElementById("passrow2");
		if (type.selectedIndex==0 || type.selectedIndex==2 || type.selectedIndex==4) {
			row1.style.display="block";
			row2.style.display="block";
		}
		else {
			row1.style.display="none";
			row2.style.display="none";
		}
	}
	
	function checkform(frm) {
		if (frm.u_netid.value=="") {
			alert('<?php print $es_lang["username_required"];?>');
			frm.u_netid.focus();
			return false;
		}
		if (frm.u_name.value=="") {
			alert('<?php print $es_lang["name_required"];?>');
			frm.u_name.focus();
			return false;
		}
		return true;
	}
	
	function change_background(colorbox) {
		colorbox.style.backgroundColor = colorbox.value;
	}
</script>
<form method="post" name="userform" enctype="multipart/form-data" onsubmit="return checkform(this);">
<input type="hidden" name="user_id" value="<?php print $u_id?>">
<input type="hidden" name="action" value="update">
<table>
<tr><td align="right" class="text"><?php print $es_lang["username"];?></td><td><input type="text" name="user_netid" value="<?php print $employee["user_netid"]?>" maxlength="30"></td>
<td rowspan="9">
<?php if (!empty($employee["user_picture"])) print '<img align="right" src="photos/'.$employee["user_picture"].'" height="200">'; ?>
</td></tr>
<tr><td align="right" class="text"><?php print $es_lang["employee_type"];?></td><td><select name="user_newtype" onchange="show_password(this);">
	<option value="Employee" <?php if ($employee["user_type"]=="Employee") print "selected";?>><?php print $es_lang["employee"];?></option>
	
	<option value="Supervisor" <?php if ($employee["user_type"]=="Supervisor") print "selected";?>><?php print $es_lang["supervisor"];?></option>
	
<?php if (strstr($user["user_type"],"Admin")!==false) { ?>
	<option value="Admin" <?php if ($employee["user_type"]=="Admin") print "selected";?>><?php print $es_lang["admin"];?></option>
	
<?php } ?>
</select>
</td></tr>
<?php
$disp="none";
if (preg_match("/LDAP/", $employee["user_type"])==0) {
	$disp="block";
}
print '<tr><td align="right" class="text">'.$es_lang["password"].'</td><td><input type="password" name="pass1"></td></tr>';
print '<tr><td align="right" class="text">'.$es_lang["confirm_password"].'</td><td><input type="password" name="pass2"></td></tr>';
?>
<tr><td align="right" class="text"><?php print $es_lang["full_name"]; ?></td><td><input type="text" name="user_name" value="<?php print $employee["user_name"]?>"></td></tr>
<tr><td align="right" class="text"><?php print "Title"; ?></td><td><input type="text" name="user_major" value="<?php print $employee["user_major"]?>"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["work_phone"]; ?></td><td><input type="text" name="user_workphone" value="<?php print $employee["user_workphone"]?>"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["home_phone"]; ?></td><td><input type="text" name="user_homephone" value="<?php print $employee["user_homephone"]?>"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["location"]; ?></td><td><input type="text" name="user_location" value="<?php print $employee["user_location"]?>"></td></tr>
<tr><td align="right" class="text"><?php print $es_lang["email"]; ?></td><td><input type="text" name="user_email" value="<?php print $employee["user_email"]?>"></td></tr>


<tr><td align="right" class="text"><?php print "Schedule Type";?></td><td><select name="schedule_type">
	<option value="day"><?php print "Day";?></option>
	
	<option value="night"><?php print "Night";?></option>
	
<?php  ?>
</select>
</td></tr>


<tr><td align="right" class="text" valign="top"><?php print $es_lang["notes"]; ?></td><td colspan="2"><textarea name="user_notes" rows=5 cols=50><?php print $employee["user_notes"]?></textarea></td></tr>
<tr><td align="right" class="text" valign="top"><?php print $es_lang["sup_notes"]; ?></td><td colspan="2"><textarea name="user_supnotes" rows=5 cols=50><?php print $employee["user_supnotes"]?></textarea></td></tr>
<tr><td colspan="3"><br></td></tr>
<tr><td align="right" class="text" valign="top"><?php print $es_lang["upload_picture"]; ?></td><td colspan="2" class="text"><input type="hidden" name="MAX_FILE_SIZE" value="150000"><input type="file" name="user_picture"><br>
<?php if (!empty($employee["user_picture"])) print '<input type="checkbox" name="deletepic" value="1"> Delete Picture'; ?>
</td></tr>
<tr><td colspan="3"><br></td></tr>
<tr><td align="right" class="text" valign="top"><?php print $es_lang["supervisors"]; ?></td><td colspan="2"><select name="u_supervisors[]" size="10" multiple>
<?php
	//-- get the supervisors for the supervisor selection list
	$sups = get_employee_supervisors($employee);
	$supervisors = get_supervisors();
	foreach($supervisors as $supervisor) {
		print "<option value=\"".$supervisor["user_id"]."\"";
		//-- compare each supervisor with the user's supervisor list to see if their is a match
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
<tr><td colspan="3"><br></td></tr>
<tr><td></td><td><input type="submit" value="<?php print $es_lang["update"]; ?>"></td></tr>
</table>
</form>
<?php
	
print_footer();

print_supervisor_menu();
?>
