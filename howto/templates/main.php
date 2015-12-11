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

				<?php
					$NavItems = array(
						"Help" => array(
							$Link->Get("help/structure",			"Structure"),
							$Link->Get("help/configuration",		"Configuration"),
							$Link->Get("help/load-style",			"Load style"),
							$Link->Get("help/adding-pages",			"Adding pages"),
							$Link->Get("help/meta-data",			"Meta data"),
							$Link->Get("help/multisite",			"MultiSite"),
							$Link->Get("help/vars/5/test",			"Page parameters"),
							$Link->Get("help/undefined-urls",		"Undefined URL's")
						),
						"Classes" => array(
							$Link->Get("classes/phpzevelop-class",	"PHPZevelop"),
							$Link->Get("classes/adding-classes",	"Custom classes"),
							$Link->Get("classes/path-class",		"Path class"),
							$Link->Get("classes/subloader-class",	"SubLoader class"),
							$Link->Get("classes/database-class",	"Database class"),
							$Link->Get("classes/link-class",		"Link class")
						)
					);
				?>

				<nav id='nav' class="non-mobile-only"><ul>
					<?php
						foreach($NavItems as $Name => $Array)
						{
							echo "<li><span class='head'>".$Name."</span><ul>";
							foreach($Array as $Value)
								echo "<li>".$Value."</li>";
							echo "</ul></li>";
						}
					?>
				</ul><div style="width: 100%; clear: both;"></div></nav>

				<div id="nav" class="mobile-only">
					<?php
						foreach($NavItems as $Name => $Array)
						{
							echo "<div class='head'>".$Name."</div>";
							echo "<div class='expandable'>";
							foreach($Array as $Value){
								echo "<div>".$Value."</div>";
							}
							echo "</div>";
						}
					?>
				</div>
			
				<?php echo $PHPZevelop->PageContent; ?>

				<br />
				<hr />
				<p style="font-size: 12px;">&copy; PHPZevelop 2015 - <a href="https://twitter.com/_Zephni" target="_blank">@_Zephni</a> (Craig Dennis)</p>
			</div>
		</div>
	</body>
</html>