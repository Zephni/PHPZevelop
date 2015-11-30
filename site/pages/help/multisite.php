<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Meta data");
?>
<h2>MultiSite</h2>

<p>Just as the main site has been placed in the "site/" directory, you can arrange multiple sites based off the same configuration.
To add/remove these sites you can manage them by adapting the MultiSite property when istantiating the config object in "/config.php":</p>

<pre class="code" style="font-size: 12px;">
	"MultiSite" => array("admin", "site2", "site3");
</pre>

<p>The above example means that if "/admin" is the first part of the path (after the domain) PHPZevelop will look inside the "admin" directory
for a site to load rather than the standard "/site" path. If you require an alias for the path you can use the below solution, note that the
key MUST be a string and not an integer.</p>

<pre class="code" style="font-size: 12px;">
	"MultiSite" => array(
		"example1" => "directory",
		"example2" => "directory1/directory2"
	);
</pre>

<p>The number of MultiSites is limitless, the key (eg. "example1" above) is the path you will need to append to your domain to access the MultiSite,
where as the value ("directory" above) is the directory that the site is contained in (just like the standard "site" directory).</p>

<p>A MultiSite can be placed as deep in the directory structure as needed, or just in the root, whatever suits your arrangement best.</p>

<h3>Important note for numeric multisites</h3>

<p>If you are not specifying the keys seperately and wish to contain your MultiSite directory with a numeric name eg. "1" this will work, eg:</p>

<pre class="code" style="font-size: 12px;">
	"MultiSite" => array("admin", "1", "2");
</pre>

<p>However if you want to specify a MultiSite alias as a number then you will need to preceed it with a forward slash "/" eg:</p>

<pre class="code" style="font-size: 12px;">
	"MultiSite" => array(
		"admin" => "admin",
		"/1" => "directory1",
		"/2" => "directory1/directory2"
	);
</pre>

<p>This is necessary because PHP considers strings that represent integers AS integers, so it cannot properly tell the difference between the two,
this is why PHPZevelop uses the forward slash to tell it that this is an alias and not an automatically generated numeric key.</p>