<p>Using PHPZevelop you can pass variables through the URL in two different ways.</p>

<h4>Option 1 - Pretty URL's</h4>

<p>Imagine you have a page that exists in the following location:</p>

<pre class="code">/site/articles/search.php</pre>

<p>You can access this by going to:</p>

<pre class="code">/articles/search</pre>

<p>PHPZevelop will pass back that page. But if you pass extra "/items" to that URL they will act as parameters to that page. Note that if a page exists at that location
it will choose that instead.</p>

<p>By default the PassParams option in "/config.php" will disable this functionality but can be turned on by setting it to true on individual pages like this one, or it can be set
to true by default. for more information click <a href="#UndefinedUrls">here</a>.</p>

<p>When using this option, you will notice at the bottom of the page that $_GET contains the parameters passed through the URL. By default they will be indexed as "param_0", "param_1"
etc, but this can be changed in the global file by changing $prependParam. If $prependParam is an empty string the parameters will be indexed as a plain integer instead of text.</p>

<p>Example, a page exists here:</p>

<pre class="code">/site/page/test.php</pre>

<p>By going to the following URL:</p>

<pre class="code">/page/test/someinfo/somemoreinfo</pre>

<p>The test.php would be able to access all of the passed data like so:</p>

<pre class="code">// $_GET["param_0"] is equal to "someinfo"<br />// $_GET["param_1"] is equal to "somemoreinfo"</pre>


<h4>Option 2 - Standard format</h4>
<p>articles/search?var1=5&var2=test</p>

<p>After the "path" has been passed through the URL you can append variables using the pattern:</p>

<pre class="code">?name=value</pre>

<p>For multiple variables use:</p>

<pre class="code">?name1=value1&amp;name2=value2</pre>

<p>You can then retrieve "value" by using $_GET["name"] inside your pages.</p>