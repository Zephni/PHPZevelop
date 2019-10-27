<?php
	$Table = DBTool::GetTable($_GET["param_0"]);

	$TableConfig = DBTool::TableConfigArray($Table["real_name"]);

	if(!HasPermission("table", $Table["real_name"], "select"))
		die("You do not have permission to view this table");

	if(isset($_GET["reset"]) && $_GET["reset"] == "true")
	{
		unset($_SESSION["admin_filters"], $_GET["reset"]);
	}

	foreach($_GET as $K => $V)
	{
		if(substr($K, 0, 5) != "param")
			$_POST[$K] = urldecode($V);
	}

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
		"" => "administration",
		"select" => "select",
		"select/".$Table["real_name"] => $Table["name"]
	));
?></div>

<div style="width: 100%; position: relative;">
<h1>
<?php
	echo "Browsing ".((!isset($_POST["field1"])) ? "all " : "").strtolower($Table["name"]);
?>
</h1>

<?php if(false){ ?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#DatePickerTstamp").change(function(){
			var temp = new Date($(this).val());
			$("#TstampShower").val( temp.getTime().toString().substr(0, 10) );
		});

		var temp = new Date();
		$("#TstampShower").val( temp.getTime().toString().substr(0, 10) );
	});
</script>
<div style="position: absolute; top: 20px; right: 20px;">
	<input id="DatePickerTstamp" type="text" class="datetimepicker" value="<?php echo date("d-m-Y"); ?>" style="width: 120px;" />
	<input id="TstampShower" type="text" style="width: 120px;" />
</div>
<?php } ?>

<a href="<?php $PHPZevelop->Path->GetPage("add/".$_GET["param_0"]); ?>" style="position: absolute; top: 20px; right: 0px;" class="btn btn-primary">
	Add new to <strong><?php echo $_GET["param_0"]; ?></strong>
</a>

<a href="<?php $PHPZevelop->Path->GetPage("downloadcsv/".$_GET["param_0"]); ?>" style="position: absolute; top: 36px; right: 0px; margin-top: 28px;" class="btn btn-info btn-sm">
	Download CSV dataset <strong>(<span id="Count">counting...</span>)</strong>
</a>

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
		".FilterFormSet *" => array("width" => "120px", "padding" => "4px"),
		".FilterFormSet select" => array("box-shadow" => "none", "border" => "none"),
		"input[type='submit']" => array("width" => "200px")
	));

	echo $FilterForm->BuildHeaderHTML();
	echo $FilterForm->BuildHTML();

	$Where = array();
	if(count($_POST) > 0)
		$Where = $FilterForm->DataToMySQLWhere($_POST);

	$_SESSION["DBWhere"] = $Where;

	$Rows = $DB->Select("COUNT(id) as 'count'", $_GET["param_0"], $Where, true);

	$ExtraFields = array();

	$FieldList = ArrGet($TableConfig, "Options");
	if($FieldList == false) $FieldList = array("edit", "delete");

	foreach($FieldList as $Item)
	{
		$Item = trim($Item);
		if($Item == "edit" && HasPermission("table", $Table["real_name"], "edit"))
			$ExtraFields[] = "<center><a class='btn btn-info' href='".$PHPZevelop->Path->GetPage("edit/".$Table["real_name"]."/#", true)."'>Edit</a></center>";
		else if($Item == "delete" && HasPermission("table", $Table["real_name"], "delete"))
			$ExtraFields[] = "<center><a class='btn btn-danger' href='".$PHPZevelop->Path->GetPage("delete/".$Table["real_name"]."/#", true)."'>Delete</a></center>";
		else if(substr($Item, 0, 7) == "custom|") // custom html, like custom|<center>Some text</center>
			$ExtraFields[] = substr($Item, 7);

	}
?>

<br /><br />
<?php
	$Order = (isset($TableConfig["DefaultOrder"]) && $TableConfig["DefaultOrder"][0] != "") ? "ORDER BY ".implode(",", $TableConfig["DefaultOrder"]) : "ORDER BY id DESC";

	echo DBTool::DisplayList(array(
		"Table" => $Table["real_name"],
		"TableClass" => "table table-stripped",
		"InfoRowClass" => "thead-dark",
		//"HideFields" => $HideFields,
		"NoDataHTML" => "<br /><center><p style='color: #888888'>No data available, <a href='".$PHPZevelop->Path->GetPage("add/".$Table["real_name"], true)."'>create ".strtolower($Table["name"])." item</a></p></center>",
		"ExtraFields" => $ExtraFields,
		"Where" => $Where,
		"Order" => $Order,
		"Limit" => $Pagination->BeginItems.",".$Pagination->Options["PerPage"]
	));
?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#Count").html("<?php echo $Rows["count"]; ?>");
	});
</script>

<br />
<br />
<br />

<?php //echo "<pre style='color: #EEEEEE;'>".print_r($_SESSION["DBWhere"], true)."</pre>"; ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('table').DataTable({
            aoColumnDefs: [{
				bSortable: false,
				aTargets: [ -1, -2 ]
			}]
        });
    });
</script>