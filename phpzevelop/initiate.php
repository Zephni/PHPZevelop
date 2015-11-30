<?php
	/* Check if current path is a MultiSite
	------------------------------*/
	if(isset($PHPZevelop->CFG->MultiSite) && count($PHPZevelop->CFG->MultiSite) > 0)
	{
		foreach($PHPZevelop->CFG->MultiSite as $alias => $directory)
		{
			if(is_numeric($alias))
				$alias = $directory;

			if(array_shift(explode("/", $PHPZevelop->CFG->PagePath)) == $alias || array_pop(explode("/", $PHPZevelop->CFG->LocalDir)) == $alias)
			{
				$PHPZevelop->CFG->PagePath = str_replace("//", "/", "/".str_replace($alias, "", $PHPZevelop->CFG->PagePath));
				$PHPZevelop->CFG->Site = $directory;
				$PHPZevelop->CFG->LocalDir .= "/".$alias;
			}
		}
	}
	
	/* Build Config strings
	------------------------------*/
	$PHPZevelop->CFG->SiteDir = ROOT_DIR."/".$PHPZevelop->CFG->Site;

	if(in_array($PHPZevelop->CFG->Site, $PHPZevelop->CFG->MultiSite))
		$PHPZevelop->CFG->SiteDirLocal = "/".$PHPZevelop->CFG->Site;
	else
		$PHPZevelop->CFG->SiteDirLocal = $PHPZevelop->CFG->LocalDir."/".$PHPZevelop->CFG->Site;

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
	$checkFile = explode("/", $PHPZevelop->CFG->RootDirs->Pages."/".$PHPZevelop->CFG->PagePath);
	$checked[] = implode("/", $checkFile);
	while($checkFile != "%END%" && !is_file(implode("/", $checkFile).".php")){

		foreach($defaultFiles as $item){
			if(is_file(implode("/", (array)$checkFile)."/".$item)){
				$PHPZevelop->CFG->PagePath = str_replace($PHPZevelop->CFG->RootDirs->Pages."/", "", implode("/", (array)$checkFile)."/".$item);
				$checkFile = "%END%";
				break;
			}
		}

		if($checkFile != "%END%"){
			$newArr[] = array_pop($checkFile);
		
			if(is_file(implode("/", (array)$checkFile).".php")){
				$PHPZevelop->CFG->PagePath = str_replace($PHPZevelop->CFG->RootDirs->Pages, "", implode("/", $checkFile));
				$checkFile = "%END%";
			}else{				
				foreach($defaultFiles as $item){
					if(is_file(implode("/", (array)$checkFile)."/".$item)){
						$PHPZevelop->CFG->PagePath = str_replace($PHPZevelop->CFG->RootDirs->Pages."/", "", implode("/", (array)$checkFile)."/".$item);
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

	$PHPZevelop->CFG->PagePath = rtrim($PHPZevelop->CFG->PagePath, ".php");

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