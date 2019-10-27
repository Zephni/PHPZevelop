<?php	
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Creating table",
		"PassParams" => true
	));
	
	if(!HasPermission("general", "database") || !HasPermission("general", "create"))
		die("You do not have permission to create tables");

	if(count($_POST) > 0)
	{
		ValidateValues::Run($_POST, array());
		
		if(count(ValidateValues::$InvalidPairs) == 0){
			$SQL = "CREATE TABLE ".$_POST["table_name"]." (";

			$SQLFields = array();
			$SQLFields[] = "id MEDIUMINT NOT NULL AUTO_INCREMENT";
			if(isset($_POST["field_name"]))
			{
				for($I = 0; $I < count($_POST["field_name"]); $I++)
					$SQLFields[] = $_POST["field_name"][$I]." ".$_POST["field_type"][$I]." NOT NULL "
						.(($_POST["field_comment"][$I] != "") ? " COMMENT '".$_POST["field_comment"][$I]."'" : "")
						.(($_POST["field_default"][$I] != "") ? " DEFAULT '".$_POST["field_default"][$I]."'" : "");
			}
			$SQL .= implode(", ", $SQLFields);
			$SQL .= ", PRIMARY KEY (id)) ";

			$SQL .= " COMMENT='".addslashes($_POST["table_comment"])."'";
			$SQL .= ";";

			$DB->QuerySingle($SQL);
			//die(print_r($DB->Errors));

			$PHPZevelop->Location("manage/modify-table/".$_POST["table_name"]);
		}
	}
?>

<style type="text/css">
	#addField {margin-top: 10px;}
	#addField:hover {cursor: pointer;}
	.group-spacer {margin: 8px 0px 12px 0px;}
	.removeField {color: #DD3333; margin-left: 10px;}
	.removeField:hover {cursor: pointer;}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		FieldHTML =  "<tr><td>Field <span class='removeField'>(remove)</span></td></tr><tr><td><div class='form-group group-spacer'>";
		FieldHTML += "<input type='text' class='form-control d-inline' name='field_name[]' style='width: 24%; margin-right: 1.3%;' placeholder='field name' />";
		FieldHTML += "<select type='text' class='form-control d-inline' name='field_type[]' style='width: 24%; margin-right: 1.3%;'><option>VARCHAR(255)</option><option>MEDIUMTEXT</option></select>";
		FieldHTML += "<input type='text' class='form-control d-inline' name='field_default[]' style='width: 24%; margin-right: 1.2%;' placeholder='field default' />";
		FieldHTML += "<input type='text' class='form-control d-inline' name='field_comment[]' style='width: 24%;' placeholder='field comment' />";
		FieldHTML += "</div></td></tr>";

		$("#addField").click(function(){
			$(this).parent().before(FieldHTML);

			$(".removeField").click(function(){
				$(this).parent().parent().next().remove();
				$(this).parent().parent().remove();
			});
		});
	});
</script>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration",
		"create" => "Creating table"
	));
?></div>

<h1>Creating table</h1>

<p>Note that the "id" field will be added automatically with auto_increment.<br /><br /></p>

<?php
	$FormGen = new FormGen();
	$FormGen->AddElement(array("type" => "text", "class" => "form-control", "name" => "table_name", "placeholder" => "eg. table_name", "required"=> "required"), array("title" => "Table name"));
	$FormGen->AddElement(array("type" => "html", "value" => "<span id='addField' class='btn btn-info'>Add field +</span><br /><br />"));
	$FormGen->AddElement(array("type" => "textarea", "class" => "form-control", "name" => "table_comment"), array("title" => "Table comments"));
	$FormGen->AddElement(array("type" => "submit"));

	echo $FormGen->Build();
?>