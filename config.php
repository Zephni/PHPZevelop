<?php
	// Config
	$PHPZevelop->NewObject("CFG", (object) array(
		"Site" => "site",
		"MultiSite" => array("admin"),
		"RootDir" => ROOT_DIR,
		"LocalDir" => LOCAL_DIR,
		"MainDir" => MAIN_DIR,
		"PassParamsAutomatically" => false,
		"PreParam" => "param_",
		"DefaultFiles" => array("index.php", "default.php", "home.php")
	));