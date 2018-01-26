<?php	
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Adding field",
		"PassParams" => true
	));
	
	if(count($_POST) > 0)
	{
		ValidateValues::Run($_POST, array());
		
		if(count(ValidateValues::$InvalidPairs) == 0)
		{
			$TableFields = DBTool::GetTableColumns($_POST["table_name"]);

			if(array_key_exists($_POST["field_name"], $TableFields))
			{
				$MSG = "Field '".$_POST["field_name"]."' already exists";
			}
			else
			{
				$DB->QuerySingle("ALTER TABLE ".$_POST["table_name"]." ADD ".$_POST["field_name"]." VARCHAR(255) NOT NULL DEFAULT '".$_POST["field_default"]."' COMMENT '".$_POST["field_comment"]."'");
				$PHPZevelop->Location("/admin343872/select/".$_POST["table_name"]);
			}
		}
	}
?>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration",
		"create" => "Adding field"
	));
?></div>

<h1>Adding field</h1>

<?php if(isset($MSG)) echo "<h2 style='color: red;'>".$MSG."</h2>"; ?>

<?php
	$Tables = array();
	foreach($DB->Query("SHOW TABLES") as $K => $V)
		$Tables[$V["Tables_in_kbbarkco_db"]] = $V["Tables_in_kbbarkco_db"];

	$FormGen = new FormGen();
	$FormGen->AddElement(array("type" => "select", "name" => "table_name"), array("title" => "Table", "data" => $Tables));
	$FormGen->AddElement(array("type" => "text", "name" => "field_name"), array("title" => "Field name"));
	$FormGen->AddElement(array("type" => "text", "name" => "field_default"), array("title" => "Field default"));
	$FormGen->AddElement(array("type" => "text", "name" => "field_comment"), array("title" => "Field options"));
	$FormGen->AddElement(array("type" => "submit"));

	echo $FormGen->Build(array("Data" => $_POST));
?>