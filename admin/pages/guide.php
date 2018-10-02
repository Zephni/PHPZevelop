<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Guide",
		"PassParams" => false
	));
?>

<div class="breadcrumbs"><?php
	Breadcrumbs::Build(array(
		"" => "administration",
		"guide" => "guide"
	));
?></div>

<style type="text/css">
	pre {background: #EEEEEE; padding: 6px; font-size: 12px; display: inline-block; margin-left: 10px;}
	h2.Section {margin-top: 40px; margin-bottom: 10px;}
	p.SectionDescription {margin-bottom: 10px; font-size: 14px; line-height: 20px;}
	div.Tip {font-size: 13px; display: block; margin: 0px 0;}
</style>

<h1>PHPZevelop database guide</h1>

<h2 class="Section">Table options</h2>

<p class="SectionDescription">Format used is:<br />
key1::value1<br />
key2::value2,value3
</p>

<div class="Tip">Choose column amount (Default is 1): <pre>ColNum::3</pre></div>

<div class="Tip">Display file manager and choose location:
<pre>FileManager::true</pre>
<pre>FileManagerDefaultLocation::articles</pre>
</div>

<div class="Tip">Link to display on edit page (likely for previews): <pre>EditLink::/article/[id]/admin|Preview article</pre></div>

<div class="Tip">Default order to display on select page: <pre>DefaultOrder::live ASC, tstamp DESC</pre></div>

<div class="Tip">Default fields to display on select page (Default is all): <pre>DefaultFields::author,type,image,title,live,tstamp</pre></div>

<div class="Tip">Options to display on edit page (Default is edit,delete, extras must be custom added in edit.php): <pre>Options::edit,preview</pre></div>

<div class="Tip">Hide table: <pre>Status::hidden</pre></div>


<h2 class="Section">Column options</h2>

<p class="SectionDescription">Format used is:<br />
key1:value1;key2:value2</p>

<div class="Tip">Textarea: <pre>type:textarea</pre></div>

<div class="Tip">Select (predefined values): <pre>type:select;values:key1|value1:key2|value2</pre></div>

<div class="Tip">Select (join values): <pre>type:select;join:tablename:columntitle</pre></div>

<div class="Tip">Checkboxes  (predefined values): <pre>type:checkbox;data:key1|value1:key2|value2</pre></div>

<div class="Tip">Checkboxes (join values): <pre>type:checkbox;join:tablename:columntitle</pre></div>

<br /><br /><br /><br /><br /><br /><br />