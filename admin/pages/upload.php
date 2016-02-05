<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Upload",
		"PassParams" => true
	));
?>

<?php
	$formGen = new formGen();

	if(isset($_GET["param_0"]))
		$_GET["tbl"] = $_GET["param_0"];

	if(isset($_GET["param_1"]))
		$_GET["id"] = $_GET["param_1"];

	$tableCfgPath = $PHPZevelop->CFG->SiteDirRoot."/config/".$_GET["tbl"].".php";

	if(file_exists($tableCfgPath)){ // Check config file exists for this table

		require($tableCfgPath);

		if(count($_POST) > 0){ // If submitted

			if(count($strToTimeFields) > 0){ // Set timestamps for specified fields
				foreach($strToTimeFields as $item){
					$_POST[$item] = strtotime($_POST[$item]);
				}
			}

			if(count($fileUploadLocations) > 0){ // Upload files to specified locations
				foreach($fileUploadLocations as $k => $v){
					if($_FILES[$k]['name'] != ""){
						$file = new UploadFile($_FILES[$k]);
						
						if(isset($forceImageNames))
							$file->renameFile($forceImageNames[$k]);
						
						$file->process($v);
						
						$_POST[$k] = $file->fileName;

						if(!$file->processed())
							$imgError = true;

						if(isset($imageForceSizes) && count($imageForceSizes) > 0){
							if(array_key_exists($k, $imageForceSizes)){
								try{
					                $img = new SimpleImage($fileUploadLocations[$k].$file->fileName);
					                $img->adaptive_resize($imageForceSizes[$k][0], $imageForceSizes[$k][1]);
					                $img->save();
					            }catch(Exception $e){
					                
					            }
							}
						}

					}else{
						$_POST[$k] = "#/noupdate/#";
					}
				}
			}

			if(!isset($imgError)){
				$success = insertQueryFromArray($_GET["tbl"], $_POST);
				if($success){
					$lastInsertId = $DB->lastInsertId;
					$msg = "<h3 style='color: green;'>Successfully uploaded</h3>";
					echo "<script>window.location.href='".$PHPZevelop->Path->GetPage("upload/".$_GET["tbl"])."'</script>";
					$_POST = array();
				}else{
					$msg = "<h3 style='color: red;'>There was an error</h3><p>".$DB->errorHandler()."</p>";
				}
			}else{
				$msg = "<h3 style='color: red;'>There was an error</h3><p>".$file->getMsg()."</p>";
			}
		}

		$formGen->setFormAttributes(array("action" => "", "method" => "post", "enctype" => "multipart/form-data", "class" => "mainForm"));
		$html = $formGen->generateForm(true);

	}else{ // If does not exist

		$html = "
			The configuration does not exist for this table,
			first create the setup in 'rootPath/config/'
		";

	}
?>

<div id="pageContent">
	<?php if(isset($msg)) echo $msg; ?>
	<h1><a href="">Upload to table "<?php echo $_GET["tbl"]; ?>"</a></h1>
	<?php echo $constMsg; ?>
	<p><?php echo $html; ?></p>
</div>