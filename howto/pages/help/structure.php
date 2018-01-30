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

<p>The standard site structure is as follows, same is applied for additional <?php $Link->Out("help/multisite", "MultiSites"); ?>.</p>

<pre class="code">
classes/
css/
scripts/
templates/
config.php
initiate.php
</pre>

<p><span style="color: green;">classes/</span>: All classes in this directory will be included by default from global.php</p>
<p><span style="color: green;">css/</span>: .css files for site styling.</p>
<p><span style="color: green;">scripts/</span>: A directory for JavaScript files, by default JQuery is instantiated and "scripts/main.js" is called from "inc/header.php"</p>
<p><span style="color: green;">templates/</span>: Template files should be placed here. See more <?php $Link->Out("help/load-style", "here"); ?>.</p>
<p><span style="color: green;">config.php</span>: You can override config properties here. All pages loaded in the containing site will have the same config applied.</p>
<p><span style="color: green;">global.php</span>: For any site specific code. By default this file runs all specified include directories. See more <?php $Link->Out("classes/subloader-class", "here"); ?>.</p>