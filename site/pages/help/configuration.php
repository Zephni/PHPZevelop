<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Configuration");
?>

<h2>Configuration</h2>

<p>The below configuration is an example configuration. The config for this setup is "config.php" in the root directory: </p>

<pre class="code">
$PHPZevelop->NewObject("CFG", (object) array(
	"SiteTitle"		=> "PHPZevelop",
	"Site"			=> "site",
	"MultiSite"		=> array("admin"),
	"PassParams"		=> false,
	"PreParam"		=> "param_",
	"DefaultFiles"		=> array("index.php", "default.php", "home.php")
));
</pre>

<p>As $PHPZevelop->CFG is an object, values can be returned by using the following format: </p>

<pre class="code">
echo $PHPZevelop->CFG->SiteTitle;
echo $PHPZevelop->CFG->DB->Host;
</pre>

<p>Configuration can be overwritten in the site directory "config.php". This could be nessesary for overwriting default configuration in <a href="<?php $PHPZevelop->Path->GetPage("help/multisite"); ?>">MultiSite</a> site.</p>

<h3>Below is an explanation of each of these values</h3>

<p><span style="color: green;">SiteTitle</span>: This will be displayed in the title of each webpage, and can be used else where in the site.</p>

<p><span style="color: green;">Site</span>: The directory that your site data belongs in</p>

<p><span style="color: green;">MultiSite</span>: Other site configuration directories</p>

<p><span style="color: green;">PassParams</span>: If true, parameters can be passed to all pages. For more options check <a href="<?php $PHPZevelop->Path->GetPage("help/vars/5/test"); ?>">here</a></p>

<p><span style="color: green;">PreParam</span>: Parameters passed will be accessable with this property and then an integer. For more information check <a href="<?php $PHPZevelop->Path->GetPage("help/vars/5/test"); ?>">here</a></p>

<p><span style="color: green;">DefaultFiles</span>: An array of files that can be used as home pages, or default fallback files. For more information check <a href="<?php $PHPZevelop->Path->GetPage("help/adding-pages"); ?>">here</a></p>