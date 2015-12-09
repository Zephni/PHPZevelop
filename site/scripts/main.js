$(document).ready(function(){
	// Common Javascript

	$("#nav.mobile-only .expandable").hide();

	$("#nav .head").click(function(){
		if($(this).attr("expanded") != "true")
		{

			$(this).next().slideDown();
			$(this).attr("expanded", "true");
		}
		else
		{
			$(this).next().slideUp();
			$(this).attr("expanded", "false");
		}
	});
});