<?php
	$Table = DBTool::GetTable($_GET["param_0"]);

	$TableConfig = DBTool::TableConfigArray($Table["real_name"]);

	if($Administrator->Data["username"] != "Zephni" && isset($TableConfig["Disabled"]) && strtolower($TableConfig["Disabled"][0]) == "true")
		$PHPZevelop->Location("");
	
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => $Table["name"],
		"PassParams" => true
	));

?>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"admin343872" => "administration",
		"select/".$Table["real_name"] => $Table["name"]
	));
?></div>

<h1>Browsing <?php echo strtolower($Table["name"]); ?></h1>

<?php
	if(count($_POST) > 0)
	{
		$_SESSION["admin_filters"] = $_POST;
	}

	if(isset($_SESSION["admin_filters"]) && isset($_SESSION["admin_current_table"]) && $Table["real_name"] == $_SESSION["admin_current_table"])
	{
		$_POST = $_SESSION["admin_filters"];
	}
	
	$_SESSION["admin_current_table"] = $Table["real_name"];

	function moveElement(&$array, $a, $b) {
		$out = array_splice($array, $a, 1);
		array_splice($array, $b, 0, $out);
	}

	$cols = array(); foreach($Table["columns"] as $Item) $cols[] = $Item["column_name"];
	moveElement($cols, array_search("title", $cols), 0);

	$FilterForm = new FilterForm(array(
		"Fields" => $cols
	), array(
		".FilterFormSet *" => array("width" => "200px", "padding" => "8px"),
		".FilterFormSet select" => array("box-shadow" => "none", "border" => "none"),
		"input[type='submit']" => array("width" => "200px")
	));

	echo $FilterForm->BuildHeaderHTML();
	echo $FilterForm->BuildHTML();

	$Where = array();
	if(count($_POST) > 0)
	{
		$Where = $FilterForm->DataToMySQLWhere($_POST);
	}

	$_SESSION["DBWhere"] = $Where;


	$Rows = $DB->Select("COUNT(id) as 'count'", $_GET["param_0"], $Where, true);
	$Pagination->Options["URL"] = $PHPZevelop->Path->GetPage("select/".$_GET["param_0"]."?page=(PN)".(isset($_POST["search"]) ? "&search=".$_POST["search"] : ""), true);
	$Pagination->SetPage((isset($_GET["page"])) ? $_GET["page"] : 1);
	$PaginationHTML = $Pagination->BuildHTML($Rows["count"]);

	$ExtraFields = array();

	$FieldList = ArrGet($TableConfig, "Options");
	if($FieldList == false) $FieldList = array("edit", "delete");

	foreach($FieldList as $Item)
	{
		$Item = trim($Item);
		if($Item == "edit")
			$ExtraFields[] = "<center><a href='".$PHPZevelop->Path->GetPage("edit/".$Table["real_name"]."/#", true)."'>edit</a></center>";
		else if($Item == "delete")
			$ExtraFields[] = "<center><a href='".$PHPZevelop->Path->GetPage("delete/".$Table["real_name"]."/#", true)."'>delete</a></center>";
		else if($Item == "preview")
		{
			if(ArrGet($TableConfig, "EditLink", 0) != "")
			{
				$TempEditLink = explode("|", $TableConfig["EditLink"][0]);
				$TempEditLink[0] = str_replace("[id]", "#", $TempEditLink[0]);
				$ExtraFields[] = "<a href='".$TempEditLink[0]."' target='_blank' style='position: relative; left: 0px;'>".$TempEditLink[1]."</a>";
			} unset($TempEditLink);
		}
		else if($Item == "entries"){
			$ExtraFields[] = "<center>
				*special::showentries*<br />
				<a href='".$PHPZevelop->Path->GetPage("select/comp_entries/?search=".urlencode("*comp_id = ")."#".urlencode(", options like optin:1"), true)."'>entries</a>
			</center>";
		}else if($Item == "projects"){
			$ExtraFields[] = "<center>projects: *special::showprojectcount*</center>";
		}
	}
?>

<a href="<?php $PHPZevelop->Path->GetPage("downloadcsv/".$_GET["param_0"]); ?>" style="position: absolute; right: 32px; margin-top: -6px; padding: 5px 13px 5px 13px; color: white; background: <?php echo $Administrator->Data["theme_color"]; ?>; text-decoration: none;">
	Download CSV dataset | <?php echo $Rows["count"]; ?> results
</a>

<?php
	echo $PaginationHTML;
?>

<?php
	$Order = (isset($TableConfig["DefaultOrder"]) && $TableConfig["DefaultOrder"][0] != "") ? "ORDER BY ".implode(",", $TableConfig["DefaultOrder"]) : "ORDER BY id DESC";

	echo DBTool::DisplayList(array(
		"Table" => $Table["real_name"],
		"HideFields" => array("id"),
		"NoDataHTML" => "<br /><center><p style='color: #888888'>No data available, <a href='".$PHPZevelop->Path->GetPage("add/".$Table["real_name"], true)."'>create ".strtolower($Table["name"])." item</a></p></center>",
		"ExtraFields" => $ExtraFields,
		"Where" => $Where,
		"Order" => $Order,
		"Limit" => $Pagination->BeginItems.",".$Pagination->Options["PerPage"]
	));
?>