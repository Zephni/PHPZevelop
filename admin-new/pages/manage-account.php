<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Manage account",
		"PassParams" => true
	));

	$Table = DBTool::GetTable(Administrator::$DBTABLEDEFAULT);
	$OrigionalData = $DB->Select("*", $Table["real_name"], array(array("id", "=", $Administrator->Data["id"])), true);

	if(count($_POST) > 0)
	{
		if($_POST["password"] == "")
			unset($_POST["password"]);

		ValidateValues::Run($_POST, array(
			"password" => function($Value, &$String){
				if(strlen($Value) < 6){
					$String = "Password must be at least 6 characters";
					return false;
				}
			}
		));

		if(count(ValidateValues::$InvalidPairs) == 0)
		{
			foreach(ValidateValues::$ValidPairs as $K => $V)
				$Administrator->Data[$K] = $V;

			if(isset(ValidateValues::$ValidPairs["password"]))
				$Administrator->SetPassword(ValidateValues::$ValidPairs["password"]);

			$Administrator->Update();
			$Administrator->Login();
		}

		
		$LogString = array(); foreach(ValidateValues::$ValidPairs as $K => $V) if($OrigionalData[$K] != $V) $LogString[] = $K;
	}
	
	$Data = $DB->Select("*", $Table["real_name"], array(array("id", "=", $Administrator->Data["id"])), true);
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".nav-item").removeClass("active");
		$(".nav-item").find("a[href='/<?php echo $PHPZevelop->CFG->Site; ?>/manage-account']").addClass("active");
	});
</script>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration",
		"manage" => "managing account"
	));
?></div>

<h1 class="display-4">Managing Account</h1>
<hr />
<br />

<?php
	$MSG = array();
	foreach(ValidateValues::$ValidPairs as $K => $V)
		if($OrigionalData[$K] != $V)
			$MSG[] = "<span style='color: green;'>Updated ".$K."</span>";
	
	echo "<p>".implode(", ", array_merge(ValidateValues::$ErrorMessages, $MSG))."</p>";
?>

<?php
	$Data["password"] = "";
	
	echo FormGen::DBFormBuild(DBTool::GetTable($Table["real_name"]), array(
		"Data" => $Data,
		"HideFields" => array("id", "salt", "active", "last_active", "login_attempts", "permissions"),
		"SubmitText" => "Update"
	));
?>

<br /><br />

<table>
	<tr>
		<td><p>Hashed password:</p></td>
		<td> </td>
		<td><p><?php echo $Administrator->Data["password"]; ?></p></td>
	</tr>
	<tr>
		<td><p>Salt:</p></td>
		<td> </td>
		<td><p><?php echo $Administrator->Data["salt"]; ?></p></td>
	</tr>
</table>