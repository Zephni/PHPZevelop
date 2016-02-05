<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Manage files",
		"PassParams" => true
	));
?>

<?php
	/* Page specific code 
	-----------------------------------*/
	$page_msg = "";
	$perms = "";
	
	// Set root
	$root = $PHPZevelop->CFG->FrontSiteRoot."/images";
	$base_path = $PHPZevelop->CFG->FrontSite."/images";
	
	// Append path if passed
	$path = NULL;
	if (isset($_GET['file']) && strlen($_GET['file']) > 0) {
		$path = '/'.$_GET['file'];
	}
	
	// Actions
	if(isset($_POST['action']) && $_POST['action'] == 'upload'){
		$ufile = new UploadFile($_FILES['image']);
		$ufile->limitToImage = true;
		$ufile->process('../images'.$path.'/');
		$page_msg = $ufile->getMsg();
	}elseif(isset($_POST['action']) && $_POST['action'] == 'delete'){
		if(unlink('../images'.$path.'/'.$_POST['filename'])){
			$page_msg = "<p style='color: green; font-size: small;'>'../images".$path."/".$_POST['filename']."' has been removed</p>";
		}else{
			$page_msg = "<p style='color: red; font-size: small;'>There was an error removing '../images".$path."/".$_POST['filename']."'</p>";
		}
	}
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
        <script type="text/javascript">
        	$(document).ready(function(){
				$('.display').click(function(){
					pathStr = $(this).attr('value');
					pathStr = pathStr.slice(pathStr.search('images/'));
					$('.img-src').html("/"+pathStr).hide().fadeIn(300);
					image = $('<img src="'+$(this).attr('value')+'" class="image" />').css({'margin-top':'10px'});
					image.draggable();
					fileView = $('.file-view').css({'overflow':'hidden','max-height':'360px'}).html(image);
					image.load(function(){
						fileView.slideDown();
						image.hide().fadeIn(500);
						if(image.height() > 360){fileView.height(fileView.height()); fileView.animate({'height':'360px'}, function(){fileView.css({'overflow':'auto'});});}
						else{fileView.height(fileView.height()); fileView.animate({'height':(image.height()+20)+'px'}, function(){fileView.css({'overflow':'auto'});});}
						closeButton = $('.file-view-head').html('<div class="close-button">close</div>');
						$('.file-view-head').slideDown();
						closeButton.click(function(){
							$('.file-view-head').slideUp();
							$('.file-view').slideUp();
							$('.img-src').fadeOut(300);
						});
					});
				});
				
				$('tbody tr').click( function() {
					window.location = $(this).find('a').attr('href');
				});
				
				$('tbody tr').hover(function(){
					$(this).css({'background':'#EEEEFF'});
				}, function(){
					$(this).css({'background':''});
				});
			});
</script>
<style>
	.container a {text-decoration: none; font-size: 13px;}
	.container tbody tr {cursor: pointer;}		
	.container .image {
		display: block; margin: 0 auto; -moz-box-shadow: 0 0 5px 5px #BBB;-webkit-box-shadow: 0 0 5px 5px#BBB; box-shadow: 0 0 5px 5px #BBB;	
	}
	.container .image:hover {
		cursor: move;
	}
	.container {position: relative;margin: 10px auto; width: 800px;}
	.container .file-view {
		position: relative;
		display: none;
		border: 1px solid #CCCCCC;
		max-width: 800px;
		overflow: auto;
		margin-bottom: 10px;
		background: url('images/manage_files/trans.jpg') repeat;
		clear: right;
	}
	.container .file-view-head {
		position: relative;
		display: none;
		height: 23px;
		width: 100%;
		clear: right;
	}
	.container .file-view-head .close-button {
		position: absolute; right: 0px; top: 0px;
		background: #222222; opacity: 0.8; color: white; padding: 2px 7px 2px 7px;
		border-radius: 5px 5px 0 0;
	}
	.container input[type=submit] {border: 1px solid #CCCCCC; background: #06C; padding: 2px; color: white; border-radius: 3px;}
	.container input[type=submit].delete {padding: 0px 4px 2px 4px; border: 1px solid #CCCCCC; background: #D55; color: white; border-radius: 3px;}
	.container input[type=submit]:hover {cursor: pointer;}
	.container .close-button:hover {cursor: pointer;}
	.container h2 {color: #06C; font-size: 28px;}
	
	table.dir-list a {color: #06C;display: block;}
	table.dir-list {margin: 1px auto 10px auto;width: 800px;border: 1px solid #CCCCCC;border-collapse: collapse;}
	table.dir-list thead tr {background: #06C;color: white;}
	table.dir-list tbody tr td img.icon {}
	table.dir-list tbody tr {border: 1px solid #CCCCCC;}
	table.dir-list tbody tr.row1 {background: #F7F7F7;}
	table.dir-list tbody tr.row2 {background: #FFFFFF;}
	table.dir-list tbody tr td {padding: 2px 5px; border: 1px solid #CCCCCC; vertical-align: middle; height: 25px;}
</style>

<div id="pageContent">
	<div class="container">
		<a href="?p=manage_files"><h2><?php echo $PHPZevelop->CFG->SiteTitle; ?> Image Manager</h2></a>
	    <?php echo $page_msg; ?>
	    <form action="" method="post" enctype="multipart/form-data" style="float: left; display: block;">
	        <input type="hidden" name="action" value="upload" />
	        <input type="file" name="image" required='required' />
	        <input type="submit" value="Upload" />
	    </form>
	    <br />
	    <?php
	    	if(file_exists($root.$path)){
	    		$perms = substr(sprintf('%o', fileperms($root.$path)), -4);
		        if(substr($perms, 3, 1) != "7"){$perms = "<span style='color: red;'>".$perms."</span>";}
		        else{$perms = "<span style='color: green;'>".$perms."</span>";}
	    	}
	    ?>
	    <span style='font-size: 12px; float: right; font-weight: normal; padding-top: 9px;'><?php echo '\''.$root.$path.'\''.' | Perms: \''.$perms.'\''; ?></span>
	    <div class='file-view-head'></div>
	    <div class='file-view'></div>
	    <div style="color: #06C;" class='img-src'></div>
	    <table class='dir-list'>
	        <thead>
	            <tr>
	                <td style='width: 18px;'></td>
	                <td>File name</td>
	                <td style="width: 100px;">File size</td>
	                <td style="width: 155px;">Last modified</td>
	                <td style="width: 23px;"></td>
	            </tr>
	        </thead>
	        <tbody>
	        <?php
	            $dir_list = GetDirectoryListing($root, $path);
	            if($path != NULL){echo '<tr><td><img src="images/manage_files/folder_icon.gif" class="icon" /></td><td><a href="?p=manage_files&file='.urlencode(substr(dirname($root.$path), strlen($root) + 1)).'">..</a></td><td></td><td></td><td></td></tr>';}
	            if(is_array($dir_list)){
	            	$i = 0;
	            	foreach($dir_list as $item){
		                if($i%2 == 0){$switch_class = "row1";}else{$switch_class = "row2";}
		                if(is_file($root.$path.'/'.$item['basename'])){
		                    $item['icon'] = "images/manage_files/file_icon.gif";
		                    $item['link'] = /*$base_path.$path.'/'.$item['basename']*/"#' class='display' value='/".$base_path.$path.'/'.$item['basename'];
		                    $item['delete'] = "<form action='' method='post' style='display: block;'><input type='hidden' name='action' value='delete'><input type='hidden' name='filename' value='".$item['basename']."'><input type='submit' value='x' class='delete'></form>";
		                }else{
		                    $item['icon'] = "images/manage_files/folder_icon.gif";
		                    $item['link'] = "?p=manage_files&file=".$item['link'];
		                    $item['delete'] = "";
		                }
		                echo "
		                    <tr class='".$switch_class."'>
		                        <td><a href='".$item['link']."'><img src='".$item['icon']."' class='icon' /></a></td>
		                        <td><a href='".$item['link']."'>".$item['basename']."</a></td>
		                        <td><a href='".$item['link']."'>".BytesToSize($item['filesize'])."</a></td>
		                        <td><a href='".$item['link']."'>".$item['last_modified']."</a></td>
		                        <td>".$item['delete']."</td>
		                    </tr>
		                ";
		                $i++;
		            }
	            }
	        ?>
	        </tbody>
	    </table>
	</div>
</div>  	
