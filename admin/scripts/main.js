$(document).ready(function(){
	// Common Javascript
	SetImagePreview(".ImageSelector", ".PreviewImage");

	/*
		POPUP MESSAGES
	*/
	SetupPopupMessages();
	$(".popupMessageBox .head span").click(function(){
		$(".popupBackground").fadeOut(300);
	});
});

function ConfirmDelete() {
	var r = confirm("Are you sure you want to delete?");
	if (r == true)
		return true;
	else
		return false;

}

function SetupPopupMessages()
{
	$("head").append('<style type="text/css">.popupBackground {background: rgba(30, 30, 30, 0.5); width: 100%; height: 100%; position: fixed; z-index: 10; text-align: center; display: none;}.popupMessageBox {background: #EFEBDE; width: auto; position: relative; display: inline-block; z-index: 11; border: 2px solid #222C31; vertical-align: middle; border-radius: 10px; margin: 20px;}.popupMessageBox .head {padding: 7px 10px 7px 10px; background: #222C31; border-bottom: 2px solid #222C31; text-align: left; border-radius: 5px 5px 0 0; color: white;}.popupMessageBox .head span.closeButton {position: absolute; right: 2px; top: 2px; border: 2px solid #FFFFFF; border-radius: 6px; padding: 0px 9px 4px 9px; line-height: 22px;}.popupMessageBox .head span.closeButton:hover {cursor: pointer;}.popupMessageBox .body {padding: 25px; text-align: center; color: #333333;}</style>');
	$("body").prepend('<table class="popupBackground"><tr><td><div class="popupMessageBox"><div class="head"><span class="headMsg"></span> <span class="closeButton">x</span></div><div class="body"></div></div></td></tr></table>');
}

function Popup(Title, Message)
{
	$(".popupBackground .headMsg").html(Title);
	$(".popupBackground .body").html(Message);
	$(".popupBackground").fadeIn(300);
}

/*
	Set
*/
function SetImagePreview(InputSelector, ImageSelector, ImageRejig)
{
	$(InputSelector).change(function(){
		if(this.files && this.files[0])
	    {
	        var reader = new FileReader();
	        reader.onload = function(e){
	            $(ImageSelector).attr('src', e.target.result);

	            if(ImageRejig !== undefined)
	            	ImageRejig($(ImageSelector));
	        }
	        reader.readAsDataURL(this.files[0]);
	    }
	});

	$(window).resize(function(){
		if(ImageRejig !== undefined)
			ImageRejig($(ImageSelector));
	});
}