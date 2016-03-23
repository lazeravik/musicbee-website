<script type="text/javascript">
function showNotification (msg,type,color) {
	hideNotification ();
	var msgType; //message type will show icon
	if (type=="error"){
		msgType="err_msg";
	}
	else if(type=="success"){
		msgType="scs_msg";
	}
	else {
		msgType="nrml_msg";
	}

	$('body').append("<div class=\"notify fadeInRight animated "+color+" "+msgType+" \" id=\"notification\" ><div class=\"notify_wrap_left\">"+msg+"</div><button class=\"closeNotify\" onclick=\"$('#notification').remove();\"><i class=\"fa fa-times\"></i> </button></div>");
	$('#notification').delay(5000).fadeOut('normal', function() { $(this).remove()});
}

function hideNotification () {
	if ($('#notification').length) {
		$.fx.off = false;
		$('#notification').hide('fast');
		$('#notification').remove();
	}
}



//This function executes when an ajax call is finished or failed.
//accepts json as a parameter, and provide a function callback provided via json object
function notificationCallback(data) {
    var obj = jQuery.parseJSON(data);
    if (obj.status == 0) {
        showNotification(obj.data, "error", "red_color");
    } else if (obj.status == 1) {
        showNotification(obj.data, "success", "green_color");
    }

    //If a callback function is provided, we will call this via notificationFunctionCallback(string func_name) method
    if (obj.callback_function != null) {
    	notificationFunctionCallback(obj.callback_function);
    }

}

//create and call the function by it's name
function notificationFunctionCallback(function_name){
	new Function( "return " + function_name + "()")();
}



</script>