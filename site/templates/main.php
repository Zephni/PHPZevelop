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

		<!-- JAVASCRIPT -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="<?php echo $PHPZevelop->Path->GetScript("main.js"); ?>"></script>
	</head>
	<body>
		
		<div class="outerContainer">
			<div id="header" class="innerContainer">
				<h1><?php echo $PHPZevelop->CFG->SiteTitle; ?></h1>
				<hr />
			</div>
		</div>

		<div class="outerContainer">
			<div class="innerContainer">
				<?php echo $PHPZevelop->PageContent; ?>
			</div>
		</div>
		
		<div class="outerContainer">
			<div class="innerContainer">

			</div>
		</div>

	</body>
</html>