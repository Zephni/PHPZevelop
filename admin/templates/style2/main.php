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

				$(window).resize(function(){
					ResizeLeftSpacer();
				});

				ResizeLeftSpacer();

				function ResizeLeftSpacer()
				{
					var left = $("#leftPanel").width()+40;
					var width = ($(window).width() - left);
					$("#content").css({"left":(left)+"px", "width":(width)+"px"});
				}
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
				plugins: "link image media code paste table",
				paste_auto_cleanup_on_paste : true,
	            paste_remove_styles: true,
	            paste_remove_styles_if_webkit: true,
	            paste_strip_class_attributes: "all",
	            allow_html_in_named_anchor: true,
	            allow_unsafe_link_target: true,
	            enable_elements:"*[*]",
				menubar: false,
				statusbar: true,
				height : 200,
				toolbar: 'pastetext | bold italic alignleft aligncenter alignright alignjustified fontsizeselect | link unlink | bullist numlist image table | code',
  				fontsize_formats: '10pt 12pt 14pt 18pt 24pt 36pt',
				automatic_uploads: false,
				content_css: "<?php echo $PHPZevelop->Path->GetCSS('tinymce.css'); ?>",

				// Image stuff
				convert_urls : false,
				image_caption: true,
				image_advtab: true
			});
		</script>

		<link rel="apple-touch-icon" sizes="57x57" href="/site/images/favicons/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/site/images/favicons/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/site/images/favicons/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/site/images/favicons/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/site/images/favicons/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/site/images/favicons/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/site/images/favicons/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/site/images/favicons/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/site/images/favicons/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/site/images/favicons/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/site/images/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/site/images/favicons/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/site/images/favicons/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
	</head>
	<body>
		
		<div id="topBar">

			<div class="item" style="padding-left: 15px;">
				<a href="<?php $PHPZevelop->Path->GetPage("") ?>">
					<span style="font-weight: bold;"><?php echo $PHPZevelop->CFG->SiteTitle; ?></span>
				</a>
			</div>

			<div style="position: absolute; right: 0px; top: 0px;">

				<div class="item">
					<a href="<?php $PHPZevelop->Path->GetPage("../") ?>">
						<img src="<?php echo $PHPZevelop->Path->GetImage("components/arrow-icon.png"); ?>">
						<span>Go to front end</span>
					</a>
				</div>

				<div class="item">
					<a href="<?php $PHPZevelop->Path->GetPage("") ?>">
						<img src="<?php echo $PHPZevelop->Path->GetImage("components/user-icon.png"); ?>">
						<span>Hello, <?php echo $Administrator->Data["username"]; ?>!</span>
					</a>
				</div>

				<div class="item" style="padding-right: 20px;">
					<a href="<?php $PHPZevelop->Path->GetPage("signout") ?>">
						<img src="<?php echo $PHPZevelop->Path->GetImage("components/logout-icon.png"); ?>">
						<span>Logout</span>
					</a>
				</div>

				<script type="text/javascript">
					$(document).ready(function(){
						function SetTimeString()
						{
							var dNow = new Date();
							var localdate= dNow.getFullYear() + '/' + (dNow.getMonth()+1) + '/' + dNow.getDate() + ' ' + dNow.getHours() + ':' + dNow.getMinutes();
							$("#currentTime").html(localdate);
						}

						SetTimeString();
						window.setInterval(function(){
							SetTimeString();
						}, 1000);
					});
				</script>
				<div class="item" style="padding-right: 25px; padding-left: 20px; background: #1E2731; border-left: 2px solid #0D1620;">
					<span id="currentTime" style="font-size: 12px;"></span>
				</div>

			</div>
			
		</div>
		
		<div id="leftPanel">
			<div class="topPadding"></div>
			<div class="padding" style="padding-top: 0px; margin-top: 20px; overflow-y: auto; max-height: 80%;">
				
				<table style="width: 100%; height: 100%;">
					<tr>
						<td><a href="<?php $PHPZevelop->Path->GetPage("file-manager") ?>">File explorer</a></td>
						<td></td>
					</tr>

					<?php
						foreach($Tables as $K => $V)
						{
							if(isset($TableOptions[$K]["Status"]) && ($TableOptions[$K]["Status"] == "disabled" || $TableOptions[$K]["Status"] == "hidden"))
								continue;

							echo '<tr>
								<td><a href="'.$PHPZevelop->Path->GetPage("select/".$K, true).'">'.$V.'</a></td>
								<td><a href="'.$PHPZevelop->Path->GetPage("add/".$K, true).'">Add</a></td>
							</tr>';
							
							if(isset($TableOptions[$K]["Navigation"]))
							{
								foreach(explode("|", $TableOptions[$K]["Navigation"]) as $Item)
								{
									$Parts = explode(",", $Item);

									$Extra = "";
									if(isset($Item[2]))
										$Extra = '<a href="'.$PHPZevelop->Path->GetPage($Parts[3], true).'" style="width: 100%;">'.$Parts[2].'</a>';

									echo '<tr>
										<td><a href="'.$PHPZevelop->Path->GetPage($Parts[1], true).'" style="padding-left: 20px; width: 100%;">- '.$Parts[0].'</a></td>
										<td>'.$Extra.'</td>
									</tr>';
								}
							}
						}
					?>
				</table>
				
			</div>
		</div>

		<div id="content">
			<div class="contentBox">
				<div class="topPadding"></div>
				<?php echo $PHPZevelop->PageContent; ?>
			</div>
		</div>

	</body>
</html>