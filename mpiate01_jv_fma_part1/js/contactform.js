/*Empty field control*/
/*It takes the id of the field. It checks if the field is empty
and it return a boolean value of true or false*/
function isEmpty(idField) 
{
	var elem = document.getElementById(idField);
	var isEmpty = false;
	if (elem.value === "")
	{
		isEmpty = true;
	}
	return isEmpty;
}

/*Show errors and Hide errors*/
/*It takes the id of the field and it change the class of the releted error span field*/
function showErrors(idField) 
{
		var elemErrFieldName = idField + "Err";
		var elemErr = document.getElementById(elemErrFieldName);
		elemErr.className = "error show";
}

function hideErrors(idField) 
{
		var elemErrFieldName = idField + "Err";
		var elemErr = document.getElementById(elemErrFieldName);
		elemErr.className = "error hide";
}

/*First name validation*/
/*The function is used to validate the field fname, it checks if the field is empty 
and if it follows the regular expression given
It return false if there are errors*/
function fnameValidation() 
{
	var allowsubmit = true;
	var fnameRegEx = /^([a-zA-Z]+){2}$/;
	var idField = "fname";
	var elem = document.getElementById(idField).value;
	if ((isEmpty(idField))||(!fnameRegEx.test(elem)))
	{
		showErrors(idField);
		allowsubmit = false;
	} 
	else
	{
		hideErrors(idField);
	}
	return allowsubmit;
}

/*Last name validation*/
function lnameValidation() 
{
	var allowsubmit = true;
	/*only 1 hyphen allowed*/
	var lnameRegEx = /^([a-zA-Z]+)([\-]?)([a-zA-Z]+)$/;
	var idField = "lname";
	var elem = document.getElementById(idField).value;
	if (isEmpty(idField)||(!lnameRegEx.test(elem)))
	{
		showErrors(idField);
		allowsubmit = false;
	} 
	else
	{
		hideErrors(idField);
	}
	return allowsubmit;
}

/*Title validation*/
function titleValidation() {
	var allowsubmit = true;
	var idField = "title";
	if (isEmpty(idField))
	{
		showErrors(idField);
		allowsubmit = false;
	} 
	else
	{
		hideErrors(idField);
	}
	return allowsubmit;
}

/*ZHA validation*/
function zhaValidation() {
	var allowsubmit = true;
	/*ZHA or zha */
	var zhaRegEx = /^((ZHA)|(zha)){1}[0-9]{6}$/;
	var idField = "zha";
	var elem = document.getElementById(idField).value;
	if (isEmpty(idField)||(!zhaRegEx.test(elem)))
	{
		showErrors(idField);
		allowsubmit = false;
	} 
	else
	{
		hideErrors(idField);
	}
	return allowsubmit;
}

/*email validation*/
function emailValidation() 
{
	var allowsubmit = true;
	/*Website reference for email regex
	http://www.w3resource.com/javascript/form/email-validation.php*/
	var emailRegEx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	var idField = "email";
	var elem = document.getElementById(idField).value;
	if (isEmpty(idField)||(!emailRegEx.test(elem)))
	{
		showErrors(idField);
		allowsubmit = false;
	} 
	else
	{
		hideErrors(idField);
	}
	return allowsubmit;
}

/*telephone number validation*/
function phoneValidation() 
{
	var allowsubmit = true;
	var phoneRegEx = /^[0-9]{11}$/;
	var idField = "tnumber";
	var elem = document.getElementById(idField).value;
	if(!isEmpty(idField))
	{	
		if (!phoneRegEx.test(elem))
		{
			showErrors(idField);
			allowsubmit = false;
		} 
		else
		{
			hideErrors(idField);
		}
	}
	else
	{
		hideErrors(idField);
	}
	return allowsubmit;
}

/*Set focus to fname*/
function setFocus()
{
	var idFieldFocus = "fname";
	var txtBox = document.getElementById(idFieldFocus);
	txtBox.focus();
}

/*Set a default text*/
function setDefaultTxt()
{
	var idField = "zha";
	var defaultText = "Enter ZHA/zha 123456.";
	var txtElem = document.getElementById(idField);
	txtElem.value = defaultText;
	txtElem.onfocus = function() 
	{
  		if (this.value === defaultText) 
  		{
    		this.value = "";
    		this.className = "onfocus";
  		}
 	};
 	txtElem.onblur = function() 
 	{
  		if (isEmpty(idField)) 
  		{
    		this.value = defaultText;
    		this.className = "onblur";
  		}
 	};

}

/*Tool tip function mouseover - mouseout*/
function toolTip() 
{    
	var toolTip = document.getElementById('tip');      
  	document.getElementById('qmark').onmouseover = function() 
  	{
  		toolTip.className='tooltip show';
  	} 	    
  	document.getElementById('qmark').onmouseout = function() 
  	{
  		toolTip.className='tooltip hide';
  	} 	
}	

/*The function starts the validation of each field
	and it return a boolean value of true if everything is fine*/
function sumValidations()
{
	var allowsubmit = false;
	var fname = fnameValidation();
	var lname = lnameValidation();
	var title = titleValidation();
	var zha = zhaValidation();
	var email = emailValidation();
	var tnumber = phoneValidation();
	if (fname && lname && title && zha && email && tnumber) 
	{
		allowsubmit = true;
	}
	return allowsubmit;
}

/*this function re-groups all the tools used*/
function formTools() 
{
	toolTip();
	setDefaultTxt();
	setFocus();
}

function checkForm() 
{   
	formTools();        
  	document.getElementById("frm1").onsubmit = function()
  	{
  		return sumValidations();
	} 
}	

window.onload=checkForm;























