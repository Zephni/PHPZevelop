jQuery.fn.extend({
	ZSwitch: function(Bool = null, Name = ""){
		return this.each(function(){
			if(Name == "") Name = $(this).attr("name");

			var NewElement = $("<div></div>");
			NewElement.css({"background":"#FFFFFF", "border-radius":"16px", "height":"20px", "width":"60px", "position":"relative", "border":"2px solid #CCCCCC"});
			NewElement.show();

			var SlitElement = $("<div></div>");
			SlitElement.css({"position":"absolute", "background":"#DDDDDD", "width":"76%", "height":"3px", "border-radius":"10px", "top":"50%", "left":"50%", "margin-top":"-1px", "margin-left":"-38%"});

			var ToggleElement = $("<div></div>");
			ToggleElement.css({"background":"#CCCCCC", "width":"16px", "height":"16px", "border-radius":"16px", "position":"absolute", "top":2});

			if(Bool !== true && Bool !== 1 && Bool !== false && Bool !== 0 && Bool !== "true" && Bool !== "false")
				Bool = $(this).attr("value");

			if(Bool == true){
				ToggleElement.css({"background":"#3ce873"});
				ToggleElement.css({"left":3});
				ToggleElement.animate({"left":(NewElement.width() - ToggleElement.width()/2)-11});
			}
			else{
				ToggleElement.css({"background":"#f94d55"});
				ToggleElement.css({"left":(NewElement.width() - ToggleElement.width()/2)-11});
				ToggleElement.animate({"left":3});
			}

			var InputElement = $("<input type='hidden' value='' />");
			InputElement.attr("value", (Bool) ? "1" : "0");
			InputElement.attr("name", Name);

			NewElement.append(SlitElement, ToggleElement, InputElement);

			$(this).replaceWith(NewElement);

			NewElement.click(function(){
				$(this).ZSwitch(!Bool, Name);
			});
		});
	}
});