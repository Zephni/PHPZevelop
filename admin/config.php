<?php
	/* Config
	------------------------------*/	
	$PHPZevelop->CFG->FrontSite = "site";
	$PHPZevelop->CFG->FrontSiteRoot = ROOT_DIR."/".$PHPZevelop->CFG->FrontSite;
	$PHPZevelop->CFG->FrontSiteLocal = LOCAL_DIR."/".$PHPZevelop->CFG->FrontSite;

	/* Config
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		// Strings
		"SiteTitle"			=> "Admin",

		// Structural
		"LoadStyle"			=> "Template",
		"Template"			=> "main"
	));

	$PHPZevelop->CFG->Accounts = array(
		array(
			"User" => "admin",
			"Pass" => "pass"
		)
	);

	if($PHPZevelop->CFG->SiteMode == SiteMode::Development)
	{
		$PHPZevelop->CFG->DB = (object) array(
			"Host" => "",
			"User" => "",
			"Pass" => "",
			"Name" => ""
		);
	}
	elseif($PHPZevelop->CFG->SiteMode == SiteMode::Live)
	{
		$PHPZevelop->CFG->DB = (object) array(
			"Host" => "",
			"User" => "",
			"Pass" => "",
			"Name" => ""
		);
	}