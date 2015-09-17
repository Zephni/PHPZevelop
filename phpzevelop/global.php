<?php
	/* Config
	------------------------------*/

	// Include config file
	require_once(ROOT_DIR."/config.php");

	$CFG->SiteDir = ROOT_DIR.$CFG->Site;
	$CFG->SiteDirLocal = LOCAL_DIR.$CFG->Site;

	$CFG->RootDirs = (object) array();
	$CFG->LocalDirs = (object) array();
	foreach(array("Classes", "Inc", "Pages", "Images", "Scripts", "CSS") as $item){
		$CFG->RootDirs->$item = $CFG->SiteDir."/".strtolower($item);
		$CFG->LocalDirs->$item = $CFG->SiteDirLocal."/".strtolower($item);
	}

	// Pass indexed parameters if file doesn't exist - but can find file in directory chain
	if($CFG->AutoParamPass == true && strlen($PAGE_PATH) > 0){
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

		$newArr = array_reverse($newArr);
		for($i = 0; $i < count($newArr); $i++)
			$_GET[$prependParam.$i] = $newArr[$i];
		
		unset($newArr, $checkFile, $prependParam);
	}

	$PAGE_PATH = rtrim($PAGE_PATH, ".php");

	/* Include all classes
	------------------------------*/
	require_once($CFG->MainDirClasses."/class.subloader.php");
	$SubLoader = new SubLoader($CFG->MainDirClasses);
	$SubLoader->RunIncludes();

	/* Instantiate
	------------------------------*/
	require_once($CFG->MainDir."/instantiate.php");