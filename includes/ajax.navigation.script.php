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
		loadPageGet(generateUrl ($dataUrl.attr('data-load-page')), (!! $dataUrl.attr('data-get-req'))? $dataUrl.attr('data-get-req'): "");
	} else {
		//loads default starting page
		loadPageGet(generateUrl ($('a[href="#overview"]').attr('data-load-page')), "");
	}	
});

/* 	Execute if any secondery nav button is pressed gets the 'data-href' to change the addressbar */
$('a[data-load-page]').on('click', function(e) {
	e.preventDefault();
	loadPageGet(generateUrl($(this).attr('data-load-page')), (!! $(this).attr('data-get-req'))? $(this).attr('data-get-req'): "");
	window.location.hash = $(this).attr('data-href');
});

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
		type: "GET",
	}).done(function(data) {
		$('#ajax_area').html(data); //replace the previous page data with the loaded one
		gotoTop(); //when new  page load complete go to top
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

function gotoTop() {
	$.fx.off = false;
	$("html, body").animate({ scrollTop: 0 }, "fast");
	$.fx.off = true;
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