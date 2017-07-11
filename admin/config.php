<?php
	$PHPZevelop->CFG->SiteTitle = $PHPZevelop->CFG->SiteTitle." Administration";
	
	// Database name
	$DatabaseName = $PHPZevelop->CFG->DB->Name;
	$FrontEndLocationRoot = ROOT_DIR."/site";
	$FrontEndLocationLocal = LOCAL_DIR."/site";
	$FrontEndImageLocationRoot = str_replace("//", "/", $FrontEndLocationRoot."/images");
	$FrontEndImageLocationLocal = str_replace("//", "/", $FrontEndLocationLocal."/images");