
$(function () {

    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });
    $('.scrollup').click(function () {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });

    // Hiding elements of modal at start
    $('.set_hide').each( function() {
        $(this).css('display', 'none');
    });

    // Hiding elements and value of modal after close modal
    $('#cloooose').on( 'hidden.bs.modal', function () {
        $('#phone_box').css('display', 'block');
        $('#modal-order').css('height', '375px');
        $('.set_hide').each( function() {
            $(this).css('display', 'none');
        });

        $('#sms_code').val('');
        $('#name_user').val('');
        $('#adult-selected').removeAttr('id');
        $('#amount_adults').html('0');
        $('#kids-selected').removeAttr('id');
        $('#amount_kids').html('0');
        $('#record').css('display', 'none');
        $('#attention').css('display', 'none')
    });

    // Click handler for swithcing views
    $('.modal_next').each( function () {
        $(this).click( function () {
            var current_modal = parseInt( $(this).attr('modal') );
            var option = $(this).attr('option');
            switch_modals( current_modal, option ) 
        });
    });

    // Get amount of adults
    $('#modal-count-adults div').each( function ( ) {
        $(this).click( function () {

            $('#adult-selected').removeAttr('id');
            $(this).attr('id', 'adult-selected');
            
            var amount_adults = $(this).attr('value');
            $('#amount_adults').html(amount_adults);

        });
    });

    // Get amount of kids
    $('#modal-count-kids div').each( function ( ) {
        $(this).click( function () {

            $('#kids-selected').removeAttr('id');
            $(this).attr('id', 'kids-selected');
            
            var amount_kids = $(this).attr('value');
            $('#amount_kids').html(amount_kids);

        });
    });

    // Select pay option
    $('#order_pay_option input').each( function () {
        $(this).click( function () {

            var pay_option = $(this).attr('option');

            $('#order_pay_option').css('display', 'none');
            $('#order_attention').css('display', 'block')
            $('#record').css('display', 'block');
        });
    });

    $('#record').click(function () {
        call()
    });
    
    $('#recorddel').click(function () {
        recorddel()
    });
    function getCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    function URLToArray(url) {
        var request = {};
        var pairs = url.split('&');
        for (var i = 0; i < pairs.length; i++) {
            if (!pairs[i])
                continue;
            var pair = pairs[i].split('=');
            request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
        }
        return request;
    }


    if (sessvars.recordphone == undefined) sessvars.recordphone = [];
    // if (sessvars.recordarr == undefined)sessvars.recordarr = [];
    if (sessvars.my_records == undefined)sessvars.my_records = [];
    function call() {
	if ($('#inputRules').is(":checked")) {
            $('#inputRules').removeClass('error');
        } else {
            $('#inputRules').addClass('error');
            alert("Пожалуйста, ознакомьтесь с правилами посещения батутного зала и примите их.");
 	    return;
	}
        var msg = $('#formx').serialize();
        var url = URLToArray(msg);//console.log(url);//alert(url['save']);
        url['phone'] = url['phone'].replace(/-/g, '');
        for (var user_id in sessvars.recordphone) {
            if (sessvars.recordphone[user_id] == url['phone'])
                msg = msg + '&user_sess=1&user_id=' + user_id;
        };
        $.ajax({
            type: 'POST',
            url: '/addRecord',
            data: msg,
            dataType: "json",
            async: true,
            success: function (data) {
                //data = JSON.parse(data);
                if(data['result']==1) {
                    $('.error.error_name').removeClass('hidden');
                    $('#inputName').addClass('error');
                    $('.error.error_userphone').addClass('hidden');
                    $('#inputPhone').removeClass('error');
                    $('.error.error_count').addClass('hidden');
                    $('#inputCount').removeClass('error');
                    $('.error.error_error').addClass('hidden');
                } else if(data['result']==2) {
                    $('.error.error_userphone').removeClass('hidden');
                    $('#inputPhone').addClass('error');
                    $('.error.error_name').addClass('hidden');
                    $('#inputName').removeClass('error');
                    $('.error.error_count').addClass('hidden');
                    $('#inputCount').removeClass('error');
                    $('.error.error_error').addClass('hidden');
                } else if(data['result']==3) {
                    $('.error.error_count').html(data['message']);
                    $('.error.error_count').removeClass('hidden');
                    $('#inputCount').addClass('error');
                    $('.error.error_userphone').addClass('hidden');
                    $('#inputPhone').removeClass('error');
                    $('.error.error_name').addClass('hidden');
                    $('#inputName').removeClass('error');
                    $('.error.error_error').addClass('hidden');
                } else if(data['result']==4) {
                    $('.error.error_error').html(data['message']);
                    $('.error.error_error').removeClass('hidden');
                    $('.error.error_name').addClass('hidden');
                    $('#inputName').removeClass('error');
                    $('.error.error_userphone').addClass('hidden');
                    $('#inputPhone').removeClass('error');
                    $('.error.error_count').addClass('hidden');
                    $('#inputCount').removeClass('error');
                } else {
                    $('.error.error_name').addClass('hidden');
                    $('.error.error_userphone').addClass('hidden');
                    $('.error.error_count').addClass('hidden');
                    $('.error.error_error').addClass('hidden');
                    $('#inputPhone').removeClass('error');
                    $('#inputName').removeClass('error');
                    $('#inputCount').removeClass('error');

                    $('.done.error_name').removeClass('hidden');

                    $('#record').addClass('hidden');
                    $('#inputPhone').addClass('hidden');
                    $('#inputName').addClass('hidden');
                    $('#inputCount').addClass('hidden');
                    $('#inputSave').addClass('hidden');
		    $('#inputRules').addClass('hidden');
                    $('#inputLastname').addClass('hidden');
                    $('.modal-body label').addClass('hidden');
                    setTimeout(function(){
                        $('.done.error_name').addClass('hidden');
                        $('#inputPhone').removeClass('hidden');
                        $('#inputName').removeClass('hidden');
                        $('#inputCount').removeClass('hidden');
                        $('#inputLastname').removeClass('hidden');
                        $('#record').removeClass('hidden');
                        $('#inputSave').removeClass('hidden');
			$('#inputRules').removeClass('hidden');
                        $('.modal-body label').removeClass('hidden');
                        loadTimeTable($('#schedule_date').val());
                        if(!!!url['save']) $('#formx')[0].reset();
                        $('.close').click();
                    },3000);
                };
            },
            error: function (xhr, str) {
                alert('Извините, происзошла ошибка при обращении к серверу.');
            }
        });
    }

    function switch_modals ( current_modal, option ) {

        switch (option) {
            case 'order_single': {
                $('#order_single').css('display', 'block');
                // $('#order_attention').css('display', 'block');
                $('#order_pay_option').css('display', 'block');
                break;
            }
            case 'order_rent': {
                $('#order_rent').css('display', 'block');
                // $('#order_attention').css('display', 'block');
                $('#order_pay_option').css('display', 'block');
                break;
            }
            case 'order_adults': {
                $('#modal-order').css('height', 'auto');
                $('.order_adults').each( function () {
                    $(this).css('display', 'block');
                });
                $('#order_pay_option').css('display', 'block');
                break;
            }
            case 'order_kids': {
                $('#modal-order').css('height', 'auto');
                $('.order_adults').each( function () {
                    $(this).css('display', 'block')
                });
                $('.order_kids').each( function () {
                    $(this).css('display', 'block')
                });
                $('#order_pay_option').css('display', 'block');
                break;
            }
        }

        var next_modal = current_modal + 1;
        // var current_tip = '';
        $('.modal_record').each( function( index ) {

            

            if ( index == current_modal ) {

                // Required fields [dev] 

                // if ( index < 3 ) {
                //     var element = '#' + $(this).attr('id');
                //     var value =  $( element + ' .form-control' ).val();

                //     if ( current_tip.length != 0 ) {
                //         current_tip = $( element + ' .form-control' ).attr('placeholder');
                //         console.log(current_tip);
                //         console.log( $( element + ' .form-control' ) );
                //     }
                    
                //     if (!value.length) {
                //         $( element + ' .form-control' ).attr('placeholder', 'Поле должно быть заполнено!');
                //         return false
                //     } 

                //     $( element + ' .form-control' ).attr('placeholder', current_tip);

                // }

                $(this).css('display', 'none');

            }

            else if ( index == next_modal ) {
                $(this).css('display', 'block');
            }

        });

    };

    function recorddel() {
        var phone = $('#inputDelPhone').val();
        var rec = $('#inputRecord').val();
        phone = phone.replace(/-/g, '');
        var msg = $('#formd').serialize().replace(/-/g, '');
        msg = msg + '&delRecordByPhone=1&user_id='+rec; //sessvars.my_records[rec];
        var url = URLToArray(msg);
        $.ajax({
            type: 'POST',
            url: '/delRecord',
            data: msg,
            success: function (data) {
                data = JSON.parse(data);
                if(data['result']==1) {
                    $('.error.error_userphone.undel').addClass('hidden');
                    $('#inputDelPhone').removeClass('error');
                    $('.error.error_userphone.del').removeClass('hidden');
                    $('#inputDelPhone').addClass('error');
                } else if(data['result']==2) {
                    $('.error.error_userphone.del').addClass('hidden');
                    $('#inputDelPhone').removeClass('error');
                    $('.error.error_userphone.undel').removeClass('hidden');
                    $('#inputDelPhone').addClass('error');
                } else {
                    $('#record_' + url['record_id']).remove();

                    $('.error.error_userphone.del').addClass('hidden');
                    $('.error.error_userphone.undel').addClass('hidden');
                    $('#inputDelPhone').removeClass('error');
                    $('.done.error_userphone.undel').removeClass('hidden');

                    $('#inputDelPhone').addClass('hidden');
                    $('#recorddel').addClass('hidden');
                    $('.modal-content label').addClass('hidden');
                    setTimeout(function(){
                        $('.done.error_userphone.undel').addClass('hidden');
                        $('#inputDelPhone').removeClass('hidden');
                        $('.modal-content label').removeClass('hidden');
                        $('#recorddel').removeClass('hidden');

                        loadTimeTable(data['activitydate']);
                        $('#formd')[0].reset();
                        $('.close').click();
                    },3000);
                };
            },
            error: function (xhr, str) {
                console.log('Возникла ошибка: ' + xhr.responseCode);
            }
        });
    }
    

    function loadTimeTable(date) {
        var res = $.ajax({
            type: "GET",
            url: "/getTimeTable/"+date,
            dataType: "html",
            async: true,
            success: function (data) {
                $('#datatable_record').html(data);
            },
            error: function () {
                $('#datatable_record').html("Извините, что то пошло не так.");
            }
        });
    }


    var transEndEventNames = {
            'WebkitTransition': 'webkitTransitionEnd',
            'MozTransition': 'transitionend',
            'OTransition': 'oTransitionEnd',
            'msTransition': 'MSTransitionEnd',
            'transition': 'transitionend'
        },
        transEndEventName = transEndEventNames[Modernizr.prefixed('transition')],
        $wrapper = $('#custom-inner'),
        $calendar = $('#calendar'),
        cal = $calendar.calendario({
            onDayClick: function ($el, $contentEl, dateProperties) {

                $('.fc-row div').removeClass('selectday');

                $el.addClass('selectday');
                var date = dateProperties.year + '-' + (dateProperties.month <= 9 ? '0' + dateProperties.month : dateProperties.month) + '-' + (dateProperties.day.length == 1 ? '0' + dateProperties.day : dateProperties.day);

                loadTimeTable(date);

                //alert(date);
                /*if (htmlresult[date] == undefined) {
                    var a, b = activityList(date);
                    htmlresult = $.extend(htmlresult, b);
                }
                $('#datatable_record').html(htmlresult[date]);
                if ($contentEl.length > 0) {
                    showEvents(htmlresult, dateProperties);
                    showData(htmlresult, dateProperties);
                }*/
            },/*,
            caldata: codropsEvents,*///--------------------------------
            displayWeekAbbr: true
        }),
        $month = $('#custom-month').html(cal.getMonthName()),
        $year = $('#custom-year').html(cal.getYear());

    $('#custom-next').on('click', function () {
        var a = $('.selectday');
        if (a[0]) {
            var day = a[0].innerText.replace(/\D+/g, "");
        }
        cal.gotoNextMonth(updateMonthYear);
        var n = $('.fc-date');
        if (day > 0 && n[day - 1]) {
            var s = n[day - 1].parentNode;
        } else {
            var s = n[n.length - 1].parentNode;
        }
        s.click();
    });
    $('#custom-prev').on('click', function () {
        var a = $('.selectday');
        if (a[0]) {
            var day = a[0].innerText.replace(/\D+/g, "");
        }
        cal.gotoPreviousMonth(updateMonthYear);
        var n = $('.fc-date');
        if (day > 0) {
            var s = n[day - 1].parentNode;
            s.click();
        }
    });


    function updateMonthYear() {
        $month.html(cal.getMonthName());
        $year.html(cal.getYear());
    }

    // just an example..
    function showEvents(htmlresult, dateProperties) {

        hideEvents();

        var $events = $('<div id="custom-content-reveal" class="custom-content-reveal">' +
                '<h4>Events for ' + dateProperties.monthname + ' ' + dateProperties.day + ', ' + dateProperties.year + '</h4></div>'),
            $close = $('<span class="custom-content-close"></span>').on('click', hideEvents);

        //$events.append(htmlresult.html(), $close).insertAfter($wrapper);
        setTimeout(function () {
            $events.css('top', '0%');
        }, 25);

    }

    function hideEvents() {
        var $events = $('#custom-content-reveal');
        if ($events.length > 0) {
            $events.css('top', '100%');
            Modernizr.csstransitions ? $events.on(transEndEventName, function () {
                $(this).remove();
            }) : $events.remove();
        }
    }



});
$.mask.definitions['*'] = "[A-Za-zА-Яа-я -]";
$("#inputPhone").mask("8-999-999-99-99", {placeholder: " "});
$("#inputDelPhone").mask("8-999-999-99-99", {placeholder: " "});
$("#inputName").mask('*?*************************************************', {placeholder: ""});
$("#inputSurname").mask('*?*************************************************', {placeholder: ""});
//$("#phone").mask("8-999-999-9999");


