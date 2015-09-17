<?php
	// Config
	$CFG = (object) array(
		"SiteTitle" => "PHPZevelop",
		"Domain" => "Zephni.com/PHPZevelop",
		"Site" => "/site",
		"AutoParamPass" => true,
		"RootDir" => ROOT_DIR,
		"LocalDir" => LOCAL_DIR,
		"MainDir" => MAIN_DIR,
		"MainDirClasses" => MAIN_DIR."/classes",
		"PassParamsAutomatically" => true,
		"PreParam" => "param_",
		"DefaultFiles" => array("index.php", "default.php", "home.php")
	);

	// For multiple sites, you can set the variables below
	$site = "admin";
	if(array_shift(explode("/", $PAGE_PATH)) == $site || array_pop(explode("/", LOCAL_DIR)) == $site)
	{
		$PAGE_PATH = str_replace("//", "/", "/".str_replace($site, "", $PAGE_PATH));
		$CFG->Site = "/".$site;
		$CFG->LocalDir .= "/".$site;
	}