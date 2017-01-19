<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Manage images",
		"Template" => "none",
		"PassParams" => true
	));

	// Delete
	if(isset($_GET["param_".((string)count($_GET)-1)]) && substr($_GET["param_".((string)count($_GET)-1)], 0, 8) == "fdelete=" && strlen($_GET["param_".((string)count($_GET)-1)]) > 0)
	{
		$DeleteItem = $_GET["param_".((string)count($_GET)-1)];
		unset($_GET["param_".((string)count($_GET)-1)]);
		$CurrentDirectory = array();
		
		for($I = 0; $I < count($_GET); $I++){
			if(ArrGet($_GET, "param_".$I) != "") $CurrentDirectory[] = $_GET["param_".$I];
			else break;
		}
		
		$DeleteItem = explode("=", $DeleteItem);
		$DeleteItemRoot = $FrontEndImageLocationRoot."/".implode("/", $CurrentDirectory)."/".urldecode(str_replace("-specialfullstop-", ".", $DeleteItem[1]));
		unlink($DeleteItemRoot);
		$PHPZevelop->Location("file-manager/".implode("/", $CurrentDirectory));
	}

	// Get current directory info
	$CurrentDirectory = array();
	
	for($I = 0; $I < count($_GET); $I++)
	{
		if(ArrGet($_GET, "param_".$I) != "") $CurrentDirectory[] = $_GET["param_".$I];
		else break;
	}

	$DirectoryString = implode("/", $CurrentDirectory);
	$RootDirectory = $FrontEndImageLocationRoot."/".$DirectoryString;

	if(!file_exists($RootDirectory))
		$PHPZevelop->Location("file-manager");

	// Upload
	if(isset($_FILES["image"]) && strlen($_FILES["image"]["name"]) > 0)
	{
		$Image = new upload($_FILES["image"]);
		$Image->mime_check = true;
		$Image->allowed = array('application/pdf', 'image/*');
		$Image->file_overwrite = true;

		if(isset($_POST["renameto"]) && strlen($_POST["renameto"]) > 0)
		{
			$NewName = explode(".", $_POST["renameto"]);
			$Image->file_new_name_body = implode(".", array_slice($NewName, 0, count($NewName) -1));
			$Image->file_new_name_ext  = implode(".", array_slice($NewName, count($NewName) -1));
		}

		if($Image->uploaded)
		{
			$Image->process($FrontEndImageLocationRoot.str_replace("//", "/", "/".$DirectoryString));

			if($Image->processed)
				$NewName = array($Image->file_dst_name_body, $Image->file_dst_name_ext);
			else
				$Error = "File upload error (".$Image->file_src_mime.")";
		}
		else
		{
			$Error = $Image->error;
		}
	}
?>

<?php
	if(!isset($Error) && ArrGet($_FILES, "image", "name") != "")
		echo "<input value='".str_replace("//", "/", $FrontEndImageLocationLocal."/".$DirectoryString."/".implode(".", $NewName))."' style='padding: 5px; width: 95%; margin: auto;' />";
	else if(isset($Error))
		echo "<h3 style='color: #BA1F24;'>".$Error."</h3>";
?>

<html>
<head>
	<style type="text/css">
		table.FormGen {width: 100%;}
		table.FormGen tr td {padding: 5px;}
		table.FormGen tr td:first-of-type {padding-left: 0px;}
		table.FormGen tr td:last-of-type {padding-right: 0px;}
		table.FormGen input {width: 100%; padding: 6px; box-sizing: border-box; font-size: 16px;}
		table.FormGen textarea {width: 100%; padding: 6px; box-sizing: border-box; font-size: 16px; height: 100px;}
		table.FormGen select {width: 100%; padding: 6px; box-sizing: border-box; font-size: 16px;}
		table.FormGen img {width: 100%;}
		table.FormGen input[type="submit"].highlight {background: #009ACD; color: white; border: none; width: 100%; padding: 7px 25px;}
		table.FormGen input[type="submit"]:hover {cursor: pointer;}
	</style>
</head>
<body style="margin: 0px; overflow: hidden; background: none;">

		<table style="position: relative; left: -10px;">
			<tr>
				<td>
					<?php
						$FormGen = new FormGen();
						//$PreHTML = "<table style='width: 100%;'><tr><td><img src='".$PHPZevelop->Path->GetImage("components/no-image-icon.jpg", true)."' style='width: 100px;' class='PreviewImage' /></td><td>";
						//$PostHTML = "</td></tr></table>";
						$FormGen->AddElement(array("type" => "file", "name" => "image"));
						$FormGen->AddElement(array("name" => "renameto", "placeholder" => "eg image.png", "style" => "width: 230px;"));
						$FormGen->AddElement(array("type" => "submit", "value" => "Upload", "class" => "highlight"));
						echo $FormGen->Build(array("ColNum" => 3));
					?>
				</td>
			</tr>
		</table>

</body>
</html>