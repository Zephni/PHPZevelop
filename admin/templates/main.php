<html>
	<head>
		<title><?php echo $PHPZevelop->CFG->SiteTitle; ?> - <?php echo $PHPZevelop->CFG->PageTitle; ?></title>

		<!-- JAVASCRIPT -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type="text/javascript" src="<?php $PHPZevelop->Path->GetScript("main.js"); ?>"></script>

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
		
		<link rel="stylesheet" type="text/css" href="<?php $PHPZevelop->Path->GetCSS("style.php?c1=".urlencode($Administrator->Data["theme_color"])); ?>">
	</head>
	<body>

		<script type="text/javascript">
			$(document).ready(function(){
				function ClosestMatch(url, arr){
					var PointsArr = [];

					arr.each(function(key){
						PointsArr[key] = 0;
						for(var i = 0; i < url.length; i++){
							if($(this).attr("href")[i] == url[i]) PointsArr[key]++;
							else break;
						}
					});

					return arr.eq([PointsArr.indexOf(Math.max.apply(window, PointsArr))]);
				}

				ClosestMatch("<?php echo $PHPZevelop->CFG->SiteDirLocal.$PHPZevelop->CFG->PagePath; ?>", $("#LeftCol a")).addClass("selected");
			});
		</script>

		<div id="NavTop">
			<div class="item" style="padding-left: 15px;">
				<a href="<?php $PHPZevelop->Path->GetPage(""); ?>">
					<span style="font-weight: normal;"><?php echo $PHPZevelop->CFG->SiteTitle; ?></span>
				</a>
			</div>

			<div style="position: absolute; right: 0px; top: 0px;">

				<div class="item">
					<a href="<?php $PHPZevelop->Path->GetPage("../") ?>">
						<img src="<?php echo $PHPZevelop->Path->GetImage("components/arrow-icon.png"); ?>"><span>Go to front end</span>
					</a>
				</div>

				<div class="item">
					<a href="<?php $PHPZevelop->Path->GetPage("manage-account") ?>">
						<img src="<?php echo $PHPZevelop->Path->GetImage("components/user-icon.png"); ?>"><span>Hello, <?php echo $Administrator->Data["username"]; ?>!</span>
					</a>
				</div>

				<div class="item" style="padding-right: 20px;">
					<a href="<?php $PHPZevelop->Path->GetPage("signout") ?>">
						<img src="<?php echo $PHPZevelop->Path->GetImage("components/logout-icon.png"); ?>"><span>Logout</span>
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

				<div class="item" style="padding-right: 25px; padding-left: 20px; background: #333333;">
					<span id="currentTime" style="font-size: 12px;"></span>
				</div>
			</div>
			
		</div>

		<div id="LeftCol">
			<span>Navigation</span>
			<a href="<?php $PHPZevelop->Path->GetPage("home"); ?>">Home</a>
			<a href="<?php $PHPZevelop->Path->GetPage("file-manager"); ?>">File manager</a>

			<span>Tables</span>
			<table style="width: 100%;">
			<?php
				foreach(DBTool::GetAllTables() as $Key => $Item)
				{
					$TableConfig = DBTool::TableConfigStringArray($Item["information"]["table_comment"]);
					if(isset($TableConfig["Status"]) && $TableConfig["Status"][0] == "hidden") continue;

					echo "<tr>";
					echo "<td><a href='".$PHPZevelop->Path->GetPage("select/".$Key, true)."'>".$Item["name"]."</td>";

					if(!isset($TableConfig["AllowAdd"]) || $TableConfig["AllowAdd"][0] != "false")
						echo '<td style="width: 15%; text-align: right;"><a href="'.$PHPZevelop->Path->GetPage("add/".$Key, true).'" style="display: inline-block;">Add</a></td>';
					else
						echo "<td></td>";

					echo "</tr>";
				}
			?>
			</table>

			<span>Account</span>
			<a href="<?php $PHPZevelop->Path->GetPage("manage-account"); ?>">Manage account</a>
			<a href="<?php $PHPZevelop->Path->GetPage("signout"); ?>">Sign out</a>
		</div>

		<div id="Content">
			<?php echo $PHPZevelop->PageContent; ?>
		</div>

	</body>
</html>