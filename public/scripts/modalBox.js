/*
ModalBox.js v 1.0.2
Author: sudhanshu yadav
Copyright (c) 2013 Sudhanshu Yadav, released under the MIT license.
s-yadav.github.com
*/
;(function ($, window, document, undefined) {
    $.fn.modalBox = function (method, option) {

        if (methods[method]) {
            methods[method].call(this, option);
        } else if (typeof method === 'object' || !method) {
            methods.open.call(this, method);
        }
        return this;
    };
    $.modalBox = {};

    //default options

    $.modalBox.defaults = {
        //all properties with unit like 10px
        width: 'auto',
        height: 'auto',
        left: 'auto',
        top: 'auto',
        overlay: true,
        iconClose: true,
        keyClose: true,
        bodyClose: true,

        //callback function
        onOpen: function () {},
        onClose: function () {}
    };

    //global methods

    //to close all modal box
    $.modalBox.close = function () {
        $('.iw-modalBox').each(function () {
            methods.close.call($(this));
        });

    };


    //internal method
    var keyEvent = function (e) {
        var keyCode = e.keyCode;
        //check for esc key is pressed.
        if (keyCode == 27) {
            $.modalBox.close();
        }
    };
    var clickEvent = function (e) {
        //check if modalbox is defined in data
        if (e.data) {
            methods.close.call(e.data.modalBox);
        } else {
            $.modalBox.close();
        }
    };
    var resizeEvent = function (e) {
        var closeBtn = e.data.closeBtn,
            elm = e.data.elm;
        closeBtn.css({
            top: '10px',
            right: '10px',
            position: 'fixed',
            'z-index': '99'
        });
    };
    //to show overlay
    var addOverlay = function () {
        $('body').append('<div class="iw-modalOverlay"></div>');
        $('.iw-modalOverlay').css({
            display: 'block',
            width: '100%',
            height: '100%',
            position: 'fixed',
            top: 0,
            left: 0,
            'z-index': '98'
        });

    };

    var methods = {
        open: function (option) {
            option = $.extend({}, $.modalBox.defaults, option);

            var elm = this,
                elmWidth = elm.width(),
                elmHeight = elm.height(),
                elmWidthO = elm.outerWidth(),
                elmHeightO = elm.outerHeight(),
                windowWidth = $(window).width(),
                windowHeight = $(window).height(),
                width = Math.min(elmWidthO, windowWidth) - (elmWidthO - elmWidth),
                height = Math.min(elmHeightO, windowHeight) - (elmHeightO - elmHeight);

            //to add modalBox class
            elm.data('iw-size', {
                'width': elmWidth,
                'height': elmHeight
            }).addClass('iw-modalBox');
			
			//to maintian box-sizing property if a user define width and height use css method else use width/ height method.	
            if(option.width != 'auto'){
					elm.css('width',option.width);
				}
			else{
					elm.width(width);	
				}
			
            if(option.height != 'auto'){
					elm.css('height',option.height);
				}
            else if(option.height != null){
                    elm.css('height', '100%');
                }
			else{
					elm.height(height);	
				}



            var top = '50%',
                left = '50%',
                marginLeft = elm.outerWidth() / 2,
                marginTop = elm.outerHeight() / 2;

            if (option.left != 'auto') {
                left = option.left;
                marginLeft = '0';
            }
            if (option.top != 'auto') {
                top = option.top;
                marginTop = '0';
            }

            elm.css({
                top: top,
                left: left,
                position: 'fixed',
                display: 'block',
                'margin-left': -marginLeft,
                'margin-top': -marginTop,
                'z-index': '99',
                'background': 'transparent',
            });

            if (option.overlay) {
                addOverlay();
            }

            //to bind close event   
            if (option.iconClose) {

                    var randId = Math.ceil(Math.random() * 1000) + 'close';
                    var closeBtn = $('<button id="' + randId + '" class="close_modal"><i class="fa fa-close"></i> Close</button>');
                    elm.attr('closecloseBtn', randId);
                    closeBtn.bind('click', {
                        modalBox: elm
                    }, clickEvent);

                $('body').append(closeBtn);
                $('#main_upload_body > #close').attr('data-test',randId);

            }

            if (option.keyClose) {
                $(document).bind('keyup.iw-modalBox', keyEvent);
            }

            if (option.bodyClose) {
                /*create a overlay(or use existing) in which we will give close event to overlay
                        and not in the body to come out of bubbling issue */
                var overlay = $('.iw-modalOverlay');
                if (overlay.length === 0) {
                    addOverlay();
                    overlay = $('.iw-modalOverlay');
                    overlay.css({
                        'background': 'none'
                    });
                }
                overlay.bind('click', clickEvent);
            }
            //call callback function
            option.onOpen.call(this);
            elm.data('closeFun', option.onClose);

        },
        close: function () {
            var elm = this;
            if (elm.data('iw-size')) {
                //close modal and unbind all event associated with it.
                var closeBtnId = elm.attr('closecloseBtn');
                if (closeBtnId) {
                    elm.removeAttr('closecloseBtn');
                    $('#' + closeBtnId).remove();
                }
                elm.css({
                    'display': 'none'
                });
				$('body').removeAttr('style');
                elm.removeAttr('style');
                //call callback function
                elm.data('closeFun').call(this);

                //restore modal box
                elm.removeData('iw-size').removeData('closeFun')
                //remove class
                .removeClass('iw-modalBox');

                //if all modal box is closed unbinde all events.
                if ($('.iw-modalBox').length === 0) {
                    $('.iw-modalOverlay').remove();
                    $(document).unbind('keyup.iw-modalBox');
                    $(window).unbind('resize.iw-modalBox');
                }
            }

        }
    };
})(jQuery, window, document);