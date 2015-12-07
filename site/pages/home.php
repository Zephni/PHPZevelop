<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Home"
	));
?>

<h2>Home</h2>
<p><a href="https://github.com/Zephni/PHPZevelop">PHPZevelop</a> is a PHP framework for ease of use and adaptability. I am building it for personal use -  
but if anybody wishes to fork it please feel free! Any comments and ideas are welcome, you can also email me on: contact at zephni.com</p>

<div id="disqus_thread"></div>
<script>
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');

s.src = '//phpzevelop.disqus.com/embed.js';

s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>

<b><br />My ethos is to create a platform that provides an intuitive website structure that is well organised and quick to develop upon.
It should always use standard PHP and HTML and be fully adaptable without fuss.</b>

<p>Downloading the framework and running on a PHP webserver (Whether windows or unix) by default will display this help site for explaining how to use it.</p>

<p>The file structure for PHPZevelop is as follows: </p>

<pre class="code">
site/
phpzevelop/
.htaccess
config.php
index.php
</pre>

<p><span style="color: green;">site/</span>: For all the files regarding your website</p>

<p><span style="color: green;">phpzevelop/</span>: Contains dependant PHPZevelop files</p>

<p><span style="color: green;">.htaccess</span>: Is a set of rules for the working of the framework</p>

<p><span style="color: green;">config.php</span>: Main configuration</p>

<p><span style="color: green;">index.php</span>: Brings together the framework for usage</p>