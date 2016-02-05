<?php
	// Page setup
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"		=> "Login",
		"FileOrder"		=> array("[page]")
	));

	// Login attempts
	if(!isset($_SESSION["login_attempts"]))
		$_SESSION["login_attempts"] = 0;

	if(isset($_POST["username"])){
		$_SESSION["login_attempts"]++;

		if(isset($_SESSION["login_attempts"]) && $_SESSION["login_attempts"] < 3)
		{
			foreach($PHPZevelop->CFG->Accounts as $Account)
			{
				if($_POST["username"] == $Account["User"])
				{
					// Correct user/pass
					if($_POST["password"] == $Account["Pass"])
					{
						$_SESSION["loggedin"] = true;
						$_SESSION["username"] = $_POST["username"];
						header("Location: ".$PHPZevelop->Path->GetPage("", true));
					// Invalid pass
					}else{
						$msg = "<h4 style='color: red;'>Invalid username/password combination</h4>";
					}
				// Invalid user
				}else{
					$msg = "<h4 style='color: red;'>Invalid username/password combination</h4>";
				}
			}
		}
		else
		{
			$msg = "<h4 style='color: red;'>Too many login attempts. Please contact the administrator</h4>";
		}
	}

	if(isset($tries) && $tries >= $maxTries)
		$msg = "<h4 style='color: red;'>You have tried to log in too many times with an invalid username/password combination</h4>";
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $PHPZevelop->CFG->SiteTitle; ?> login (<?php echo $_SESSION["login_attempts"]; ?>)</title>
		<style type="text/css">
			body, html {margin: 0px; padding: 0px; width: 100%; height: 100%;}
			body {background: #EEEEEE; color: #444444; font-family: "Arial", Gadget, sans-serif;}
			p, h1, h2, h3, h4, h5 {margin: 0px 0px 20px 0px; padding: 0px;}
			h1 {border-bottom: 1px solid #CCCCCC; padding-bottom: 5px; color: #00688B;}
			h2 {}
			a {color: #00688B;}

			#body {position: relative; width: 100%; height: 100%;}
			#box {padding: 15px; margin: -50px auto 0px auto; position: relative; top: 25%; border: 1px solid #CCCCCC; background: #FFFFFF; box-shadow: 2px 2px 2px 2px #CCCCCC; border-radius: 5px;}

			form.general {margin: auto; width: 550px;}
			form.general table {width: 100%;}
			form.general input {width: 400px; padding: 5px; border: 1px solid #CCCCCC; border-radius: 5px;}
			form.general input[type="submit"] {width: 412px; background: #00688B; color: white;}
			form.general input[type="submit"]:hover {cursor: pointer;}
		</style>
	</head>
	<body>
		<div id="body">
			<div id="box" style="width: 800px;">
				<h1><?php echo $PHPZevelop->CFG->SiteTitle; ?> - Administration</h1>
				<?php if(isset($msg)) echo $msg; ?>
				<h2>Login to your account</h2>
				<form action="<?php $PHPZevelop->Path->GetPage("login"); ?>" method="post" class="general">
					<table>
						<tr>
							<td colspan="2">
								<p style="text-align: justify;">Please log in with your account credentials, if you are having trouble logging in to your account
								contact <a href="mailto:craig.dennis@burdamagazines.co.uk">craig.dennis@burdamagazines.co.uk</a>.</p>
							</td>
						</tr>
						<tr>
							<td style="width: 120px;">Username</td>
							<td><input type="text" name="username" <?php if(!isset($_POST["username"])) echo 'autofocus="1"'; ?> required="required" value="<?php echo (isset($_POST["username"])) ? $_POST["username"] : ""; ?>" /></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input type="password" name="password" <?php if(isset($_POST["username"])) echo 'autofocus="1"'; ?> required="required" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" value="Log in" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</body>
</html>