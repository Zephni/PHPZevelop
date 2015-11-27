<?php
	/* Include all classes
	------------------------------*/
	require_once(MAIN_DIR."/classes/class.subloader.php");
	$SubLoader = new SubLoader(MAIN_DIR."/classes");
	$SubLoader->RunIncludes();

	/* PHPZevelop setup
	------------------------------*/
	$PHPZevelop = new PHPZevelop();

	/* Include config file
	------------------------------*/
	require_once(ROOT_DIR."/config.php");
	
	/* Check if current path is a MultiSite
	------------------------------*/
	if(isset($PHPZevelop->CFG->MultiSite) && count($PHPZevelop->CFG->MultiSite) > 0)
	{
		foreach($PHPZevelop->CFG->MultiSite as $site)
		{
			if(array_shift(explode("/", $PAGE_PATH)) == $site || array_pop(explode("/", LOCAL_DIR)) == $site)
			{
				$PAGE_PATH = str_replace("//", "/", "/".str_replace($site, "", $PAGE_PATH));
				$PHPZevelop->CFG->Site = $site;
				$PHPZevelop->CFG->LocalDir .= "/".$site;
			}
		}
	}
	
	/* Build Config strings
	------------------------------*/
	$PHPZevelop->CFG->SiteDir = ROOT_DIR."/".$PHPZevelop->CFG->Site;
	$PHPZevelop->CFG->SiteDirLocal = LOCAL_DIR."/".$PHPZevelop->CFG->Site;

	$PHPZevelop->CFG->RootDirs = (object) array();
	$PHPZevelop->CFG->LocalDirs = (object) array();
	foreach(array("Classes", "Inc", "Pages", "Images", "Scripts", "CSS") as $item){
		$PHPZevelop->CFG->RootDirs->$item = $PHPZevelop->CFG->SiteDir."/".strtolower($item);
		$PHPZevelop->CFG->LocalDirs->$item = $PHPZevelop->CFG->SiteDirLocal."/".strtolower($item);
	}

	/* Include config from site
	------------------------------*/
	if(file_exists($PHPZevelop->CFG->SiteDir."/config.php"))
		require_once($PHPZevelop->CFG->SiteDir."/config.php");
	
	/* Pass indexed parameters if file doesn't exist - but can find file in directory chain
	------------------------------*/
	$prependParam = $PHPZevelop->CFG->PreParam;
	$defaultFiles = $PHPZevelop->CFG->DefaultFiles;
	$newArr = array();
	$checkFile = explode("/", $PHPZevelop->CFG->RootDirs->Pages."/".$PAGE_PATH);
	$checked[] = implode("/", $checkFile);
	while($checkFile != "%END%" && !is_file(implode("/", $checkFile).".php")){

		foreach($defaultFiles as $item){
			if(is_file(implode("/", (array)$checkFile)."/".$item)){
				$PAGE_PATH = str_replace($PHPZevelop->CFG->RootDirs->Pages."/", "", implode("/", (array)$checkFile)."/".$item);
				$checkFile = "%END%";
				break;
			}
		}

		if($checkFile != "%END%"){
			$newArr[] = array_pop($checkFile);
		
			if(is_file(implode("/", (array)$checkFile).".php")){
				$PAGE_PATH = str_replace($PHPZevelop->CFG->RootDirs->Pages, "", implode("/", $checkFile));
				$checkFile = "%END%";
			}else{				
				foreach($defaultFiles as $item){
					if(is_file(implode("/", (array)$checkFile)."/".$item)){
						$PAGE_PATH = str_replace($PHPZevelop->CFG->RootDirs->Pages."/", "", implode("/", (array)$checkFile)."/".$item);
						$checkFile = "%END%";
						break;
					}
				}
			}

			if(implode("/", (array)$checkFile) == $PHPZevelop->CFG->RootDirs->Pages)
				$checkFile = "%END%";
		}else{
			break;
		}
	}

	$PAGE_PATH = rtrim($PAGE_PATH, ".php");

	/* Path
	------------------------------*/
	$PHPZevelop->NewObject("Path", new Path($PHPZevelop->CFG));

	/* Page
	------------------------------*/
	$PHPZevelop->NewObject("Page", new Page());

	/* Custom setup
	------------------------------*/
	if(file_exists($PHPZevelop->CFG->SiteDir."/global.php"))
		require_once($PHPZevelop->CFG->SiteDir."/global.php");