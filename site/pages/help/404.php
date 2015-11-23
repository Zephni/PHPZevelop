<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"404");
?>
<h2>404 Page</h2>

<p>You have a two options when dealing with 404 pages:</p>


<h3>Option 1 (Default): No pages accept parameters unless specified</h3>

<p>This is a strict option that will not allow anything to be passed to pages through the URL, to use this mode simply set the following in "/config.php"</p>

<pre class="code">"PassParamsAutomatically" => false</pre>

<p>If you want to specify a page to be able to take parameters you can add:</p>

<pre class="code">$PHPZevelop->CFG->PassParamsAutomatically = false;</pre>

<p>.. to the top of the PHP page below the Meta data. To see this in action have a look <a href="<?php $PHPZevelop->Path->GetPage("help/vars/5/test"); ?>" target="_blank">here</a>. In "help/vars.php"
PassParamsAutomatically has been set to true in the configuration instance, so any parameters passed will be accessible on that page.</p>


<br /><h3>Option 2: All pages accept parameters unless specified</h3>

<p>To use this method you simply have to make sure that the config file at "/config.php" has the following property set to true:</p>

<pre class="code">"PassParamsAutomatically" => true</pre>

<p>To explain what this does.. say you have a page located at "/help/test.php" which can be accessed by simply using "/help/test" as a URL, 
if you were to put "/help/test/value1/value2" that page would be able to access them variables (as explained <a href="<?php $PHPZevelop->Path->GetPage("help/vars/5/test"); ?>">here</a>).</p>

<p>Also if you didn't have a page at "/help/test.php" then using the same URL as above would return all of them parameters to the home or index page.</p>

<p>If you wish to have a specific page that doesn't allow parameters through you would have to add the following to the top of the page in the PHP section:</p>

<pre class="code">$PHPZevelop->CFG->PassParamsAutomatically = false;</pre>