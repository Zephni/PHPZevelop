<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"404");

	// Turn parameters off for this page
	$PHPZevelop->CFG->PassParamsAutomatically = false;
?>
<h2>404 Page</h2>

<p>You have a few options when dealing with 404 pages:</p>

<h3>Option 1 (Default): Make all URL's pass parameters to the nearest existing page</h3>

<p>To use this method you simply have to make sure that the config file at "/config.php" has the following property set to true:</p>

<pre class="code">"PassParamsAutomatically" => true</pre>

<p>That may sound complicated so here is an example. Say you have a page located at "/example/test.php" which can be accessed by simply using "/example/test" as a URL, 
if you were to put "/example/test/value1/value2" that page would be able to access them variables (as explained <a href="<?php $PHPZevelop->Path->GetPage("example/vars/5/test"); ?>">here</a>).</p>

<p>Also if you didn't have a page at "/example/test.php" then using the same URL as above would return all of them parameters to the home or index page.</p>


<h3>Option 2: Have optional pages that can't take parameters</h3>

<p>To use this method it is the same as the above except on a specific page that you don't wish to allow parameters through you would have to add the following to the top of the page in the PHP section:</p>

<pre class="code">$PHPZevelop->CFG->PassParamsAutomatically = false;</pre>

<p>Now this page will only be accessed by going to it's direct path, any parameters passed after it will result in the default 404 page. This page has this configuration property set, 
	too see it in use click <a href="<?php $PHPZevelop->Path->GetPage("example/404/no-vars-allowed"); ?>" target="_blank">here</a>.</p>


<h3>Option 3: No pages accept parameters</h3>

<p>This is a very strict option that will not allow anything to be passed to pages through the URL, to use this mode simply set the following in "/config.php"</p>

<pre class="code">"PassParamsAutomatically" => false</pre>