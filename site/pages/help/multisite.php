<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Meta data");
?>
<h2>MultiSite</h2>

<p>Just as the main site has been placed in the "site/" directory, you can arrange multiple sites based off the same configuration.
To add/remove these sites you can manage them by adapting the MultiSite property attached to the $CFG array in "/config.php":</p>

<pre class="code" style="font-size: 12px;">
	"MultiSite" => array("site2", "admin")
</pre>

<p>Using the above example, going to "/site2" in the URL will load up the site belonging to that directory, same with "/admin".</p>

<p>To change the default site you can simple change the Site property attached to the $CFG array in "/config.php".</p>