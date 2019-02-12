<?php
	$Table = DBTool::GetTable($_GET["param_0"]);
	$TableConfig = DBTool::TableConfigArray($Table["real_name"]);
	$Data = $DB->Select("*", $Table["real_name"], array(array("id", "=", $_GET["param_1"])), true);

	if($Table["real_name"] == "administrators")
		die("Cannot modify rows in this table with the standard edit method");

	if(!HasPermission("table", $Table["real_name"], "edit"))
		die("You do not have permission to edit rows in this table");

	if($Administrator->Data["username"] != "Zephni" && isset($TableConfig["Disabled"]) && strtolower($TableConfig["Disabled"][0]) == "true")
		$PHPZevelop->Location("");

	// Delete image
	if(isset($_GET["imgdel"]))
	{
		$ImgDel = urldecode($_GET["imgdel"]);
		$ImageFile = explode("/", $ImgDel);
		$ImageFile = $ImageFile[count($ImageFile)-1];
		$FullImgPath = $FrontEndImageLocationRoot."/".$ImgDel;
		try {unlink($FullImgPath);} catch (Exception $e) {}

		$Data[$_GET["imgfield"]] = explode(",", $Data[$_GET["imgfield"]]);
		if(($key = array_search($ImageFile, $Data[$_GET["imgfield"]])) !== false)
			unset($Data[$_GET["imgfield"]][$key]);
		$Data[$_GET["imgfield"]] = implode(",", $Data[$_GET["imgfield"]]);

		$DB->Update($Table["real_name"], array($_GET["imgfield"] => $Data[$_GET["imgfield"]]), array(array("id", "=", $_GET["param_1"])));
		$PHPZevelop->Location($PHPZevelop->CFG->PagePath);
	}
	
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Editing #".$Data["id"]." in ".$Table["name"],
		"PassParams" => true
	));

	if(isset($_POST["_submit"]))
	{
		unset($_POST["_submit"]);
		
		$Data = $DB->Select("*", $Table["real_name"], array(array("id", "=", $_GET["param_1"])), true);
		
		// Resets checkboxs to empty if none passed
		foreach($Data as $K => $V)
		{
			$ConfigArray = DBTool::FieldConfigArray($Table["columns"][$K]["column_comment"]);
			if(ArrGet($ConfigArray, "type", 0) == "checkbox")
			{
				$DB->Update($Table["real_name"], array($K => ""), array(array("id", "=", $_GET["param_1"])));
			}
		}

		ValidateValues::Run($_POST, array());

		foreach(array_merge(ValidateValues::$ValidPairs, $_FILES) as $K => $V){
			$ConfigArray = DBTool::FieldConfigArray($Table["columns"][$K]["column_comment"]);
			if(ArrGet($ConfigArray, "type", 0) == "timestamp") ValidateValues::$ValidPairs[$K] = strtotime($V);
			if(ArrGet($ConfigArray, "type", 0) == "file") UploadFile($K, $ConfigArray, $V);
			if(ArrGet($ConfigArray, "type", 0) == "image") UploadImage($K, $ConfigArray, $V);
			if(ArrGet($ConfigArray, "type", 0) == "images" && isset($_FILES[$K]["name"]) && count($_FILES[$K]["name"]) > 0){
				$Offset = 0;
				foreach(explode(",", $Data[$K]) as $Temp)
					if(!empty($Temp))
						$Offset++;
				//die("(".$Offset.")");
				UploadImage($K, $ConfigArray, $V, $Offset); // Will upload multiple, and set an offset
			}
			if(ArrGet($ConfigArray, "type", 0) == "checkbox" && isset(ValidateValues::$ValidPairs[$K]) && ValidateValues::$ValidPairs[$K] != "") ValidateValues::$ValidPairs[$K] = implode("|", $V);
		}

		if(count(ValidateValues::$InvalidPairs) == 0)
		{
			$DB->Update($Table["real_name"], ValidateValues::$ValidPairs, array(array("id", "=", $_GET["param_1"])));
		}
		
		$LogString = array(); foreach(ValidateValues::$ValidPairs as $K => $V) if($Data[$K] != $V) $LogString[] = $K;
		if(count($LogString) > 0) ChangeLog("Updated '".$Table["name"]."' #".$_GET["param_1"]." fields: ".implode(", ", $LogString));
	}
	
	$Data = $DB->Select("*", $Table["real_name"], array(array("id", "=", $_GET["param_1"])), true);
?>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration",
		"select/".$Table["real_name"] => $Table["name"],
		"edit" => "Editing #".$Data["id"]." in ".$Table["name"]
	));
?></div>

<?php
	if(isset($TableConfig["FileManager"][0]))
	{
		$CustomFileManager = array(
			"JustUpload" => true,
			"DefaultLocation" => trim($TableConfig["FileManagerDefaultLocation"][0])
		);
		
		// Need to include here
	}
?>

<h1>Editing #<?php echo $Data["id"]; ?> in <?php echo strtolower($Table["name"]); ?></h1>

<p style="position: relative;">
	<?php
		if(ArrGet($TableConfig, "EditLink", 0) != "")
		{
			$TempEditLink = explode("|", $TableConfig["EditLink"][0]);
			$TempEditLink[0] = str_replace("[id]", $_GET["param_1"], $TempEditLink[0]);
			if(strstr($TempEditLink[0], "alias") !== false) $TempEditLink[0] = str_replace("[alias]", $Data["alias"], $TempEditLink[0]);
			echo "<a href='".$TempEditLink[0]."' target='_blank' style='position: relative; left: 0px;'>".$TempEditLink[1]."</a>";
		} unset($TempEditLink);
	?>
	
	<?php if(HasPermission("table", $_GET["param_0"], "delete")){ ?>
	<a href="<?php $PHPZevelop->Path->GetPage("delete/".$_GET["param_0"]."/".$_GET["param_1"]); ?>" style='position: absolute; right: 0px;'>Delete this item</a>
	<?php } ?>
</p>

<?php
	$HideFields = array("id");
	$DisableFields = array();
	$Permissions = json_decode($Administrator->Data["permissions"], true);
	if(isset($Permissions[0]["table"][0][$Table["real_name"]]))
	{
		foreach($Permissions[0]["table"][0][$Table["real_name"]] as $Item)
		{
			if(substr($Item, 0, strlen("hide_")) == "hide_") $HideFields[] = substr($Item, strlen("hide_"));
			if(substr($Item, 0, strlen("disable_")) == "disable_") $DisableFields[] = substr($Item, strlen("disable_"));
		}
	}
	
	if(isset($TableConfig["HideFields"]))
		foreach($TableConfig["HideFields"] as $K => $V) $HideFields[] = trim($V);
	

	echo FormGen::DBFormBuild(DBTool::GetTable($Table["real_name"]), array(
		"Data" => $Data,
		"HideFields" => $HideFields,
		"DisableFields" => $DisableFields,
		"SubmitText" => "Update",
		"SubmitValue" => true,
		"ColNum" => (isset($TableConfig["ColNum"])) ? $TableConfig["ColNum"][0] : 1
	));
?>