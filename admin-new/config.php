<?php
	$PHPZevelop->CFG->SiteTitle = "PHPZevelop";
	
	
	$PHPZevelop->CFG->DB = (object) array(
		"Host" => "webregulate.co.uk",
		"User" => "phpzevel_user",
		"Pass" => "phpzeveloppass",
		"Name" => "phpzevel_database"
	);

	// Database name
	$DatabaseName = $PHPZevelop->CFG->DB->Name;
	$FrontEndLocationRoot = ROOT_DIR."/site";
	$FrontEndLocationLocal = str_replace("//", "/", LOCAL_DIR."/site");
	$FrontEndImageLocationRoot = str_replace("//", "/", $FrontEndLocationRoot."/images");
	$FrontEndImageLocationLocal = str_replace("//", "/", $FrontEndLocationLocal."/images");