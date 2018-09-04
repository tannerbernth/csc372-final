//Input username
var regUsername = document.getElementById("username");
if (regUsername != null) {
	var rUError = document.getElementById("userLength");
	var userMessage = "Please enter a username!";
	regUsername.addEventListener("input",function(){checkInput(this,rUError,1)});
	regUsername.addEventListener("blur",function(){checkBlur(this,rUError,1,userMessage)});
//Input password
	var regPassword = document.getElementById("password");
	var rPError = document.getElementById("passLength");
	var passMinLength = 8; //Change Password Length requirement
	var passMessage = "Password must be " + passMinLength + " characters or longer!";
	regPassword.addEventListener("input",function(){checkInput(this,rPError,passMinLength);});
	regPassword.addEventListener("blur",function(){checkBlur(this,rPError,passMinLength,passMessage)});
//Input confirm password
	var regConfirmPass = document.getElementById("confirmPassword");
	var rCPError = document.getElementById("confirmPassLength");
	var cPassMessage = "Passwords do not match!";
	regConfirmPass.addEventListener("input",function(){
		matchFieldInput(this,rCPError,regPassword.value.length,regPassword,passMinLength)});
	regConfirmPass.addEventListener("blur",function(){
		if (regPassword.value.length >= passMinLength) {matchFieldBlur(this,rCPError,regPassword.value.length,regPassword,cPassMessage)}
		else {matchFieldBlur(this,rCPError,passMinLength,regPassword,passMessage)}});
//Submit - Register
	var submit = document.getElementById("newMember");
	var sError = document.getElementById("unfilled");
	sError.style.display = "none";
	var submitMessage = "All fields must be filled!";
	submit.addEventListener("submit",function(event){
	if(event.preventDefault) event.preventDefault();
	checkAll(sError,submitMessage,passMinLength,submit,event);});
} else {
//Input password
	var regPassword = document.getElementById("password");
	var rPError = document.getElementById("passLength");
	var passMinLength = 8; //Change Password Length requirement
	var passMessage = "Password must be " + passMinLength + " characters or longer!";
	regPassword.addEventListener("input",function(){checkInput(this,rPError,passMinLength);});
	regPassword.addEventListener("blur",function(){checkBlur(this,rPError,passMinLength,passMessage)});
//Input new password
	var regConfirmPass = document.getElementById("confirmPassword");
	var rCPError = document.getElementById("confirmPassLength");
	regConfirmPass.addEventListener("input",function(){checkInput(this,rPError,passMinLength);});
	regConfirmPass.addEventListener("blur",function(){checkBlur(this,rCPError,passMinLength,passMessage)});
}
//Colors
var lightblue = "rgba(179,237,255,1)";
var lightorange = "rgba(255,215,179,1)";

/*-----------------------------------------------
 * function checkInput
 *-----------------------------------------------
 * Checks the user input as it is typed and 
 * changes the input field background color
 * depending on the length of the input. The 
 * error message is hidden if the input is longer
 * than the specified minLength.
 *----------------------------------------------*/
function checkInput(field,errorField,minLength) {
	if (field.value.length >= minLength) {
		errorField.style.display = "none";
		field.style.background = lightblue;
	} else {
		field.style.background = lightorange;
	}
}

/*-----------------------------------------------
 * function checkBlur
 *-----------------------------------------------
 * Checks the user input when focus of the input
 * field is lost. changes the input field
 * background color depending on the length of 
 * the input. The error message is hidden if the
 * input is longer than the specified minLength.
 *----------------------------------------------*/
function checkBlur(field,errorField,minLength,message) {
	if (field.value.length < minLength) {
		errorField.style.display = "block";
		errorField.innerHTML = '<div class="arrowLeft"></div>'+ message;
		field.style.background = lightorange;
	} else {
		errorField.style.display = "none";
		field.style.background = lightblue;
	}
}

/*-----------------------------------------------
 * function matchFieldInput
 *-----------------------------------------------
 * Similar to checkInput except this function
 * also checks if two input fields are matching. 
 *----------------------------------------------*/
function matchFieldInput(field,errorField,minLength,compareField,requiredLength) {
	if (field.value.length >= minLength && field.value == compareField.value && minLength >= requiredLength && field.value.length > 0) {
		errorField.style.display = "none";
		field.style.background = lightblue;
	} else { 
		field.style.background = lightorange;
	}
}

/*-----------------------------------------------
 * function matchFieldBlur
 *-----------------------------------------------
 * Similar to checkBlur except this function
 * also checks if two input fields are matching. 
 *----------------------------------------------*/
function matchFieldBlur(field,errorField,minLength,compareField,message) {
	if (field.value.length < minLength || field.value != compareField.value) {
		errorField.style.display = "block";
		errorField.innerHTML = '<div class="arrowLeft"></div>'+ message;
		field.style.background = lightorange;
	} else {
		errorField.style.display = "none";
		field.style.background = lightblue;
	}
}

/*-----------------------------------------------
 * function checkAll
 *-----------------------------------------------
 * Checks that all fields have been filled out. 
 * An error message is displayed if all fields
 * have not been filled out.  
 *----------------------------------------------*/
function checkAll(errorField,message,requiredLength,form,event) {
	if (regUsername.value.length < 1 || regPassword.value.length < requiredLength || regConfirmPass.value != regPassword.value) {
		errorField.innerHTML = message;
		errorField.style.display = "block";
		rCPError.style.display = "none";
		rPError.style.display = "none";
		rUError.style.display = "none";
		event.returnValue = false;
	} else {
		event.returnValue = true;
	}
}