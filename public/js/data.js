
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
        var a = [$('#inputName').val(), $('#inputSurname').val(), $('#inputPhone').val()];
        if ((!a[0] && !a[1]) || !a[2]) {
            if (!a[0] && !a[1]) {
                $('.error.error_name').removeClass('hidden');
                $('#inputName').addClass('error');
            }
            if (!a[2]) {
                $('.error.error_userphone').removeClass('hidden');
                $('#inputPhone').addClass('error');
            }
        }
        else {
            var msg = $('#formx').serialize();
            var url = URLToArray(msg);
            url['phone'] = url['phone'].replace(/-/g, '');
            for (var user_id in sessvars.recordphone) {
                if (sessvars.recordphone[user_id] == url['phone'])
                    msg = msg + '&user_sess=1&user_id=' + user_id;
            }
            $.ajax({
                type: 'POST',
                url: '/addRecord',
                data: msg,
                dataType: "json",
                async: true,
                success: function (data) {
                    if (data["error"] == "none") {
                        loadTimeTable($('#schedule_date').val());
                        $('#formx')[0].reset();
                        $('.error.error_name').addClass('hidden');
                        $('.error.error_userphone').addClass('hidden');
                        $('#inputPhone').removeClass('error');
                        $('#inputName').removeClass('error');
                        $('.close').click();
                    } else {
                        alert(data["error"]);
                    }                    
                },
                error: function (xhr, str) {
                    alert('Извините, происзошла ошибка при обращении к серверу.');
                }
            });
        }
    }

    function recorddel() {
        var phone = $('#inputDelPhone').val();
        var rec = $('#inputRecord').val();
        phone = phone.replace(/-/g, '');
        if (!phone) {
            $('.error.error_userphone.del').removeClass('hidden');
            $('#inputDelPhone').addClass('error');
        }
        else if (sessvars.recordphone[sessvars.my_records[rec]] != phone) {
            $('.error.error_userphone.undel').removeClass('hidden');
            $('#inputDelPhone').addClass('error');
        } else {
            var msg = $('#formd').serialize().replace(/-/g, '');
            msg = msg + '&delRecordByPhone=1&user_id=' + sessvars.my_records[rec];
            //
            $.ajax({
                type: 'POST',
                url: '/delRecord',
                data: msg,
                success: function (data) {

                    var url = URLToArray(msg);
                    var badge = $('.selected-tr .badge')[0].innerText;
                    if (badge) {
                        badge = badge.split(': ');
                        badge[1]++;
                        if (badge[1] == 1 && $('.selected-tr .finished')) {
                            var a = [];
                            var tr = $('#record_' + url['record_id']).parent().parent();
                            a['data-id'] = tr.find("#write_n .finished")[0].getAttribute('data-id');
                            a['data-date'] = tr.find("#write_n .finished")[0].getAttribute('data-date');
                            a['data-time'] = tr.find("#write_n .finished")[0].getAttribute('data-time');

                            $('#record_' + url['record_id']).parent().parent().find("#write_n")[0].innerHTML = '<button type="button" data-toggle="modal" data-id="' + a['data-id'] + '" data-date="' + a['data-date'] + '" data-time="' + a['data-time'] + '" data-target=".fade" class="btn btn-secondary">Запись</button>';
                            $('.selected-tr .finished').remove()
                        }
                        badge = badge[0] + ': ' + badge[1];
                        $('#record_' + url['record_id']).parent().find('.badge')[0].innerText = badge


                    }
                    // console.log($('.selected-tr #write_n'));
                    //$('.selected-tr #write_n')[0].innerHTML = '<button type="button" data-toggle="modal" data-id="3" data-actid="undefined" data-date="2016-07-30" data-time="14:00" data-target=".fade" class="btn btn-secondary">Запись</button>';
                    $('#record_' + url['record_id']).remove();

                    $('#formd')[0].reset();
                    $('.error.error_userphone del').addClass('hidden');
                    $('#inputDelPhone').removeClass('error');
                    $('.close').click();
                },
                error: function (xhr, str) {
                    console.log('Возникла ошибка: ' + xhr.responseCode);
                }
            });
        }

    }
    

    function loadTimeTable(date) {
        var res = $.ajax({
            type: "GET",
            url: "/getTimeTable/"+date,
            dataType: "html",
            async: true,
            success: function (data) {
                $('#datatable_record').html(data);console.log(111);
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