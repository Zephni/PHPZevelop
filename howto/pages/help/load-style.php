<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Load style",
		"LoadStyle"	 => "FileOrder",
		"FileOrder"  => array("inc/header", "[page]", "inc/footer")
	));
?>

<h2>Load style</h2>

<p>There are two methods of loading your page and layout, "Template" and "FileOrder" mode. To change mode you can set the "LoadStyle" property of $PHPZevelop->CFG
either in "config.php", "site/config.php" or on individual pages.</p>

<h3>"Template" mode (Default)</h3>

<p>To use this mode "LoadStyle" will need to be switched to "Template" mode and the default "Template" must be set. Your config must be setup at any of the three
locations with the following data: </p>

<pre class="code">
"LoadStyle" => "Template",
"Template"  => "main" // "main" can be changed to any <?php echo FILE_EXT; ?> file in "site/templates"
</pre>

<p>The template file in "templates/" should contain the entire page from top to bottom, the page content found in "pages/" can be echoed to the page
wherever you require it using: </p>

<pre class="code">
<?php echo htmlentities("<?php echo \$PHPZevelop->PageContent; ?>"); ?>
</pre>

<p>This page has been swapped to "FileOrder" mode at the top of the file by overriding the CFG object data. It should say "(FileOrder mode)" in the
header to demonstrate that it's working.</p>

<h3>"FileOrder" mode</h3>

<p>To use this mode "LoadStyle" will need to be switched to "FileOrder" mode and the default "FileOrder" must be set. Your config must be setup at any of the three
locations with the following data: </p>

<pre class="code">
"LoadStyle" => "FileOrder",
"FileOrder" => <?php echo htmlentities('array("inc/header", "[page]", "inc/footer")'); ?>
</pre>

<p>Note that "FileOrder"'s value should be an array, and that each element should contain the path to the correct file relative to the site it's contained in. To insert
the page file use the [page] parameter, this will inject the current page in to the correct location amongst the file order.</p>