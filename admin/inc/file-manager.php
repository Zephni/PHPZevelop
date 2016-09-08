<?php
	if(isset($User) && !$User->LoggedIn())
		$PHPZevelop->Location("login");

	// Delete
	if(isset($_GET["param_".((string)count($_GET)-1)]) && substr($_GET["param_".((string)count($_GET)-1)], 0, 8) == "fdelete=" && strlen($_GET["param_".((string)count($_GET)-1)]) > 0)
	{
		$DeleteItem = $_GET["param_".((string)count($_GET)-1)];
		unset($_GET["param_".((string)count($_GET)-1)]);
		$CurrentDirectory = array();
		for($I = 0; $I < count($_GET); $I++){
			if(isset($_GET["param_".$I]) && strlen($_GET["param_".$I]) > 0) $CurrentDirectory[] = $_GET["param_".$I];
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
		if(isset($_GET["param_".$I]) && strlen($_GET["param_".$I]) > 0) $CurrentDirectory[] = $_GET["param_".$I];
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

		if(isset($_POST["renameto"]) && strlen($_POST["renameto"]) > 0)
		{
			$NewName = explode(".", $_POST["renameto"]);
			$Image->file_overwrite = true;
			$Image->file_new_name_body = implode(".", array_slice($NewName, 0, count($NewName) -1));
			$Image->file_new_name_ext  = implode(".", array_slice($NewName, count($NewName) -1));
		}

		if($Image->uploaded) $Image->process($FrontEndImageLocationRoot.str_replace("//", "/", "/".$DirectoryString));
		else print_r($Image);
	}
?>

<h2>File Manager
<?php
	if(isset($_FILES["image"]) && strlen($_FILES["image"]["name"]) > 0)
		echo "<input value='".$FrontEndImageLocationRoot.str_replace("//", "/", "/".$DirectoryString."/").$Image->file_new_name_body.".".$Image->file_new_name_ext."' style='padding: 5px; margin-left: 15px; margin-top: -5px;' />";
?>
</h2>

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

<?php
	if(isset($OnlyUploadForm) && $OnlyUploadForm == true)
		die();
?>

<style type="text/css">
	.item {height: 58px; box-sizing: border-box; width: 90%; display: inline-block;}
	.item a {color: #333333; width: 100%; height: 100%; display: inline-block; padding: 6px; box-sizing: border-box;}
	.item img {display: inline-block; height: 45px;}
	.item span {display: inline-block; line-height: 48px; vertical-align: top; padding-left: 15px;}
	.item:hover {background: #76B5F9;}
	.item:hover span {color: white;}
	
	.delete {height: 58px; box-sizing: border-box; width: 5%; display: inline-block; vertical-align: top; text-align: center;}
	.delete a {line-height: 58px; color: white; background: #BA1F24; padding: 1px 9px 3px 8px;}
</style>

<?php
	$Listings = scandir($RootDirectory);

	foreach($Listings as $Item)
	{
		if(($RootDirectory == $FrontEndImageLocationRoot."/" && $Item == "..") || $Item == ".")
			continue;

		$IsDirectory = (is_dir($FrontEndImageLocationRoot."/".$DirectoryString."/".$Item));
		if($IsDirectory)
		{
			$Icon = "directory-icon.png";
			$ALink = $PHPZevelop->CFG->SiteDirLocal."/file-manager/";
			$ALink .= ($Item == "..") ? substr($DirectoryString, 0, strrchr("/", $DirectoryString)) : $DirectoryString."/".$Item;
			$Target = "";
		}
		else
		{
			$ALink = $FrontEndImageLocationLocal."/".$DirectoryString."/".$Item;
			$Parts = explode(".", $Item);
			$Icon = ($Parts[count($Parts)-1] == "jpeg") ? "jpg" : $Parts[count($Parts)-1];
			$Icon = strtolower($Icon."-icon.png");
			if(!file_exists($PHPZevelop->Path->GetImageRoot("components/".$Icon, true)))
				$Icon = "file-icon.png";
			$Target = "target='_blank'";
		}

		echo "<div class='item'><a href='".$ALink."' ".$Target.">
			<img src='".$PHPZevelop->Path->GetImage("components/".$Icon, true)."' />
			<span>".$Item."</span>
			</a>
		</div>";

		if(!$IsDirectory)
			echo "<div class='delete'>
					<a href='".$PHPZevelop->Path->GetPage("file-manager/".$DirectoryString."/fdelete=".str_replace(".", "-specialfullstop-", urlencode($Item)), true)."'>x</a>
				</div>";
	}
?>

<script type="text/javascript">
	$(document).ready(function(){
		SetImagePreview(".ImageSelector", ".PreviewImage");
	});
</script>