<?php
	// Config
	$PHPZevelop->NewObject("CFG", (object) array(
		"Site"						=> "site",
		"MultiSite"					=> array("admin"),
		"PassParams"				=> false,
		"PreParam"					=> "param_",
		"Page404"					=> "error/404",
		"DefaultFiles"				=> array("index", "default", "home")
	));