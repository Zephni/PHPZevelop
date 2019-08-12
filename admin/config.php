<?php
	$PHPZevelop->CFG->SiteTitle = "PHPZevelop";
	
	
	$PHPZevelop->CFG->DB = (object) array(
		"Host" => "webregulate.co.uk",
		"User" => "zephnico_phpz",
		"Pass" => "phpzevelop34",
		"Name" => "zephnico_phpzevelop"
	);

	// Database name
	$DatabaseName = $PHPZevelop->CFG->DB->Name;
	$FrontEndLocationRoot = ROOT_DIR."/site";
	$FrontEndLocationLocal = str_replace("//", "/", LOCAL_DIR."/site");
	$FrontEndImageLocationRoot = str_replace("//", "/", $FrontEndLocationRoot."/images");
	$FrontEndImageLocationLocal = str_replace("//", "/", $FrontEndLocationLocal."/images");