<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Link class"
	));
?>

<h2>Link class</h2>

<p>The link class is for shortening and speeding up code when dealing with links. It exists in the "site/classes" directory as 
"class.link.php" and is instantiated in "site/instantiate.php".</p>

<p>Example, to link to this page you would use: </p>

<pre class="code">
$Link->Out("classes/link-class.php");
</pre>

<p>This would echo the following HTML: </p>

<pre class="code">
<?php echo htmlentities("<a href='/classes/link-class.php'>/classes/link-class.php</a>") ?>
</pre>

<p>If PHPZevelop is stored in a sub folder or you are using a MultiSite the returned URL would automatically be correctly assigned.</p>

<p>If you wish to change the link text you can pass a second parameter: </p>

<pre class="code">
$Link->Out("classes/link-class.php", "Link text");
</pre>

<p>If you want to link to an external site make sure to include "http://" or "https://" at the beginning of the URL so the class knows
you are trying to link externally.</p>

<p>To add attributes to the link class other than "href" you can pass an options array with the key=>values representing the attribute
keys and values. If you do not pass a string as the second parameter and pass an array instead, it will keep the first string parameter
as the link text and use the array as the attributes list.</p>

<p>Finally by using $Link->Get instead of $Link->Out, the Link class will return the value rather than echo it out.</p>