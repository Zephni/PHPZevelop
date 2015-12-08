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
				<a href="<?php echo $PHPZevelop->Path->GetPage(""); ?>">
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
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/structure"); ?>">Structure</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/adding-pages"); ?>">Adding pages</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/meta-data"); ?>">Meta data</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/multisite"); ?>">MultiSite</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/vars/5/test"); ?>">Page parameters</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("help/undefined-urls"); ?>">Undefined URL's</a></td>
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
								<td><a href="<?php $PHPZevelop->Path->GetPage("classes/adding-classes"); ?>">Custom classes</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("classes/page-class"); ?>">Page class</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("classes/path-class"); ?>">Path class</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("classes/subloader-class"); ?>">SubLoader class</a></td>
								<td><a href="<?php $PHPZevelop->Path->GetPage("classes/database-class"); ?>">Database class</a></td>
							</table>
						</td>
					</tr>
				</table>