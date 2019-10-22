<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Home"
	));
?>

<!-- Jumbotron -->
<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-push-8">
				<img src="<?php $PHPZevelop->Path->GetImage("PHPZevelopLogo.png"); ?>" class="img-responsive" alt="PHPZevelop Logo" style="padding: 20px; margin: auto;" />
			</div>
			<div class="col-md-8 col-md-pull-4">
				<br />
				<h1 class="hidden-sm hidden-xs"><?php echo $PHPZevelop->CFG->SiteTitle; ?></h1>
				<p>
					A PHP framework for speeding up development by 1000%... probably. PHPZevelop is ready for you to design and add pages to your new site, while taking care of all the complicated bits! And comes with a built in CMS that configures itself based on your MySQL database automatically.
				</p>
				<a href="https://github.com/Zephni/PHPZevelop" target="_blank" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">PHPZevelop features</a>
				<a href="https://github.com/Zephni/PHPZevelop" target="_blank" class="btn btn-primary btn-lg">Download on GitHub</a>
			</div>
		</div>
	</div>
</div>

<!-- Container -->
<div class="container">
	<div class="col-sm-8 col-xs-12">
		<div class="row">
			<?php
				ScanDirectory($PHPZevelop->CFG->Site."/howto", function($Item, $CurrentDir){
					global $PHPZevelop;
					if(substr($Item, 0, 1) != ".")
					{
						echo "<section id='".PrettyTitle($Item, true)."'>";
						echo "<h2>".PrettyTitle($Item)."</h2><hr />";
						ScanDirectory($PHPZevelop->CFG->Site."/howto/".$Item, function($Item2, $CurrentDir){
							global $PHPZevelop;
							echo "<section id='".PrettyTitle($Item2, true)."'>";
							echo "<h3>".PrettyTitle($Item2)."</h3>";
							$HTML = file_get_contents($PHPZevelop->CFG->Site."/howto/".$CurrentDir."/".$Item2);
							echo $HTML;
							echo "</section>";
						});
						echo "</section>";
					}
				});
			?>
		</div>
	</div>

	<div class="col-xs-1 hidden-xs"></div>
	
	<style type="text/css">
		#sideBar{
			position: -webkit-sticky;
			position: sticky;
			top: 90px;
		}
	</style>
	<div id="sideBar" class="col-xs-3 hidden-xs">
		<div class="row">
			<div id="sidebarNav">
				<form class="form">
					<div class="form-group">
					<input type="text" class="form-control" placeholder="Search">
					</div>
				</form>

				<nav>
				<ul class="nav nav-stacked">
					<li><a href="#Home">Home</a></li>
					<?php
						ScanDirectory($PHPZevelop->CFG->Site."/howto", function($Item){
							global $PHPZevelop;
							if(substr($Item, 0, 1) != ".")
							{
								echo "<li class='nav-item'>";
								echo "<a class='sideNavItem' href='#".PrettyTitle($Item, true)."'>".PrettyTitle($Item)."</a>";
								
								ScanDirectory($PHPZevelop->CFG->Site."/howto/".$Item, function($Item2){
									echo "<li class='nav-item' style='padding-left: 15px;'><a class='sideNavItem' href='#".PrettyTitle($Item2, true)."'>".PrettyTitle($Item2)."</a></li>";
								});
	
								echo "</li>";
							}
						});

						function ScanDirectory($Path, $Callback)
						{
							foreach(scandir($Path) as $Item)
							{
								if(substr($Item, 0, 1) != ".")
								{
									$CurrentDir = explode("/", $Path);
									$CurrentDir = $CurrentDir[count($CurrentDir)-1];
									$Callback($Item, $CurrentDir);
								}
							}
						}

						function PrettyTitle($Title, $RemoveSpaces = false)
						{
							$Title = str_replace("-", " ", $Title);
							$Title = str_replace(".php", "", $Title);
							$Title = ucfirst($Title);
							if($RemoveSpaces){
								$Title = ucwords($Title);
								$Title = str_replace(" ", "", $Title);
							}
							return $Title;
						}
					?>
				</ul>
				</nav>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">PHPZevelop Features</h4>
      </div>
	  <style type="text/css">
	  	.modal-body li {font-size: 1.2em; padding: 10px 0;}
	  </style>
      <div class="modal-body">
        <ul>
			<li>Extremely lightweight</li>
			<li>Works with PHP 7</li>
			<li>Comes with prebuilt CMS</li>
			<li>Build and manage database from within</li>
			<li>Logical page and file structure</li>
			<li>Automatically makes URLs pretty (ie. website.com/about rather than website.com/about.php)</li>
			<li>Multiple load styles based on preference</li>
			<li>Default template comes with Bootstrap</li>
			<li>Multiple sites on a single PHPZevelop installation</li>
		</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>