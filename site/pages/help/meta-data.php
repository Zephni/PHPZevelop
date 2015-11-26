<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Meta data");
?>
<h2>Meta data</h2>

<p>To change the default meta data for the framework you can change the constant values in "inc/header.php" on lines 4-7:</p>

<pre class="code" style="font-size: 12px;">
/* Defaults if not defined
------------------------------*/
if($PHPZevelop->Get("PAGE_TITLE")	== null)	$PHPZevelop->Set("PAGE_TITLE", "");
if($PHPZevelop->Get("META_TITLE")	== null)	$PHPZevelop->Set("META_TITLE", "PHPZevelop PHP FrameWork");
if($PHPZevelop->Get("META_DESCRIPTION")	== null)	$PHPZevelop->Set("META_DESCRIPTION", "PHP framework for ease of use and adaptability");
if($PHPZevelop->Get("META_KEYWORDS")	== null)	$PHPZevelop->Set("META_KEYWORDS", "PHP, Framework, Zephni");
</pre>

<p>These definitions can be placed in individual pages so the defaults can be overwritten, for example at the top of this file it uses:</p>

<pre class="code">
&lt;?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Meta data");
?&gt;
</pre>

<p>.. so as you can see the title of this page (or tab) is now the site title (Which is set in "global.php") followed by a " - " and then the $PHPZevelop->PAGE_TITLE.
If no page title is passed and the default page title is set to an empty string, only the site title will appear. If you wish to override any of the way the title appears, 
it can by edited manually on line 15 of "inc/header.php":</p>

<pre class="code">
&lt;title&gt;&lt;?php if($PHPZevelop-&gt;Get(&quot;PAGE_TITLE&quot;) != &quot;&quot;) echo $PHPZevelop-&gt;CFG-&gt;SiteTitle.&quot; - &quot;.$PHPZevelop-&gt;Get(&quot;PAGE_TITLE&quot;); else echo $PHPZevelop-&gt;CFG-&gt;SiteTitle; ?&gt;&lt;/title&gt;
</pre>