<?php
	/* Config
	------------------------------*/	
	$PHPZevelop->CFG->FrontSite = "site";
	$PHPZevelop->CFG->FrontSiteRoot = ROOT_DIR."/".$PHPZevelop->CFG->FrontSite;
	$PHPZevelop->CFG->FrontSiteLocal = LOCAL_DIR."/".$PHPZevelop->CFG->FrontSite;

	/* Config
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		// Structural
		"LoadStyle"			=> "FileOrder",
		"FileOrder"			=> array("inc/header", "[page]", "inc/footer")
	));

	$PHPZevelop->CFG->Accounts = array(
		array(
			"User" => "admin",
			"Pass" => "pass"
		)
	);

	$PHPZevelop->CFG->DB = (object) array(
		"Host" => "",
		"User" => "",
		"Pass" => "",
		"Name" => ""
	);
