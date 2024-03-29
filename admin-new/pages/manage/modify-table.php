<?php	
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Modifying table",
		"PassParams" => true
	));

	if(!HasPermission("general", "database") || !HasPermission("general", "modify"))
		die("You do not have permission to modify tables");

	if(strtolower($_GET["param_0"]) == Administrator::$DBTABLEDEFAULT)
		die("You cannot modify or delete the administrator table (".Administrator::$DBTABLEDEFAULT.")");
	
		$Table = DBTool::GetTable($_GET["param_0"]);


	if(isset($_GET["param_1"]) && $_GET["param_1"] == "delete")
	{
		$DB->QuerySingle("DROP TABLE `".$_GET["param_0"]."`");
		$PHPZevelop->Location("");
	}

	if(count($_POST) > 0)
	{
		ValidateValues::Run($_POST, array());
		
		// Edit table here

		// Alter / add columns
		if(isset($_POST["field_name"]))
		{
			for($I = 0; $I < count($_POST["field_name"]); $I++)
			{
				if(isset($_POST["original_field_name"][$I]))
				{
					$DB->QuerySingle("ALTER TABLE `".$Table["real_name"]."` CHANGE `".$_POST["original_field_name"][$I]."` `".$_POST["field_name"][$I]."` ".$_POST["field_type"][$I]." NOT NULL");
					$DB->QuerySingle("ALTER TABLE `".$Table["real_name"]."` MODIFY `".$_POST["field_name"][$I]."` ".$_POST["field_type"][$I]." COMMENT '".$_POST["field_comment"][$I]."'");
					$DB->QuerySingle("ALTER TABLE `".$Table["real_name"]."` ALTER `".$_POST["field_name"][$I]."` SET DEFAULT '".$_POST["field_default"][$I]."'");
				}
				else
				{
					$After = ($I == 0) ? "id" : $_POST["field_name"][$I-1];
					$DB->QuerySingle("ALTER TABLE `".$Table["real_name"]."` ADD COLUMN `".$_POST["field_name"][$I]."` ".$_POST["field_type"][$I]." NOT NULL AFTER `".$After."`");
					$DB->QuerySingle("ALTER TABLE `".$Table["real_name"]."` MODIFY `".$_POST["field_name"][$I]."` ".$_POST["field_type"][$I]." COMMENT '".$_POST["field_comment"][$I]."'");
					$DB->QuerySingle("ALTER TABLE `".$Table["real_name"]."` ALTER `".$_POST["field_name"][$I]."` SET DEFAULT '".$_POST["field_default"][$I]."'");
				}
			}
		}
		
		// Fields marked for delete
		if(strlen($_POST["marked_for_delete"]) > 0)
		{
			foreach(explode(",", $_POST["marked_for_delete"]) as $Alias)
			{
				$DB->QuerySingle("ALTER TABLE `".$Table["real_name"]."` DROP COLUMN `".$Alias."`");
			}
		}

		// Update table comment
		$DB->QuerySingle("ALTER TABLE `".$Table["real_name"]."` COMMENT='".addslashes($_POST["table_comment"])."'");

		// Rename table
		if($_POST["table_name"] != $Table["real_name"])
		{
			$DB->QuerySingle("ALTER TABLE `".$Table["real_name"]."` RENAME `".$_POST["table_name"]."`;");
			$PHPZevelop->Location("manage/modify-table/".$_POST["table_name"]."/changes");
		}
		
		$Table = DBTool::GetTable($_GET["param_0"]);
	}
?>

<style type="text/css">
	#addField:hover {cursor: pointer;}
	.removeField {color: #DD3333; margin-left: 10px;}
	.removeField:hover {cursor: pointer;}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		FieldHTML =  "<tr class='field'><td>Field <span class='removeField'>(remove)</span></td></tr><tr><td><div class='form-group group-spacer'>";
		FieldHTML += "<input type='text' class='form-control d-inline' name='field_name[]' style='width: 24%; margin-right: 1.3%;' placeholder='field name' />";
		FieldHTML += "<select type='text' class='form-control d-inline' name='field_type[]' style='width: 24%; margin-right: 1.3%;'>";
		<?php foreach($FieldTypeOptions as $Item){ ?>
		FieldHTML += "<option><?= $Item ?></option>";
		<?php } ?>
		FieldHTML += "</select>";
		FieldHTML += "<input type='text' class='form-control d-inline' name='field_default[]' style='width: 24%; margin-right: 1.2%;' placeholder='field default' />";
		FieldHTML += "<input type='text' class='form-control d-inline' name='field_comment[]' style='width: 24%;' placeholder='field comment' />";
		FieldHTML += "</div></td></tr>";

		<?php
			foreach($Table["columns"] as $K => $V)
			{
				if($K == "id")
					continue;
				?>
				LoadedFieldHTML =  "<tr class='field'><td>Field <span class='removeField'>(remove)</span></td></tr><tr><td><div class='form-group group-spacer'>";
				LoadedFieldHTML += "<input type='hidden' name='original_field_name[]' style='width: 24%; margin-right: 1.3%;' value='<?php echo preg_replace("/\r?\n|\r/", "", str_replace("'", "", $K)); ?>' />";
				LoadedFieldHTML += "<input type='text' class='form-control d-inline' name='field_name[]' style='width: 24%; margin-right: 1.3%;' placeholder='field name' value='<?php echo preg_replace("/\r?\n|\r/", "", str_replace("'", "", $K)); ?>' />";

				LoadedFieldHTML += "<select type='text' class='form-control d-inline' name='field_type[]' style='width: 24%; margin-right: 1.3%;'>";
				<?php foreach($FieldTypeOptions as $Item){ ?>
					LoadedFieldHTML += "<option <?= (strtoupper($V["column_type"]) == $Item) ? "selected='selected'" : "" ; ?>><?= $Item ?></option>";
				<?php } ?>
				LoadedFieldHTML += "</select>";

				LoadedFieldHTML += "<input type='hidden' name='original_field_default[]' style='width: 24%; margin-right: 1.3%;' value='<?php echo preg_replace("/\r?\n|\r/", "", str_replace("'", "", $V["column_default"])); ?>' />";
				LoadedFieldHTML += "<input type='text' class='form-control d-inline' name='field_default[]' style='width: 24%; margin-right: 1.2%;' placeholder='field default' value='<?php echo preg_replace("/\r?\n|\r/", "", str_replace("'", "", $V["column_default"])); ?>' />";

				LoadedFieldHTML += "<input type='hidden' name='original_field_comment[]' style='width: 24%;' value='<?php echo preg_replace("/\r?\n|\r/", "", str_replace("'", "", $V["column_comment"])); ?>' />";
				LoadedFieldHTML += "<input type='text' class='form-control d-inline' name='field_comment[]' style='width: 24%;' placeholder='field comment' value='<?php echo preg_replace("/\r?\n|\r/", "", str_replace("'", "", $V["column_comment"])); ?>' />";
				LoadedFieldHTML += "</div></td></tr>";
				$("#addField").parent().before(LoadedFieldHTML);
				
				$(".removeField").unbind().click(function(){
					$("input[name='marked_for_delete']").show();
					$("#markedtext").show();
					$("input[name='marked_for_delete']").attr("value", ($("input[name='marked_for_delete']").attr("value") + ","+$(this).parent().parent().next().find("input[name='field_name[]']").attr("value")));
					if($("input[name='marked_for_delete']").attr("value").charAt(0) == ",")
						$("input[name='marked_for_delete']").attr("value", $("input[name='marked_for_delete']").attr("value").substr(1));
					$(this).parent().parent().next().remove();
					$(this).parent().parent().remove();
				});
				<?php
			}
		?>
		
		$("#addField").click(function(){
			$(this).parent().before(FieldHTML);
			
			$(".removeField").unbind().click(function(){
				$(this).parent().parent().next().remove();
				$(this).parent().parent().remove();
			});
		});
		
		$("input[name='marked_for_delete']").hide();
		$("#markedtext").hide();
	});
</script>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration",
		"/" => "Modifying table",
		"modify_table" => $_GET["param_0"]
	));
?></div>

<?php if(count($_POST) > 0 || (isset($_GET["param_1"]) && $_GET["param_1"] == "changes")) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		Modified <strong>'<?php echo $_GET["param_0"]; ?>'</strong> table successfully
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php } ?>

<a href="<?php $PHPZevelop->Path->GetPage("manage/modify-table/".$_GET["param_0"]."/delete"); ?>" style="float: right; relative; top: -24px;" class="confirm btn btn-danger">Delete <?php echo $_GET["param_0"]; ?></a>

<h1>Modifying table "<?php echo $Table["real_name"]; ?>"</h1>

<?php
	$FormGen = new FormGen();
	$FormGen->AddElement(array("type" => "text", "name" => "marked_for_delete", "readonly" => "readonly", "value" => "", "style" => "background: #eeeeee; color: #333333; border: 1px solid #333333; margin-bottom: 20px;"), array("title" => "<span id='markedtext' style='color: red;'>Marked for delete</span>"));
	$FormGen->AddElement(array("type" => "text", "name" => "table_name", "value" => $Table["real_name"]), array("title" => "Table name"));
	$FormGen->AddElement(array("type" => "html", "value" => "<span id='addField' class='btn btn-info'>Add field +</span><br /><br />"));
	$FormGen->AddElement(array("type" => "textarea", "name" => "table_comment", "value" => $Table["information"]["table_comment"]), array("title" => "Table comments"));
	$FormGen->AddElement(array("type" => "submit", "value" => "Modify table"));

	echo $FormGen->Build();
?>