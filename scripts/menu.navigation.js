
$secondery_nav_break = 701;
var $nav_bar = $("#secondery_nav");
var cached_menu_height;
var menu_elem = $("#main_menu");

$(window).resize(function() {
	primary_menu_dropdown();

	if($nav_bar.length > 0)
		secondery_nav_sticky();
});

$(document).ready(function(){
	primary_menu_dropdown();

	if($nav_bar.length > 0)
		secondery_nav_sticky();
});


function primary_menu_dropdown(){
	if(cached_menu_height != menu_elem.height()) {
		cached_menu_height = menu_elem.height();
		$(".primary_submenu").each(function (e) {
			$(this).css("top", cached_menu_height);
		});
	}
}

function secondery_nav_sticky() {
	if (window.innerWidth < $secondery_nav_break) {
		$nav_bar.unstick();
	} else {
		$nav_bar.unstick();
		$nav_bar.sticky({topSpacing: 0});
	}
}

function expand_second_menu() {
	var $second_nav = $('#secondery_nav > .secondery_nav_wrap');

	if ($second_nav.hasClass('expanded')) {
		$second_nav.removeClass('expanded');
	} else {
		$second_nav.addClass('expanded');
	}
}