window.onload = function() {

	function readCookie(name) {
	    var nameEQ = name + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0;i < ca.length;i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1,c.length);
	        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	    }
	    return null;
	}	

	function login() {
		token_flag = 1;
		// проверка токена
		// if(webix.storage.cookie.get("token")) webix.storage.cookie.clear();
		if(readCookie('token_name')) webix.storage.cookie.clear();
		var form_profile = {
			view:"form",
			borderless:true,
			elements: [
				{ id:"profile_login",view:"text", label:"Логин", name:"profile_login", invalidMessage:"Должно быть заполнено" },
				{ id:"profile_password",view:"text", type:"password", label:"Пароль", name:"profile_password", invalidMessage:"Должно быть заполнено" },
				{ view:"button", value:"Вход", hotkey:"enter",
					click:function(){
						if ( $$("profile_login").getParentView().validate() ) {
							var login = $$("profile_login").getValue();
							var passw = $$("profile_password").getValue();

							$.ajax({
								type: 'GET',
								url: '/admin/login',
								data: 'login=' + login + '&passw=' + passw,
								success: function(data) {
									var data = JSON.parse(data);
									if ( data.result == 0 ) {
										console.log(readCookie('time_taken'));
										console.log(readCookie('time_expire'));

										// webix.storage.cookie.put("token", "1");
										// webix.storage.cookie.put("username", "Админ");
										// $$("profile_login").getTopParentView().hide();

										// обновляет страницу, но теперь есть cookie
										// location.href = '/admin/';
										
									}
									else if ( data.result > 0 ) {
										webix.message({type:"error",text:"Неверный логин или пароль"});
									}
								}
							});

							// if(login=="admin" && passw=="721982") {
							// 	webix.storage.cookie.put("token", "1");
							// 	webix.storage.cookie.put("username", "Админ");
							// 	$$("profile_login").getTopParentView().hide();
							// 	location.href = '/admin/';
							// }
							// else webix.message({type:"error",text:"Неверный логин или пароль"});
						};
					}
				},
				{ view:"button", value:"Отмена (гостевой доступ)",
					click:function(){ 
						$$("profile_login").getTopParentView().hide();
					}
				}
			],
			rules:{
				"profile_login":webix.rules.isNotEmpty,
				"profile_password":webix.rules.isNotEmpty
			},
			elementsConfig:{
				labelPosition:"top",
			}
		};

		webix.ui({
			view:"window",
			id:"win_profile",
			width:300,
			position:"center",
			modal:true,
			head:"Авторизация",
			body:webix.copy(form_profile)
		});

		function showForm_profile(winId, node){
			$$(winId).getBody().clear();
			$$(winId).show(node);
			$$(winId).getBody().focus();
		};

		showForm_profile("win_profile") ;
	};

	// Start?
	var text = 0;

	// проверка токена
	if(readCookie('token_name')) {

		// проверить с сервером
		
		// text = 0/1
		text = 1;
	};

	// if(webix.storage.cookie.get("token")) {
	// 	text = 1;
	// };

	if(text==0) {
		//webix.storage.cookie.clear();
		var account = {
			rows:[
				{},
				{
					cols:[
						{ view:"button", type:"iconButton", icon:"sign-in", width:90, borderless:true, label:"Вход", click:function(){ login() } },
						{ width:50 }
					]
				}
			]
		};
	}
	else {
		var account = {
			rows: [
				{height: 5},
				{
					view: "menu", width: 200,
					template: function (obj) {
						var html = "<div style='height:100%;width:100%;'>";
						html += "<!--img src='/img/account.png' class='profile_photo'/><font class='profile_text'-->" + webix.storage.cookie.get("username") + " </font>";
						html += "<span class='webix_icon fa-angle-down'></span></div>";
						return html;
					},
					submenuConfig: {
						padding: 0
					},
					type: {
						height: 50
					},
					data: [
						{
							width: 200,
							padding: 0,
							submenu: [
								{id: 4, icon: "sign-out", value:"Выход"}
							],
						}
					],
					on: {
						onMenuItemClick: function (id) {
							if (this.getMenuItem(id).id == 4) {
								webix.storage.cookie.clear();
								setTimeout(function () {
									location.href = "index.html";
								}, 10);
							};
						}
					}
				}
			]
		}
	};

	function show_progress_bar(delay) {
	}
	/* Functions - start */

	function checkAutorization() {
	    var result = false;
	    // проверка токена
	    if (readCookie('token_name')) {
	        result = true;
	    }
	    // if (webix.storage.cookie.get("token")) {
	    //     result = true;
	    // }
	    ;
	    return result;
	};

	function switchFlag() {
	    if (checkAutorization()) token_flag = 1;
	    else token_flag = 0;
	};
	var token_flag = 0;
	switchFlag();
	// проверка токена
	// if (webix.storage.cookie.get("token")) var menu = {
	if (readCookie('token_name')) var menu = {
	    view: "sidebar",
	    data: [
	        {
	            id: "schedule", value: "Расписание по-умолчанию", icon: 'calendar',
	            data: [
	                {id: "day1", value: "ПН"},
	                {id: "day2", value: "ВТ"},
	                {id: "day3", value: "СР"},
	                {id: "day4", value: "ЧТ"},
	                {id: "day5", value: "ПТ"},
	                {id: "day6", value: "СБ"},
	                {id: "day7", value: "ВС"}
	            ]
	        },
	        /*{id: "days_custom", value: "Дополнительные занятия", icon: 'calendar-o'},*/
	        /*{
	            id: "clients", value: "Клиенты", icon: 'group',
	            data: [*/
	                { id: "record_users", value: "Запись на занятия", icon: 'group' },
	                /*{id: "users", value: "Контакты клиентов"}
	            ]
	        },*/
	        /*{id: "trener", value: "Тренеры", icon: 'child'},
	        {id: "activity", value: "Занятия", icon: 'bicycle'}*/
	    ],
	    on: {
	        onAfterSelect: function (id) {
	            //var mid = this.getItem(id).id;
	            $$("main").showBatch(id);
	            $$("header").setValue(this.getItem(id).value);
	        }
	    }
	};
	else var menu = {
	    view: "sidebar",
	    data: [
	        {
	            id: "schedule", value: "Расписание по-умолчанию", select: true,
	            data: [{id: "day1", value: "ПН"}
	            ]
	        }
	    ],
	    on: {
	        onAfterSelect: function (id) {
	            //var mid = this.getItem(id).id;
	            $$("main").showBatch(id);
	            $$("header").setValue(this.getItem(id).value);
	        }
	    }
	};
	var views = {
	    rows: [
	        {
	            id: "main",
	            visibleBatch: "orders",
	            height: "auto",
	            rows: [
	                {cols: [{view: "toolbar", height: 50, elements: [{view: "label", id: "header", label: "Расписание по-умолчанию", template: "<center>#label#</center>"}]}]},
	                {rows: [{animate: false, cells: [days_default[1]]}], batch: "day1"},
	                {rows: [{animate: false, cells: [days_default[2]]}], batch: "day2"},
	                {rows: [{animate: false, cells: [days_default[3]]}], batch: "day3"},
	                {rows: [{animate: false, cells: [days_default[4]]}], batch: "day4"},
	                {rows: [{animate: false, cells: [days_default[5]]}], batch: "day5"},
	                {rows: [{animate: false, cells: [days_default[6]]}], batch: "day6"},
	                {rows: [{animate: false, cells: [days_default[7]]}], batch: "day7"},
	                {rows: [{animate: false, cells: [days_custom]}], batch: "days_custom"},
	                {rows: [{animate: false, cells: [record_users]}], batch: "record_users"},
	                {rows: [{animate: false, cells: [users]}], batch: "users"},
	            ],
	        }
	    ]
	};


	var ui = {
	    view: "scrollview",
	    scroll: false,
	    body: {
	        rows: [
	            {
	                cols: [
	                    {
	                        view: "toolbar", padding: 3,
	                        elements: [
	                            {
	                                view: "button",
	                                type: "icon",
	                                template: "<img src='/img/logo_3.png'>",
	                                width: 40,
	                                css: "app_button",
	                                click: function () {
	                                    $$("$sidebar1").toggle()
	                                }
	                            },
	                            {
	                                type: "header",
	                                borderless: true,
	                                height: 56,
	                                template: "<img src='' hidden valign='top' class='logo'><font class='logo_text'>lvlUP</font>"
	                            },
	                            {},
	                            account
	                        ]
	                    },

	                ]
	            },
	            {cols: [menu, views]}
	        ]
	    }
	};

	/* Interface - finish */
	/* Webix - start */

	webix.ready(function () {
	    webix.ui.fullScreen();
	    webix.ui(ui);
	    $$("$sidebar1").select("record_users");
	    $$("$sidebar1").callEvent("onAfterSelect", ["record_users"]);
	    setInterval(function () {
	        if (token_flag == 0) {
	            login();
	        }
	    }, 10);

	});

}