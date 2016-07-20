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
                                labelPosition: "left"
                            }, {height: 25}
                        ]
                    }, {width: 25},
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
                            {template: "День", type: "section"},
                            {
                                id: 'activitydate',
                                view: "datepicker",
                                format: '%Y-%m-%d',
                                timepicker: false,
                                labelPosition: "left"
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
                    },
                ]
            }, {
                cols: [
                    {},
                    {},
                    {
                        view: "button", value: "Сохранить", hotkey: "enter", icon: 'send',
                        click: function () {


                            var d = {};
                            d['activityname'] = $$("name").getValue();
                            d['trainer_id'] = $$("trainer").getValue();
                            d['activitycomment'] = $$("comment").getValue();
                            d['starttime'] = $$("time_start").getValue();
                            d['endtime'] = $$("time_end").getValue();
                            d['activitydate'] = $$("activitydate").getValue();
                            d['maxcount'] = $$("max").getValue();
                            d['mincount'] = $$("min").getValue();
                            d['setActivity'] = 1;
                            d['setSchedule'] = 1;
                            if (!d['activityname'])webix.message({type: "error", text: "Введите название тренировки"});
                            if (!d['starttime'])webix.message({
                                type: "error",
                                text: "Введите время начала тренировки"
                            });
                            if (!d['activitydate'])webix.message({
                                type: "error",
                                text: "Выберите день проведения тренировки"
                            });
                            if (d['activityname'] && d['starttime'] && d['activitydate']) {
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
function edit_custom_day() {
    var day = $$("$sidebar1").wg[0];
    day = day.substr(day.length - 1);
    var edit_custom_day_var = $$("days_custom_datatable").getSelectedItem();
    if (!edit_custom_day_var) {
        webix.message({type: "error", text: "Выберите запись!"});
        return;
    }
    var edit_custom_day = {
        view: "form",
        borderless: true,
        elements: [
            {
                cols: [
                    {
                        rows: [
                            {template: "Название тренировки", type: "section"},
                            {id: "name", view: "text", labelPosition: "left", value: edit_custom_day_var.activityname},
                            {template: "Тренер", type: "section"},
                            {
                                id: "trainer",
                                view: "richselect",
                                labelPosition: "left",
                                value: edit_custom_day_var.trainer_id,
                                options: [{id: 1, value: "Бобруйченко"}, {id: 2, value: "Мохнатов"}]
                            },
                            {template: "Комментарий", type: "section"},
                            {id: "comment", view: "text", labelPosition: "left"},
                            {height: 25}
                        ]
                    },
                    {width: 25},
                    {
                        width: 270,
                        rows: [
                            {template: "Время", type: "section"},
                            {
                                id: 'starttime',
                                view: "datepicker",
                                type: "time",
                                stringResult: true,
                                format: '%H:%i',
                                placeholder: 'Начало',
                                value: edit_custom_day_var.starttime
                            },
                            {
                                id: 'endtime',
                                view: "datepicker",
                                type: "time",
                                stringResult: true,
                                format: '%H:%i',
                                placeholder: 'Окончание',
                                value: edit_custom_day_var.endtime
                            },
                            {template: "День", type: "section"},
                            {
                                id: 'activitydate',
                                view: "datepicker",
                                format: '%Y-%m-%d',
                                timepicker: false,
                                labelPosition: "left"
                            }
                        ]
                    },
                    {width: 25},
                    {
                        width: 200,
                        rows: [
                            {template: "Количество участников", type: "section"},
                            {
                                id: "mincount",
                                view: "text",
                                labelPosition: "right",
                                placeholder: 'Минимум',
                                value: edit_custom_day_var.mincount
                            },
                            {height: 23},
                            {
                                id: "maxcount",
                                view: "text",
                                labelPosition: "right",
                                placeholder: 'Максимум',
                                value: edit_custom_day_var.maxcount
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
                            d['schedule_id'] = edit_custom_day_var.id;
                            d['activity_id'] = edit_custom_day_var.activity_id;
                            d['activityname'] = $$("name").getValue();
                            d['trainer_id'] = $$("trainer").getValue();
                            d['activitycomment'] = $$("comment").getValue();
                            d['starttime'] = $$("starttime").getValue();
                            d['endtime'] = $$("endtime").getValue();
                            d['activitydate'] = $$("activitydate").getValue();
                            d['maxcount'] = $$("maxcount").getValue();
                            d['mincount'] = $$("mincount").getValue();
                            d['setActivity'] = 1;
                            d['setSchedule'] = 1;
                            if (!d['activityname'])webix.message({type: "error", text: "Введите название тренировки"});
                            if (!d['starttime'])webix.message({
                                type: "error",
                                text: "Введите время начала тренировки"
                            });
                            if (!d['activitydate'])webix.message({type: "error", text: "Выберите день"});
                            if (d['activityname'] && d['starttime'] && d['activitydate']) {
                                webix.ajax().get("/controllers/db_querys.php", d);
                                $$("win_edit_custom_day").getTopParentView().hide();
                            }
                        }
                    },
                    {
                        view: "button", value: "Отмена",
                        click: function () {
                            $$("win_edit_custom_day").getTopParentView().hide();
                        }
                    }
                ]
            }
        ],
        rules: {},
        elementsConfig: {
            labelPosition: "top",
        }
    };
    webix.ui({
        view: "window",
        id: "win_edit_custom_day",
        width: 810,
        position: "center",
        modal: true,
        head: "Редактировать тренировку на этот день",
        body: webix.copy(edit_custom_day)
    });

    function showEdit_custom_day(winId, node) {
        $$(winId).getBody().clear();
        $$(winId).show(node);
        $$(winId).getBody().focus();
    };

    showEdit_custom_day("win_edit_custom_day");
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
        rules: {},
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
function edit_default_day() {
    var day = $$("$sidebar1").wg[0];
    day = day.substr(day.length - 1);
    var edit_default_day_var = $$("activity_datatable" + day).getSelectedItem();
    if (!edit_default_day_var) {
        webix.message({type: "error", text: "Выберите запись!"});
        return;
    }
    var edit_default_day = {
        view: "form",
        borderless: true,
        elements: [
            {
                cols: [
                    {
                        rows: [
                            {template: "Название тренировки", type: "section"},
                            {id: "name", view: "text", labelPosition: "left", value: edit_default_day_var.activityname},
                            {template: "Тренер", type: "section"},
                            {
                                id: "trainer",
                                view: "richselect",
                                labelPosition: "left",
                                value: edit_default_day_var.trainer_id,
                                options: [{id: 1, value: "Бобруйченко"}, {id: 2, value: "Мохнатов"}]
                            },
                            {template: "Комментарий", type: "section"},
                            {id: "comment", view: "text", labelPosition: "left"},
                            {height: 25}
                        ]
                    },
                    {width: 25},
                    {
                        width: 270,
                        rows: [
                            {template: "Время", type: "section"},
                            {
                                id: 'starttime',
                                view: "datepicker",
                                type: "time",
                                stringResult: true,
                                format: '%H:%i',
                                placeholder: 'Начало',
                                value: edit_default_day_var.starttime
                            },
                            {
                                id: 'endtime',
                                view: "datepicker",
                                type: "time",
                                stringResult: true,
                                format: '%H:%i',
                                placeholder: 'Окончание',
                                value: edit_default_day_var.endtime
                            },
                            {template: "День недели", type: "section"},
                            {
                                id: "weekday",
                                view: "richselect",
                                labelPosition: "left",
                                value: edit_default_day_var.cycleday,
                                options: [{id: 1, value: "ПН"}, {id: 2, value: "ВТ"}, {id: 3, value: "СР"}, {
                                    id: 4,
                                    value: "ЧТ"
                                }, {id: 5, value: "ПТ"}, {id: 6, value: "СБ"}, {id: 7, value: "ВС"}]
                            }
                        ]
                    },
                    {width: 25},
                    {
                        width: 200,
                        rows: [
                            {template: "Количество участников", type: "section"},
                            {
                                id: "mincount",
                                view: "text",
                                labelPosition: "right",
                                placeholder: 'Минимум',
                                value: edit_default_day_var.mincount
                            },
                            {height: 23},
                            {
                                id: "maxcount",
                                view: "text",
                                labelPosition: "right",
                                placeholder: 'Максимум',
                                value: edit_default_day_var.maxcount
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
                            d['schedule_id'] = edit_default_day_var.id;
                            d['activity_id'] = edit_default_day_var.activity_id;
                            d['activityname'] = $$("name").getValue();
                            d['trainer_id'] = $$("trainer").getValue();
                            d['activitycomment'] = $$("comment").getValue();
                            d['starttime'] = $$("starttime").getValue();
                            d['endtime'] = $$("endtime").getValue();
                            d['cycleday'] = $$("weekday").getValue();
                            d['maxcount'] = $$("maxcount").getValue();
                            d['mincount'] = $$("mincount").getValue();
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
                                $$("win_edit_default_day").getTopParentView().hide();
                            }
                        }
                    },
                    {
                        view: "button", value: "Отмена",
                        click: function () {
                            $$("win_edit_default_day").getTopParentView().hide();
                        }
                    }
                ]
            }
        ],
        rules: {},
        elementsConfig: {
            labelPosition: "top",
        }
    };
    webix.ui({
        view: "window",
        id: "win_edit_default_day",
        width: 810,
        position: "center",
        modal: true,
        head: "Редактировать тренировку на этот день",
        body: webix.copy(edit_default_day)
    });

    function showEdit_default_day(winId, node) {
        $$(winId).getBody().clear();
        $$(winId).show(node);
        $$(winId).getBody().focus();
    };

    showEdit_default_day("win_edit_default_day");
};
function delete_it() {
    var day = $$("$sidebar1").wg[0];
    day = day.substr(day.length - 1);
    var d = $$("activity_datatable" + day);
    if (!d) d = $$("days_custom_datatable")
    var delete_day_var = d.getSelectedItem();
    if (!delete_day_var) {
        webix.message({type: "error", text: "Выберите запись!"});
        return;
    }
    var delete_day = {
        view: "form",
        borderless: true,
        elements: [
            {
                cols: [
                    {
                        view: "button", value: "Удалить", hotkey: "enter",
                        click: function () {
                            var d = {};
                            d['schedule_id'] = delete_day_var.id;
                            d['setSchedule'] = 1;
                            if (d['schedule_id'] > 0) {
                                webix.ajax().get("/controllers/db_querys.php", d);
                                $$("win_delete_day").getTopParentView().hide();
                            }
                        }
                    },
                    {
                        view: "button", value: "Отмена",
                        click: function () {
                            $$("win_delete_day").getTopParentView().hide();
                        }
                    }
                ]
            }
        ],
        rules: {},
        elementsConfig: {
            labelPosition: "top",
        }
    };
    webix.ui({
        view: "window",
        id: "win_delete_day",
        width: 300,
        position: "center",
        modal: true,
        head: "Удалить запись",
        body: webix.copy(delete_day)
    });

    function showDelete_day(winId, node) {
        $$(winId).getBody().clear();
        $$(winId).show(node);
        $$(winId).getBody().focus();
    };

    showDelete_day("win_delete_day");
};
function new_user() {
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
        rules: {},
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
function edit_user() {
    var day = $$("$sidebar1").wg[0];
    day = day.substr(day.length - 1);
    var edit_default_day_var = $$("activity_datatable" + day).getSelectedItem();
    if (!edit_default_day_var) {
        webix.message({type: "error", text: "Выберите запись!"});
        return;
    }
    var edit_default_day = {
        view: "form",
        borderless: true,
        elements: [
            {
                cols: [
                    {
                        rows: [
                            {template: "Название тренировки", type: "section"},
                            {id: "name", view: "text", labelPosition: "left", value: edit_default_day_var.activityname},
                            {template: "Тренер", type: "section"},
                            {
                                id: "trainer",
                                view: "richselect",
                                labelPosition: "left",
                                value: edit_default_day_var.trainer_id,
                                options: [{id: 1, value: "Бобруйченко"}, {id: 2, value: "Мохнатов"}]
                            },
                            {template: "Комментарий", type: "section"},
                            {id: "comment", view: "text", labelPosition: "left"},
                            {height: 25}
                        ]
                    },
                    {width: 25},
                    {
                        width: 270,
                        rows: [
                            {template: "Время", type: "section"},
                            {
                                id: 'starttime',
                                view: "datepicker",
                                type: "time",
                                stringResult: true,
                                format: '%H:%i',
                                placeholder: 'Начало',
                                value: edit_default_day_var.starttime
                            },
                            {
                                id: 'endtime',
                                view: "datepicker",
                                type: "time",
                                stringResult: true,
                                format: '%H:%i',
                                placeholder: 'Окончание',
                                value: edit_default_day_var.endtime
                            },
                            {template: "День недели", type: "section"},
                            {
                                id: "weekday",
                                view: "richselect",
                                labelPosition: "left",
                                value: edit_default_day_var.cycleday,
                                options: [{id: 1, value: "ПН"}, {id: 2, value: "ВТ"}, {id: 3, value: "СР"}, {
                                    id: 4,
                                    value: "ЧТ"
                                }, {id: 5, value: "ПТ"}, {id: 6, value: "СБ"}, {id: 7, value: "ВС"}]
                            }
                        ]
                    },
                    {width: 25},
                    {
                        width: 200,
                        rows: [
                            {template: "Количество участников", type: "section"},
                            {
                                id: "mincount",
                                view: "text",
                                labelPosition: "right",
                                placeholder: 'Минимум',
                                value: edit_default_day_var.mincount
                            },
                            {height: 23},
                            {
                                id: "maxcount",
                                view: "text",
                                labelPosition: "right",
                                placeholder: 'Максимум',
                                value: edit_default_day_var.maxcount
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
                            d['schedule_id'] = edit_default_day_var.id;
                            d['activity_id'] = edit_default_day_var.activity_id;
                            d['activityname'] = $$("name").getValue();
                            d['trainer_id'] = $$("trainer").getValue();
                            d['activitycomment'] = $$("comment").getValue();
                            d['starttime'] = $$("starttime").getValue();
                            d['endtime'] = $$("endtime").getValue();
                            d['cycleday'] = $$("weekday").getValue();
                            d['maxcount'] = $$("maxcount").getValue();
                            d['mincount'] = $$("mincount").getValue();
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
                                $$("win_edit_default_day").getTopParentView().hide();
                            }
                        }
                    },
                    {
                        view: "button", value: "Отмена",
                        click: function () {
                            $$("win_edit_default_day").getTopParentView().hide();
                        }
                    }
                ]
            }
        ],
        rules: {},
        elementsConfig: {
            labelPosition: "top",
        }
    };
    webix.ui({
        view: "window",
        id: "win_edit_default_day",
        width: 810,
        position: "center",
        modal: true,
        head: "Редактировать тренировку на этот день",
        body: webix.copy(edit_default_day)
    });

    function showEdit_default_day(winId, node) {
        $$(winId).getBody().clear();
        $$(winId).show(node);
        $$(winId).getBody().focus();
    };

    showEdit_default_day("win_edit_default_day");
};
function delete_user() {
    var user = $$("$sidebar1").wg[0];
    user = user.substr(user.length - 1);

    d = $$("record_users_datatable");
    if(!d) d = $$("users_datatable");
    var delete_user_var = d.getSelectedItem();
    if (!delete_user_var) {
        webix.message({type: "error", text: "Выберите запись!"});
        return;
    }
    var delete_user = {
        view: "form",
        borderless: true,
        elements: [
            {
                cols: [
                    {
                        view: "button", value: "Удалить", hotkey: "enter",
                        click: function () {
                            var d = {};
                            d['record_id'] = delete_user_var.record_id;
                            d['setRecord'] = 1;
                            if (d['record_id'] > 0) {
                                webix.ajax().get("/controllers/db_querys.php", d);
                                $$("win_delete_user").getTopParentView().hide();
                            }
                        }
                    },
                    {
                        view: "button", value: "Отмена",
                        click: function () {
                            $$("win_delete_user").getTopParentView().hide();
                        }
                    }
                ]
            }
        ],
        rules: {},
        elementsConfig: {
            labelPosition: "top",
        }
    };
    webix.ui({
        view: "window",
        id: "win_delete_user",
        width: 300,
        position: "center",
        modal: true,
        head: "Удалить запись",
        body: webix.copy(delete_user)
    });

    function showDelete_user(winId, node) {
        $$(winId).getBody().clear();
        $$(winId).show(node);
        $$(winId).getBody().focus();
    };

    showDelete_user("win_delete_user");
};
var call_recording_filter = {
    type: "space",
    cols: [
        {view: "button", type: "iconButton", icon: 'clock-o', width: 120, label: "Добавить", click: new_default_day},
        {
            view: "button",
            type: "iconButton",
            icon: "pencil",
            width: 160,
            label: "Редактировать",
            click: edit_default_day
        },
        {
            view: "button",
            type: "iconButton",
            icon: "remove",
            width: 115,
            label: "Удалить",
            click: delete_it
        },
        {},

    ]
};
var call_custom_filter = {
    type: "space",
    cols: [
        {view: "button", type: "iconButton", icon: 'clock-o', width: 120, label: "Добавить", click: new_custom_day},
        {
            view: "button",
            type: "iconButton",
            icon: "pencil",
            width: 160,
            label: "Редактировать",
            click: edit_custom_day
        },
        {
            view: "button",
            type: "iconButton",
            icon: "remove",
            width: 115,
            label: "Удалить",
            click: delete_it
        },
        {},
    ]
};
var users_filter = {
    type: "space",
    cols: [
        //{view: "button", type: "iconButton", icon: 'clock-o', width: 120, label: "Добавить", click: new_user},
        //{view: "button",  type: "iconButton", icon: "pencil", width: 160, label: "Редактировать", click: edit_user},
        {
            view: "button",
            type: "iconButton",
            icon: "remove",
            width: 115,
            label: "Удалить",
            click: delete_user
        },
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
function maxcount(mystr) {
    if (mystr['maxcount'] != false && mystr['maxcount'] != undefined) return mystr['maxcount'];
    else return '';
}
function mincount(mystr) {
    if (mystr['mincount'] != false && mystr['mincount'] != undefined) return mystr['mincount'];
    else return '';
}
function endtime(mystr) {
    if (mystr['endtime'] != false && mystr['endtime'] != undefined) return mystr['endtime'];
    else return '';
}
function starttime(mystr) {
    if (mystr['starttime'] != false && mystr['starttime'] != undefined) return mystr['starttime'];
    else return '';
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
                        id: "activity_datatable" + i,
                        view: "datatable",
                        resizeColumn: true,
                        resizeRow: false,
                        hover: "myhover",
                        footer: true,
                        navigation: true,
                        tooltip: false,
                        css: "my_style",
                        select: "row",
                        columns: [
                            {
                                id: "num",
                                header: "",
                                width: 50,
                                css: {"text-align": "right"},
                                template: deact
                            },
                            {
                                id: "starttime",
                                header: "Начало",
                                width: 70,
                                css: {"text-align": "left"},
                                template: starttime
                            },
                            {
                                id: "endtime",
                                header: "Окончание",
                                width: 90,
                                css: {"text-align": "left"},
                                template: endtime
                            },
                            {
                                id: "activityname",
                                header: [{text: "Название тренировки"}],
                                width: 200,
                                sort: "server"
                            },
                            {
                                id: "maxcount",
                                header: [{text: "Квота"}],
                                width: 70,
                                editor: "int",
                                template: maxcount
                            },
                            {
                                id: "activitycomment",
                                header: [{text: "Комментарий"}],
                                tooltip: true,
                                width: 400,
                            }
                        ],
                        url: "/controllers/db_querys.php?getScheduleCicle=1&cycleday=" + i
                    }
                ]
            },
        ],
    };
}

var days_custom = {
    id: "days_custom",
    rows: [
        call_custom_filter,
        {
            cols: [
                {
                    id: "days_custom_datatable",
                    view: "datatable",
                    resizeColumn: true,
                    resizeRow: false,
                    hover: "myhover",
                    footer: true,
                    navigation: true,
                    tooltip: false,
                    css: "my_style",
                    select: "row",
                    columns: [
                        {
                            id: "num",
                            header: "",
                            width: 50,
                            css: {"text-align": "right"},
                            template: deact
                        },
                        {
                            id: "activitydate",
                            header: "Дата",
                            width: 100,
                            css: {"text-align": "left"},
                        },
                        {
                            id: "starttime",
                            header: "Начало",
                            width: 70,
                            css: {"text-align": "left"},
                            template: starttime
                        },
                        {
                            id: "endtime",
                            header: "Окончание",
                            width: 90,
                            css: {"text-align": "left"},
                            template: endtime
                        },
                        {
                            id: "activityname",
                            header: [{text: "Название тренировки"}],
                            width: 200,
                            sort: "server"
                        },
                        {
                            id: "maxcount",
                            header: [{text: "Квота"}],
                            width: 70,
                            editor: "int",
                            template: maxcount
                        },
                        {
                            id: "activitycomment",
                            header: [{text: "Комментарий"}],
                            tooltip: true,
                            width: 400,
                        }
                    ],
                    url: "/controllers/db_querys.php?getSchedule=1&adm=1"
                }
            ]
        },
    ],
}

var record_users = {
    id: "record_users",
    rows: [
        users_filter,
        {
            cols: [
                {
                    id: "record_users_datatable",
                    view: "datatable",
                    resizeColumn: true,
                    resizeRow: false,
                    hover: "myhover",
                    footer: true,
                    navigation: true,
                    tooltip: false,
                    css: "my_style",
                    select: "row",
                    columns: [
                        {
                            id: "num",
                            header: "",
                            width: 50,
                            css: {"text-align": "right"},
                            //template: deact
                        },
                        {
                            id: "activitydate",
                            header: "Дата",
                            width: 100,
                            css: {"text-align": "left"},
                        },
                        {
                            id: "starttime",
                            header: "Начало",
                            width: 70,
                            css: {"text-align": "left"},
                            template: starttime
                        },
                        {
                            id: "username",
                            header: [{text: "Имя"}],
                            width: 200,
                            template:"#username# #surname#",
                            sort: "server"
                        },
                        {
                            id: "phone",
                            header: [{text: "Телефон"}],
                            width: 120,
                            editor: "int"
                        },
                        {
                            id: "activityname",
                            header: [{text: "Название тренировки"}],
                            width: 150,
                        },
                        {
                            id: "usercomment",
                            header: [{text: "Комментарий к пользователю"}],
                            tooltip: true,
                            width: 400,
                        }
                    ],
                    url: "/controllers/db_querys.php?getAdmUsersRecords=1&adm=1"
                }
            ]
        },
    ],
};

 var users = {
 id: "users",
 rows: [
 users_filter,
 {
 cols: [
 {
 id: "users_datatable",
 view: "datatable",
 resizeColumn: true,
 resizeRow: false,
 hover: "myhover",
 footer: true,
 navigation: true,
 tooltip: false,
 css: "my_style",
 select: "row",
 columns: [
 {
 id: "num",
 header: "",
 width: 50,
 css: {"text-align": "right"},

 },
 {
 id: "username",
 header: [{text: "Имя"}],
 width: 200,
 template:"#username# #surname#",
 sort: "server"
 },
 {
 id: "phone",
 header: [{text: "Телефон"}],
 width: 120,
 editor: "int"
 },
 {
 id: "usercomment",
 header: [{text: "Комментарий к пользователю"}],
 tooltip: true,
 width: 400,
 }
 ],
 url: "/controllers/db_querys.php?getUsers=1&adm=1"
 }
 ]
 },
 ],
 };