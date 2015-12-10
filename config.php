<?php
	/* Config
	------------------------------*/
	$PHPZevelop->NewObject("CFG", (object) array(
		// Strings
		"SiteTitle"			=> "New project",
		"PageTitle"			=> "",
		"MetaTitle"			=> "",
		"MetaDescription"	=> "",
		"MetaKeywords"		=> "",

		// Structural
		"Site"				=> "site",
		"MultiSite"			=> array("howto"),
		"Template"			=> "main",
		"PassParams"		=> false,
		"PreParam"			=> "param_",
		"Page404"			=> "error/4s04",
		"DefaultFiles"		=> array("index", "default", "home")
	));