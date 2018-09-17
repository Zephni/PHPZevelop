<?php
	$PHPZevelop->CFG->SiteTitle = "PHPZevelop";
	
	/*
	$PHPZevelop->CFG->DB = (object) array(
		"Host" => "",
		"User" => "",
		"Pass" => "",
		"Name" => ""
	);*/

	// Database name
	$DatabaseName = $PHPZevelop->CFG->DB->Name;
	$FrontEndLocationRoot = ROOT_DIR."/site";
	$FrontEndLocationLocal = LOCAL_DIR."/site";
	$FrontEndImageLocationRoot = str_replace("//", "/", $FrontEndLocationRoot."/images");
	$FrontEndImageLocationLocal = str_replace("//", "/", $FrontEndLocationLocal."/images");