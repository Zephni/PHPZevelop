<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Edit",
		"PassParams" => true
	));

	// Go to login if not logged in
	if(isset($User) && !$User->LoggedIn())
		$PHPZevelop->Location("login");

	// Build $Options and check if disabled
	$Options = (isset($TableOptions[$_GET["param_0"]]["Options"])) ? explode(",", $TableOptions[$_GET["param_0"]]["Options"]) : array();
	if(ArrGet($TableOptions, $_GET["param_0"], "Status") == "disabled" || (count($Options) > 0 && !in_array("edit", $Options)))
		die("Disabled");

	// Build $ColumnNames and $ColumnCommands
	GetColumnsCommands($_GET["param_0"], $Columns, $ColumnCommands);
	$ColumnNames = array();
	foreach($Columns as $Item)
		$ColumnNames[] = $Item["column_name"];

	// On post
	if(isset($_POST) && count($_POST) > 0)
	{
		foreach($ColumnNames as $Item)
		{
			if(isset($ColumnCommands[$Item]["type"][0]) && $ColumnCommands[$Item]["type"][0] == "timestamp")
				$_POST[$Item] = strtotime($_POST[$Item]);
		}

		InsertQueryFromArray($_GET["param_0"], $_POST);
		$LastInsertId = $DB->LastInsertId;
		AppendLog("Added item #".$LastInsertId." to ".$_GET["param_0"]);

		//IMAGE
		foreach($ColumnNames as $Column)
		{
			if(ArrGet($ColumnCommands, $Column, "type", 0) != "image" || ArrGet($ColumnCommands, $Column, "type", 0) != "file") continue;
			
			if(ArrGet($_FILES, $Column, "name") != "")
			{
				$Image = new upload($_FILES[$Column]);

				if($Image->uploaded)
				{
					foreach(array_keys($ColumnCommands[$Column]) as $Item)
					{
						if(substr($Item, 0, 3) == "io_")
						{
							$ImgOptionKey = substr($Item, 3);
							$Val = $ColumnCommands[$Column]["io_".$ImgOptionKey][0];
							$Val = str_replace("[id]", $LastInsertId, $Val);
							$Val = str_replace("[timestamp]", time(), $Val);
							$Image->$ImgOptionKey = $Val;
						}
					}

					$Image->process($FrontEndImageLocationRoot."/".(string)$ColumnCommands[$Column]["filelocation"][0]);

					if($Image->processed)
					{
						$Array = array();
						$Array[$Column] = $Image->file_dst_name_body.".".$Image->file_dst_name_ext;
						UpdateQueryFromArray($_GET["param_0"], $Array, "WHERE id=:id", $LastInsertId);
					}
					else
						die($Image->error."<br /><br />".$FrontEndImageLocationRoot."/".(string)$ColumnCommands[$Column]["filelocation"][0]);
				}
			}
		}
		//IMAGE

		$PHPZevelop->Location("edit/".$_GET["param_0"]."/".$LastInsertId);
	}
?>

<h2>Adding to table <?php echo ucfirst(str_replace("_", "", $_GET["param_0"])); ?></h2>
<br />

<?php
	$FormGen = new FormGen();

	foreach($Columns as $Item)
	{
		if(strtolower($Item["column_name"]) == "id") continue;

		$Title = ucfirst(ltrim(str_replace("_", " ", $Item["column_name"]), " "));
		$Type = (isset($ColumnCommands[$Item["column_name"]]["type"][0])) ? $ColumnCommands[$Item["column_name"]]["type"][0] : "text";
		$Class = (isset($ColumnCommands[$Item["column_name"]]["class"][0])) ? $ColumnCommands[$Item["column_name"]]["class"][0] : "";

		if($Type == "select")
		{
			$Options = array("0" => " - none -");
			if(isset($ColumnCommands[$Item["column_name"]]["join"]))
			{
				foreach($DB->Query("SELECT id,".$ColumnCommands[$Item["column_name"]]["join"][1]." FROM ".$ColumnCommands[$Item["column_name"]]["join"][0]) as $Option)
					$Options[$Option["id"]] = $Option[$ColumnCommands[$Item["column_name"]]["join"][1]];
			}
			else if(isset($ColumnCommands[$Item["column_name"]]["values"]))
			{
				foreach($ColumnCommands[$Item["column_name"]]["values"] as $Val)
				{
					$Val = explode("|", $Val);
					$Options[$Val[0]] = $Val[1];
				}
			}

			$FormGen->AddElement(array("type" => $Type, "name" => $Item["column_name"], "value" => $Item["column_default"], "class" => $Class), array("title" => $Title, "data" => $Options));
		}
		elseif($Type == "timestamp")
		{
			$FormGen->AddElement(array("type" => "text", "name" => $Item["column_name"], "value" => date("Y/m/d G:i"), "class" => "datetimepicker ".$Class), array("title" => $Title));
		}
		elseif($Item["column_name"] == "image")
		{
			$PreHTML = "<table style='width: 100%;'><tr><td style='width: 12%;'><img src='".$PHPZevelop->Path->GetImage("components/no-image-icon.jpg", true)."' class='PreviewImage' /></td><td>";
			$PostHTML = "</td></tr></table>";
			$FormGen->AddElement(array("type" => "file", "name" => $Item["column_name"], "class" => "ImageSelector ".$Class), array("title" => $Title, "prehtml" => $PreHTML, "posthtml" => $PostHTML));
		}
		else
		{
			$FormGen->AddElement(array("type" => $Type, "name" => $Item["column_name"], "value" => $Item["column_default"], "class" => $Class), array("title" => $Title));
		}
	}
	
	$FormGen->AddElement(array("type" => "submit"));

	echo $FormGen->Build();
?>

<script type="text/javascript">
	$(document).ready(function(){
		SetImagePreview(".ImageSelector", ".PreviewImage");
	});
</script>

<?php
	// File manager
	if(ArrGet($TableOptions, $_GET["param_0"], "FileManager") == "true")
	{
		if(!isset($TableOptions[$_GET["param_0"]]["FileManagerDefaultLocation"]))
			$TableOptions[$_GET["param_0"]]["FileManagerDefaultLocation"] = "";
	?>
	<br /><br />
	<iframe src="<?php $PHPZevelop->Path->GetPage("file-manager-full/".$TableOptions[$_GET["param_0"]]["FileManagerDefaultLocation"]); ?>"
		style="width: 99.5%; height: 185px; border: 1px solid #CCCCCC; margin: auto; background: white;"></iframe>
	<?php
	}
?>