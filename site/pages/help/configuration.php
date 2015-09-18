<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Configuration");
?>

<h2>Configuration</h2>

<p>The below configuration is an example configuration. The config for this setup is "config.php" in the root directory: </p>

<pre class="code">
$CFG = (object) array(
	"SiteTitle" => "PHPZevelop",
	"Site" => "site",
	"MultiSite" => array(),
	"RootDir" => ROOT_DIR,
	"LocalDir" => LOCAL_DIR,
	"MainDir" => MAIN_DIR,
	"MainDirClasses" => MAIN_DIR."/classes",
	"PassParamsAutomatically" => false,
	"PreParam" => "param_",
	"DefaultFiles" => array("index.php", "default.php", "home.php")
);
</pre>

<p>As $PHPZevelop->CFG is an object, values can be returned by using the following format: </p>

<pre class="code">
echo $PHPZevelop->CFG->SiteTitle;
echo $PHPZevelop->CFG->DB->Host;
</pre>

<h3>Below is an explanation of each of these values</h3>

<p><span style="color: green;">SiteTitle</span>: This will be displayed in the title of each webpage, and can be used else where in the site. You can set it to an empty string if you wish</p>

<p><span style="color: green;">Site</span>: The directory that your site data belongs in</p>

<p><span style="color: green;">MultiSite</span>: Other site configuration directories</p>

<p><span style="color: green;">RootDir</span>: The root path to the site eg:</p>

<pre class="code">/home/username/public_html</pre>

<p><span style="color: green;">LocalDir</span>: This is the public facing directory that the site lives in after the domain name</p>

<p><span style="color: green;">MainDir</span>: The root directory of phpzevelop, by standard this lives in the same directory as index.php</p>

<p><span style="color: green;">MainDirClasses</span>: The root directory of classes that belong to phpzevelop</p>

<p><span style="color: green;">PassParamAutomatically</span>: If true, parameters can be passed to all pages. For more options check <a href="<?php $PHPZevelop->Path->GetPage("example/vars/5/test"); ?>">here</a></p>

<p><span style="color: green;">PreParam</span>: Parameters passed will be accessable with this property and then an integer. For more information check <a href="<?php $PHPZevelop->Path->GetPage("example/vars/5/test"); ?>">here</a></p>

<p><span style="color: green;">DefaultFiles</span>: An array of files that can be used as home pages, or default fallback files. For more information check <a href="<?php $PHPZevelop->Path->GetPage("example/adding-pages"); ?>">here</a></p>