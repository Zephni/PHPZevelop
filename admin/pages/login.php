<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Login",
		"Template"	 => "style2/fullwidth"
	));

	if(count($_POST) > 0)
	{
		$MSG = User::AttemptLogin($User, $_POST["username"], $_POST["password"]);
		
		if($MSG === true)
			AppendLog("Successful login");
		else
			AppendLog("Failed login with username '".$_POST["username"]."'");
	}

	if(isset($_GET["param_0"]))
	{
		$Split = explode("-", $_GET["param_0"]);

		if($Split[0] == "activated")
		{
			$ActivatedUser = User::Get(array(array("id", "=", $Split[1])));
			$_POST["username"] = $ActivatedUser->Data["username"];
		} 
	}
	
	if($User->LoggedIn())
		$PHPZevelop->Location("home");
?>

<style type="text/css">
	#loginForm {width: 50%; margin: auto; background: #EEEEEE; border: 1px solid #009ACD; box-sizing: border-box; padding-bottom: 15px;}
	#loginForm h2 {margin: 0px; padding: 15px;}
	#loginForm h3 {margin: 0px; padding: 9px 13px;}
	#loginForm table.FormGen {width: 95%; margin: auto;}

	@media screen and (max-width: 900px){
		#loginForm {width: 100%;}
	}
</style>

<br />
<div id="loginForm">
	<h2>Login</h2>

	<?php if(isset($MSG)) echo $MSG; ?>

	<?php
		$FormGen = new FormGen();
		$FormGen->AddElement(array("type" => "text", "name" => "username", "autofocus" => "autofocus", "required" => "required"), array("title" => "Username"));
		$FormGen->AddElement(array("type" => "password", "name" => "password", "required" => "required"), array("title" => "Password"));
		$FormGen->AddElement(array("type" => "submit", "value" => "Login"));
		unset($_POST["password"]);
		echo $FormGen->Build(array("data" => $_POST));
	?>
</div>