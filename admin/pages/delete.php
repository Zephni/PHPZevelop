<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "",
		"PassParams" => true
	));

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