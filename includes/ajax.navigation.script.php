<script type="text/javascript">
<?php 
/** 
* PHP is used for commenting this tuff. We don't want external user to know what thing does
* To use this navigation system, the page must have a top nav bar. Since the system is built exclusively for it 
**/ 
 //sticky secondery navbar ?>
$("#secondery_nav").sticky({ topSpacing: 0 });

<?php
//If the document is loaded we want to check if any hash is available in the url. and redirect user to those url
//otherwise load the default page ?>
$(document).ready(function(){
	if (window.location.hash) {
		$dataUrl = $('a[href="' + window.location.hash + '"]');
		$generatedUrl = generateUrl ($dataUrl.attr('data-load-page'));
		if (!!$dataUrl.attr('data-get-req')) {
			loadPageGet($generatedUrl, $dataUrl.attr('data-get-req'))
		}else{
			loadPageGet($generatedUrl, "");
		}
	} else {
		loadDefaultPage ();
	}
	//checks if the url hash change then loads page with the hash data
	$(window).on('hashchange', function() {
		if (window.location.hash) {
			$dataUrl = $('a[href="' + window.location.hash + '"]');
			$generatedUrl = generateUrl ($dataUrl.attr('data-load-page'));
			loadPageGet($generatedUrl, (!!$dataUrl.attr('data-get-req'))?$dataUrl.attr('data-get-req'): "");
			} else {
			loadDefaultPage ();
		}
	});
	/* 	Execute if any secondery nav button is pressed
	gets the 'data-href' to change the addressbar */
	$('#nav').delegate('a', 'click', function() {
		hideNotification ();
		window.location.hash = $(this).attr('data-href');
		return false;
	});
});


function loadDefaultPage () {
	$generatedUrl = generateUrl ($('a[href="#overview"]').attr('data-load-page'));
	loadPageGet($generatedUrl, "");
}

/* 	Takes 'url' parameter and loads the page into the container,
when a request is made the loading spinner shows and hide if the request is OK
or Failed */
function loadPageGet (reqUrl, getReq) {
	$.fx.off = true; // turn off jquery animation effects
	$('#loading_icon').show(); //show loading icon'
	showHideOverlay(); //show overlay while loading
	$.ajax({
    url: reqUrl+"?"+getReq,
    cache: false,
    type: "POST",
	}).done(function(data) {
		$('#ajax_area').html(data);
		$.fx.off = false;
		$("html, body").animate({ scrollTop: 0 }, "fast");
		$.fx.off = true;
	}).fail(function( jqXHR, textStatus, errorThrown )  {
		showNotification("<b style=\"text-transform: uppercase;\">"+textStatus+"</b> - "+errorThrown, "error", "red_color");
	}).always(function() {
		$('#loading_icon').hide();
		showHideOverlay(); //hide overlay while loading
	});
}
//generates Url used to get files with ajax request
function generateUrl (data) {
	$subDir = "<?php $_SERVER['DOCUMENT_ROOT']; ?>/includes/";
	$ext = ".template.php";
	return $subDir + data + $ext;
}

function showHideOverlay () {
	if ($('#disable_overlay').length) {
		$('#disable_overlay').remove();
	} else {
		$('#main_panel').append("<div id=\"disable_overlay\" style=\"display:block;\"></div>")
	}
}

</script>

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/system.notification.script.php'; ?>