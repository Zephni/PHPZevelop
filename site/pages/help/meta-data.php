<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "MetaData"
	));
?>
<h2>Meta data</h2>

<p>To change the default meta data for the framework you can change the values in "inc/header.php" on lines 4-7:</p>

<pre class="code" style="font-size: 12px;">
/* Defaults if not defined
------------------------------*/
if(!isset($PHPZevelop->CFG->PageTitle))	      $PHPZevelop->CFG->PageTitle	= "";
if(!isset($PHPZevelop->CFG->MetaTitle))	      $PHPZevelop->CFG->MetaTitle	= "PHPZevelop PHP FrameWork";
if(!isset($PHPZevelop->CFG->MetaDescription)) $PHPZevelop->CFG->MetaDescription	= "PHP framework for ease of use and adaptability";
if(!isset($PHPZevelop->CFG->MetaKeywords))    $PHPZevelop->CFG->MetaKeywords	= "PHP, Framework, Zephni";
</pre>

<p>These definitions can be placed in individual pages so the defaults can be overwritten, for example at the top of this file it uses:</p>

<pre class="code">
&lt;?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "MetaData"
	));
?&gt;
</pre>

<p>.. so as you can see the title of this page is now the site title (Which is set in "global.php") followed by a " - " and then the $PHPZevelop->CFG->PageTitle.
If no page title is passed and the default page title is set to an empty string, only the site title will appear. If you wish to override any of the way the title appears, 
it can by edited manually on line 16 of "inc/header.php":</p>

<pre class="code">
&lt;title&gt;&lt;?php
echo $PHPZevelop-&gt;CFG-&gt;SiteTitle.($PHPZevelop-&gt;CFG-&gt;PageTitle != &quot;&quot; ? &quot; - &quot;.$PHPZevelop-&gt;CFG-&gt;PageTitle : &quot;&quot;);
?&gt;&lt;/title&gt;
</pre>