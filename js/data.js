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
    function call() {

        var msg = $('#formx').serialize();
        $.ajax({
            type: 'GET',
            url: '/controllers/db_querys.php',
            data: msg,
            success: function (data) {
                $('.close').click();
                var name = $('#inputName').val();
                $('.selected-tr #write_n_f').append('<div class="yourname">' + name + '</div>')
                $('tr').removeClass('selected-tr');
                //$(".selected-tr #write_n_f").html('<span>'+$('#inputName').val()+'</span>');
            },
            error: function (xhr, str) {
                console.log('Возникла ошибка: ' + xhr.responseCode);
            }
        });

    }
/*
    function cicle(){//getScheduleCicle
        var res = $.ajax({
            type: "GET",
            url: "/controllers/db_querys.php",
            data: "getScheduleCicle=1",
            dataType: "json",
            async: false,
            success: function (data) {
            },
            error: function () {
                console.log('Ajax problem >> data.js << ');
            }
        }).responseJSON;
        return res;
    }
*/
    var codropsEvents, htmlresult = activityList();
    function activityList(opendate) {
       // var cicle = cicle();
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
            type: "GET",
            url: "/controllers/db_querys.php",
            data: "getSchedule=1&date=" + opendate,
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
            for (var line in res[date]) {
                var d = res[date][line]['activitydate']==undefined?date:res[date][line]['activitydate'];
                //console.log(res[date][line]['activitydate']);
                recorddate = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2), +res[date][line]['starttime'].substr(0, 2) - 3);
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
                //span = document.createElement('span');//<span class="badge">8/12</span>
                /*if (res[date][line]['count'] != undefined && res[date][line]['count'] > 0 && recorddate >= now) {
                 span.className = "badge";
                 span.innerHTML = 'Уже записались: ' + res[date][line]['count'] + ' чел.';
                 }*/
                if (res[date][line]['username'] != undefined && res[date][line]['username'] != false) {//<div class="yourname">test</div>

                    for (var i in res[date][line]['username']) {
                        div = document.createElement('div');
                        div.className = "username";
                        div.innerHTML = res[date][line]['username'][i];
                        td.appendChild(div);
                    }
                }


                //td.appendChild(span);
                tr.appendChild(td);
                td = document.createElement('td');
                td.id = 'write_n';

                if (recorddate >= now) {

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
                }else{
                    td.innerHTML = '<div class="finished">Запись окончена</div>';
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
        cal.gotoNextMonth(updateMonthYear);
    });
    $('#custom-prev').on('click', function () {
        cal.gotoPreviousMonth(updateMonthYear);
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
$.mask.definitions['*']="[A-Za-zА-Яа-я -]";
$("#inputPhone").mask("8-999-999-99-99?99999",{placeholder:" "});
$("#inputName").mask('*?*************************************************',{placeholder:""});
$("#inputSurname").mask('*?*************************************************',{placeholder:""});
//$("#phone").mask("8-999-999-9999");