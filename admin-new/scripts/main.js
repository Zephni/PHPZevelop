$(document).ready(function(){
	// Common Javascript
	SetImagePreview(".ImageSelector", ".PreviewImage");
});

$(function() {
    $('.confirm').click(function(e) {
        e.preventDefault();
        if (window.confirm("Are you sure?")) {
            location.href = this.href;
        }
    });
});

/*
	SetImagePreview
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