<?php
	/* Defaults if not defined
	------------------------------*/
	if($PHPZevelop->Get("PAGE_TITLE")		== null)	$PHPZevelop->Set("PAGE_TITLE", "");
	if($PHPZevelop->Get("META_TITLE")		== null)	$PHPZevelop->Set("META_TITLE", "PHPZevelop PHP FrameWork");
	if($PHPZevelop->Get("META_DESCRIPTION")	== null)	$PHPZevelop->Set("META_DESCRIPTION", "PHP framework for ease of use and adaptability");
	if($PHPZevelop->Get("META_KEYWORDS")	== null)	$PHPZevelop->Set("META_KEYWORDS", "PHP, Framework, Zephni");
?>

<!DOCTYPE html>

<html>
	<head>
		<!-- META DATA -->
		<title><?php if($PHPZevelop->Get("PAGE_TITLE") != "") echo $PHPZevelop->CFG->SiteTitle." - ".$PHPZevelop->Get("PAGE_TITLE"); else echo $PHPZevelop->CFG->SiteTitle; ?></title>
		<meta charset="UTF-8">
		<meta name="title" content="<?php echo $PHPZevelop->Get("META_TITLE"); ?>">
		<meta name="description" content="<?php echo $PHPZevelop->Get("META_DESCRIPTION"); ?>">
		<meta name="keywords" content="<?php echo $PHPZevelop->Get("META_KEYWORDS"); ?>">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="<?php echo $PHPZevelop->Path->GetCSS("style1.css"); ?>">

		<!-- JAVASCRIPT -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="<?php echo $PHPZevelop->Path->GetScript("main.js"); ?>"></script>
	</head>
	<body>
		<div id="fixed-container">
			<div class="wrapper">
				<a href="https://github.com/Zephni/PHPZevelop" id="download-on-github" target="_blank">Download on GitHub</a>
			</div>
		</div>
		<div class="wrapper">
			<div class="padding">
				<a href="<?php echo $PHPZevelop->CFG->LocalDir; ?>/">
					<h1>PHPZevelop</h1>
				</a>
				<table id="nav">
					<tr>
						<td class="head">Help</td>
					</tr>
					<tr>
						<td>
							<table style="width: 100%;">
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/configuration"); ?>">Configuration</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/adding-pages"); ?>">Adding pages</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/meta-data"); ?>">Meta data</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/multisite"); ?>">MultiSite</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/vars/5/test"); ?>">Page parameters</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/404"); ?>">Undefined URL's</a></td>
							</table>
						</td>
					</tr>
					
					<tr>
						<td class="head">Classes</td>
					</tr>
					<tr>
						<td>
							<table style="width: 100%;">
								<td><a href="<?php $PHPZevelop->Path->GetPage("classes/phpzevelop-class"); ?>">PHPZevelop</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("classes/page-class"); ?>">Page class</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("classes/path-class"); ?>">Path class</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("classes/subloader-class"); ?>">SubLoader class</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("classes/database-class"); ?>">Database class</a></td>
							</table>
						</td>
					</tr>
				</table>