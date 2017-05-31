<?php
	$Table = DBTool::GetTable($_GET["param_0"]);
	$TableConfig = DBTool::TableConfigArray($Table["real_name"]);
	$Data = $DB->Select("*", $Table["real_name"], array(array("id", "=", $_GET["param_1"])), true);

	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Editing #".$Data["id"]." in ".$Table["name"],
		"PassParams" => true
	));

	if(count($_POST) > 0)
	{
		$Data = $DB->Select("*", $Table["real_name"], array(array("id", "=", $_GET["param_1"])), true);

		ValidateValues::Run($_POST, array());

		foreach(array_merge(ValidateValues::$ValidPairs, $_FILES) as $K => $V){
			$ConfigArray = DBTool::FieldConfigArray($Table["columns"][$K]["column_comment"]);
			if(ArrGet($ConfigArray, "type", 0) == "timestamp") ValidateValues::$ValidPairs[$K] = strtotime($V);
			if(ArrGet($ConfigArray, "type", 0) == "file") UploadFile($K, $ConfigArray, $V);
			if(ArrGet($ConfigArray, "type", 0) == "image") UploadImage($K, $ConfigArray, $V);
		}

		if(count(ValidateValues::$InvalidPairs) == 0)
			$DB->Update($Table["real_name"], ValidateValues::$ValidPairs, array(array("id", "=", $_GET["param_1"])));

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
		
		include("file-manager.php");
	}

?>

<h1>Editing #<?php echo $Data["id"]; ?> in <?php echo strtolower($Table["name"]); ?></h1>

<?php
	if(ArrGet($TableConfig, "EditLink", 0) != "")
	{
		$TempEditLink = explode("|", $TableConfig["EditLink"][0]);
		$TempEditLink[0] = str_replace("[id]", $_GET["param_1"], $TempEditLink[0]);
		echo "<p><a href='".$TempEditLink[0]."' target='_blank'>".$TempEditLink[1]."</a></p>";
	} unset($TempEditLink);
?>

<?php
	echo FormGen::DBFormBuild(DBTool::GetTable($Table["real_name"]), array(
		"Data" => $Data,
		"HideFields" => array("id"), 
		"SubmitText" => "Update"
	));
?>