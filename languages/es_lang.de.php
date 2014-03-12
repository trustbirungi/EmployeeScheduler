<?php
/*********************************************************
	File: es_lang.en.php
	Project: Employee Scheduler
	Author: John Finlay
	Revision: $Revision: 1.3 $
	Date: $Date: 2004/12/02 18:47:06 $
	Comments:
		English Language file.
	
	Copyright (C) 2003  Brigham Young University

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
**********************************************************/

//-- the starting date of the week: 0 = Sunday
$WEEKSTART = 1;
$TIME_FORMAT = "g:i a";

//-- days of the week
$es_lang["sunday"]		= "Sonntag";
$es_lang["monday"]		= "Montag";
$es_lang["tuesday"]		= "Dienstag";
$es_lang["wednesday"]	= "Mittwoch";
$es_lang["thursday"]	= "Donnerstag";
$es_lang["friday"]		= "Freitag";
$es_lang["saturday"]	= "Samstag";

$es_lang["weekly"]		= "Wöchentlich";
$es_lang["weeks"]		= "Wochen";

//-- es_functions.php file messages
$es_lang["error_page_title"]	= "Employee Scheduler Fehler";
$es_lang["no_db_connect"]		= "Verbindung zu MySQL Datenbank auf $DBHOST Benutzernamen $DBUSER fehlgeschlagen";
$es_lang["no_db_select"]		= "Datenbank $DBNAME auf $DBHOST konnte nicht gewählt werden";
$es_lang["query_error"]			= "Beim Ausführen folgender Abfrage trat ein Fehler auf:";
$es_lang["netid_not_exists"]	= "Der Benutzername existiert nicht.";
$es_lang["invalid_credentials"]	= "Benutzername und Passwort sind unkorrekt.";
$es_lang["no_ldap"]				= "Verbindung zu LDAP Server fehlgeschlagen";
$es_lang["no_employee"]			= "Benutzername und Passwort passen nicht zu einem gültigen Mitarbeitereintrag.";
$es_lang["access_denied"]		= "Zugriff verweigert. Nur Supervisoren dürfen auf diesen Bereich zugreifen.";
$es_lang["login_title"]			= "Employee Scheduler Login";
$es_lang["enter_username"]		= "Bitte geben Sie Benutzernamen und Passwort an, um sich einzuloggen";
$es_lang["username"]			= "Benutzername:";
$es_lang["password"]			= "Passwort:";
$es_lang["login"]				= "Login";
$es_lang["help"]				= "Hilfe";
$es_lang["printer_friendly"]	= "Druckversion";
$es_lang["logout"]				= "Logout";
$es_lang["welcome"]				= "Willkommen ";
$es_lang["my_schedule"]			= "Mein Zeitplan";
$es_lang["my_past_schedule"]		= "Meine vergangenen Zeitpläne";
$es_lang["my_info"]				= "Meine Informationen";
$es_lang["my_sups"]				= "Meine Supervisoren";
$es_lang["my_colleagues"]		= "Meine Kollegen";
$es_lang["my_positions"]		= "Meine Positionen";
$es_lang["reports"]				= "Berichte";
$es_lang["supervisors"]			= "Supervisoren";
$es_lang["employees"]			= "Mitarbeiter";
$es_lang["areas_and_positions"]	= "Bereiche & Positionen";
$es_lang["update_successful"]	= "Änderung erfolgreich durchgeführt";
$es_lang["area_list"]			= "Bereichsliste";
$es_lang["default_user"]		= "Legen Sie einen Standard-Supervisor an und beginnen Sie, das System zu nutzen.";
$es_lang["edit_settings"]		= "Einstellungen anpassen";

//-- edit employee messages
$es_lang["edit_employee"]		= "Berabeite Mitarbeiter";
$es_lang["information"]			= "Information";
$es_lang["password_mismatch"]	= "Die eingegebenen Passwörter stimmen nicht überein.";
$es_lang["username_exists"]		= "Es existiert bereits ein Benutzer mit diesem Benutzernamen.";
$es_lang["contact_users_sup"]	= "Bitte kontaktieren Sie den Supervisor dieses Benutzers, ";
$es_lang["have_assigned"]		= ", um diese auch Ihnen zuzuordnen.";
$es_lang["username_required"]	= "Sie müssen einen Benutzernamen angeben.";
$es_lang["name_required"]		= "Sie müssen einen Namen angeben.";
$es_lang["employee_type"]		= "Mitarbeiter Typ:";
$es_lang["employee"]			= "Mitarbeiter";
$es_lang["ldap_employee"]		= "LDAP Mitarbeiter";
$es_lang["supervisor"]			= "Supervisor";
$es_lang["ldap_supervisor"]		= "LDAP Supervisor";
$es_lang["admin"]				= "Administrator";
$es_lang["ldap_admin"]			= "LDAP Administrator";
$es_lang["confirm_password"]	= "Passwortbestätigung";
$es_lang["full_name"]			= "Voller Name:";
$es_lang["major"]				= "Major:";
$es_lang["work_phone"]			= "Telefon mobil:";
$es_lang["home_phone"]			= "Telefon Zuhause:";
$es_lang["location"]			= "Ort:";
$es_lang["email"]				= "Email:";
$es_lang["minimum_hours"]		= "Minimal benötigte Stunden:";
$es_lang["maximum_hours"]		= "Maximal mögliche Stunden:";
$es_lang["desired_hours"]		= "Gewünschte Stunden:";
$es_lang["notes"]				= "Bemerkungen:";
$es_lang["sup_notes"]			= "Supervisor Bemerkungen:";
$es_lang["upload_picture"]		= "Lade Foto hoch:";
$es_lang["update"]				= "Ändern";
$es_lang["delete_pic"]			= "Lösche Foto";
$es_lang["user_color"]			= "Benutzer Farbe";

//-- colleagues messages
$es_lang["my_colleagues"]		= "Meine Kollegen";
$es_lang["no_colleagues"]		= "Aktuell sind keine Kollegen zugewiesen, um mit Ihnen zu arbeiten.";
$es_lang["view_schedule"]		= "betrachte Zeitplan";
$es_lang["position_assignments"]	= "Positionszuordnung";

//-- My Information messages
$es_lang["my_info"] 			= "Meine Informationen";

//-- Edit schedule messages
$es_lang["email_subject"]		= "Zeitplan geändert";
$es_lang["hello"]				= "Hallo";
$es_lang["email_msg1"]			= "Ein Zeitplan wurde geändert für einen Ihrer Mitarbeiter, ";
$es_lang["email_msg2"]			= "\nSie können sich den Zeitplan anschauen, indem Sie den folgenden Link anklicken und sich einloggen:";
$es_lang["email_msg3"]			= "Falls Sie Fragen haben zu diesem Zeitplan, wenden Sie sich an";
$es_lang["edit_my_schedule"]	= "Meinen Zeitplan bearbeiten";
$es_lang["confirm_comment"]		= "Sind Sie sicher, daß Sie diesen Kommentar löschen wollen?";
$es_lang["confirm_course"]		= "Sind Sie sicher, daß SIe diesen Kurs löschen wollen?";
$es_lang["edit_instructions"]	= "Bearbeiten Sie Ihren Zeitplan weiter unten.  Alle Zeitblöcke stehen standardmäßig auf \"Nicht verfügbar\". Markieren Sie die Zeiten, zu denen Sie verfügbar sind, indem Sie eine Wunschstufe wählen und dann in die entsprechenden Kästchen im Zeitplan klicken.  Die Wunschstufe 3 bedeutet, daß Sie unbedingt zu dieser Zeit arbeiten wollen. Wuschstufe 1 bedeutet, daß Sie zwar zu dieser Zeit verfügbar sind, aber nicht notwendigerweise dort arbeiten wollen. <br><br>Haben Sie bereits ein Zeitintervall mit einer Wuschstufe markiert und möchten dies wieder rückgängig machen, wählen Sie wieder \"Nicht verfügbar\" an und klicken auf die entsprechenden Kästchen.<br><br> Ihr Supervisor möchte möglicherweise Ihren groben Studienplan, falls Sie Student sind, einsehen können. Sie können Ihre Studienzeiten markieren, indem Sie auf \"Kurs\" klicken und mit dieser Farbe die entsprechenden Zeiten im Zeitplan markieren.  Zum entfernen eines solchen Eintrages klicken Sie bitte den Enfernen-Link daneben an.<br><br>Sie können außerdem einen Kommentar für Ihren Supervisor bezüglich einer bestimmten Termins hinterlegen, indem Sie \"Kommentar\" anwählen und die entsprechende Stelle im Zeitplan markieren.  Um einen Kommntar zu entfernen, klicken Sie bitte auf den Entfernen-Link daneben.<br><br>Wenn Ihr Supervisor Sie für einen bestimmten Zeitraum fest einteilt, wird dieser Zeitraum weiß markiert und der Name der zugeteilten Position erscheint darin.  Sie müssen den Supervisor kontaktieren, falls Sie eine Änderung für einen Zeitraum wünschen, für den Sie bereits fest eingeteilt wurden.";
$es_lang["edit_schedule"]	= "Zeitplan bearbeiten";
$es_lang["course"]			= "Kurs";
$es_lang["unavailable"]		= "Nicht verfügbar";
$es_lang["pref1"]			= "Wunschstufe 1 (niederigste)";
$es_lang["pref2"]			= "Wunschstufe 2";
$es_lang["pref3"]			= "Wunschstufe 3 (höchste)";
$es_lang["comment"]			= "Kommentar";
$es_lang["min_left"]		= "Restliche Stunden bis Minimum:";
$es_lang["max_left"]		= "Restliche Stunden bis Maximum:";
$es_lang["total_hours"]		= "Insgesamt verfügbare Stunden:";
$es_lang["hours_desired"]	= "Insgesamt gewünschte Stunden:";
$es_lang["total_scheduled"]	= "Insgesamt eingeplante Stunden:";
$es_lang["view24"]			= "24-Stunden Ansicht";
$es_lang["view18"]			= "18-Stunden Ansicht";
$es_lang["hours"]			= "Stunden";
$es_lang["sun"]				= "SON";
$es_lang["mon"]				= "MON";
$es_lang["tue"]				= "DIE";
$es_lang["wed"]				= "MIT";
$es_lang["thu"]				= "DON";
$es_lang["fri"]				= "FRE";
$es_lang["sat"]				= "SAM";
$es_lang["finished"]		= "Fertig";
$es_lang["building"]		= "Gebäude:";
$es_lang["fill"]			= "Fülle";
$es_lang["email_sup"]		= "Sollen meine Supervisoren über meinen geänderten Zeitplan per Email informiert werden?";
$es_lang["save_schedule"]	= "Zeitplan speichern";

//-- view schedule messages
$es_lang["schedule"]		= "Zeitplan";
$es_lang["emp_schedule"]	= "Mitarbeiter Zeitplan";
$es_lang["not_created"]		= "hat noch keinen Zeitplan angelegt.";
$es_lang["have_note_created"]	= "Sie haben noch keinen Zeitplan angelegt.";
$es_lang["click_to_create"]	= "Klicken Sie hier, um einen neuen anzulegen.";
$es_lang["to_help"]			= "Als Einstiegshilfe dient das";
$es_lang["tutorial"]		= "Tutorial";
$es_lang["list"]			= "Liste";
$es_lang["hide_weekends"]	= "Blende Wochenenden aus";
$es_lang["show_weekends"]	= "Zeige Wochenenden";
$es_lang["week"]			= "Woche";
$es_lang["pref_level"]		= "Wunschstufe";
$es_lang["past_schedules"]	= "Vergangene Zeitpläne";

//-- position schedule
$es_lang["no_pos_schedule"]	= "Sie haben noch keinen Zeitplan für diese Position angelegt.";
$es_lang["select_schedule"]	= "Wählen Sie unten einen anderen Zeitplan:";
$es_lang["view"]			= "Ansicht";
$es_lang["not_scheduled"]	= "Sie sind im Augenblick für keine Position zum arbeiten eingeteilt.  Wenn Ihr Supervisor Sie für eine Position fest einteilt, werden diese in der Liste erscheinen.";

//-- help page
$es_lang["help_page_title"]	= "Employee Scheduler Hilfe";
$es_lang["help_topics"]		= "Hilfethemen";

//-- areas
$es_lang["edit_area"]		= "Bearbeite Bereich";
$es_lang["area_name"]		= "Bereich Name:";
$es_lang["description"]		= "Beschreibung:";
$es_lang["area_sups"]		= "Supervisoren zu diesem Bereich:";
$es_lang["save"]			= "Speichern";
$es_lang["no_areas"]		= "Sie sind keinem Bereich zurgeordnet.";
$es_lang["click_new_area"]	= "Klicken Sie hier, um einen neuen Bereich anzulegen.";
$es_lang["sup_instructions"]	= "Als Einstiegshilfe möchten Sie möglicherweise das <a href=\"es_help.php?page=es_help_sup_tutorial.php\" target=\"help\">Supervisor Tutorial</a> durchlaufen, das Sie durch die Supervisor-Funktionen des <b>Employee Scheduler</b> führen wird.<br><br>\n";
$es_lang["add_area"]		= "Neuen Berecih hinzufügen";
$es_lang["area_confirm"]	= "Sind Sie sicher, daß Sie diesen Bereich enfernen wollen?  Das löschen eines Bereichs zieht das Löschen all seiner zugeorneten Positionen und Zeitpläne nach sich.  Außerdem werden alle Supervisor-Zuordnungen für diesen Bereich entfernt.";
$es_lang["add_position"]	= "Positionen hinzufügen";
$es_lang["pos_confirm"]		= "Sind Sie sicher, daß Sie diese Position löschen wollen?";

//-- edit employee schedule
$es_lang["hello"]			= "Hallo";
$es_lang["email_msg4"]		= "hat Ihren Zeitplan gändert.\nKlicken Sie auf folgenden Link und loggen Sie sich ein, um den Zeitplan einzusehen:";
$es_lang["email_msg5"]		= "Für Fragen zu diesem Zeitplan kontaktieren Sie bitte";
$es_lang["schedule_update"]	= "Zeitplan Änderung";
$es_lang["assign_to_pos"]	= "Einer Position zuordnen";
$es_lang["no_pos_schedules"]	= "<b>Sie haben noch keinen Zeitplan angelegt</b>  <br>Gehen Sie als erstes zu <a href=\"es_sup_index.php\">Bereiche und Positionen</a> und legen Sie einen Zeitplan für die benötigten Positionen an. Danach können Sie hierhin zurückkehren und Mitarbeiter für die Postions-Zeitpläne zuweisen.<br>\n";
$es_lang["show_on_schedule"]	= "Soll diese Zuweisung im Zeitplan des Mitarbeiters angezeigt werden?";
$es_lang["change_pos_sched"]	= "Positions-Zeitplan bearbeiten";
$es_lang["email_employee"]	= "Soll der Mitarbeiter über die Änderung seines Zeitplanes per Email benachrichtigt werden?";
$es_lang["schedule_conflict"]	= "Die eingegebenen Zeiten in diesem Zeitplan stehen in Konflikt mit einem anderem Zeitplan.  Zeitpläne mit Zeitüberlappungen sind nicht erlaubt.";

//-- edit position
$es_lang["edit_position"]	= "Position bearbeiten";
$es_lang["pos_name"]		= "Position Name:";
$es_lang["area"]			= "Bereich:";

//-- edit position schedule
$es_lang["new_name"]		= "Es existiert zu dieser Position bereits ein Zeitplan mit diesem Namen.  Bitte geben Sie einen neuen Namen für den Zeitplan ein.";
$es_lang["save_sucess"]		= "Zeitplan wurde erfolgreich gespeichert.";
$es_lang["email_msg6"]		= "Ein neuer Zeiplan für diese Postion wurde angelegt.";
$es_lang["email_msg7"]		= "Folgen Sie dem Link und loggen Sie sich ein, um den Zeitplan anzuschauen:";
$es_lang["email_success"]	= "Emails erfolgreich gesendet an";
$es_lang["need_name"]		= "Sie müssen einen Namen für diesen Zeitplan angeben.";
$es_lang["no_hyphen"]		= "Bitte benutzen Sie keinen Bindestrich ('-') im Namen des Zeitplans.";
$es_lang["max_reached"]		= "Die maximal möglichen Stunden für diesen Mitarbeiter wurden erreicht.";
$es_lang["schedule_name"]	= "Zeitplan Name:";
$es_lang["repeat_interval"]	= "Wiederholungsinterval:";
$es_lang["start_date"]		= "Startdatum:";
$es_lang["end_date"]		= "Enddatum:";
$es_lang["email_employees"]	= "Die angebenen Mitarbeiter über den geänderten Zeitplan per Email informieren?";
$es_lang["no_schedule"]		= "Sie haben noch keinen Zeitplan für diese Postion angelegt.";
$es_lang["confirm_schedule"]	= "Sind Sie sicher, daß Sie den gewählten Zeitplan löschen wollen?";
$es_lang["select_schedule"]	= "Wählen Sie untenstehend einen anderen Zeitplan aus:";

//-- employee list
$es_lang["no_emp"]			= "Ihnen sind im Augenblick keine Mitarbeiter zugeordnet.";
$es_lang["click_new_emp"]	= "Hier klicken, um einen neuen Mitarbeiter hinzuzufügen.";
$es_lang["add_new_emp"]		= "Neuen Mitarbeiter hinzufügen";
$es_lang["edit"]			= "Bearbeiten";
$es_lang["delete"]			= "Löschen";
$es_lang["new"]				= "Neu anlegen";
$es_lang["emp_confirm"]		= "Sind Sie sicher, daß Sie diesen Mitarbeiter löschen wollen?";
$es_lang["email_emp"]		= "Email an alle Mitarbeiter";

//-- email employees
$es_lang["email_emp_inst"]	= "Benutzen Sie dieses Formular, um eine Email an alle Iher Mitarbeiter zu versenden.  Bitte Füllen Sie den Text für den Betreff und den Nachrichtentext aus und klicken Sie auf den Senden-Knopf.";
$es_lang["subject"]			= "Betreff";
$es_lang["body"]			= "Nachrichtentext";
$es_lang["send"]			= "Nachricht senden";
$es_lang["send_successful"]	= "Nachricht erfolgreich gesendet an";

//-- supervisor list
$es_lang["new_supervisor"]	= "Neuen Supervisor hinzufügen";
$es_lang["confirm_sup"]		= "Sind Sie sicher, daß Sie diesen Supervisor löschen möchten?";

//-- admin
$es_lang["settings_saved"]	= "Einstellungen gespeichert.";
$es_lang["se_SITE_URL"] 	= "Site URL";
$es_lang["se_SESSION_COOKIE_TIMEOUT"]	= "Session Timeout";
$es_lang["se_CHARACTER_SET"]	= "Zeichensatz";
$es_lang["se_SITE_ADMIN_EMAIL"]	= "Site Administrator Email";
$es_lang["se_COMPANY_URL"]		= "Firmen-URL";
$es_lang["se_COMPANY_NAME"]		= "Firmename";
$es_lang["se_START_HOUR"]		= "Früheste Startzeit";
$es_lang["se_END_HOUR"]			= "Späteste Endzeit";
$es_lang["se_DEFAULT_TIME_BLOCKS"]	= "Standardmäßige Zeitauflösung";
$es_lang["se_ES_SHOW_STATS"]	= "Zeige Ausführungs-Statistik";
$es_lang["yes"]					= "Ja";
$es_lang["no"]					= "Nein";
$es_lang["se_ES_FULL_MAIL_TO"]	= "Benutze vollständige Namen im Nachrichtenkopf";

//-- reports
$es_lang["emp_report"]			= "Mitarbeiter Stundenbericht";
$es_lang["emp_report_descr"]	= "Dieser Bericht zeigt an, wieviele Stunden Mitarbeiter innerhalb eines Zeitraumes pro Postion eingeteilt waren.";
$es_lang["pos_report"]			= "Bereichsposition Stundenreport";
$es_lang["pos_report_descr"]	= "Dieser Bereicht zeigt an, wieviele Stunden insgesamt innerhalb eines Zeitraumes für Ihre Bereiche und Postionen zugeteilt wurden.";
$es_lang["view_report"]			= "Bericht ansehen";
$es_lang["select_report"]		= "Wählen Sie untenstehend einen Bericht zur Ansicht aus:";
$es_lang["position"]			= "Position";
$es_lang["total_for_area"]		= "Gesamt Stunden pro Bereich";
$es_lang["total"]				= "Gesamt";

?>
