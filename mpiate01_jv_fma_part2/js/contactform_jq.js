/*Custom rules for jquery validation plugin*/
jQuery.validator.addMethod("notNum", function(value, element){
	return this.optional(element) || /^([a-zA-Z]+)$/.test(value);
}, "Numeric characters not allowed");
jQuery.validator.addMethod("notNumHyphon", function(value, element){
	return this.optional(element) || /^([a-zA-Z]+)([\-]?)([a-zA-Z]+)$/.test(value);
}, "Numeric characters not allowed, only a '-' within 2 names");
jQuery.validator.addMethod("zhanumber", function(value, element){
	return this.optional(element) || /^((ZHA)|(zha)){1}[0-9]{6}$/.test(value);
}, "Please enter a valid ZHA number");
jQuery.validator.addMethod("validemail", function(value, element){
	return this.optional(element) || /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
}, "Please enter a valid email address");
jQuery.validator.addMethod("validphone", function(value, element){
	return this.optional(element) || /^[0-9]{11}$/.test(value);
}, "Please enter a valid phone number");

/*Function used to set the focus on the field fname*/
function fnameFocus(){
	$('#fname').focus();
}

/*Function used to show/hide the tip for the zha number*/
function toolTip(){
	$('#qmark')
		.mouseover(function(){
			$('#tip').addClass('show').removeClass('hide');	
		})
		.mouseout(function(){
			$('#tip').addClass('hide').removeClass('show');
		});
}
/*Function used to set a default text in a field*/
function setDefaultTxt()
{
	var idField = "#zha";
	var defaultText = "Enter ZHA or zha 123456.";
	$(idField).val(defaultText);
	$(idField)
		.focus(function() 
		{
	  		if ($(this).val() === defaultText) 
	  		{
	  			this.value = "";
	    		$(idField).addClass('onfocus').removeClass('onblur');	
	  		}
	 	})
	 	.blur(function() 
	 	{
	  		if (this.value === "") 
	  		{
	    		this.value = defaultText;
	    		$(idField).addClass('onblur').removeClass('onfocus');	
	  		}
	 	});
}


$().ready(function(){
	fnameFocus();
	toolTip();
	setDefaultTxt();

	/*jQuery validation plugin*/
	$('#frm1').validate({
		rules: {
			fname: {
				required: true,
				notNum: true,
				minlength: 2
			},
			lname: {
				required: true,
				notNumHyphon: true
			},
			title: "required",
			zha: {
				required: true,
				zhanumber: true
			},
			email: {
				required: true,
				validemail:true,
			},
			tnumber:{
				required: false,
			 	validphone: true
			}
		},
		messages: {
			fname:{
				required:"Please enter your first name",
				minlength:"Your first name must be at least 2 characters long"
			},
			lname: {
				required:"Please enter your last name"
			},
			title : "Please enter a title",
			zha: {
				required:"Please enter your ZHA number"
			},
			email: {
				required:"Please enter your email address"
			}
		}
	});
});



