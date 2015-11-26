<?php
	/* Include config file
	------------------------------*/
	require_once(ROOT_DIR."/config.php");
	
	/* Check if current path is a MultiSite
	------------------------------*/
	if(isset($CFG->MultiSite) && count($CFG->MultiSite) > 0)
	{
		foreach($CFG->MultiSite as $site)
		{
			if(array_shift(explode("/", $PAGE_PATH)) == $site || array_pop(explode("/", LOCAL_DIR)) == $site)
			{
				$PAGE_PATH = str_replace("//", "/", "/".str_replace($site, "", $PAGE_PATH));
				$CFG->Site = $site;
				$CFG->LocalDir .= "/".$site;
			}
		}
	}
	
	/* Build Config strings
	------------------------------*/
	$CFG->SiteDir = ROOT_DIR."/".$CFG->Site;
	$CFG->SiteDirLocal = LOCAL_DIR."/".$CFG->Site;

	$CFG->RootDirs = (object) array();
	$CFG->LocalDirs = (object) array();
	foreach(array("Classes", "Inc", "Pages", "Images", "Scripts", "CSS") as $item){
		$CFG->RootDirs->$item = $CFG->SiteDir."/".strtolower($item);
		$CFG->LocalDirs->$item = $CFG->SiteDirLocal."/".strtolower($item);
	}

	/* Include config from site
	------------------------------*/
	if(file_exists($CFG->SiteDir."/config.php"))
		require_once($CFG->SiteDir."/config.php");
	
	/* Pass indexed parameters if file doesn't exist - but can find file in directory chain
	------------------------------*/
	$prependParam = $CFG->PreParam;
	$defaultFiles = $CFG->DefaultFiles;
	$newArr = array();
	$checkFile = explode("/", $CFG->RootDirs->Pages."/".$PAGE_PATH);
	$checked[] = implode("/", $checkFile);
	while($checkFile != "%END%" && !is_file(implode("/", $checkFile).".php")){

		foreach($defaultFiles as $item){
			if(is_file(implode("/", (array)$checkFile)."/".$item)){
				$PAGE_PATH = str_replace($CFG->RootDirs->Pages."/", "", implode("/", (array)$checkFile)."/".$item);
				$checkFile = "%END%";
				break;
			}
		}

		if($checkFile != "%END%"){
			$newArr[] = array_pop($checkFile);
		
			if(is_file(implode("/", (array)$checkFile).".php")){
				$PAGE_PATH = str_replace($CFG->RootDirs->Pages, "", implode("/", $checkFile));
				$checkFile = "%END%";
			}else{				
				foreach($defaultFiles as $item){
					if(is_file(implode("/", (array)$checkFile)."/".$item)){
						$PAGE_PATH = str_replace($CFG->RootDirs->Pages."/", "", implode("/", (array)$checkFile)."/".$item);
						$checkFile = "%END%";
						break;
					}
				}
			}

			if(implode("/", (array)$checkFile) == $CFG->RootDirs->Pages)
				$checkFile = "%END%";
		}else{
			break;
		}
	}

	$PAGE_PATH = rtrim($PAGE_PATH, ".php");

	/* Include all classes
	------------------------------*/
	require_once($CFG->MainDir."/classes/class.subloader.php");
	$SubLoader = new SubLoader($CFG->MainDir."/classes");
	$SubLoader->RunIncludes();

	/* Instantiate
	------------------------------*/
	require_once($CFG->MainDir."/instantiate.php");

	/* Custom setup
	------------------------------*/
	if(file_exists($PHPZevelop->CFG->SiteDir."/global.php"))
		require_once($PHPZevelop->CFG->SiteDir."/global.php");