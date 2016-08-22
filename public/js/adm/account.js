function login() {
	token_flag = 1;
	if(webix.storage.cookie.get("token")) webix.storage.cookie.clear();
	var form_profile = {
		view:"form",
		borderless:true,
		elements: [
			{ id:"profile_login",view:"text", label:"Логин", name:"profile_login", invalidMessage:"Должно быть заполнено" },
			{ id:"profile_password",view:"text", type:"password", label:"Пароль", name:"profile_password", invalidMessage:"Должно быть заполнено" },
			{ view:"button", value:"Вход", hotkey:"enter",
				click:function(){
					if ($$("profile_login").getParentView().validate() ) {
						var login = $$("profile_login").getValue();
						var passw = $$("profile_password").getValue();

						if(login=="admin" && passw=="721982") {
							webix.storage.cookie.put("token", "1");
							webix.storage.cookie.put("username", "Админ");
							$$("profile_login").getTopParentView().hide();
							location.href = '/admin/';
						}
						else webix.message({type:"error",text:"Неверный логин или пароль"});
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

var text = 0;
if(webix.storage.cookie.get("token")) {
	text = 1;
};

if(text==0) {
	//webix.storage.cookie.clear();
	var account = {
		rows:[
			{},
			{
				cols:[
					{ view:"button", type:"iconButton", icon:"sign-in", width:90, borderless:true, label:"Вход", click:function(){login();} },
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