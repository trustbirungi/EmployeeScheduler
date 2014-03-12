<?php 
	include ("es_functions.php");


	$theaders = array("Hours", "MON", "TUE", "WED", "THUR", "FRI", "SAT", "SUN");

	/*$tdata = array("12:00AM", "1:AM", "2:AM", "3:00AM", "4:00AM", "5:00AM", "6:00AM", "7:00AM", "8:00AM", "9:00AM", "10:00AM", "11:00AM", "12:00PM", "1:00PM", "2:00PM", "3:00PM", "4:00PM", "5:00PM", "6:00PM", "7:00PM", "8:00PM", "9:00PM", "10:00PM", "11:00PM");*/

	$tdata = array("6:00AM", "7:00AM", "8:00AM", "9:00AM", "10:00AM", "11:00AM", "12:00PM", "1:00PM", "2:00PM", "3:00PM", "4:00PM", "5:00PM", "6:00PM");

	print_header("Shift Generator");
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
			$x = rand(0, 6);
			$y = rand(0, 6);
			$z = rand(0, 6);

			if($x == $y) {
				$y++;
				if($y == 6) {
					$y--;
				}
			}elseif($y == $z && $z != 6) {
				$z++;
			}elseif($x == $z && $x != 0) {
				$x--;
			}


			for($i = 0; $i < sizeof($tdata); $i++) {
				echo "<tr>";
				echo "<td>". $tdata[$i] ."</td>";
				for($j = 0; $j < 7; $j++) {
					if($j == $x || $j == $y || $j == $z) {
						echo "<td class = 'freecell'></td>";
						continue;
					}

					echo "<td class = 'timecell'></td>";


				}
				
				echo "</tr>";
				
			}

		?>
		
</table>


<?php 
	$days_off = $x.$y.$z;
	echo "<br />";
	echo $days_off;


	$arr_1 = str_split($days_off);
	echo "<br />";
	print_r($arr_1);
?>

<?php 
	function shift_generator($shift_type) {
			$x = rand(0, 6);
			$y = rand(0, 6);
			$z = rand(0, 6);

			$days_off = $x.$y.$z;

			$query = "INSERT INTO es_users (shift_type, days_off) VALUES ('$shift_type', '$days_off')";

	}

?>