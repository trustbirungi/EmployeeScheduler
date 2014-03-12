<?php require("es_functions.php") ?>


<!DOCTYPE html>
<!--[if IE 7]>					<html class="ie7 no-js" lang="en">     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js" lang="en">     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" lang="en">  <!--<![endif]-->
<head>
<title>MakHospital | Login</title>

<link rel="shortcut" href="images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="./stylesheets/style.css" />

<link href = "reg.css" type = "text/css" rel = "stylesheet" />
<link href = "styles.css" type = "text/css" rel = "stylesheet" />

<!-- initialize jQuery Library -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<!--[if lt IE 9]>
    <script src="js/modernizr.custom.js"></script>
	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
	<script type="text/javascript" src="js/ie.js"></script>
<![endif]-->

<meta charset="UTF-8"></head>
<body class="color-1 pattern-1 h-style-1 text-1">
	
	<!-- ***************** - BEGIN Top Holder - ***************** -->
	<div class="top-holder"></div><!--/ top-holder-->
	<!-- ***************** - END Top Holder - ******************* -->
	
	
	<!-- ***************** - BEGIN Wrapper - ******************* -->
	<div id="wrapper">
		
		
		

				<!-- ************ - BEGIN Breadcrumbs - ************** -->
				<div id="breadcrumbs">
					<a title="Home" href="#"></a>  
				</div><!--/ breadcrumbs-->	
				<!-- ************ - END Breadcrumbs - ************** -->

				
				<!-- ************ - BEGIN Content Wrapper - ************** -->	
				<div class="content-wrapper">
				
					<?php 
						//-- authenticate the user
						$user = auth_user();
						$url = "";
						//-- if they are a supervisor then forward them to the supervisor home page
						if (preg_match("/(Supervisor)|(Admin)/", $user["u_type"])) $url= "es_sup_index.php?".session_name()."=".session_id();
						//-- forward to employee homepage
						else $url = "es_emp_index.php?".session_name()."=".session_id();
						header("Location: $url");

						print_header("Welcome");
						print_r($user);
						print "<br /><br />If you are seeing this, your browser did not forward you to the correct page.  Click <a href=\"$url\">here</a> to continue.<br />\n";
						print_footer();
						//exit;

					?>
					
				</div><!--/ content-wrapper-->
				<!-- ************ - END Content Wrapper - ************** -->	

				
			</section><!--/ content-->
			
			
			<?php include("footer.php") ?>
			
			
		</div><!--/ #content-wrapper-->
		
		
		

	</div><!--/ wrapper--> 
	<!-- ***************** - END Wrapper - ***************** -->	
	
	<div id="back-top">
		<a href="#top"></a>
	</div><!--/ back-top-->
	
<script type="text/javascript" src="./js/general.js"></script>
</body>
</html>

