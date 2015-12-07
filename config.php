<?php
	/* Config
	------------------------------*/
	$PHPZevelop->NewObject("CFG", (object) array(
		// Strings
		"Site"				=> "site",
		"PageTitle"			=> "",
		"MetaTitle"			=> "PHPZevelop PHP FrameWork",
		"MetaDescription"	=> "PHP framework for ease of use and adaptability",
		"MetaKeywords"		=> "PHP, Framework, Zephni",

		// Structural
		"MultiSite"			=> array("admin"),
		"PassParams"		=> false,
		"PreParam"			=> "param_",
		"Page404"			=> "error/404",
		"DefaultFiles"		=> array("index", "default", "home")
	));