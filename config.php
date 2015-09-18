<?php
	// Config
	$CFG = (object) array(
		"Site" => "site",
		"MultiSite" => array(),
		"RootDir" => ROOT_DIR,
		"LocalDir" => LOCAL_DIR,
		"MainDir" => MAIN_DIR,
		"MainDirClasses" => MAIN_DIR."/classes",
		"PassParamsAutomatically" => false,
		"PreParam" => "param_",
		"DefaultFiles" => array("index.php", "default.php", "home.php")
	);