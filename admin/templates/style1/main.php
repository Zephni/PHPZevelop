<!DOCTYPE html>

<html>
	<head>
		<!-- META DATA -->
		<title><?php
			echo $PHPZevelop->CFG->SiteTitle.($PHPZevelop->CFG->PageTitle != "" ? " - ".$PHPZevelop->CFG->PageTitle : "");
		?></title>
		<meta charset="UTF-8">
		<meta name="title" content="<?php echo $PHPZevelop->CFG->MetaTitle; ?>">
		<meta name="description" content="<?php echo $PHPZevelop->CFG->MetaDescription; ?>">
		<meta name="keywords" content="<?php echo $PHPZevelop->CFG->MetaKeywords; ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="<?php echo $PHPZevelop->Path->GetCSS($DefaultStyle.".css"); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $PHPZevelop->Path->GetCSS("formgen.css"); ?>">

		<!-- JAVASCRIPT -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="<?php echo $PHPZevelop->Path->GetScript("main.js"); ?>"></script>

		<script type="text/javascript">
			$(document).ready(function(){
				$("#content").css({
					"min-height":$("#nav").height()+20
				});
			});
		</script>

		<!-- JQuery datetimepicker -->
		<link rel="stylesheet" href="<?php $PHPZevelop->Path->GetScript("datetimepicker-master/jquery.datetimepicker.css"); ?>" />
		<script src="<?php $PHPZevelop->Path->GetScript("datetimepicker-master/jquery.datetimepicker.js"); ?>"></script>
		<script>
			$(document).ready(function(){
				$('.datetimepicker').datetimepicker();
			});
		</script>

		<!-- TinyMCE -->
		<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
		<script>
			tinymce.init({
				selector:'textarea.wysiwyg',
				plugins: "link image media code paste",
				paste_auto_cleanup_on_paste : true,
	            paste_remove_styles: true,
	            paste_remove_styles_if_webkit: true,
	            paste_strip_class_attributes: "all",
	            image_dimensions: false,
				menubar: false,
				statusbar: true,
				height : 200,
				toolbar: 'pastetext bold italic alignleft aligncenter alignright | link unlink | bullist numlist image fontsizeselect | code',
  				fontsize_formats: '10pt 12pt 14pt 18pt 24pt 36pt',
				automatic_uploads: false,
				content_css: "<?php echo $PHPZevelop->Path->GetCSS('tinymce.css'); ?>"
			});
		</script>
	</head>
	<body>
		
		<div class="outerContainer">
			<div class="innerContainer">
				<div id="header">
					<div class="horizontalPadding">
						<a href="<?php $PHPZevelop->Path->GetPage(""); ?>" style="text-decoration: none !important;">
							<h1 style='font-size: 38px !important;'><?php echo $PHPZevelop->CFG->SiteTitle; ?></h1>
						</a>
						<hr />
					</div>
				</div>

				<div id="body">
					
					<div id="nav" class="nonMobileOnly">
						<h2>Navigation</h2>

						<div>
							<a href="<?php echo $PHPZevelop->Path->GetPage("file-manager", true) ?>" style="width: 100%;">File manager</a>
						</div>
						
						<?php
							foreach($Tables as $K => $V)
							{
								if(isset($TableOptions[$K]["Status"]) && ($TableOptions[$K]["Status"] == "disabled" || $TableOptions[$K]["Status"] == "hidden"))
									continue;

								echo '<div>
									<a href="'.$PHPZevelop->Path->GetPage("select/".$K, true).'">'.$V.'</a>
									<span>|</span>
									<a href="'.$PHPZevelop->Path->GetPage("add/".$K, true).'">Add</a>
								</div>';								

								if(isset($TableOptions[$K]["Navigation"]))
								{
									foreach(explode("|", $TableOptions[$K]["Navigation"]) as $Item)
									{
										$Parts = explode(",", $Item);
										echo '<div>
											<a href="'.$PHPZevelop->Path->GetPage($Parts[1], true).'" style="padding-left: 20px; width: 100%;">- '.$Parts[0].'</a>
										</div>';
									}
								}
							}
						?>
					</div>

					<div id="content">
						<?php echo $PHPZevelop->PageContent; ?>
					</div>
					
				</div>

				<div id="footer">
					<div class="horizontalPadding">
						<hr />
						<p style="font-size: 12px; margin-bottom: 0px;">&copy;  PHPZevelop <?php echo date("Y"); ?> - <?php $Link->Out("https://twitter.com/_Zephni", "@_Zephni"); ?> (Craig Dennis)</p>
					</div>
				</div>
			</div>
		</div>

	</body>
</html>