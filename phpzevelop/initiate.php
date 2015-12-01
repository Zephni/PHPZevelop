<?php
	/* Check if current path is a MultiSite
	------------------------------*/
	if(isset($PHPZevelop->CFG->MultiSite) && count($PHPZevelop->CFG->MultiSite) > 0)
	{
		foreach($PHPZevelop->CFG->MultiSite as $alias => $directory)
		{
			if(is_numeric($alias))
				$alias = $directory;
			
			$alias = ltrim($alias, "/");

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
	if(file_exists($PHPZevelop->CFG->SiteDir."/config".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDir."/config".FILE_EXT);
	
	/* Pass indexed parameters if file doesn't exist - but can find file in directory chain
	------------------------------*/
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

	$PHPZevelop->CFG->PagePath = $FilePath;

	/* Path
	------------------------------*/
	$PHPZevelop->NewObject("Path", new Path($PHPZevelop->CFG));

	/* Page
	------------------------------*/
	$PHPZevelop->NewObject("Page", new Page());

	/* Custom setup
	------------------------------*/
	if(file_exists($PHPZevelop->CFG->SiteDir."/global".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDir."/global".FILE_EXT);