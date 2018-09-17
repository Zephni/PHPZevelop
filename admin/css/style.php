<?php
	header("Content-type: text/css; charset: UTF-8");

	if(!isset($_GET["c1"]))
		$HexColor1 = "#2F60AA";
	else
		$HexColor1 = urldecode($_GET["c1"]);
?>

@import url('https://fonts.googleapis.com/css?family=Droid+Sans');
html, body {width: 100%; height: 100%; padding: 0px; margin: 0px; border: 0px;}
body {background: #FFFFFF; color: #333333; position: absolute; font-family: "Droid Sans"; font-size: 1em;}

h1 {font-family: "Droid Sans";}
p {font-family: "Droid Sans"; font-size: 14px; line-height: 14px;}
a {color: <?php echo $HexColor1; ?>;}
hr {color: white;}
a img {border: none; text-decoration: none;}

.InnerContainer {width: 100%; max-width: 1280px; min-width: 750px; margin: auto; position: relative; box-sizing: border-box; padding: 0 20px;}

form input    {font-size: 16px !important; padding: 5px; border-radius: 1px; width: 100%; border: 1px solid #CCCCCC; margin-bottom: 10px; outline: none; font-family: "Droid Sans";}
form textarea {font-size: 16px !important; padding: 5px; border-radius: 1px; width: 100%; border: 1px solid #CCCCCC; margin-bottom: 10px; outline: none; font-family: "Droid Sans"; min-height: 100px;}
form select   {font-size: 16px !important; padding: 5px; border-radius: 1px; width: 100%; border: 1px solid #CCCCCC; margin-bottom: 10px; outline: none; font-family: "Droid Sans";}
form input[type="file"] {border: none; box-shadow: none; position: relative; top: 3px;}
form input[type="color"] {padding: 0px 8px; width: 80px;}
form input[type="submit"] {font-size: 16px; background: <?php echo $HexColor1; ?>; border: none; color: white; font-weight: bold; outline: none;}
form input[type="submit"]:hover {cursor: pointer;}
form table {width: 100%;}

form td {padding-bottom: 3px;}

#NavTop {display: block; height: 39px; background: <?php echo $HexColor1; ?>; padding: 0px 0px; color: white; border-bottom: 2px solid #333333; width: 100%;}
#NavTop .item {display: inline-block; padding: 7px 14px 8px 7px;}
#NavTop .item a {color: white;}
#NavTop .item a:hover {color: #FFFFFF;}
#NavTop .item img {display: inline-block; height: 22px; vertical-align: middle;}
#NavTop .item span {display: inline-block; font-size: 14px; line-height: 22px; padding-left: 4px; position: relative; top: 1px;}

#Content {width: 100%; min-height: 100%; box-sizing: border-box; position: relative; overflow: auto; padding: 20px 0px 0 0px;}

.PreviewImage {width: 100%;}

/* Breadcrumbs
---------------------------*/
.breadcrumbs {display: block; padding: 9px 0 9px 0; font-size: 13px; border-bottom: 1px solid #CCCCCC; margin-bottom: 7px;}
.breadcrumbs span {font-size: 11px;}
.breadcrumbs a {}

/* DBTool::DisplayList */
.DBToolTable {width: 100%;}
.DBToolInfoRow td {background: <?php echo $HexColor1; ?>; padding: 7px 10px; color: white;}
.DBToolRow td {padding: 10px;}
.DBToolRow td a {text-decoration: none;}
.DBToolRow:nth-child(even) {background: #EEEEEE;}
.DBToolExtraField {width: 10%;}

/* Mobile only */
@media screen and (max-width: 890px){
	.InnerContainer{padding: 0 10px;}
}

/* Non mobile only*/
@media screen and (min-width: 890px){
	
}