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
	define("LOCAL_DIR", "/".rtrim(ltrim(substr($_SERVER["PHP_SELF"], 0, strlen($_SERVER["PHP_SELF"]) -strlen("index".FILE_EXT)-1), "/"), "/"));

	/* Get page path (should be the path after LOCAL_DIR with no trailing slash and query string removed)
	------------------------------*/
	$inter = explode("?", rtrim(ltrim(substr($_SERVER["REQUEST_URI"], strlen(LOCAL_DIR)), "/"), "/"));
	$PHPZevelop->CFG->PagePath = array_shift($inter);
	unset($inter);
	
	/* Check if current path is a MultiSite
	------------------------------*/
	$PHPZevelop->CFG->IsMultiSite = false;
	if(isset($PHPZevelop->CFG->MultiSite) && count($PHPZevelop->CFG->MultiSite) > 0)
	{
		foreach($PHPZevelop->CFG->MultiSite as $alias => $directory)
		{
			if(is_numeric($alias)) $alias = $directory;
			$alias = ltrim($alias, "/");

			if(strtok($PHPZevelop->CFG->PagePath, "/") == $alias || substr(strrchr(LOCAL_DIR, "/"), 1) == $alias)
			{
				$PHPZevelop->CFG->PagePath = substr($PHPZevelop->CFG->PagePath, strlen($alias));
				$PHPZevelop->CFG->Site = $directory;
				$PHPZevelop->CFG->IsMultiSite = true;
			}
		}
	}

	/* Build Config strings
	------------------------------*/
	$PHPZevelop->CFG->SiteDirRoot = ROOT_DIR."/".$PHPZevelop->CFG->Site;
	$PHPZevelop->CFG->SiteDirLocal = str_replace("//", "/", LOCAL_DIR."/".$PHPZevelop->CFG->Site);

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
	function BulidPath($PHPZevelop)
	{
		$PHPZevelop->CFG->TestedPagePath = $PHPZevelop->CFG->PagePath;
		$PathParts = explode("/", $PHPZevelop->CFG->PagePath);
		$PHPZevelop->Set("URLParameters", array());

		for($I = count($PathParts); $I >= 0; $I--)
		{
			$PHPZevelop->CFG->ExistingFilePath = implode("/", array_slice($PathParts, 0, $I));

			if(is_file($PHPZevelop->CFG->RootDirs->Pages."/".$PHPZevelop->CFG->ExistingFilePath.FILE_EXT))
			{
				break 1;
			}
			else
			{
				foreach($PHPZevelop->CFG->DefaultFiles as $item)
				{
					if(is_file($PHPZevelop->CFG->RootDirs->Pages."/".$PHPZevelop->CFG->ExistingFilePath."/".$item.FILE_EXT)){
						$PHPZevelop->CFG->ExistingFilePath = $PHPZevelop->CFG->ExistingFilePath."/".$item;
						break 2;
					}
				}
				
				if(!is_file($PHPZevelop->CFG->RootDirs->Pages."/".$PHPZevelop->CFG->ExistingFilePath) && $I > 0)
					$PHPZevelop->Append("URLParameters", $PathParts[$I-1]);
			}
		}

		return $PHPZevelop;
	}

	$OrigionalPath = $PHPZevelop->CFG->PagePath;
	$PHPZevelop = BulidPath($PHPZevelop);
	
	/* Path
	------------------------------*/
	$PHPZevelop->NewObject("Path", new Path($PHPZevelop->CFG));

	/* Check if parameters have been passed
	------------------------------*/
	$KeysUsed = array();
	if(count($PHPZevelop->Get("URLParameters")) > 0)
	{
		foreach(array_reverse($PHPZevelop->Get("URLParameters")) as $k => $v)
		{
			$KeysUsed[] = $PHPZevelop->CFG->PreParam.$k;
			$_GET[$PHPZevelop->CFG->PreParam.$k] = $v;
		}
	}

	/* Site specific
	------------------------------*/
	// Global
	if(file_exists($PHPZevelop->CFG->SiteDirRoot."/global".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDirRoot."/global".FILE_EXT);

	// Instantiate
	if(file_exists($PHPZevelop->CFG->SiteDirRoot."/instantiate".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDirRoot."/instantiate".FILE_EXT);

	// Check if path was changed
	if($OrigionalPath != $PHPZevelop->CFG->PagePath)
	{
		$PHPZevelop = BulidPath($PHPZevelop);
		$PHPZevelop->NewObject("Path", new Path($PHPZevelop->CFG));

		/* Check if parameters have been passed
		------------------------------*/
		if(count($PHPZevelop->Get("URLParameters")) > 0)
		{
			if(count($KeysUsed) > 0)
				foreach($KeysUsed as $Key)
					unset($_GET[$Key]);
			
			foreach(array_reverse($PHPZevelop->Get("URLParameters")) as $k => $v)
				$_GET[$PHPZevelop->CFG->PreParam.$k] = $v;
		}
	}

	unset($KeysUsed);

	/* Generate page
	------------------------------*/
	ob_start();
	try
	{
		// Try to require page, if not exist throw exception
		require($PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->ExistingFilePath.FILE_EXT));

		// If file included and PassParams is not set and a parameter has been passed
		if(!$PHPZevelop->CFG->PassParams && isset($_GET[$PHPZevelop->CFG->PreParam."0"]))
			throw new Exception();
	}
	catch (Exception $e)
	{
		// On failure clear ob and require 404 page (Error on failure)
		ob_clean();
		require($PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->Page404.FILE_EXT));		
	}

	// Set page content
	$PHPZevelop->PageContent = ob_get_contents();
	ob_end_clean();

	if(isset($PHPZevelop->CFG->LoadStyle) && $PHPZevelop->CFG->LoadStyle == "Template" || !isset($PHPZevelop->CFG->LoadStyle))
		require_once(MAIN_DIR."/generatepage_template".FILE_EXT);
	elseif(isset($PHPZevelop->CFG->LoadStyle) && $PHPZevelop->CFG->LoadStyle == "FileOrder")
		require_once(MAIN_DIR."/generatepage_fileorder".FILE_EXT);