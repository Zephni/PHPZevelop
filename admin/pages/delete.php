<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "",
		"PassParams" => true
	));

	if(strtolower($_GET["param_0"]) == "administrators")
		die("You cannot delete from the administrator table using the default delete function");

	$Table = DBTool::GetTable($_GET["param_0"]);

	$TableConfig = DBTool::TableConfigArray($Table["real_name"]);

	if($Administrator->Data["username"] != "Zephni" && isset($TableConfig["Disabled"]) && strtolower($TableConfig["Disabled"][0]) == "true")
		$PHPZevelop->Location("");

	if(isset($Table["real_name"]))
	{
		$Data = $DB->QuerySingle("DELETE FROM ".$Table["real_name"]." WHERE id=:id", array("id" => $_GET["param_1"]));
		$PHPZevelop->Location("select/".$Table["real_name"]);

		$LogString = "Deleted #".$_GET["param_1"]." from '".$Table["name"]."'";
		ChangeLog($LogString);
	}
?>