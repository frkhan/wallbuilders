<?php

/* Hook Up To WP*/
require_once( '../../../../wp-load.php' );
/* End Hook */

$sc = base64_decode(trim($_GET['shortcode']));
$preview = base64_decode(trim($_GET['preview']));
?>

<!DOCTYPE HTML>
<html lang="en">
	<head>	
		<?php //wp_head(); ?>
		<!-- <link rel="stylesheet" type="text/css" href="<?php //echo MPC_TINYMCE_URI . '/css/mpc-win.css' ?>" media="all" /> -->
		
		<style type="text/css">
 			 body {
 			 	overflow: hidden;
 			 	background: #FFFFFF;
 			 	height: 100%;
 			 	padding: 0;
 			 	margin-top: -25px;
 			 }
 			 
 			 #shortcode-preview,
 			 #shortcode-preview-partial,
 			 #shortcode-preview-false {
 			 	border-top: none;
				padding: 25px 0px;
				margin: 45px 25px;
				display: block;
 			 }
 			 
 			 #shortcode-preview-false img {
 			 	margin-left: 105px;
 			 }
 			 
 			 #shortcode-code {
 			 	border-top: none;
				padding: 25px 0px;
				margin: 45px 25px;
				display: block;
 			 	
 			 } 
 			 
 			 .info {
 			 	position: relative;
 			 	margin-top: 50px;
 			 }
 			 
		</style>
	</head>
	<body>
			
		
		<div id="shortcode-code">
			<?php echo $sc; ?>
		</div>

		
	</body>
</html>