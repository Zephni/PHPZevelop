<?php
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
				$PHPZevelop->CFG->PagePath = str_replace("//", "/", "/".str_replace($alias, "", $PHPZevelop->CFG->PagePath));
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
	foreach(array("Classes", "Inc", "Pages", "Images", "Scripts", "CSS") as $item){
		$PHPZevelop->CFG->RootDirs->$item = $PHPZevelop->CFG->SiteDirRoot."/".strtolower($item);
		$PHPZevelop->CFG->LocalDirs->$item = $PHPZevelop->CFG->SiteDirLocal."/".strtolower($item);
	}

	/* Include config from site
	------------------------------*/
	if(file_exists($PHPZevelop->CFG->SiteDirRoot."/config".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDirRoot."/config".FILE_EXT);
	
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
	if(file_exists($PHPZevelop->CFG->SiteDirRoot."/global".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDirRoot."/global".FILE_EXT);