<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Vars");

	// Turn parameters off for this page
	$PHPZevelop->CFG->PassParamsAutomatically = true;
?>
<h2>Variable passing test</h2>

<p>Using PHPZevelop you can pass variables through the URL in two different ways.</p>

<h3>Option 1 - Pretty URL's</h3>
<p><a href="<?php $PHPZevelop->Path->GetPage("help/vars/5/test"); ?>">Test this option</a> (See variables passed at bottom of page)</p>

<p>The page you are viewing exists here: </p>

<pre class="code">/help/vars.php</pre>

<p>So when going to: </p>

<pre class="code">/help/vars</pre>

<p>PHPZevelop will pass back that page. But if you pass extra "/items" to that URL they will act as parameters to that page. Note that if a page exists at that location
it will choose that instead.</p>

<p>By default the PassParamsAutomatically option in "/config.php" will disable this functionality but can be turned on by setting it to true on individual pages like this one, or it can be set
to true by default. for more information click <a href="<?php $PHPZevelop->Path->GetPage("help/404"); ?>" target="_blank">here</a>.</p>

<p>When using this option, you will notice at the bottom of the page that $_GET contains the parameters passed through the URL. By default they will be indexed as "param_0", "param_1"
etc, but this can be changed in the global file by changing $prependParam. If $prependParam is an empty string the parameters will be indexed as a plain integer instead of text.</p>

<h3>Option 2 - Standard format</h3>
<p><a href="<?php $PHPZevelop->Path->GetPage("help/vars?var1=5&var2=test"); ?>">Test this option</a> (See variables passed at bottom of page)</p>

<p>After the "path" has been passed through the URL you can append variables using the pattern:</p>

<pre class="code">?name=value</pre>

<p>For multiple variables use:</p>

<pre class="code">?name1=value1&amp;name2=value2</pre>

<p>You can then retrieve "value" by using $_GET["name"] inside your pages.</p>

<h3>Below is a var_dump of all the variables passed through the current URL</h3>

<pre class="code"><?php
		var_dump($_GET);
	?>
</pre>