<!DOCTYPE html>

<html>
	<head>
		<!-- META DATA -->
		<title><?php
			echo $PHPZevelop->CFG->SiteTitle.($PHPZevelop->CFG->PageTitle != "" ? " - ".$PHPZevelop->CFG->PageTitle : "");
		?></title>
		<meta charset="UTF-8">
		<meta name="title" content="<?php echo $PHPZevelop->CFG->MetaTitle; ?>">
		<meta name="description" content="<?php echo $PHPZevelop->CFG->MetaDescription; ?>">
		<meta name="keywords" content="<?php echo $PHPZevelop->CFG->MetaKeywords; ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="<?php echo $PHPZevelop->Path->GetCSS("style1.css"); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $PHPZevelop->Path->GetCSS("formgen.css"); ?>">

		<!-- JAVASCRIPT -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="<?php echo $PHPZevelop->Path->GetScript("main.js"); ?>"></script>

		<!-- JQuery datetimepicker -->
		<link rel="stylesheet" href="<?php $PHPZevelop->Path->GetScript("datetimepicker-master/jquery.datetimepicker.css"); ?>" />
		<script src="<?php $PHPZevelop->Path->GetScript("datetimepicker-master/jquery.datetimepicker.js"); ?>"></script>
		<script>
			$(document).ready(function(){
				$('.datetimepicker').datetimepicker();
			});
		</script>
	</head>
	<body>
		
		<div class="outerContainer">
			<div class="innerContainer">
				<div id="header">
					<div class="horizontalPadding">
						<a href="<?php $PHPZevelop->Path->GetPage(""); ?>" style="text-decoration: none !important;">
							<h1 style='font-size: 38px !important;'><?php echo $PHPZevelop->CFG->SiteTitle; ?></h1>
						</a>
						<hr />
					</div>
				</div>

				<div id="body">

					<?php echo $PHPZevelop->PageContent; ?>
					
				</div>

				<div id="footer">
					<div class="horizontalPadding">
						<hr />
						<p style="font-size: 12px; margin-bottom: 0px;">&copy;  PHPZevelop <?php echo date("Y"); ?> - <?php $Link->Out("https://twitter.com/_Zephni", "@_Zephni"); ?> (Craig Dennis)</p>
					</div>
				</div>
			</div>
		</div>

	</body>
</html>