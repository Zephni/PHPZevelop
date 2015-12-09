<?php
	/* Config
	------------------------------*/
	$PHPZevelop->NewObject("CFG", (object) array(
		// Strings
		"SiteTitle"			=> "PHPZevelop",
		"PageTitle"			=> "",
		"MetaTitle"			=> "PHPZevelop PHP FrameWork",
		"MetaDescription"	=> "PHP framework for ease of use and adaptability",
		"MetaKeywords"		=> "PHP, Framework, Zephni",

		// Structural
		"Site"				=> "site",
		"MultiSite"			=> array("howto"),
		"PassParams"		=> false,
		"PreParam"			=> "param_",
		"Page404"			=> "error/404",
		"DefaultFiles"		=> array("index", "default", "home")
	));