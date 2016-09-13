<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Manage images",
		"Template"	 => "fullwidthempty",
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

<div style="width: 50%;">
<?php
	$FormGen = new FormGen();
	$PreHTML = "<table style='width: 100%;'><tr><td><img src='".$PHPZevelop->Path->GetImage("components/no-image-icon.jpg", true)."' style='width: 100px;' class='PreviewImage' /></td><td>";
	$PostHTML = "</td></tr></table>";
	$FormGen->AddElement(array("type" => "file", "name" => "image", "class" => "ImageSelector"), array("prehtml" => $PreHTML, "posthtml" => $PostHTML));
	$FormGen->AddElement(array("name" => "renameto", "placeholder" => "eg image.png", "style" => "width: 230px;"));
	$FormGen->AddElement(array("type" => "submit", "value" => "Upload", "class" => "highlight"));
	echo $FormGen->Build(array("ColNum" => 3));
?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		SetImagePreview(".ImageSelector", ".PreviewImage");
	});
</script>