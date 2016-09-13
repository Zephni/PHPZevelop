<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Edit"
	));
?>

<h2>Generate a password for the user system</h2>
<p>This will produce a hashed password and salt based on the origional visible password.<br /><br /></p>

<?php
	$FormGen = new FormGen();
	$FormGen->AddElement(array("type" => "text", "name" => "password", "placeholder" => "Password"), array("title" => "Password"));
	$FormGen->AddElement(array("type" => "submit", "value" => "Submit"));
	echo $FormGen->Build(array("ColNum" => 3, "data" => $_POST));
?>

<?php
	if(count($_POST) > 0)
	{
		$TempUser = new User();
		$TempUser->SetPassword($_POST["password"]);

		echo "<p>Origional password: <b>".$_POST["password"]."</b></p>";
		echo "<p>Hashed password: <b>".$TempUser->Data["password"]."</b></p>";
		echo "<p>Generated salt: <b>".$TempUser->Data["salt"]."</b></p>";

		unset($TempUser);
	}
	
?>