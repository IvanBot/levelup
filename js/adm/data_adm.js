/**
 * Created by a.bulatova on 15.07.2016.
 */
function yesterday(x) {
    x.setDate(x.getDate() - 1);
    return x;
};
webix.i18n.timeFormat = "%H:%i";
function new_custom_day() {
    var form_order = {
        view: "form",
        borderless: true,
        elements: [
            {
                cols: [
                    {

                        rows: [
                            {template: "Название тренировки", type: "section"},
                            {id: "profile_login", view: "text", labelPosition: "left"},


                            {template: "Тренер", type: "section"},
                            {
                                id: "profile_payment", view: "richselect", labelPosition: "left",
                                options: [
                                    {id: 1, value: "Бобруйченко"},
                                    {id: 2, value: "Мохнатов"}
                                ]
                            },
                            {template: "Комментарий", type: "section"},
                            {
                                id: "comment",
                                view: "text",
                                //labelWidth: 110,
                                labelPosition: "left"
                            }, {height: 25}


                        ]
                    }, {width: 25},

                    {
                        width: 270,
                        rows: [
                            {template: "Время", type: "section"},
                            {
                                view: "datepicker",
                                type: "time",
                                stringResult: true,
                                format: '%H:%i',
                                placeholder: 'Начало'
                            },
                            {
                                view: "datepicker",
                                type: "time",
                                stringResult: true,
                                format: '%H:%i',
                                placeholder: 'Окончание'
                            },
                            {template: "День", type: "section"},
                            {view: "datepicker", format: '%Y-%m-%d', timepicker: false, labelPosition: "left"}
                        ]
                    },

                ]
            }, {
                cols: [
                    {},
                    {},
                    {
                        view: "button", value: "Сохранить", hotkey: "enter",
                        click: function () {
                            if ($$("profile_login").getParentView().validate()) {
                                var login = $$("profile_login").getValue();
                                var passw = $$("profile_password").getValue();

                                if (login == "admin" && passw == "admin") {
                                    webix.storage.cookie.put("token", "1");
                                    webix.storage.cookie.put("username", "Админ");
                                    $$("profile_login").getTopParentView().hide();
                                    location.href = 'index.html';
                                }
                                else webix.message({type: "error", text: "Неверный логин или пароль"});
                            }
                            ;
                        }
                    },
                    {
                        view: "button", value: "Отмена",
                        click: function () {
                            $$("profile_login").getTopParentView().hide();
                        }
                    }
                ]
            }
        ],
        rules: {
            "profile_login": webix.rules.isNotEmpty,
            "profile_password": webix.rules.isNotEmpty
        },
        elementsConfig: {
            labelPosition: "top",
        }
    };

    webix.ui({
        view: "window",
        id: "win_order",
        width: 810,
        position: "center",
        modal: true,
        head: "Добавить тренировку",
        body: webix.copy(form_order)
    });

    function showForm_order(winId, node) {
        $$(winId).getBody().clear();
        $$(winId).show(node);
        $$(winId).getBody().focus();
    };

    showForm_order("win_order");
};
function new_default_day() {
    var day = $$("$sidebar1").wg[0];
    day = day.substr(day.length - 1);

    var form_order = {
        view: "form",
        borderless: true,
        elements: [{
            cols: [{
                rows: [
                    {template: "Название тренировки", type: "section"},
                    {id: "name", view: "text", labelPosition: "left"},


                    {template: "Тренер", type: "section"},
                    {
                        id: "trainer", view: "richselect", labelPosition: "left",
                        options: [
                            {id: 1, value: "Бобруйченко"},
                            {id: 2, value: "Мохнатов"}
                        ]
                    },
                    {template: "Комментарий", type: "section"},
                    {
                        id: "comment",
                        view: "text",
                        //labelWidth: 110,
                        labelPosition: "left"
                    }, {height: 25}
                ]
            },
                {width: 25},

                {
                    width: 270,
                    rows: [
                        {template: "Время", type: "section"},
                        {
                            id: 'time_start',
                            view: "datepicker",
                            type: "time",
                            stringResult: true,
                            format: '%H:%i',
                            placeholder: 'Начало'
                        },
                        {
                            id: 'time_end',
                            view: "datepicker",
                            type: "time",
                            stringResult: true,
                            format: '%H:%i',
                            placeholder: 'Окончание'
                        },
                        {template: "День недели", type: "section"},
                        {
                            id: "weekday", view: "richselect", labelPosition: "left",
                            value: day,
                            options: [
                                {id: 1, value: "ПН"},
                                {id: 2, value: "ВТ"},
                                {id: 3, value: "СР"},
                                {id: 4, value: "ЧТ"},
                                {id: 5, value: "ПТ"},
                                {id: 6, value: "СБ"},
                                {id: 7, value: "ВС"}
                            ]
                        }
                    ]
                }, {width: 25},
                {
                    width: 200,
                    rows: [
                        {template: "Количество участников", type: "section"},
                        {
                            id: "min",
                            view: "text",
                            labelPosition: "right",
                            placeholder: 'Минимум'
                        },
                        {height: 23},
                        {
                            id: "max",
                            view: "text",
                            labelPosition: "right",
                            placeholder: 'Максимум'
                        }
                    ]
                }
            ]
        },
            {
                cols: [
                    {},
                    {},
                    {
                        view: "button", value: "Сохранить", hotkey: "enter",
                        click: function () {


                            var d = {};
                            d['activityname'] = $$("name").getValue();
                            d['trainer_id'] = $$("trainer").getValue();
                            d['activitycomment'] = $$("comment").getValue();
                            d['starttime'] = $$("time_start").getValue();
                            d['endtime'] = $$("time_end").getValue();
                            d['cycleday'] = $$("weekday").getValue();
                            d['maxcount'] = $$("max").getValue();
                            d['mincount'] = $$("min").getValue();
                            //opendate = new Date();
                            //d['activitydate'] = opendate.getFullYear() + '-' + ((opendate.getMonth() + 1) <= 9 ? '0' + (opendate.getMonth() + 1) : (opendate.getMonth() + 1)) + '-' + (opendate.getDate() < 9 ? '0' + opendate.getDate() : opendate.getDate());
                            d['setActivity'] = 1;
                            d['setSchedule'] = 1;
                            if (!d['activityname'])webix.message({type: "error", text: "Введите название тренировки"});
                            if (!d['starttime'])webix.message({
                                type: "error",
                                text: "Введите время начала тренировки"
                            });
                            if (!d['cycleday'])webix.message({type: "error", text: "Выберите день недели"});
                            if (d['activityname'] && d['starttime']) {
                                webix.ajax().get("/controllers/db_querys.php", d);
                                $$("win_order").getTopParentView().hide();
                            }
                        }
                    },
                    {
                        view: "button", value: "Отмена",
                        click: function () {
                            $$("win_order").getTopParentView().hide();
                        }
                    }
                ]
            }
        ],
        rules: {
            "profile_login": webix.rules.isNotEmpty,
            "profile_password": webix.rules.isNotEmpty
        },
        elementsConfig: {
            labelPosition: "top",
        }
    };

    webix.ui({
        view: "window",
        id: "win_order",
        width: 810,
        position: "center",
        modal: true,
        head: "Добавить тренировку на этот день",
        body: webix.copy(form_order)
    });

    function showForm_order(winId, node) {
        $$(winId).getBody().clear();
        $$(winId).show(node);
        $$(winId).getBody().focus();
    };

    showForm_order("win_order");
};

var call_recording_filter = {
    type: "space",
    cols: [
        {view: "button", type: "iconButton", width: 130, label: "Добавить", click: new_default_day},
        {},

    ]
};


function deact(mystr) {
    var s = '' + mystr['dots'];
    if (s.indexOf('deact') >= 0) {
        return mystr['num'] + "<div title='не выводится в расписании' class='deactivated'></div>";
    } else {
        return mystr['num'];
    }
}
function count(mystr) {
//  var mystr = '' + mystr;

    if (mystr['maxcount'] != false &&mystr['maxcount'] != undefined && mystr['mincount'] != false && mystr['mincount'] != undefined && mystr['mincount'] <= mystr['maxcount']) {
        return "от " + mystr['mincount'] + " до " + mystr['maxcount'] + " человек"
    } else if (mystr['maxcount'] > 0 && mystr['maxcount'] != undefined && mystr['maxcount'] != false) {
        return "до " + mystr['maxcount'] + " человек"
    } else{return '';}

}

var days_default = [];
for (var i = 1; i < 8; i++) {
    days_default[i] = {
        id: "day" + i,
        rows: [
            call_recording_filter,
            {
                cols: [
                    {
                        id: "orders_pending_datatable",
                        view: "datatable",
                        resizeColumn: true,
                        resizeRow: true,
                        select: true,
                        hover: "myhover",
                        footer: true,
                        navigation: true,
                        tooltip: true,
                        multiselect: true,
                        css: "my_style",
                        columns: [	//currency
                            {
                                id: "num",
                                header: "",
                                width: 50,
                                tooltip: false,
                                css: {"text-align": "right"},
                                //cssFormat: deact,
                                template: deact
                            },
                            {
                                id: "starttime",
                                header: "Время",
                                tooltip: false,
                                width: 120,
                                css: {"text-align": "left"},
                                template: "#starttime# - #endtime#",
                                // cssFormat: deact
                            },
                            /* {
                             id: "endtime",
                             header: "Время окончания",
                             width: 70,
                             css: {"text-align": "right"},
                             sort: "server"
                             },*/
                            {
                                id: "activityname",
                                tooltip: false,
                                header: [{text: "Название тренировки"}/*, {content: 'serverSelectFilter'}*/],
                                //options: ["Принят", "Назначен", "Выполняется", "Завершен", "Отказан"],
                                width: 300,
                                sort: "server",
                                //cssFormat: deact
                            },
                            {
                                id: "maxcount",
                                tooltip: false,
                                header: [{text: "Квота"}],
                                width: 180,
                                template: count,
                                // cssFormat: deact
                            },
                            {
                                id: "activitycomment",
                                header: [{text: "Комментарий"}],
                                width: 300,
                                // cssFormat: deact
                            },
                            {
                                id: "cycledayname",
                                tooltip: false,
                                header: "",
                                width: 70,
                                //cssFormat: deact,
                                template: "#cycledayname#"
                            }
                        ],
                        url: "/controllers/db_querys.php?getScheduleCicle=1&cycleday=" + i
                    }
                ]
            },
        ],
    };
}
//console.log(days_default);