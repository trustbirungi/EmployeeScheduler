<?php 
	$theaders = array("Hours", "MON", "TUE", "WED", "THUR", "FRI", "SAT", "SUN");

	$tday = array("6:00AM", "7:00AM", "8:00AM", "9:00AM", "10:00AM", "11:00AM", "12:00PM", "1:00PM", "2:00PM", "3:00PM", "4:00PM", "5:00PM", "6:00PM");

	$tnight = array("6:00PM", "7:00PM", "8:00PM", "9:00PM", "10:00PM", "11:00PM", "12:00AM", "1:00AM", "2:00AM", "3:00AM", "4:00AM", "5:00AM", "6:00AM");

	$user_netid = $user["user_netid"];

	$query = "SELECT shift_type, days_off FROM es_user WHERE user_netid = '$user_netid'";

	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	$shift_type = $row["shift_type"];

	$days_off = str_split($row["days_off"]);

	$x = $days_off[0];
	$y = $days_off[1];
	$z = $days_off[2];

?>


	<table border = "2">
		<thead>
			<?php 
				for($i = 0; $i < sizeof($theaders) ; $i++){	
					echo "<th>". $theaders[$i] ."</th>";
					
				}
			?>
		</thead>

		<?php 

		if($shift_type == "day") {
			for($i = 0; $i < sizeof($tday); $i++) {
				echo "<tr>";
				echo "<td>". $tday[$i] ."</td>";
				for($j = 0; $j < 7; $j++) {
					if($j == $x || $j == $y || $j == $z) {
						echo "<td class = 'freecell'></td>";
						continue;
					}

					echo "<td class = 'timecell'></td>";
				}

				echo "</tr>";
			}

		}else {
			for($i = 0; $i < sizeof($tnight); $i++) {
				echo "<tr>";
				echo "<td>". $tnight[$i] ."</td>";
				for($j = 0; $j < 7; $j++) {
					if($j == $x || $j == $y || $j == $z) {
						echo "<td class = 'freecell'></td>";
						continue;
					}

					echo "<td class = 'timecell'></td>";
				}

				echo "</tr>";
			}
		}

		?>
		
</table>


