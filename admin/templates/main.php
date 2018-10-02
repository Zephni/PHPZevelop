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

		<!-- ZSwitch -->
		<script src="<?php $PHPZevelop->Path->GetScript("ZSwitch.js"); ?>"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$("input[type='ZSwitch']").ZSwitch(<?php echo (isset($Data["live"]) && $Data["live"] == "1") ? 1 : 0; ?>);
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
	            valid_elements:"*[*]",
	            valid_children : "*[*]",
	            //force_br_newlines : true,
	            //force_p_newlines : false,
	            //forced_root_block : '',
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
		
		<link rel="stylesheet" type="text/css" href="<?php $PHPZevelop->Path->GetCSS("style.php?c1=".urlencode($Administrator->Data["theme_color"])); ?>" />

		<!-- -->
		<script async defer src="//assets.pinterest.com/js/pinit.js"></script>
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
			<div class="InnerContainer">
				<div class="item" style="padding-left: 0px !important;">
					<a href="<?php $PHPZevelop->Path->GetPage(""); ?>">
						<span style="font-weight: normal;"><?php echo $PHPZevelop->CFG->SiteTitle; ?></span>
					</a>
				</div>

				<div style="position: absolute; right: 0px; top: 0px;">

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
		</div>

		<style type="text/css">
			#NavBar {width: 100%; background: #F4F4F4; padding: 0px 0px; border-bottom: 3px solid #DDDDDD;}
			#primary_nav_wrap {}
			#primary_nav_wrap a {padding: 16px 12px 12px 12px; font-size: 12px; font-family: "HelveticaNeue","Helvetica Neue", Helvetica; position: relative;}
			#primary_nav_wrap ul {list-style: none; position: relative; margin:0; padding: 0;}
			#primary_nav_wrap ul a{display: block; color: #333333; text-decoration: none; font-weight: 700;}
			#primary_nav_wrap ul li {position:relative; display: inline-block; margin:0;}
			#primary_nav_wrap ul li.current {background:#DDDDDD;}
			#primary_nav_wrap ul li:hover {background: #DDDDDD;}
			#primary_nav_wrap ul ul {display: none; position: absolute; top: 100%; left: 0; background: #F4F4F4; padding: 0; border-left: 2px solid #E6E6E6; border-bottom: 2px solid #DDDDDD; border-right: 2px solid #E6E6E6;}
			#primary_nav_wrap ul ul li {float: none; width: 200px;}
			#primary_nav_wrap ul ul a {}
			#primary_nav_wrap ul ul ul {top: 0; left: 100%;}
			#primary_nav_wrap ul li:hover > ul {display: block; z-index: 1;}
			#primary_nav_wrap ul ul li {border-top: 2px solid #E6E6E6;}
			#primary_nav_wrap ul ul ul li {background: #F1F1F1;}
			
			#primary_nav_wrap span {position: absolute; right: 5px; top: 12px; font-size: 12px;}
			#primary_nav_wrap a:not([href]) {color: #444444 !important; cursor: default;}
		</style>
		<script type="text/javascript">
			$(document).ready(function(){
				// Hides li's that have a tag without href, and also have ul, but no li elements inside (basically empty / useless li items)
				$("li:has(ul)").each(function(){
					if($(this).has("a") && $(this).find("a:first-of-type").attr("href") == undefined && $(this).find("ul") != undefined && $(this).find("ul li").length == 0)
						$(this).hide();
				});
			});
		</script>
		<div id="NavBar">
			<div class="InnerContainer">

				<nav id="primary_nav_wrap">
				  <ul>
				    <li><a href="<?php $PHPZevelop->Path->GetPage("manage-account"); ?>">Account &nbsp;&nbsp;<span>&#9207;</span></a>
				    	<ul>
				    		<li><a href="<?php $PHPZevelop->Path->GetPage("manage-account"); ?>">My account</a></li>
							<li><a href="<?php $PHPZevelop->Path->GetPage("signout"); ?>">Sign out</a></li>
				    	</ul>
				    </li>

				    <li><a>Database &nbsp;&nbsp;<span>&#9207;</span></a>
				    	<ul>
							<?php if(HasPermission("general", "database")){ ?>
							<?php if(HasPermission("general", "administrator_select") || HasPermission("general", "administrator_add")){ ?>
							<li><a <?php if(HasPermission("general", "administrator_select")){ ?>href="<?php $PHPZevelop->Path->GetPage("manage/administrators"); ?>"<?php } ?>>Administrators</a>
								<ul>
									<?php if(HasPermission("general", "administrator_add")){ ?>
									<li><a <?php if(HasPermission("general", "administrator_add")){ ?>href="<?php $PHPZevelop->Path->GetPage("manage/administrators-add"); ?>"<?php } ?>>Add administrator</a>
									<?php } ?>
								</ul>
							</li>
							<?php } ?>
							<?php if(HasPermission("general", "create")){ ?>
							<li><a href="<?php $PHPZevelop->Path->GetPage("manage/create-table"); ?>">Create table</a></li>
							<?php } ?>
							<li>
								<a>Modify table</a>
								<ul>
									<?php    
										foreach(DBTool::GetAllTables() as $Key => $Item)
										{
											$TableConfig = DBTool::TableConfigStringArray($Item["information"]["table_comment"]);
											if(isset($TableConfig["Status"]) && $TableConfig["Status"][0] == "hidden") continue;

											echo "<li><a href='".$PHPZevelop->Path->GetPage("manage/modify-table/".$Key, true)."'>".$Item["name"]."</a></li>";
										}
									?>
								</ul>
							</li>
							<?php } ?>
							<?php if(HasPermission("general", "file_manager")){ ?>
							<li><a <?php if(HasPermission("general", "file_manager")){ ?>href="<?php $PHPZevelop->Path->GetPage("file-manager"); ?>"<?php } ?>>File manager</a></li>
							<?php } ?>
							<li><a href="<?php $PHPZevelop->Path->GetPage("guide"); ?>">Guide</a></li>
				    	</ul>
				    </li>
					
					<li>
						<a>Tables &nbsp;&nbsp;<span>&#9207;</span></a>
						<ul>
							<?php
								foreach(DBTool::GetAllTables() as $Key => $Item)
								{
									$TableConfig = DBTool::TableConfigStringArray($Item["information"]["table_comment"]);
									if(isset($TableConfig["Status"]) && $TableConfig["Status"][0] == "hidden") continue;

									if(HasPermission("table", $Key, "select") || HasPermission("table", $Key, "add"))
										echo "<li><a ".((HasPermission("table", $Key, "select")) ? "href='".$PHPZevelop->Path->GetPage("select/".$Key, true)."'" : "").">".$Item["name"]."</a>";

									if(!isset($TableConfig["AllowAdd"]) || $TableConfig["AllowAdd"][0] != "false")
									{
										echo "<ul>";
										if(HasPermission("table", $Key, "add")) echo '<li><a '.((HasPermission("table", $Key, "add") ? 'href="'.$PHPZevelop->Path->GetPage("add/".$Key, true) : '')).'">Add</a></li>';
										if(HasPermission("table", $Key, "select")) echo '<li><a '.((HasPermission("table", $Key, "select") ? 'href="'.$PHPZevelop->Path->GetPage("select/".$Key, true) : '')).'">Browse</a></li>';
										echo "</ul>";
									}
									
									echo "</li>";
								}
							?>
						</ul>
					</li>

					<?php if(strlen($Administrator->Data["quickbar"]) > 0){ ?>
					<li><a>Quick bar &nbsp;&nbsp;<span>&#9207;</span></a>
						<ul>
							<?php
								foreach(explode(PHP_EOL, $Administrator->Data["quickbar"]) as $Item)
								{
									$Item = explode("|", $Item);
									echo "<li><a href='".$PHPZevelop->Path->GetPage($Item[0], true)."'>".$Item[1]."</a></li>";
								}
							?>
						</ul>
					</li>
					<?php } ?>
				  </ul>
				</nav>

			</div>
		</div>

		<div id="Content">
			<div class="InnerContainer">
				<?php echo $PHPZevelop->PageContent; ?>
			</div>
		</div>
	</body>
</html>