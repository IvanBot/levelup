/**
 * Created by a.bulatova on 15.07.2016.
 */
function yesterday(x) {
    x.setDate(x.getDate() - 1);
    return x;
};

webix.i18n.timeFormat = "%H:%i";
webix.i18n.dateFormat = "%Y-%m-%d";
webix.i18n.setLocale();
function call_custom_refresh() {
//    $$("from").data.value = '';

    $(".webix_cal_icon_clear").click();
    if($$("deleted").data.value != undefined)$$("deleted").setValue('0')
    $$("days_custom_datatable").enable();
    $$("days_custom_datatable").clearAll();
    $$("days_custom_datatable").showProgress({type: "icon", hide: true});
    $$("days_custom_datatable").load("routes.php?getSchedule=1&adm=1");
};
function call_default_refresh() {
    if($$("deleted").data.value != undefined)$$("deleted").setValue('0')
    var day = $$("$sidebar1").wg[0];
    day = day.substr(day.length - 1);
    $$("activity_datatable" + day).enable();
    $$("activity_datatable" + day).clearAll();
    $$("activity_datatable" + day).showProgress({type: "icon", hide: true});
    $$("activity_datatable" + day).load("/admin/getScheduleCicle?cycleday=" + day);
};
function call_custom_fil() {
    if ($$("to").data.value < $$("from").data.value)webix.message({
        type: "error",
        text: "Дата окончания меньше даты начала"
    });
    var from = $$("from").data.value != undefined ? $$("from").data.text : '';
    var to = $$("to").data.value != undefined ? $$("to").data.text : '';
    var del = $$("deleted").data.value != undefined ? $$("deleted").data.value : '';
    if (!($$("to").data.value < $$("from").data.value)) {
        $$("days_custom_datatable").enable();
        $$("days_custom_datatable").clearAll();
        $$("days_custom_datatable").showProgress({type: "icon", hide: true});
        $$("days_custom_datatable").load("/admin/getSchedule?adm=1&from=" + from + "&to=" + to + "&del=" + del);
    }
};


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
                            if (!d['activityname'])webix.message({type: "error", text: "Введите название тренировки"});
                            if (!d['starttime'])webix.message({
                                type: "error",
                                text: "Введите время начала тренировки"
                            });
                            if (!d['activitydate'])webix.message({
                                type: "error",
                                text: "Выберите день проведения тренировки"
                            });
                            var from = $$("from").data.value != undefined ? $$("from").data.text : '';
                            var to = $$("to").data.value != undefined ? $$("to").data.text : '';
                            var del = $$("deleted").data.value != undefined ? $$("deleted").data.value : '';
                            if (d['activityname'] && d['starttime'] && d['activitydate']) {
                                webix.ajax().get("../routes.php", d);
                                $$("win_order").getTopParentView().hide();
                                $$("days_custom_datatable").clearAll();
                                $$("days_custom_datatable").showProgress({type: "icon", hide: true});
                                $$("days_custom_datatable").load("/admin/getSchedule?adm=1&from=" + from + "&to=" + to + "&del=" + del);
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
                            {
                                id: "comment",
                                view: "text",
                                labelPosition: "left",
                                value: edit_custom_day_var.activitycomment
                            },
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
                                labelPosition: "left",
                                value: edit_custom_day_var.activitydate
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
                            if (!d['activityname'])webix.message({type: "error", text: "Введите название тренировки"});
                            if (!d['starttime'])webix.message({
                                type: "error",
                                text: "Введите время начала тренировки"
                            });
                            if (!d['activitydate'])webix.message({type: "error", text: "Выберите день"});
                            if (d['activityname'] && d['starttime'] && d['activitydate']) {
                                webix.ajax().get("../routes.php", d);
                                $$("win_edit_custom_day").getTopParentView().hide();
                                $$("days_custom_datatable").clearAll();
                                $$("days_custom_datatable").showProgress({type: "icon", hide: true});
                                $$("days_custom_datatable").load("/admin/getSchedule?adm=1");
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
    //$$("starttime").disable();
    //$$("activitydate").disable();
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
        elements: [
            {
                cols: [
                    {
                        rows:[
                            { template: "Общее", type: "section" },
                            {
                                rows:[
                                    { id:"add_def_name", view:"text", label:"Название тренировки", labelWidth:170, labelAlign:"right", labelPosition:"left", placeholder:"Название тренировки" },
                                    {
                                        id:"add_def_trainer", view:"richselect", label:"Выбор тренера", labelWidth:170, labelAlign:"right", labelPosition:"left", placeholder:"Выбор тренера",
                                        options: [
                                            {id: 1, value: "Бобруйченко"},
                                            {id: 2, value: "Мохнатов"}
                                        ]
                                    },
                                    {
                                        id:"add_def_max", view:"counter", label:"Число участников", labelWidth:170, labelAlign:"right", labelPosition:"left", min:1, value:1,
                                        on:{
                                            onChange:function(){
                                                if(isNaN($$('add_def_max').getValue())) $$('add_def_max').setValue("1");
                                            }
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    { width:30 },
                    {
                        rows:[
                            { template: "День и время", type: "section" },
                            {
                                rows:[
                                    { id: 'add_def_time_start', view: "datepicker", type: "time", stringResult: true, format: '%H:%i', placeholder: 'Начало', label:"Начало", labelWidth:120, labelAlign:"right", labelPosition:"left" },
                                    { id: 'add_def_time_end', view: "datepicker", type: "time", stringResult: true, format: '%H:%i', placeholder: 'Окончание', label:"Окончание", labelWidth:120, labelAlign:"right", labelPosition:"left" },
                                    {
                                        id:"add_def_weekday", view:"richselect", labelPosition:"left", value:day, label:"День недели", labelWidth:120, labelAlign:"right",
                                        options: [
                                            {id: 1, value: "Понедельник"},
                                            {id: 2, value: "Вторник"},
                                            {id: 3, value: "Среда"},
                                            {id: 4, value: "Четверг"},
                                            {id: 5, value: "Пятница"},
                                            {id: 6, value: "Суббота"},
                                            {id: 7, value: "Воскресенье"}
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            { template:"<center>Комментарий</center>", type:"clean", height:25 },
            { id:"add_def_comment", view:"textarea", height:150, labelPosition:"top" },
            {
                cols: [
                    {},
                    {
                        view: "button", value: "Сохранить", hotkey: "enter",
                        click: function () {
                            var d = {};
                            d['activityname'] = $$("add_def_name").getValue();
                            d['trainer_id'] = $$("add_def_trainer").getValue();
                            d['activitycomment'] = $$("add_def_comment").getValue();
                            d['starttime'] = $$("add_def_time_start").getValue();
                            d['endtime'] = $$("add_def_time_end").getValue();
                            d['cycleday'] = $$("add_def_weekday").getValue();
                            d['maxcount'] = $$("add_def_max").getValue();
                            if (!d['activityname'])webix.message({type: "error", text: "Введите название тренировки"});
                            if (!d['starttime'])webix.message({
                                type: "error",
                                text: "Введите время начала тренировки"
                            });
                            if (!d['cycleday'])webix.message({type: "error", text: "Выберите день недели"});
                            if (d['activityname'] && d['starttime']) {
                                webix.ajax().get("/admin/addScheduleCicle", d, function(text){
                                    txt = JSON.parse(text);
                                    if(txt.result==0) webix.message(txt.message);
                                    else webix.message({type:"error", text:txt.message});
                                    $$("win_order").getTopParentView().hide();
                                    $$("activity_datatable" + d['cycleday']).clearAll();
                                    $$("activity_datatable" + d['cycleday']).showProgress({type: "icon", hide: true});
                                    $$("activity_datatable" + d['cycleday']).load("/admin/getScheduleCicle?cycleday=" + d['cycleday']);
                                });
                            }
                        }
                    },
                    {
                        view: "button", value: "Отмена",
                        click: function () {
                            $$("win_order").getTopParentView().hide();
                        }
                    },
                    {}
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
        width: 800,
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
                        rows:[
                            { template: "Общее", type: "section" },
                            {
                                rows:[
                                    { id:"edit_def_name", view:"text", label:"Название тренировки", labelWidth:170, labelAlign:"right", labelPosition:"left", value:edit_default_day_var.activityname },
                                    {
                                        id:"edit_def_trainer", view:"richselect", label:"Выбор тренера", labelWidth:170, labelAlign:"right", labelPosition:"left", value:edit_default_day_var.trainer_id,
                                        options: [
                                            {id: 1, value: "Бобруйченко"},
                                            {id: 2, value: "Мохнатов"}
                                        ]
                                    },
                                    {
                                        id:"edit_def_max", view:"counter", label:"Число участников", labelWidth:170, labelAlign:"right", labelPosition:"left", min:1, value:edit_default_day_var.maxcount,
                                        on:{
                                            onChange:function(){
                                                if(isNaN($$('add_def_max').getValue())) $$('add_def_max').setValue("1");
                                            }
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    { width:30 },
                    {
                        rows:[
                            { template: "День и время", type: "section" },
                            {
                                rows:[
                                    { id: 'edit_def_time_start', view: "datepicker", type: "time", stringResult: true, format: '%H:%i', value:edit_default_day_var.starttime, label:"Начало", labelWidth:120, labelAlign:"right", labelPosition:"left" },
                                    { id: 'edit_def_time_end', view: "datepicker", type: "time", stringResult: true, format: '%H:%i', value:edit_default_day_var.endtime, label:"Окончание", labelWidth:120, labelAlign:"right", labelPosition:"left" },
                                    {
                                        id:"edit_def_weekday", view:"richselect", labelPosition:"left", value:edit_default_day_var.cycleday, label:"День недели", labelWidth:120, labelAlign:"right",
                                        options: [
                                            {id: 1, value: "Понедельник"},
                                            {id: 2, value: "Вторник"},
                                            {id: 3, value: "Среда"},
                                            {id: 4, value: "Четверг"},
                                            {id: 5, value: "Пятница"},
                                            {id: 6, value: "Суббота"},
                                            {id: 7, value: "Воскресенье"}
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            { template:"<center>Комментарий</center>", type:"clean", height:25 },
            { id:"edit_def_comment", view:"textarea", height:150, labelPosition:"top", value:edit_default_day_var.comment },
            {
                cols: [
                    {},
                    {
                        view: "button", value: "Сохранить", hotkey: "enter",
                        click: function () {
                            var d = {};
                            d['schedule_id'] = edit_default_day_var.id;
                            d['activity_id'] = edit_default_day_var.activity_id;
                            d['activityname'] = $$("edit_def_name").getValue();
                            d['trainer_id'] = $$("edit_def_trainer").getValue();
                            d['comment'] = $$("edit_def_comment").getValue();
                            d['starttime'] = $$("edit_def_time_start").getValue();
                            d['endtime'] = $$("edit_def_time_end").getValue();
                            d['cycleday'] = $$("edit_def_weekday").getValue();
                            d['maxcount'] = $$("edit_def_max").getValue();
                            if (!d['activityname'])webix.message({type: "error", text: "Введите название тренировки"});
                            if (!d['starttime'])webix.message({
                                type: "error",
                                text: "Введите время начала тренировки"
                            });
                            if (!d['cycleday'])webix.message({type: "error", text: "Выберите день недели"});
                            if (d['activityname'] && d['starttime']) {
                                webix.ajax().get("/admin/editScheduleCicle", d, function(text){
                                    txt = JSON.parse(text);
                                    if(txt.result==0) webix.message(txt.message);
                                    else webix.message({type:"error", text:txt.message});
                                    $$("win_edit_default_day").getTopParentView().hide();
                                    $$("activity_datatable" + d['cycleday']).clearAll();
                                    $$("activity_datatable" + d['cycleday']).showProgress({type: "icon", hide: true});
                                    $$("activity_datatable" + d['cycleday']).load("/admin/getScheduleCicle?cycleday=" + d['cycleday']);
                                    if (day != d['cycleday']) {
                                        $$("activity_datatable" + day).clearAll();
                                        $$("activity_datatable" + day).showProgress({type: "icon", hide: true});
                                        $$("activity_datatable" + day).load("/admin/getScheduleCicle?cycleday=" + day);
                                    }
                                });
                            }
                        }
                    },
                    {
                        view: "button", value: "Отмена",
                        click: function () {
                            $$("win_edit_default_day").getTopParentView().hide();
                        }
                    },
                    {}
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
    //$$("starttime").disable();
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
                            var c = delete_day_var.cycleday;
                            d['setSchedule'] = 1;
                            if (d['schedule_id'] > 0) {
                                webix.ajax().get("/admin/delScheduleCicle", d, function(text){
                                    txt = JSON.parse(text);
                                    if(txt.result==0) webix.message(txt.message);
                                    else webix.message({type:"error", text:txt.message});
                                    $$("win_delete_day").getTopParentView().hide();
                                    if (c) {
                                        $$("activity_datatable" + c).clearAll();
                                        $$("activity_datatable" + c).showProgress({type: "icon", hide: true});
                                        $$("activity_datatable" + c).load("/admin/getScheduleCicle?cycleday=" + c);
                                    } else {
                                        $$("days_custom_datatable").clearAll();
                                        $$("days_custom_datatable").showProgress({type: "icon", hide: true});
                                        $$("days_custom_datatable").load("/admin/getSchedule?adm=1");
                                    }
                                });
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
function refresh_record(){
    //$$("record_date").setValue("");
    $$("record_date").setValue(new Date());
    $$("record_users_datatable").clearAll();
    $$("record_users_datatable").showProgress({type: "icon", hide: true});
    $$("record_users_datatable").load("/admin/getAdmUsersRecords?adm=1");
};
function record_date(){
    var sd = $$("record_date").getValue();
    var m = (sd.getMonth()+1 <10) ? "0"+(sd.getMonth()+1) : (sd.getMonth()+1);
    var d = (sd.getDate() <10) ? "0"+sd.getDate() : sd.getDate();
    var sd1 = sd.getFullYear()+"-"+m+"-"+d;
    $$("record_users_datatable").clearAll();
    $$("record_users_datatable").showProgress({type: "icon", hide: true});
    $$("record_users_datatable").load("/admin/getAdmUsersRecords?adm=1&date="+sd1);
};
function edit_record(){
    var edit_record = $$("record_users_datatable").getSelectedItem();
    if (!edit_record) {
        webix.message({type: "error", text: "Выберите запись!"});
        return;
    }

    var edit_record_item = {
        view: "form",
        borderless: true,
        elements: [
            {
                cols: [
                    {
                        rows:[
                            { template: "Общее", type: "section" },
                            {
                                rows:[
                                    { id:"edit_def_name", view:"text", label:"Название тренировки", labelWidth:170, labelAlign:"right", labelPosition:"left", value:edit_record.activityname },
                                    {
                                        id:"edit_def_trainer", view:"richselect", label:"Выбор тренера", labelWidth:170, labelAlign:"right", labelPosition:"left", value:edit_record.trainer_id,
                                        options: [
                                            {id: 1, value: "Бобруйченко"},
                                            {id: 2, value: "Мохнатов"}
                                        ]
                                    },
                                    {
                                        id:"edit_def_max", view:"counter", label:"Число участников", labelWidth:170, labelAlign:"right", labelPosition:"left", min:1, value:edit_record.maxcount,
                                        on:{
                                            onChange:function(){
                                                if(isNaN($$('add_def_max').getValue())) $$('add_def_max').setValue("1");
                                            }
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    { width:30 },
                    {
                        rows:[
                            { template: "День и время", type: "section" },
                            {
                                rows:[
                                    { id: 'edit_def_time_start', view: "datepicker", type: "time", stringResult: true, format: '%H:%i', value:edit_record.starttime, label:"Начало", labelWidth:120, labelAlign:"right", labelPosition:"left" },
                                    { id: 'edit_def_time_end', view: "datepicker", type: "time", stringResult: true, format: '%H:%i', value:edit_record.endtime, label:"Окончание", labelWidth:120, labelAlign:"right", labelPosition:"left" },
                                    {
                                        id:"edit_def_weekday", view:"richselect", labelPosition:"left", value:edit_record.cycleday, label:"День недели", labelWidth:120, labelAlign:"right",
                                        options: [
                                            {id: 1, value: "Понедельник"},
                                            {id: 2, value: "Вторник"},
                                            {id: 3, value: "Среда"},
                                            {id: 4, value: "Четверг"},
                                            {id: 5, value: "Пятница"},
                                            {id: 6, value: "Суббота"},
                                            {id: 7, value: "Воскресенье"}
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            { template:"<center>Комментарий</center>", type:"clean", height:25 },
            { id:"edit_def_comment", view:"textarea", height:150, labelPosition:"top", value:edit_record.comment },
            {
                cols: [
                    {},
                    {
                        view: "button", value: "Сохранить", hotkey: "enter",
                        click: function () {
                            var d = {};
                            d['schedule_id'] = edit_record.id;
                            d['activity_id'] = edit_record.activity_id;
                            d['activityname'] = $$("edit_def_name").getValue();
                            d['trainer_id'] = $$("edit_def_trainer").getValue();
                            d['comment'] = $$("edit_def_comment").getValue();
                            d['starttime'] = $$("edit_def_time_start").getValue();
                            d['endtime'] = $$("edit_def_time_end").getValue();
                            d['cycleday'] = $$("edit_def_weekday").getValue();
                            d['maxcount'] = $$("edit_def_max").getValue();
                            if (!d['activityname'])webix.message({type: "error", text: "Введите название тренировки"});
                            if (!d['starttime'])webix.message({
                                type: "error",
                                text: "Введите время начала тренировки"
                            });
                            if (!d['cycleday'])webix.message({type: "error", text: "Выберите день недели"});
                            if (d['activityname'] && d['starttime']) {
                                webix.ajax().get("/admin/editScheduleCicle", d, function(text){
                                    txt = JSON.parse(text);
                                    if(txt.result==0) webix.message(txt.message);
                                    else webix.message({type:"error", text:txt.message});
                                    $$("win_edit_custom_day").getTopParentView().hide();
                                    $$("activity_datatable" + d['cycleday']).clearAll();
                                    $$("activity_datatable" + d['cycleday']).showProgress({type: "icon", hide: true});
                                    $$("activity_datatable" + d['cycleday']).load("/admin/getScheduleCicle?cycleday=" + d['cycleday']);
                                    if (day != d['cycleday']) {
                                        $$("activity_datatable" + day).clearAll();
                                        $$("activity_datatable" + day).showProgress({type: "icon", hide: true});
                                        $$("activity_datatable" + day).load("/admin/getScheduleCicle?cycleday=" + day);
                                    }
                                });
                            }
                        }
                    },
                    {
                        view: "button", value: "Отмена",
                        click: function () {
                            $$("win_edit_custom_day").getTopParentView().hide();
                        }
                    },
                    {}
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
        head: "Редактировать запись на занятие",
        body: webix.copy(edit_record_item)

    });
    function showEdit_custom_day(winId, node) {
        $$(winId).getBody().clear();
        $$(winId).show(node);
        $$(winId).getBody().focus();
    };

    showEdit_custom_day("win_edit_custom_day");
};
function delete_record() {
    var user = $$("$sidebar1").wg[0];
    user = user.substr(user.length - 1);
    d = $$("record_users_datatable");
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
                            d['delRecord'] = 1;
                            if (d['record_id'] > 0) {
                                webix.ajax().get("../routes.php", d);
                                $$("win_delete_user").getTopParentView().hide();
                                $$("record_users_datatable").clearAll();
                                $$("record_users_datatable").showProgress({type: "icon", hide: true});
                                $$("record_users_datatable").load("/admin/getAdmUsersRecords?adm=1");
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
function delete_user() {
    var user = $$("$sidebar1").wg[0];
    user = user.substr(user.length - 1);

    d = $$("users_datatable");
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
                            d['user_id'] = delete_user_var.user_id;
                            d['delUsers'] = 1;
                            if (d['user_id'] > 0) {
                                webix.ajax().get("../routes.php", d);
                                $$("win_delete_user").getTopParentView().hide();
                                $$("users_datatable").clearAll();
                                $$("users_datatable").showProgress({type: "icon", hide: true});
                                $$("users_datatable").load("/admin/getUsers?adm=1");
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
        { view: "button", type: "iconButton", icon: 'clock-o', width: 120, label: "Добавить", click: new_default_day },
        { view: "button", type: "iconButton", icon: "pencil", width: 160, label: "Редактировать", click: edit_default_day },
        { view: "button", type: "iconButton", icon: "remove", width: 115, label: "Удалить", click: delete_it },
        { view: "button", type: "iconButton", icon: "refresh", width: 38, label: "", click: call_default_refresh },
        {},

    ]
};
var call_custom_filter = {
    type: "space",
    cols: [
        { view: "button", type: "iconButton", icon: 'clock-o', width: 120, label: "Добавить", click: new_custom_day },
        { view: "button", type: "iconButton", icon: "pencil", width: 160, label: "Редактировать", click: edit_custom_day },
        { view: "button", type: "iconButton", icon: "remove", width: 115, label: "Удалить", click: delete_it },
        { view: "button", type: "iconButton", icon: "refresh", width: 38, label: "", click: call_custom_refresh },
        {},
        { id: 'from', view: "datepicker", format: '%Y-%m-%d', timepicker: false, width: 120, placeholder: 'Начало', value:'' },
        { id: 'to', view: "datepicker", format: '%Y-%m-%d', timepicker: false, width: 120, placeholder: 'Окончание' },
        { id: 'deleted', width: 110, view: "checkbox", label: "Удаленные", checkValue: 1, labelWidth: 88, format: '%H:%i' },
        { view: "button", type: "iconButton", icon: "filter", width: 38, label: "", click: call_custom_fil }
    ]
};
var record_filter = {
    type: "space",
    cols: [
        //{view: "button", type: "iconButton", icon: 'clock-o', width: 120, label: "Добавить", click: new_user},
        { view: "button",  type: "iconButton", icon: "pencil", width: 160, label: "Редактировать", click:edit_record },
        { view: "button", type: "iconButton", icon: "remove", width: 115, label: "Удалить", click:delete_record },
        { view: "button", type: "iconButton", icon: "refresh", width: 38, label: "", click:refresh_record },
        {},
        { id:"record_date", view:"datepicker", width:190, label:'Дата', labelWidth:50, format:"%d-%m-%Y", value:new Date(), on:{onchange:record_date} }
    ]
};
var users_filter = {
    type: "space",
    cols: [
        //{view: "button", type: "iconButton", icon: 'clock-o', width: 120, label: "Добавить", click: new_user},
        //{view: "button",  type: "iconButton", icon: "pencil", width: 160, label: "Редактировать", click: edit_user},
        { view: "button", type: "iconButton", icon: "remove", width: 115, label: "Удалить", click: delete_user },
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

var days_default = [];              //Расписание по умолчанию
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
                            { id: "num", header: "", width: 50, css: {"text-align": "right"}, template: deact },
                            { id: "starttime", header: "Начало", width: 70, css: {"text-align": "left"}, template: starttime },
                            { id: "endtime", header: "Окончание", width: 90, css: {"text-align": "left"}, template: endtime },
                            { id: "activityname", header: [{text: "Название тренировки"}], width: 200 },
                            { id: "maxcount", header: [{text: "Квота"}], width: 70, editor: "int", template: maxcount },
                            { id: "comment", header: [{text: "Комментарий"}], tooltip: true, width: 400 }
                        ],
                        url: "/admin/getScheduleCicle?cycleday=" + i,
                        ready: function () {
                            webix.extend(this, webix.ProgressBar);
                        }
                    }
                ]
            },
        ],
    };
}

var days_custom = {     //Дополнительные занятия
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
                        { id: "num", header: "", width: 50, css: {"text-align": "right"}, template: deact },
                        { id: "activitydate", header: [{text: "Дата"}], width: 100, css: {"text-align": "left"} },
                        { id: "starttime", header: "Начало", width: 70, css: {"text-align": "left"}, template: starttime },
                        { id: "endtime", header: "Окончание", width: 90, css: {"text-align": "left"}, template: endtime },
                        { id: "activityname", header: [{text: "Название тренировки"}], width: 200 },
                        { id: "maxcount", header: [{text: "Квота"}], width: 70, editor: "int", template: maxcount },
                        { id: "activitycomment", header: [{text: "Комментарий"}], tooltip: true, width: 400 }
                    ],
                    url: "/admin/getSchedule?adm=1",
                    ready: function () {
                        webix.extend(this, webix.ProgressBar);
                    }
                }
            ]
        },
    ],
}

var record_users = {        //Запись на занятия
    id: "record_users",
    rows: [
        record_filter,
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
                        { id: "num", header: "", width: 50, css: {"text-align": "right"} },
                        { id: "activitydate", header: "Дата", width: 100, css: {"text-align": "left"} },
                        { id: "starttime", header: "Начало", width: 70, css: {"text-align": "left"}, template: starttime },
                        { id: "username", header: [{text: "Имя"}], width: 200 },
                        { id: "phone", header: [{text: "Телефон"}], width: 120, editor: "int" },
                        { id: "activityname", header: [{text: "Название тренировки"}], width: 150, },
                        { id: "usercomment", header: [{text: "Комментарий к пользователю"}], tooltip: true, width: 400, }
                    ],
                    url: "/admin/getAdmUsersRecords?&adm=1",
                    ready: function () {
                        webix.extend(this, webix.ProgressBar);
                    }
                }
            ]
        },
    ],
};

var users = {       //Контакты клиентов
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
                        { id: "num", header: "", width: 50, css: {"text-align": "right"}, },
                        { id: "username", header: [{text: "Имя"}], width: 200, template: "#username# #surname#" },
                        { id: "phone", header: [{text: "Телефон"}], width: 120, editor: "int" }
                    ],
                    url: "/admin/getUsers?adm=1",
                    ready: function () {
                        webix.extend(this, webix.ProgressBar);
                    }
                }
            ]
        },
    ],
};