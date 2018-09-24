<?php
	$Table = DBTool::GetTable("administrators");
	$TableConfig = DBTool::TableConfigArray($Table["real_name"]);
	$Data = $DB->Select("*", $Table["real_name"], array(array("id", "=", $_GET["param_0"])), true);
	
	if($Table["real_name"] != "administrators")
		die("Cannot modify rows in this table with the administrator edit method");

	if(!HasPermission("general", "administrator_edit"))
		die("You do not have permission to edit rows in this table");

	if($Administrator->Data["username"] != "Zephni" && isset($TableConfig["Disabled"]) && strtolower($TableConfig["Disabled"][0]) == "true")
		$PHPZevelop->Location("");
	
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Editing #".$Data["id"]." in ".$Table["name"],
		"PassParams" => true
	));

	if(count($_POST) > 0)
	{
		$Data = $DB->Select("*", $Table["real_name"], array(array("id", "=", $_GET["param_0"])), true);
		
		ValidateValues::Run($_POST, array());
		
		foreach(array_merge(ValidateValues::$ValidPairs, $_FILES) as $K => $V){
			$ConfigArray = DBTool::FieldConfigArray($Table["columns"][$K]["column_comment"]);
			if(ArrGet($ConfigArray, "type", 0) == "timestamp") ValidateValues::$ValidPairs[$K] = strtotime($V);
			if(ArrGet($ConfigArray, "type", 0) == "file") UploadFile($K, $ConfigArray, $V);
			if(ArrGet($ConfigArray, "type", 0) == "image") UploadImage($K, $ConfigArray, $V);
			if(ArrGet($ConfigArray, "type", 0) == "checkbox") ValidateValues::$ValidPairs[$K] = implode("|", $V);
		}
		
		if(count(ValidateValues::$InvalidPairs) == 0)
			$DB->Update($Table["real_name"], ValidateValues::$ValidPairs, array(array("id", "=", $_GET["param_0"])));
		
		$LogString = array(); foreach(ValidateValues::$ValidPairs as $K => $V) if($Data[$K] != $V) $LogString[] = $K;
		if(count($LogString) > 0) ChangeLog("Updated '".$Table["name"]."' #".$_GET["param_0"]." fields: ".implode(", ", $LogString));
	}
	
	$Data = $DB->Select("*", $Table["real_name"], array(array("id", "=", $_GET["param_0"])), true);
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
			$TempEditLink[0] = str_replace("[id]", $_GET["param_0"], $TempEditLink[0]);
			if(strstr($TempEditLink[0], "alias") !== false) $TempEditLink[0] = str_replace("[alias]", $Data["alias"], $TempEditLink[0]);
			echo "<a href='".$TempEditLink[0]."' target='_blank' style='position: relative; left: 0px;'>".$TempEditLink[1]."</a>";
		} unset($TempEditLink);
	?>
</p>

<?php
	echo FormGen::DBFormBuild(DBTool::GetTable($Table["real_name"]), array(
		"Data" => $Data,
		"HideFields" => array("id"), 
		"SubmitText" => "Update"
	));
?>