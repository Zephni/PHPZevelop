<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Home",
		"PassParams" => true
	));

	if(!HasPermission("general", "database") || !HasPermission("general", "administrator_select"))
		die("You do not have permission to view this table");

	if(isset($_GET["param_0"]) && $_GET["param_0"] == "delete")
	{
		if(HasPermission("general", "administrator_delete"))
			$DB->QuerySingle("DELETE FROM administrators WHERE id=:id", array("id" => $_GET["param_1"]));
	}
?>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration"
	));
?></div>

<h1><?php echo $PHPZevelop->CFG->SiteTitle; ?> administrators</h1>
<br />

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration",
		"manage/administrators" => "manage administrators"
	));
?></div>

<?php
	$Order = "ORDER BY id ASC";

	$Where = array();

	$ExtraFields = array();
	if(HasPermission("general", "administrator_edit")) $ExtraFields[] = "<center><a href='".$PHPZevelop->Path->GetPage("manage/administrators-edit/#", true)."'>edit</a></center>";
	if(HasPermission("general", "administrator_delete")) $ExtraFields[] = "<center><a href='".$PHPZevelop->Path->GetPage("manage/administrators/delete/#", true)."'>
				<img src='".$PHPZevelop->Path->GetImage("/components/delete.png", true)."' style='width: 16px;' />
			</a></center>";

	echo DBTool::DisplayList(array(
		"Table" => "administrators",
		"NoDataHTML" => "<br /><center><p style='color: #888888'>No data available, <a href='".$PHPZevelop->Path->GetPage("manage/administrators-add", true)."'>create ".strtolower("administrators")." item</a></p></center>",
		"ExtraFields" => $ExtraFields,
		"Where" => $Where,
		"Order" => $Order,
		"Limit" => $Pagination->BeginItems.",".$Pagination->Options["PerPage"]
	));