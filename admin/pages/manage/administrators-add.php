<?php
	$Table = DBTool::GetTable("administrators");
	$TableConfig = DBTool::TableConfigArray($Table["real_name"]);
	
	if($Table["real_name"] != "administrators")
		die("Cannot add rows to this table with the administrator add method");

	if(!HasPermission("general", "database") || !HasPermission("general", "administrator_add"))
		die("You do not have permission to add rows to this table");

	if($Administrator->Data["username"] != "Zephni" && isset($TableConfig["Disabled"]) && strtolower($TableConfig["Disabled"][0]) == "true")
		$PHPZevelop->Location("");
	
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
			if(ArrGet($ConfigArray, "type", 0) == "checkbox") ValidateValues::$ValidPairs[$K] = implode("|", $V);
		}
		
		if(count(ValidateValues::$InvalidPairs) == 0){
			$LastInsertID = $DB->Insert($Table["real_name"], ValidateValues::$ValidPairs);

			// Password / salt
			$AdministratorObject = Administrator::GetSingle("administrators", array("id", "=", $LastInsertID));
			$AdministratorObject->SetPassword($_POST["password"]);
			$AdministratorObject->Update();

			ChangeLog("Uploaded to '".$Table["name"]."'");
			$PHPZevelop->Location("manage/administrators");
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
	echo FormGen::DBFormBuild(DBTool::GetTable($Table["real_name"]), array(
		"Data" => $_POST,
		"HideFields" => array("id", "salt"),
		"CustomFields" => array("password" => function($FormGen, &$Item){
			
		}),
		"SubmitText" => "Create"
	));
?>

<p>Permissions (values below will be prohibited from this administrator) eg:
<pre>
[{
"general":["create","modify","remove","administrator_delete"],
"table":[{"articles":["add","select","disable_title","hide_password"]}]
}]
</pre>
</p>