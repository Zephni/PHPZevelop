<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Moving base directory");
?>
<h2>Moving base directory</h2>

<p>There will be no issues changing the directory the PHPZevelop lives in, index.php will automatically assign the base directory by default.</p>

<p>However if for some reason you wish to set this manually you can by changing "config.php" (In root directory):</p>

<pre class="code">"LocalDir" => LOCAL_DIR,</pre>

<p>.. to whatever the public containing directories are. Note if this property is non-empty you must preceed it with a forward slash "/":</p>

<pre class="code">"LocalDir" => "/containing-directory",</pre>