<?php
	/* Include base classes
	------------------------------*/
	require_once(MAIN_DIR."/classes/class.subloader".FILE_EXT);
	$SubLoader = new SubLoader(MAIN_DIR."/classes");
	$SubLoader->RunIncludes();
	
	/* PHPZevelop setup
	------------------------------*/
	$PHPZevelop = new PHPZevelop();
	require_once(ROOT_DIR."/config".FILE_EXT);

	/* Set Local directory (should be a slash '/' followed by the containing directory/ies with no trailing slash)
	------------------------------*/
	define("LOCAL_DIR", "/".rtrim(ltrim(rtrim($_SERVER["PHP_SELF"], array_pop(explode("/", $_SERVER["PHP_SELF"]))), "/"), "/"));

	/* Get page path (should be the path after LOCAL_DIR with no trailing slash and query string removed)
	------------------------------*/
	$PHPZevelop->CFG->PagePath = array_shift(explode("?", rtrim(ltrim(substr($_SERVER["REQUEST_URI"], strlen(LOCAL_DIR)), "/"), "/")));
	
	/* Check if current path is a MultiSite
	------------------------------*/
	$PHPZevelop->CFG->IsMultiSite = false;
	if(isset($PHPZevelop->CFG->MultiSite) && count($PHPZevelop->CFG->MultiSite) > 0)
	{
		foreach($PHPZevelop->CFG->MultiSite as $alias => $directory)
		{
			if(is_numeric($alias))
				$alias = $directory;
			
			$alias = ltrim($alias, "/");

			$inter1 = explode("/", $PHPZevelop->CFG->PagePath);
			$inter2 = explode("/", LOCAL_DIR);

			if(array_shift($inter1) == $alias || array_pop($inter2) == $alias)
			{
				$PHPZevelop->CFG->PagePath = substr($PHPZevelop->CFG->PagePath, strlen($alias));
				$PHPZevelop->CFG->Site = $directory;
				$PHPZevelop->CFG->IsMultiSite = true;
			}
			unset($inter1, $inter2);
		}
	}

	/* Build Config strings
	------------------------------*/
	$PHPZevelop->CFG->SiteDirRoot = ROOT_DIR."/".$PHPZevelop->CFG->Site;
	$PHPZevelop->CFG->SiteDirLocal = LOCAL_DIR."/".$PHPZevelop->CFG->Site;

	$PHPZevelop->CFG->RootDirs = (object) array();
	$PHPZevelop->CFG->LocalDirs = (object) array();
	foreach(array("Classes", "CSS", "Images", "Inc", "Pages", "Scripts", "Templates") as $item){
		$PHPZevelop->CFG->RootDirs->$item = $PHPZevelop->CFG->SiteDirRoot."/".strtolower($item);
		$PHPZevelop->CFG->LocalDirs->$item = $PHPZevelop->CFG->SiteDirLocal."/".strtolower($item);
	}

	/* Include config from site
	------------------------------*/
	if(file_exists($PHPZevelop->CFG->SiteDirRoot."/config".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDirRoot."/config".FILE_EXT);
	
	/* Pass indexed parameters if file doesn't exist - but can find file in directory chain
	------------------------------*/
	$PHPZevelop->CFG->TestedPagePath = $PHPZevelop->CFG->PagePath;
	$PathParts = explode("/", $PHPZevelop->CFG->PagePath);
	$PHPZevelop->Set("URLParameters", array());

	for($i = count($PathParts); $i >= 0; $i--)
	{
		$PHPZevelop->CFG->ExistingFilePath = implode("/", array_slice($PathParts, 0, $i));

		if(is_file($PHPZevelop->CFG->RootDirs->Pages."/".$PHPZevelop->CFG->ExistingFilePath.FILE_EXT))
			$i = 0;
		else
		{
			foreach($PHPZevelop->CFG->DefaultFiles as $item){
				if(is_file($PHPZevelop->CFG->RootDirs->Pages."/".$PHPZevelop->CFG->ExistingFilePath."/".$item.FILE_EXT)){
					$PHPZevelop->CFG->ExistingFilePath = $PHPZevelop->CFG->ExistingFilePath."/".$item;
					$i = 0;
					break;
				}
			}
			
			if(!is_file($PHPZevelop->CFG->RootDirs->Pages."/".$PHPZevelop->CFG->ExistingFilePath) && $i > 0)
				$PHPZevelop->Append("URLParameters", $PathParts[$i-1]);
		}
	}

	/* Path
	------------------------------*/
	$PHPZevelop->NewObject("Path", new Path($PHPZevelop->CFG));

	/* Check if parameters have been passed
	------------------------------*/
	if(count($PHPZevelop->Get("URLParameters")) > 0)
	{
		// Convert to $_GET
		$Params = array_reverse($PHPZevelop->Get("URLParameters"));
		for($i = 0; $i < count($Params); $i++)
			$_GET[$PHPZevelop->CFG->PreParam.$i] = $Params[$i];
		unset($Params);
	}

	/* Site specific
	------------------------------*/
	// Global
	if(file_exists($PHPZevelop->CFG->SiteDirRoot."/global".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDirRoot."/global".FILE_EXT);

	// Instantiate
	if(file_exists($PHPZevelop->CFG->SiteDirRoot."/instantiate".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDirRoot."/instantiate".FILE_EXT);

	/* Generate page
	------------------------------*/
	ob_start();
	include($PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->ExistingFilePath.FILE_EXT));
	$PHPZevelop->PageContent = ob_get_contents();
	ob_clean();

	if(isset($PHPZevelop->CFG->LoadStyle) && $PHPZevelop->CFG->LoadStyle == "Template" || !isset($PHPZevelop->CFG->LoadStyle))
		require_once(MAIN_DIR."/generatepage_template".FILE_EXT);
	elseif(isset($PHPZevelop->CFG->LoadStyle) && $PHPZevelop->CFG->LoadStyle == "FileOrder")
		require_once(MAIN_DIR."/generatepage_fileorder".FILE_EXT);