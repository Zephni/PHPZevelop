<?php
	$Table = DBTool::GetTable($_GET["param_0"]);
	$TableConfig = DBTool::TableConfigArray($Table["real_name"]);
	
	if($Table["real_name"] == Administrator::$DBTABLEDEFAULT)
		die("Cannot add rows to this table with the standard add method");

	if(!HasPermission("table", $Table["real_name"], "add"))
		die("You do not have permission to add rows to this table");

	if($Administrator->Data["username"] != "Zephni" && isset($TableConfig["Disabled"]) && strtolower($TableConfig["Disabled"][0]) == "true")
		$PHPZevelop->Location("");
	
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Adding to ".$Table["name"],
		"PassParams" => true
	));
	
	if(isset($_POST["_submit"]))
	{
		unset($_POST["_submit"]);
		
		ValidateValues::Run($_POST, array());
		
		foreach(array_merge(ValidateValues::$ValidPairs, $_FILES) as $K => $V){
			$ConfigArray = DBTool::FieldConfigArray($Table["columns"][$K]["column_comment"]);
			if(ArrGet($ConfigArray, "type", 0) == "timestamp") ValidateValues::$ValidPairs[$K] = strtotime($V);
			if(ArrGet($ConfigArray, "type", 0) == "file") UploadFile($K, $ConfigArray, $V);
			if(ArrGet($ConfigArray, "type", 0) == "image" && isset($_FILES[$K]["name"]) && count($_FILES[$K]["name"]) > 0) UploadImage($K, $ConfigArray, $V[0]); // Will upload single
			if(ArrGet($ConfigArray, "type", 0) == "images" && isset($_FILES[$K]["name"]) && count($_FILES[$K]["name"]) > 0) UploadImage($K, $ConfigArray, $V); // Will upload multiple
			if(ArrGet($ConfigArray, "type", 0) == "checkbox") ValidateValues::$ValidPairs[$K] = implode("|", $V);
		}
		
		if(count(ValidateValues::$InvalidPairs) == 0)
		{
			//die(print_r(ValidateValues::$ValidPairs));
			$DB->Insert($Table["real_name"], ValidateValues::$ValidPairs);
			if(count($DB->Errors) > 0)
				die(print_r($DB->Errors));
			
			$PHPZevelop->Location("edit/".$_GET["param_0"]."/".$Table["information"]["auto_increment"]);
		}
	}
?>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration",
		"select/".$Table["real_name"] => $Table["name"],
		"create" => "Creating ".$Table["name"]." row"
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

<h1>Creating <?php echo strtolower($Table["name"]); ?> row</h1>

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
	
	if(isset($TableConfig["HideFields"])){
		foreach($TableConfig["HideFields"] as $K => $V) $HideFields[] = trim($V);
	}
	
	echo FormGen::DBFormBuild(DBTool::GetTable($Table["real_name"]), array(
		"Data" => $_POST,
		"HideFields" => $HideFields,
		"DisableFields" => $DisableFields,
		"SubmitText" => "Create",
		"SubmitValue" => true,
		"ColNum" => (isset($TableConfig["ColNum"])) ? $TableConfig["ColNum"][0] : 1
	));
?>