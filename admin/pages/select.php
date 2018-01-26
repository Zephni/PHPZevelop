<?php
	$Table = DBTool::GetTable($_GET["param_0"]);

	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => $Table["name"],
		"PassParams" => true
	));

	$TableConfig = DBTool::TableConfigArray($Table["real_name"]);
?>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration",
		"select/".$Table["real_name"] => $Table["name"]
	));
?></div>

<h1>Browsing <?php echo strtolower($Table["name"]); ?></h1>
<br />

<?php
	// Passed search through GET
	if(isset($_GET["search"]) || isset($_GET["page"]))
	{
		$_SESSION["temp_search"] = urldecode($_GET["search"]);
		$TempPage = (isset($_GET["page"])) ? $_GET["page"] : "1";
		$PHPZevelop->Location("select/".$_GET["param_0"]."/".$TempPage);
	}

	if(isset($_SESSION["temp_search"]))
	{
		$_POST["search"] = $_SESSION["temp_search"];
		unset($_SESSION["temp_search"]);
	}
	// /Passed search through GET

	$SearchForm = new FormGen();
	$SearchForm->AddElement(array("type" => "text", "name" => "search", "placeholder" => "Search ".strtolower($Table["name"])));
	$SearchForm->AddElement(array("type" => "submit", "value" => "Search"));
	echo $SearchForm->Build(array("ColNum" => 2, "data" => $_POST));

	$Where = array();
	if(count($_POST) > 0)
	{
		if(substr($_POST["search"], 0, 1) != "*"){
			foreach($Table["columns"] as $Column){
				$Where[] = array($Column["column_name"], "LIKE", "%".$_POST["search"]."%");
				$Where[] = "OR";
			}
		}else{
			$Temp = ltrim($_POST["search"], "*");
			foreach(explode(",", $Temp) as $Item){
				$Parts = explode(" ", trim($Item));
				if(strtolower($Parts[1]) == "like") $Parts[2] = "%".$Parts[2]."%";
				$Where[] = array($Parts[0], $Parts[1], $Parts[2]);
				$Where[] = "AND";
			}
		}

		array_pop($Where);
	}

	$_SESSION["DBWhere"] = $Where;

	$Rows = $DB->Select("COUNT(id) as 'count'", $_GET["param_0"], $Where, true);
	$Pagination->Options["URL"] = $PHPZevelop->Path->GetPage("select/".$_GET["param_0"]."?page=(PN)".(isset($_POST["search"]) ? "&search=".$_POST["search"] : ""), true);
	$Pagination->SetPage((isset($_GET["param_1"])) ? $_GET["param_1"] : 1);
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