<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Home"
	));
?>

<h2>Home</h2>
<p><?php $Link->Out("https://github.com/Zephni/PHPZevelop", "PHPZevelop"); ?> is a PHP framework for ease of use and adaptability. I am building it for personal use -  
but if anybody wishes to fork it please feel free! Any comments and ideas are welcome, you can also email me on: contact at zephni.com</p>

<b>My ethos is to create a platform that provides an intuitive website structure that is well organised and quick to develop upon.
It should always use standard PHP and HTML and be fully adaptable without fuss.</b>

<p>Downloading the framework and running on a PHP webserver (Whether windows or unix) by default will display this help site for explaining how to use it.</p>

<div id="disqus_thread"></div>
<script>

var disqus_config = function () {
this.page.url = "http://zephni.com/phpzevelop"; // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = "support_page"; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};

(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');

s.src = '//phpzevelop.disqus.com/embed.js';

s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>