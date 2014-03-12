<?php


if (mail('diamondtrust66@gmail.com', 'Works!', 'An email has been generated from your localhost, congratulations!')) {

	//echo "success";

}else {
	//echo "failure";
}

echo "<br />";
//echo substr('20 October 1998', 2, -4);

$mon = substr('20October1998', 2, -4);
echo $mon;

					$months = array("MONTH", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

						
						
					foreach ($months as $month) 
					{
						

						if(strcmp($month, "October") == 0) {
					
							echo "<br />Success";
							break;
						}else {
							echo "<br />Failure";
							break;
						}
					}

?>