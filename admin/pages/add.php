<?php
	$Table = DBTool::GetTable($_GET["param_0"]);
	$TableConfig = DBTool::TableConfigArray($Table["real_name"]);
	
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Adding to ".$Table["name"],
		"PassParams" => true
	));
	
	if(count($_POST) > 0)
	{
		ValidateValues::Run($_POST, array());
		
		foreach(array_merge(ValidateValues::$ValidPairs, $_FILES) as $K => $V){
			$ConfigArray = DBTool::FieldConfigArray($Table["columns"][$K]["column_comment"]);
			if(ArrGet($ConfigArray, "type", 0) == "timestamp") ValidateValues::$ValidPairs[$K] = strtotime($V);
			if(ArrGet($ConfigArray, "type", 0) == "file") UploadFile($K, $ConfigArray, $V);
			if(ArrGet($ConfigArray, "type", 0) == "image") UploadImage($K, $ConfigArray, $V);
		}
		
		if(count(ValidateValues::$InvalidPairs) == 0){
			$DB->Insert($Table["real_name"], ValidateValues::$ValidPairs);
			ChangeLog("Uploaded to '".$Table["name"]."'");
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
		
		include("file-manager.php");
	}
?>

<h1>Creating <?php echo strtolower($Table["name"]); ?> row</h1>

<?php
	echo FormGen::DBFormBuild(DBTool::GetTable($Table["real_name"]), array(
		"Data" => $_POST,
		"HideFields" => array("id"), 
		"SubmitText" => "Create"
	));
?>