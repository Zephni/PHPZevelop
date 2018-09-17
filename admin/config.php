<?php
	$PHPZevelop->CFG->SiteTitle = "PHPZevelop";
	
	/*
	$PHPZevelop->CFG->DB = (object) array(
		"Host" => "212.67.203.173",
		"User" => "zephnico_gilmore",
		"Pass" => "lgilmore343872",
		"Name" => "zephnico_gilmore"
	);
	*/

	// Database name
	$DatabaseName = $PHPZevelop->CFG->DB->Name;
	$FrontEndLocationRoot = ROOT_DIR."/site";
	$FrontEndLocationLocal = LOCAL_DIR."/site";
	$FrontEndImageLocationRoot = str_replace("//", "/", $FrontEndLocationRoot."/images");
	$FrontEndImageLocationLocal = str_replace("//", "/", $FrontEndLocationLocal."/images");