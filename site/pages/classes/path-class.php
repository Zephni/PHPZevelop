<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Path class"
	));
?>

<h2>Path class</h2>

<p>The Path class is for getting a full path to a specified file.</p>

<h3>Properties</h3>

<pre class="code">
object $CFG is the already defined $PHPZevelop->CFG object (This is required in the Path constructor)
</pre>

<h3>Methods</h3>

<p><span style="color: green;">GetClass(string $string, bool $return = true)</span>: Returns / Echos $PHPZevelop->CFG->RootDirs->Classes."/".$string</p>

<p><span style="color: green;">GetInc(string $string, bool $return = true)</span>: Returns / Echos $PHPZevelop->CFG->RootDirs->Inc."/".$string</p>

<p><span style="color: green;">GetImage(string $string, bool $return = false)</span>: Echos / Returns $PHPZevelop->CFG->LocalDirs->Images."/".$string</p>

<p><span style="color: green;">GetPage(string $string, bool $return = false)</span>: Echos / Returns $PHPZevelop->CFG->LocalDir."/".$string</p>

<p><span style="color: green;">GetPageRoot(string $string, bool $return = true)</span>: Returns / Echos $PHPZevelop->CFG->RootDirs->Pages."/".$string</p>

<p><span style="color: green;">GetScript(string $string, bool $return = false)</span>: Echos / Returns $PHPZevelop->CFG->LocalDirs->Scripts."/".$string</p>

<p><span style="color: green;">GetCSS(string $string, bool $return = false)</span>: Echos / Returns $PHPZevelop->CFG->LocalDirs->CSS."/".$string</p>

<h3>Examples</h3>

<p>To get the local URL for a page in pages/ you could use: </p>

<pre class="code">
$PHPZevelop->Path->GetPage("home");
</pre>

<p>If the page was multiple levels deep you can use: </p>

<pre class="code">
$PHPZevelop->Path->GetPage("articles/article-name");
</pre>

<p>Note the second parameter of each method has a $return boolean that determines whether the method will "return" the value or "echo" the value. 
If you would like the value to be stored or used within a PHP script then you would pass "true" as the second parameter if "true" isn't the default (shown above):</p>

<pre class="code">
$PHPZevelop->Path->GetPage("articles/article-name", true);
</pre>

<p>Where-as if you need the path string to be echo'ed directly to the page you would pass false as the second parameter, or leave blank in cases where $return is false by default: </p>

<pre class="code">
&lt;a href="&lt;?php $PHPZevelop->Path->GetPage("article"); ?&gt;"&gt;Link text&lt;/a&gt;
</pre>

<h3>Extending path class</h3>

<p>You may want to extend the path class for your own usage, in this case put your path class file inside the "site/classes/" directory and use the following syntax: </p>

<pre class="code">
class MyPath extends Path
{
	// Methods
}
</pre>

<p>You can then overwrite the $PHPZevelop Path object by calling: </p>

<pre class="code">
$PHPZevelop->NewObject("Path", new MyPath($PHPZevelop->CFG));
</pre>

.. In the site/instantiate.php file.