<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Edit",
		"PassParams" => true
	));
?>

<?php
	$formGen = new formGen();

	$tableCfgPath = $PHPZevelop->CFG->SiteDirRoot."/config/".$_GET["param_0"].".php";

	if(file_exists($tableCfgPath)){ // Check config file exists for this table
		$data = $DB->QuerySingle("SELECT * FROM ".$_GET["param_0"]." WHERE id=:id", array("id" => $_GET["param_1"]));

		require($tableCfgPath);

		// If submitted
		if(count($_POST) > 0)
		{
			// Set timestamps for specified fields
			if(isset($strToTimeFields) && count($strToTimeFields) > 0)
			{
				foreach($strToTimeFields as $item)
				{
					if(strlen($_POST[$item]) > 0)
						$_POST[$item] = strtotime($_POST[$item]);
				}
			}
			
			// Upload files to specified locations
			if(isset($fileUploadLocations) && count($fileUploadLocations) > 0)
			{
				foreach($fileUploadLocations as $k => $v)
				{
					if($_FILES[$k]['name'] != "")
					{
						$file = new UploadFile($_FILES[$k]);
						
						if(isset($forceImageNames))
							$file->renameFile($forceImageNames[$k]);
						
						$file->process($v);
						
						$_POST[$k] = $file->fileName;
						
						if(!$file->processed())
							$imgError = true;

						if(isset($imageForceSizes) && count($imageForceSizes) > 0)
						{
							if(array_key_exists($k, $imageForceSizes))
							{
								try
								{
					                $img = new SimpleImage($fileUploadLocations[$k].$file->fileName);
					                $img->adaptive_resize($imageForceSizes[$k][0], $imageForceSizes[$k][1]);
					                $img->save();
					            }
					            catch(Exception $e)
					            {
					                die("Err: ".$e->getMessage());
					            }
							}
						}
					}else
					{
						$_POST[$k] = "#/noupdate/#";
					}
				}
			}

			if(!isset($imgError))
			{
				// Set value of checkbox's
				foreach($formGen->elements as $item)
				{
					if($item[1] == "checkbox")
					{
						if(isset($_POST[$item[2]["name"]]))
							$_POST[$item[2]["name"]] = "1";
						else
							$_POST[$item[2]["name"]] = "0";
					}
				}
				
				$success = updateQueryFromArray($table, $_POST, " WHERE id=:id", (int)$_GET["param_1"]);
				
				if($success)
				{
					$msg = "<h3 style='color: green;'>Successfully edited</h3>";
					$data = $DB->QuerySingle("SELECT * FROM ".$table." WHERE id=:id", array("id" => $_GET["param_1"]));
					$_POST = array();
				}
				else
				{
					$msg = "<h3 style='color: red;'>There was an error</h3><p>".$DB->ErrorHandler()."</p>";
				}
			}
			else
			{
				$msg = "<h3 style='color: red;'>There was a file upload error</h3><p>".$file->getMsg()."</p>";
			}
		}

		// Prepopulate fields with current data
		if(isset($strToTimeFields))
		{
			if(count((array)$strToTimeFields) > 0)
			{
				// Check for str to time fields
				foreach($strToTimeFields as $item)
					$data[$item] = date("Y/m/d H:i", (int)$data[$item]);
			}
		}
		$formGen->editData($data);

		$formGen->setFormAttributes(array("action" => "", "method" => "post", "enctype" => "multipart/form-data", "class" => "mainForm"));
		$html = $formGen->generateForm(true);

	}
	else
	{
		$html = "
			The configuration does not exist for this table, 
			first create the setup in 'rootPath/config/'
		";
	}
?>

<div id="pageContent">
	<?php if(isset($msg)) echo $msg; ?>
	
	<h1><a href="">Editing item id "<?php echo $_GET["param_1"]; ?>" in table "<?php echo $table; ?>"</a></h1>
	
	<?php echo $constMsg; ?>
	
	<p><?php echo $html; ?></p>
</div>