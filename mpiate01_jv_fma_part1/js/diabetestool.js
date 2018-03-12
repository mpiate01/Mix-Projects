/*	It returns the value of a checked radio button 

	The function valueRadioSelected returns the value of the radio button given.
	It takes 1 value, nameRadio (id of the radio button).
*/

function valueRadioSelected(nameRadio){
	var radio = document.getElementById(nameRadio);
	var radioValue;
	/*loop starts from 1 and it goes i += 2 because the radio fields are in odd positions*/
	for(var i = 1; i < radio.childNodes.length; i+=2){
	    if(radio.childNodes[i].checked){
	        radioValue = radio.childNodes[i].value;
	    }
	}
	/*parseInt because otherwise it will return a string and we could not do a sum later on*/
	return parseInt(radioValue);
}

/*	The function returns the sum of each answer's value. 

	The function calculateResult sums each answer's value by using
	valueRadioSelected function.
*/

function calculateResult() { 
	var result = valueRadioSelected('oldRadio');
	result += valueRadioSelected('bmiRadio');
	result += valueRadioSelected('famRadio');
	result += valueRadioSelected('dietRadio');
	return result;
}

/*	The function returns a string with high risks factors with a value greater or equal to 10
	
	The function check for high risk factors > or = 10
*/
function highRiskFactors() {
	var output = "Your main risk factors are your ";
	if (valueRadioSelected('oldRadio')>=10) 
	{
		output += "AGE ";
	}
	if (valueRadioSelected('bmiRadio')>=10) 
	{
		output += "BMI";
	}
	if (valueRadioSelected('famRadio')>=10) 
	{
		output += ", FAMILY";
	}
	if (valueRadioSelected('dietRadio')>=10) 
	{
		output += ", DIET";
	}
	output +=". ";
	return output;
}
/*	It returns the message to be shown in the form.
	
	The function sets up 3 different message to be shown in the form.
	It takes the sum of each answer's value.
*/
function showMessage(result) {
	var lowRisk = "Your results show that you currently have a low risk of developing diabetes. However, it is important that you maintain a healthy lifestyle in terms of diet and exercise.";
	var medRisk ='Your results show that you currently have a medium risk of developing diabetes. For more information on your risk factors, and what to do about them, please visit our diabetes advice website at <a href="http://www.zha.org.zd">http://www.zha.org.zd.</a>';
	var highRisk ="Your results show that you currently have a high risk of developing diabetes."; 
	var elemMes = document.getElementById("formMessage");
	var displayMes = document.getElementById("message");
	elemMes.className = "show";
	if (result > 25) 
	{
		highRisk += highRiskFactors();
		highRisk += 'We advise that you contact the Health Authority to discuss your risk factors as soon as you can. Please fill in our <a href="contactform.html">contact form</a> and a member of the Health Authority Diabetes Team will be in contact with you.';
		displayMes.innerHTML= highRisk;
	} 
	else if (result > 16) 
	{
		displayMes.innerHTML= medRisk;
	} 
	else 
	{
		displayMes.innerHTML= lowRisk;
	}
	return false;	
}

function displayResult() { 
	document.getElementById("btncalculate").onclick = validate;
}
function validate() {
		var result = calculateResult();
		showMessage(result);

	}
window.onload =  displayResult;
