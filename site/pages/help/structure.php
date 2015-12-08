<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Structure"
	));
?>

<h2>PHPZevelop structure</h2>

<p>The file structure for PHPZevelop is as follows: </p>

<pre class="code">
site/
phpzevelop/
.htaccess
config.php
index.php
</pre>

<p><span style="color: green;">site/</span>: For all the files regarding your website</p>

<p><span style="color: green;">phpzevelop/</span>: Contains dependant PHPZevelop files</p>

<p><span style="color: green;">.htaccess</span>: Is a set of rules for the working of the framework</p>

<p><span style="color: green;">config.php</span>: Main configuration</p>

<p><span style="color: green;">index.php</span>: Brings together the framework for usage</p>

<h2>Site structure</h2>

<p>The standard site structure is as follows, same is applied for additional <a href="<?php $PHPZevelop->Path->GetPage("help/multisite"); ?>">MultiSites</a>.</p>

<pre class="code">
classes/
css/
inc/
scripts/
config.php
global.php
instantiate.php
</pre>

<p><span style="color: green;">classes/</span>: All classes in this directory will be included by default from global.php</p>
<p><span style="color: green;">css/</span>: .css files for site styling.</p>
<p><span style="color: green;">inc/</span>: Includable PHP files should be placed here. The site header and footer files are placed here by default.</p>
<p><span style="color: green;">scripts/</span>: A directory for JavaScript files, by default JQuery is instantiated and "scripts/main.js" is called from "inc/header.php"</p>
<p><span style="color: green;">config.php</span>: You can override config properties here. All pages loaded in the containing site will have the same config applied.</p>
<p><span style="color: green;">global.php</span>: For any site specific code. By default this file runs all specified include directories. See more <a href="<?php $PHPZevelop->Path->GetPage("classes/subloader-class"); ?>">here</a>.</p>
<p><span style="color: green;">instantiate.php</span>: Here you can instantiate included classes and set up their configuration.</p>