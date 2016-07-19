function show_progress_bar(delay){}
/* Functions - start */

function checkAutorization() {
	var result = false;
	if(webix.storage.cookie.get("token")) {
		result = true;
	};
	return result;
};

function switchFlag() {
	if(checkAutorization()) token_flag=1;
	else token_flag=0;
};
var token_flag = 0;
switchFlag();
if(webix.storage.cookie.get("token")) var menu = {
	view: "sidebar",
	data:[
		{
			id:"schedule", value:"Расписание по-умолчанию",icon:'calendar',
			data:[
				{id:"day1", value:"ПН"},
				{id:"day2", value:"ВТ"},
				{id:"day3", value:"СР"},
				{id:"day4", value:"ЧТ"},
				{id:"day5", value:"ПТ"},
				{id:"day6", value:"СБ"},
				{id:"day7", value:"ВС"}
			]
		},
		{id:"add_schedule", value:"Дополнительные занятия",icon:'calendar-o'},
		/*{id:"clients", value:"Клиенты",icon:'group'},
		{id:"trener", value:"Тренеры",icon:'child'},
		{id:"activity", value:"Занятия",icon:'bicycle'}*/
	],
	on:{
		onAfterSelect:function(id){
			//var mid = this.getItem(id).id;
			$$("main").showBatch(id);
			$$("header").setValue(this.getItem(id).value);
		}
	}
};
else var menu = {
	view: "sidebar",
	data:[
		{
			id:"schedule", value:"Расписание по-умолчанию",select:true,
			data:[{id:"day1", value:"ПН"}
			]
		}
	],
	on:{
		onAfterSelect:function(id){
			//var mid = this.getItem(id).id;
			$$("main").showBatch(id);
			$$("header").setValue(this.getItem(id).value);
		}
	}
};
var views = {
	rows:[
		{
			id:"main",  
			visibleBatch:"orders",
			height:"auto",
			rows:[
				{
					cols:[
						{
							view:"toolbar",
							height:50,
							elements:[
								{view:"label", id:"header", label:"Расписание по-умолчанию", template:"<center>#label#</center>"},
							]
						}
					]
				},

				{rows:[{animate: false,cells:[days_default[1]]}], batch:"day1"},
				{rows:[{animate: false,cells:[days_default[2]]}], batch:"day2"},
				{rows:[{animate: false,cells:[days_default[3]]}], batch:"day3"},
				{rows:[{animate: false,cells:[days_default[4]]}], batch:"day4"},
				{rows:[{animate: false,cells:[days_default[5]]}], batch:"day5"},
				{rows:[{animate: false,cells:[days_default[6]]}], batch:"day6"},
				{rows:[{animate: false,cells:[days_default[7]]}], batch:"day7"},
				{rows:[{animate: false,cells:[days_custom]}], batch:"add_schedule"},
			],
		}
	]
};


var ui = {
	view:"scrollview",
	scroll:false,
	body:{
		rows:[
			{cols:[
				{
					view: "toolbar", padding:3,
					elements: [
						{
							view: "button", type: "icon", template:"<img src='/img/logo_3.png'>", width: 40, css: "app_button",
							click: function(){
								$$("$sidebar1").toggle()
							}
						},
						{type: "header", borderless:true, height:56, template:"<img src='' hidden valign='top' class='logo'><font class='logo_text'>lvlUP</font>"},
						{},
						account
					]
				},
				
			]},
			{cols:[menu, views]}
		]
	}
};

/* Interface - finish */
/* Webix - start */

webix.ready(function(){
	webix.ui.fullScreen();
	webix.ui(ui);
	$$("$sidebar1").select("add_schedule");
	$$("$sidebar1").callEvent("onAfterSelect", ["add_schedule"]);
	setInterval(function(){
		if(token_flag==0) {
			login();
		};
	},10);

});