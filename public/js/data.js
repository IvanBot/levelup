
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
                $('.error.error_username').removeClass('hidden');
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
                success: function (data) {
                    data = data.split('-');
                    var name = a[0] + ' ' + a[1];
                    url['phone'] = url['phone'].replace(/-/g, '');
                    var date = new Date(0);
                    console.log(url['myData']);
                    if (url['myData']) {

                        document.cookie = "username="+url['username'];
                        document.cookie = "surname="+url['surname'];
                        document.cookie = "phone="+url['phone'];
                        document.cookie = "myData="+url['myData'];
                        console.log(document.cookie);
                    }else{
                        document.cookie = "username=";
                        document.cookie = "surname=";
                        document.cookie = "phone=";
                        document.cookie = "myData=";
                    }
                    if (data[0] > 0)sessvars.recordphone[data[0]] = url['phone'];
                    if (data[1] > 0)sessvars.my_records[data[1]] = data[0];

                    $('.selected-tr #write_n_f').append('<div class="yourname" id="record_' + data[1] + '">' + name.trim() + '<button data-record ="' + data[1] + '" title="Удалить мою запись" type="button" class="close record" data-target=".recdel" data-toggle="modal">×</button></div>');//
                    if ($('.selected-tr #write_n_f .badge')[0] != undefined) var badge = $('.selected-tr #write_n_f .badge')[0].innerText;
                    if (badge) {
                        badge = badge.split(': ');
                        badge[1]--;
                        if (badge[1] == 0) {
                            $('.selected-tr .btn.btn-secondary').remove();
                            $('.selected-tr #write_n')[0].innerHTML = "<div class='finished' data-date='" + url['schedule_date'] + "' data-time='" + url['schedule_time'] + "' data-id='" + url['schedule_id'] + "'>Запись окончена</div>";
                        }
                        badge = badge[0] + ': ' + badge[1];
                        $('.selected-tr #write_n_f .badge')[0].innerText = badge;
                    }
                    $('#formx')[0].reset();
                    $('.error.error_username').addClass('hidden');
                    $('.error.error_userphone').addClass('hidden');
                    $('#inputPhone').removeClass('error');
                    $('#inputName').removeClass('error');
                    $('.close').click();
                },
                error: function (xhr, str) {
                    console.log('Возникла ошибка: ' + xhr.responseCode);
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

    var codropsEvents, htmlresult = activityList();

    function activityList(opendate) {
        var months = {
            '01': 'января',
            '02': 'февраля',
            '03': 'марта',
            '04': 'апреля',
            '05': 'мая',
            '06': 'июня',
            '07': 'июля',
            '08': 'августа',
            '09': 'сентября',
            '10': 'октября',
            '11': 'ноября',
            '12': 'декабря'
        };
        var now = new Date();
        if (opendate == undefined) {
            var opendate = now;
            opendate = opendate.getFullYear() + '-' + ((opendate.getMonth() + 1) <= 9 ? '0' + (opendate.getMonth() + 1) : (opendate.getMonth() + 1)) + '-' + (opendate.getDate() < 9 ? '0' + opendate.getDate() : opendate.getDate());
        }
        var res = $.ajax({
            type: "POST",
            url: "/getSchedule",
            data: "date=" + opendate,
            dataType: "json",
            async: false,
            success: function (data) {
                //color = data;
            },
            error: function () {
                console.log('Ajax problem >> data.js ');
            }
        }).responseJSON;

        var htmlresult = [];

        for (var date in res) {

            htmlresult[date] = document.createElement('table');
            htmlresult[date].className = "table_posit";
            cap = document.createElement('caption');
            h3 = document.createElement('h3');
            h3.id = 'tab-h';
            h3.innerHTML = date.substr(8, 2) + ' ' + months[date.substr(5, 2)] + ' ' + date.substr(0, 4);
            cap.appendChild(h3);
            htmlresult[date].appendChild(cap);
            tbody = document.createElement('tbody');
            tbody.id = 'tab-block';
            if (res['total_count'])delete res['total_count'];
            for (var line in res[date]) {
                var arr = res[date][line];
                delete res[date][line];
                res[date][+line] = arr;
            }
            for (var line in res[date]) {
                var d = res[date][line]['activitydate'] == undefined ? date : res[date][line]['activitydate'];
                recorddate = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2), +res[date][line]['starttime'].substr(0, 2));
                realdate = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2), +res[date][line]['starttime'].substr(0, 2), +res[date][line]['starttime'].substr(3, 2));
                if (res[date][line]['endttime'])var realenddate = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2), +res[date][line]['endttime'].substr(0, 2), +res[date][line]['endttime'].substr(3, 2));
                else var realenddate = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2), +res[date][line]['starttime'].substr(0, 2) + 1, +res[date][line]['starttime'].substr(3, 2));
                tr = document.createElement('tr');
                td = document.createElement('td');
                td.id = 'write_n_n';
                td.setAttribute('data-time', res[date][line]['starttime']);
                td.innerHTML = res[date][line]['starttime'];
                tr.appendChild(td);
                td = document.createElement('td');
                td.id = 'write_n_b';
                td.innerHTML = res[date][line]['activityname'] != undefined ? res[date][line]['activityname'] : '';
                tr.appendChild(td);
                td = document.createElement('td');
                td.id = 'write_n_f';

                if (res[date][line]['maxcount'] != undefined && res[date][line]['maxcount'] > 0 && recorddate >= now) {
                    span = document.createElement('span');//<span class="badge">8/12</span>
                    var usercount = 0;
                    if (res[date][line]['username'])usercount = res[date][line]['username'].length;
                    span.className = "badge";

                    //span.id = 'badge_' + res[date][line]['id'];
                    span.innerHTML = 'Свободно: ' + ((res[date][line]['maxcount'] - usercount) > 0 ? (res[date][line]['maxcount'] - usercount) : 0);
                    td.appendChild(span);
                }
                if (res[date][line]['username'] != undefined && res[date][line]['username'] != false) {//<div class="yourname">test</div>
                    for (var i in res[date][line]['username']) {
                        div = document.createElement('div');
                        div.id = 'record_' + res[date][line]['username'][i][1];
                        div.className = (sessvars.my_records[res[date][line]['username'][i][1]] > 0 ? "yourname" : "username");
                        div.innerHTML = res[date][line]['username'][i][0] + (sessvars.my_records[res[date][line]['username'][i][1]] > 0 ? '<button data-record ="' + res[date][line]['username'][i][1] + '" title="Удалить запись" type="button" class="close record" data-target=".recdel" data-toggle="modal">×</button>' : '');
                        td.appendChild(div);
                    }
                }


                tr.appendChild(td);
                td = document.createElement('td');
                td.id = 'write_n';

                if (recorddate >= now && ((res[date][line]['maxcount'] - usercount) > 0 || res[date][line]['maxcount'] == undefined)) {
                    button = document.createElement('button');
                    button.setAttribute('type', "button");
                    button.setAttribute('data-toggle', "modal");
                    button.setAttribute('data-id', res[date][line]['id']);
                    button.setAttribute('data-actid', res[date][line]['activityid']);
                    button.setAttribute('data-date', res[date][line]['activitydate']);
                    button.setAttribute('data-time', res[date][line]['starttime']);
                    button.setAttribute('data-target', ".fade");
                    button.className = 'btn btn-secondary';
                    button.innerHTML = 'Запись';
                    td.appendChild(button);
                } else {
                    if (now > realdate && now < realenddate) {
                        td.innerHTML = '<div class="finished now">Идет занятие</div>';
                    } else if (now > realenddate) {
                        td.innerHTML = '<div class="finished old">Занятие окончено</div>';
                    } else {
                        td.innerHTML = '<div class="finished"  data-date="' + res[date][line]['activitydate'] + '" data-time="' + res[date][line]['starttime'] + '" data-actid="' + res[date][line]['activityid'] + '" data-id="' + res[date][line]['id'] + '" >Запись окончена</div>';
                    }
                }
                tr.appendChild(td);
                tbody.appendChild(tr);
            }
            htmlresult[date].appendChild(tbody);

        }
        return res, htmlresult;
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
                if (htmlresult[date] == undefined) {
                    var a, b = activityList(date);
                    htmlresult = $.extend(htmlresult, b);
                }
                $('#datatable_record').html(htmlresult[date]);
                if ($contentEl.length > 0) {
                    showEvents(htmlresult, dateProperties);
                    showData(htmlresult, dateProperties);
                }

            },
            caldata: codropsEvents,//--------------------------------
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