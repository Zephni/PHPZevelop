<?php
	header("Content-type: text/css; charset: UTF-8");

	if(!isset($_GET["c1"]))
		$HexColor1 = "#2F60AA";
	else
		$HexColor1 = urldecode($_GET["c1"]);
?>

@import url('https://fonts.googleapis.com/css?family=Droid+Sans');
html, body {width: 100%; height: 100%; padding: 0px; margin: 0px; border: 0px;}
body {background: #FFFFFF; color: #333333; position: absolute; font-family: "Droid Sans";}

h1 {font-family: "Droid Sans";}
p {font-family: "Droid Sans"; font-size: 14px; line-height: 21px;}
a {color: <?php echo $HexColor1; ?>;}
hr {color: white;}
a img {border: none; text-decoration: none;}

form input    {font-size: 16px; padding: 12px; border-radius: 10px; width: 100%; box-shadow: 1px 1px 1px <?php echo $HexColor1; ?>; margin-bottom: 10px; border: none; outline: none; border: 1px solid #CCCCCC; font-family: "Droid Sans";}
form textarea {font-size: 16px; padding: 12px; border-radius: 10px; width: 100%; box-shadow: 1px 1px 1px <?php echo $HexColor1; ?>; margin-bottom: 10px; border: none; outline: none; border: 1px solid #CCCCCC; font-family: "Droid Sans"; min-height: 100px;}
form select   {font-size: 16px; padding: 12px; border-radius: 10px; width: 100%; box-shadow: 1px 1px 1px <?php echo $HexColor1; ?>; margin-bottom: 10px; border: none; outline: none; border: 1px solid #CCCCCC; font-family: "Droid Sans";}
form input[type="file"] {border: none; box-shadow: none; position: relative; top: 3px;}
form input[type="color"] {padding: 0px 8px; width: 80px;}
form input[type="submit"] {font-size: 16px; background: <?php echo $HexColor1; ?>; border: none; color: white; font-weight: bold; outline: none;}
form input[type="submit"]:hover {cursor: pointer;}
form table {width: 100%;}

form td {padding-bottom: 10px;}

#NavTop {display: block; height: 39px; background: <?php echo $HexColor1; ?>; padding: 0px 0px; color: white; border-bottom: 2px solid #333333; position: fixed; width: 100%; top: 0px; left: 0px; z-index: 10;}
#NavTop .item {display: inline-block; padding: 7px 12px 8px 7px;}
#NavTop .item a {color: white;}
#NavTop .item a:hover {color: #FFFFFF;}
#NavTop .item img {display: inline-block; height: 22px; vertical-align: middle;}
#NavTop .item span {display: inline-block; font-size: 14px; line-height: 22px; padding-left: 4px; position: relative; top: 1px;}

#LeftCol {width: 20%; min-width: 250px; height: 100%; display: block; position: fixed; top: 0px; left: 0px; background: #333333; box-shadow: 2px 3px 3px <?php echo $HexColor1; ?>; box-sizing: border-box; padding: 70px 20px 20px 20px; z-index: 2; overflow-y: auto;}
#LeftCol h1 {color: #EEEEEE;}
#LeftCol span {font-size: 13px; color: #CCCCCC; margin: 12px 0; display: block;}
#LeftCol a {display: block; width: 100%; padding: 8px; color: #888888; box-sizing: border-box; text-decoration: none;}
#LeftCol a:hover {color: #CCCCCC;}
#LeftCol a.selected {border-left: 3px solid <?php echo $HexColor1; ?>; background: #444444; color: #FFFFFF;}

#Content {width: 79.5%; min-height: 100%; box-sizing: border-box; padding: 80px 30px 30px 60px; position: relative; left: 20%; top: 0px; overflow: auto;}

.PreviewImage {width: 100%;}

/* Breadcrumbs
---------------------------*/
.breadcrumbs {display: block; padding: 9px 0 9px 0; font-size: 13px; border-bottom: 1px solid #CCCCCC; margin-bottom: 15px;}
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
	#LeftCol {display: none;}
	#Content {width: 100%; left: 0px; padding: 80px 20px 20px 20px;}
	#NavTop .item:first-of-type {display: none;}
}

/* Non mobile only*/
@media screen and (min-width: 890px){
	
}