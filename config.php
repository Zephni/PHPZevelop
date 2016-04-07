<?php
	/* Enums
	------------------------------*/
	abstract class SiteMode
	{
	    const Development = 1;
	    const Live = 2;
    }

	/* Config
	------------------------------*/
	$PHPZevelop->NewObject("CFG", (object) array(

		// Site specific
		"SiteMode"			=> SiteMode::Development,
		
		// Strings
		"SiteTitle"			=> "New project",
		"PageTitle"			=> "",
		"MetaTitle"			=> "",
		"MetaDescription"	=> "",
		"MetaKeywords"		=> "",

		// Structural
		"Site"				=> "site",
		"MultiSite"			=> array("howto", "admin"),
		"LoadStyle"			=> "Template",
		"Template"			=> "main",
		"PassParams"		=> false,
		"PreParam"			=> "param_",
		"Page404"			=> "error/404",
		"DefaultFiles"		=> array("index", "default", "home")
	
	));