<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Login",
		"PassParams" => false,
		"Template"	 => "login"
	));

	$MSG = "Welcome to ".$PHPZevelop->CFG->SiteTitle.", please<br />use your login below.";

	if((count($_POST) > 0) && Administrator::AttemptLogin($Administrator, $_POST["username"], $_POST["password"]) !== true)
		$MSG = "Failed login with username '<b>".$_POST["username"]."</b>'";

	if($Administrator->LoggedIn())
	{
		ChangeLog("Logged in");
		$PHPZevelop->Location("home");
	}
?>

<div id="MiddleContainer">
	<h1>Hello!</h1>
	
	<p><?php echo $MSG; ?></p>

	<br />

	<?php
		$Form = new FormGen();
		$Form->DefaultBuildOptions["FormAttributes"]["action"] = "login";

		$UserField = array("name" => "username", "type" => "text", "placeholder" => "Username");
		$PassField = array("name" => "password", "type" => "password", "placeholder" => "Password");

		if(CookieHelper::Get("keep_admin_username") == null) $UserField["autofocus"] = "autofocus";
		else $PassField["autofocus"] = "autofocus";

		$Form->AddElement($UserField);
		$Form->AddElement($PassField);
		$Form->AddElement(array("type" => "submit", "value" => "Login"));

		echo $Form->Build(array("ColNum" => "1", "data" => array_merge(array("username" => CookieHelper::Get("keep_admin_username")), $_POST)));
	?>
	
	<p><a href="#" onclick="alert('Then ask Craig...');">Forgot password</a></p>
</div>