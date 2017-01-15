<!--suppress JSUnresolvedVariable -->
<script type="text/javascript">
	function showNotification(msg, color, invalid) {
		invalid = typeof invalid !== 'undefined' ? invalid : false;

		//hide any previous notification
		hideNotification();

		$('body').append("<div class=\"notify fadeInRight animated " + color + " \" id=\"notification\" ><div class=\"notify_wrap_left\">" + msg + "</div><button class=\"closeNotify\" onclick=\"$('#notification').remove();\"><i class=\"fa fa-times\"></i> </button></div>");

		//If json data is invalid do not auto hide the notification
		if (!invalid) {
			$('#notification').delay(5000).fadeOut('normal').delay(500, function () {
				hideNotification();
			});
		}
	}

	function hideNotification() {
		if ($('#notification').length) {
			$.fx.off = false;
			$('#notification').fadeOut(500).delay(500).remove();
		}
	}


	//This function executes when an ajax call is finished or failed.
	//accepts json as a parameter, and provide a function callback provided via json object
	function notificationCallback(data) {
		//Check if the json is valid
		var obj = validateJSON(data);

		if (obj != false) {
			//Check the status value and assaign a color for the notification box
			if (obj.status == 0 || obj.status == "error") {
				showNotification(obj.data, "red_color");
			} else if (obj.status == 1 || obj.status == "success") {
				showNotification(obj.data, "green_color");
			} else if (obj.status == 2 || obj.status == "warning") {
				showNotification(obj.data, "yellow_color");
			}

			//If a callback function is provided, we will call this via notificationFunctionCallback(string func_name) method
			if (obj.callback_function != null) {
				notificationFunctionCallback(obj.callback_function);
			}
		} else {
			console.log("Here is the corrupted response: " + data);
			showNotification("<?php echo $lang['json_err_invalid']; ?><br/><textarea id=\"correupted_response\" style=\"display:none\">" + data + "</textarea> <button class=\"btn btn_blacknwhite\" onclick=\"copyError('#correupted_response')\"><?php echo $lang['json_err_copy_btn']; ?></button><p id=\"copy_error\" ></p> ", "red_color", true);
		}
	}

	//create and call the function by it's name
	function notificationFunctionCallback(function_name) {
		new Function("return " + function_name + "()")();
	}


	function validateJSON(jsonString) {
		try {
			var json = jQuery.parseJSON(jsonString);

			// Handle non-exception-throwing cases:
			// Neither JSON.parse(false) or JSON.parse(1234) throw errors, hence the type-checking,
			// but... JSON.parse(null) returns 'null', and typeof null === "object",
			// so we must check for that, too.
			if (json && typeof json === "object" && json !== null) {
				return json;
			}
		}
		catch (e) {}

		return false;
	}

	function copyError(element) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();

		$("#copy_error").text("<?php echo $lang['json_err_copied']; ?>");
	}
</script>