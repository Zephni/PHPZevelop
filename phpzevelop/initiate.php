<?php
	/* Get page path and unset $_GET["page"]
	------------------------------*/
	$PHPZevelop->CFG->PagePath = rtrim((isset($_GET["page"])) ? (string)$_GET["page"] : "", "/");
	unset($_GET["page"]);

	/* Set Local directory
	------------------------------*/
	$inter = explode("?", str_replace($PHPZevelop->CFG->PagePath, "", $_SERVER["REQUEST_URI"]));
	define("LOCAL_DIR", rtrim(array_shift($inter), "/"));
	unset($inter);
	
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
				$PHPZevelop->CFG->PagePath = str_replace("//", "/", "/".substr($PHPZevelop->CFG->PagePath, strlen($alias)));
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
	$PHPZevelop->CFG->PagePath = str_replace("//", "/", $PHPZevelop->CFG->PagePath);
	$PHPZevelop->CFG->TestedPagePath = $PHPZevelop->CFG->PagePath;
	$PathParts = explode("/", $PHPZevelop->CFG->PagePath);
	$PHPZevelop->Set("URLParameters", array());

	for($i = count($PathParts); $i >= 0; $i--)
	{
		$FilePath = implode("/", array_slice($PathParts, 0, $i));

		if(is_file($PHPZevelop->CFG->RootDirs->Pages."/".$FilePath.FILE_EXT))
			$i = 0;
		else
		{
			foreach($PHPZevelop->CFG->DefaultFiles as $item){
				if(is_file($PHPZevelop->CFG->RootDirs->Pages."/".$FilePath."/".$item.FILE_EXT)){
					$FilePath = $FilePath."/".$item;
					$i = 0;
					break;
				}
			}
			
			if(!is_file($PHPZevelop->CFG->RootDirs->Pages."/".$FilePath) && $i > 0)
				$PHPZevelop->Append("URLParameters", $PathParts[$i-1]);
		}
	}

	// Fix for default files
	if($PHPZevelop->CFG->TestedPagePath == "/" && isset($FilePath) && strlen($FilePath) > 0)
		$PHPZevelop->CFG->TestedPagePath = str_replace("//", "/", $FilePath);

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