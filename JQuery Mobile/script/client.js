$( document ).ready(function() {
	$('body').on('click','.favorites', function() {
		var dotoggle = $(this).attr("dotoggle");
		if ( dotoggle == "1"  ) {
			$(this).attr("dotoggle","0");
			$(this).addClass('plain');
			$(this).removeClass('color-toggled');
		 }
		 else {
			$(this).attr("dotoggle","1");
			$(this).addClass('color-toggled');
			$(this).removeClass('plain');
		}
	});
});	