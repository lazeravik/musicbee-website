
$secondery_nav_break = 700;
var $nav_bar = $("#secondery_nav");

function secondery_nav_sticky() {
	if (window.innerWidth < $secondery_nav_break) {
		$nav_bar.unstick();
	} else {
		$nav_bar.unstick();
		$nav_bar.sticky({topSpacing: 0});
	}
}

$(window).resize(function(e) {
	secondery_nav_sticky();
});

$(document).ready(function(){
	secondery_nav_sticky();
});

function expand_second_menu() {
	var $second_nav = $('#secondery_nav > .secondery_nav_wrap');

	if ($second_nav.hasClass('expanded')) {
		$second_nav.removeClass('expanded');
	} else {
		$second_nav.addClass('expanded');
	}
}