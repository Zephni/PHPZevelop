<?php
	// Config
	$PHPZevelop->NewObject("CFG", (object) array(
		"Site"						=> "site",
		"MultiSite"					=> array("admin"),
		"PassParams"				=> false,
		"PreParam"					=> "param_",
		"DefaultFiles"				=> array("index", "default", "home")
	));