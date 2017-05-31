<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Home",
		"PassParams" => false
	));
?>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration"
	));
?></div>

<h1><?php echo $PHPZevelop->CFG->SiteTitle; ?></h1>
<br />

<h2>Account</h2>
<p>Theme: <a href="<?php $PHPZevelop->Path->GetPage("manage-account"); ?>" style="width: 50px; border-radius: 10px; height: 15px; position: relative; top: 3px; background: <?php echo $Administrator->Data["theme_color"]; ?>; display: inline-block; margin-left: 10px;"></a></p>
<p>Username: <?php echo $Administrator->Data["username"]; ?></p>
<p>Email Address: <?php echo $Administrator->Data["email"]; ?></p>
<p><a href="<?php $PHPZevelop->Path->GetPage("manage-account"); ?>">Manage my account</a></p>
<p><a href="<?php $PHPZevelop->Path->GetPage("signout"); ?>">Sign out</a></p>

<br />
<h2>Filesystem</h2>
<p><a href="<?php $FrontEndLocationLocal; ?>" target="_blank">Front end</a> (opens in new window)</p>
<p><a href="<?php $PHPZevelop->Path->GetPage("file-manager"); ?>">Open file manager</a> (Location: <?php echo $FrontEndImageLocationLocal; ?>)</p>
<p>Root path: <?php echo $FrontEndLocationRoot; ?></p>

<br />
<h2>Tables</h2>
<table style="width: 90%; max-width: 400px;">
<?php
	foreach(DBTool::GetAllTables() as $Key => $Item)
	{
		$TableConfig = DBTool::TableConfigStringArray($Item["information"]["table_comment"]);
		if(isset($TableConfig["Status"]) && $TableConfig["Status"][0] == "hidden") continue;

		echo "<tr>";
		echo "<td><p><a href='".$PHPZevelop->Path->GetPage("select/".$Key, true)."'>".$Item["name"]."</p></td>";
		echo '<td style="width: 15%; text-align: right;"><p><a href="'.$PHPZevelop->Path->GetPage("add/".$Key, true).'" style="display: inline-block;">Add</a></p></td>';
		echo "</tr>";
	}
?>
</table>