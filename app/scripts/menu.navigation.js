
$secondery_nav_break = 701;
$primary_menu_break = 731;
var $nav_bar = $("#secondery_nav");
var cached_menu_height;
var menu_elem = $("#main_menu");
var $primary_nav = $('#main_menu > .menu_wrapper');

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
    if (window.innerWidth < $primary_menu_break) {
        $(".primary_submenu").each(function (e) {
            $(this).css("top", 'initial');
            cached_menu_height = 0;
        });
    } else if(cached_menu_height != menu_elem.height() && window.innerWidth > $primary_menu_break) {
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

function expand_primary_menu() {
    if ($primary_nav.hasClass('expanded')) {
        collapse_primary_menu();
    } else {
        $primary_nav.addClass('expanded');
    }
}

function collapse_primary_menu() {
    $primary_nav.removeClass('expanded');
}

$('#main_menu li.expand a').click(function(event) {
    //prevent triggering the link
    event.preventDefault();
    // Stop the body click from triggering
    event.stopPropagation();

    expand_primary_menu();
});

$('body').click(function() {
    collapse_primary_menu();
});