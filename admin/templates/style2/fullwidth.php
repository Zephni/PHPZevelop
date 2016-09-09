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
		
		<div id="topBar">

			<div class="item">
				<a href="<?php $PHPZevelop->Path->GetPage("") ?>">
					<span style="font-weight: bold;"><?php echo $PHPZevelop->CFG->SiteTitle; ?></span>
				</a>
			</div>

		</div>

		<div id="content">
			<div class="contentBox">
				<div class="topPadding"></div>
				<div class="padding">
					<br /><br /><br />
					<?php echo $PHPZevelop->PageContent; ?>
				</div>
			</div>
		</div>

	</body>
</html>