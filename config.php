<?php
	// Config
	$PHPZevelop->NewObject("CFG", (object) array(
		"SiteTitle"					=> "PHPZevelop",
		"Site"						=> "site",
		"MultiSite"					=> array("test" => "admin"),
		"PassParamsAutomatically"	=> false,
		"PreParam"					=> "param_",
		"DefaultFiles"				=> array("index.php", "default.php", "home.php")
	));