/*
 * OFFLINE LOGIN SCREEN FUNCTIONS AND EVENT HANDLERS
 */
 

/**
 * Sends a login request through ajax
 *
 */

function loginRequest()
{
	var handle=$('#off_handle').val();
	var password=$('#off_password').val();
	
	if(handle=='' || password==''){
		jgrowl_messages('All fields are required!','error',false); //This function is defined in basicFunctions.js
		return false;
	}
	
	//Send timeout notification to server
	$.ajax({
	type: "POST",
 	dataType:'json',
 	url: "offLineSuLogin.php",
	data: "a=off_login&handle="+handle+"&password="+password,
 	success: loginResponse,
  	error: alertError
	});
}

/**
 * Handle login request responses from the server
 *
 */

function loginResponse(response)
{
	//alert(response);return;
	if(response.error)
	{
		jgrowl_messages(response.error,'error',false);
		return;
	}
	if(response.redirect_url)
	{
		window.location.href=response.redirect_url;
	}
}

//Alert of ajax error
function alertError(e,w,f)
{
	alert('There was an error communicating to the server');
			//alert(w)
}