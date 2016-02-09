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

	$('#main_panel').append("<div class=\"notify "+color+" "+msgType+" \" id=\"notification\" style=\"display:block\"><div class=\"notify_wrap_left\">"+msg+"</div><div class=\"notify_wrap_right\"><button class=\"closeNotify\" onclick=\"$(this).parent().parent().remove();\"><i class=\"fa fa-times\"></i> </button></div><div id=\"clear\"></div></div>");
}

function hideNotification () {
	if ($('#notification').length) {
		$.fx.off = false;
		$('#notification').hide('fast');
		$('#notification').remove();
	}
}

</script>