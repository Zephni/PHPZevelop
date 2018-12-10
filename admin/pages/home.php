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

<img src="https://www.yourhomemagazine.co.uk/site/images/components/logo.png?1542717304" style="width: 33%; max-width: 500px; float: right;" />

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