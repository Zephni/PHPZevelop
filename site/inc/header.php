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
				<?php $Link->Out("https://github.com/Zephni/PHPZevelop", "Download on GitHub", array("id" => "download-on-github", "target" => "_blank")); ?>
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
								<td><?php $Link->Out("help/structure",				"PHPZevelop structure"); ?></td>
								<td><?php $Link->Out("help/configuration",			"Configuration"); ?></td>
								<td><?php $Link->Out("help/adding-pages",			"Adding pages"); ?></td>
								<td><?php $Link->Out("help/meta-data",				"Meta data"); ?></td>
								<td><?php $Link->Out("help/multisite",				"MultiSite"); ?></td>
								<td><?php $Link->Out("help/vars/5/test",			"Page parameters"); ?></td>
								<td><?php $Link->Out("help/undefined-urls",			"Undefined URL's"); ?></td>
							</table>
						</td>
					</tr>
					
					<tr>
						<td class="head">Classes</td>
					</tr>
					<tr>
						<td>
							<table style="width: 100%;">
								<td><?php $Link->Out("classes/phpzevelop-class",	"PHPZevelop"); ?></td>
								<td><?php $Link->Out("classes/adding-classes",		"Custom classes"); ?></td>
								<td><?php $Link->Out("classes/page-class",			"Page class"); ?></td>
								<td><?php $Link->Out("classes/path-class",			"Path class"); ?></td>
								<td><?php $Link->Out("classes/subloader-class",		"SubLoader class"); ?></td>
								<td><?php $Link->Out("classes/database-class",		"Database class"); ?></td>
								<td><?php $Link->Out("classes/link-class",			"Link class"); ?></td>
							</table>
						</td>
					</tr>
				</table>