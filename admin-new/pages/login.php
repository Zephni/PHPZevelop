<!doctype html>
<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
        "PageTitle"  => "Login",
        "Template"   => "none"
    ));
    
    $MSG = "";

    if(count($_POST) > 0)
	{
        $MSG = "Success";
		$LoginMsg = Administrator::AttemptLogin($Administrator, $_POST["username"], $_POST["password"]);
		if($LoginMsg !== true)
			$MSG = $LoginMsg;
	}

	if($Administrator->LoggedIn())
	{
		$PHPZevelop->Location("home");
	}
?>

<html lang="en">
	<head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title><?php echo $PHPZevelop->CFG->SiteTitle; ?> - <?php echo $PHPZevelop->CFG->PageTitle; ?></title>

		<!-- JAVASCRIPT -->
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script type="text/javascript" src="<?php $PHPZevelop->Path->GetScript("main.js?".time()); ?>"></script>

		<!-- Bootstrap 4 -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- Custom CSS -->
        <style type="text/css">
            body {background: #f5f5f5; display: flex; -webkit-box-align: center; align-items: center; -webkit-box-pack: center; justify-content: center;}
            input {padding: 10px !important; }
            .form-signin input:not([type='checkbox']) {width: 100%; max-width: 370px; margin: auto; padding: 15px;}
            .checkbox {margin-top: 10px;}
        </style>
	</head>
	<body class="text-center">
		
        <div class="container-fluid" style="height: 100vh;">
            <table style="width: 100%; height: 100%;">
                <tr>
                    <td>
                        <form class="form-signin align-middle" style="margin-top: -100px;" action="" method="post">
                            <img class="mb-4" src="<?php $PHPZevelop->Path->GetImage("Logo.png"); ?>" alt="Logo" height="150">
                            <?php if(strlen($MSG) > 0){ ?><div style="margin: auto; width: 450px;"><div class="alert alert-danger alert-dismissible" role="alert">
                                <?php echo strip_tags($MSG); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="top: -1px; position: relative;">&times;</span>
                                </button>
                            </div></div><?php } ?>
                            <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
                            <label for="username" class="sr-only">Username / Email address</label>
                            <input type="text" name="username" class="form-control" placeholder="Username / Email" required="required" autofocus="autofocus">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required="required" style="margin-top: 10px;">
                            <div class="checkbox mb-3">
                                <label>
                                    <input type="checkbox" value="remember-me"> Remember me
                                </label>
                            </div>
                            <input class="btn btn-lg btn-primary btn-block" type="submit" value="Sign in">
                            <p class="mt-5 mb-3 text-muted">PHPZevelop <?php echo date("Y"); ?></p>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
		
		<!-- JQuery & Bootstrap 4 -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	</body>
</html>