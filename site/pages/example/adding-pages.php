<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Adding pages");
?>
<h2>Pages</h2>

<h3>Adding pages</h3>

<p>By placing *.php files in the "pages/" directory they will be accessable through the URL by using the page name without the ".php" extension.</p>

<p>If the file is placed inside a directory within "pages/" then you can access it by using a "/" to delimitise folder names in the URL to access it.</p>

<p>Eg. this file lives here: </p>

<pre class="code">/pages/example/adding-pages.php</pre>

<p>.. and is being accessed through the URL by: </p>

<pre class="code">/example/adding-pages</pre>

<h3>Default pages</h3>

<p>If you were to go to <a href="<?php $PHPZevelop->Path->GetPage("example"); ?>" target="_blank"><?php $PHPZevelop->Path->GetPage("example"); ?></a> 
you would get a 404 error because even though the directory "example/" exists, it is not a readable page. If you would like "example/" to show a page you can create
any of the pages found in the $CFG array in "/config.php": </p>

<pre class="code">"DefaultFiles" => array("index.php", "default.php", "home.php")</pre>

<p>.. in the "example/" directory.</p>