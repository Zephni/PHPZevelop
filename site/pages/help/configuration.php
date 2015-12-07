<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Configuration"
	));
?>

<h2>Configuration</h2>

<p>The below configuration is an example configuration. The config for this setup is "config.php" in the root directory. </p>

<p>As $PHPZevelop->CFG is an object, values can be returned by using the following format: </p>

<pre class="code">
echo $PHPZevelop->CFG->SiteTitle;
echo $PHPZevelop->CFG->DB->Host;
</pre>

<p>Configuration can be overwritten in the site directory "config.php". This could be nessesary for overwriting default configuration in <a href="<?php $PHPZevelop->Path->GetPage("help/multisite"); ?>">MultiSite</a> site, 
you can also change configuration per page, more information here: <a href="<?php $PHPZevelop->Path->GetPage("help/meta-data"); ?>">help/meta-data</a></p>

<h3>Below is an explanation of the available values</h3>

<p><span style="color: green;">SiteTitle</span>: This will be displayed in the title of each webpage, and can be used else where in the site.</p>

<p><span style="color: green;">PageTitle</span>: Default page title</p>

<p><span style="color: green;">MetaTitle</span>: Default meta title</p>

<p><span style="color: green;">MetaDescription</span>: Default meta description</p>

<p><span style="color: green;">MetaKeywords</span>: Default meta keywords</p>

<p><span style="color: green;">Site</span>: The directory that your site data belongs in</p>

<p><span style="color: green;">MultiSite</span>: Other site configuration directories</p>

<p><span style="color: green;">PassParams</span>: If true, parameters can be passed to all pages. For more options check <a href="<?php $PHPZevelop->Path->GetPage("help/vars/5/test"); ?>">here</a></p>

<p><span style="color: green;">PreParam</span>: Parameters passed will be accessable with this property and then an integer. For more information check <a href="<?php $PHPZevelop->Path->GetPage("help/vars/5/test"); ?>">here</a></p>

<p><span style="color: green;">Page404</span>: This will define the 404 page to load if the URL cannot be found. For more information check <a href="<?php $PHPZevelop->Path->GetPage("help/undefined-urls"); ?>">here</a></p>

<p><span style="color: green;">DefaultFiles</span>: An array of files that can be used as home pages, or default fallback files. For more information check <a href="<?php $PHPZevelop->Path->GetPage("help/adding-pages"); ?>">here</a></p>