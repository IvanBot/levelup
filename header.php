<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Батутный центр LevelUP</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/font-awesome.css" rel="stylesheet">

    <? if ($_SERVER['REQUEST_URI'] == '/schedule/'): ?>
        <link rel="stylesheet" type="text/css" href="/css/calendar.css"/>
        <link rel="stylesheet" type="text/css" href="/css/custom_2.css"/>
    <? endif ?>
    <link rel="shortcut icon" href="/img/ico.ico">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="wrapper">

    <a href="#" class="scrollup">Наверх</a>


    <!-- Основное меню -->

    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#responsive-menu">
                    <span class="sr-only">Открыть меню</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"><img src="/img/logo.png" height="110" width="453"
                                                               class="hidden-xs hidden-sm hidden-md"
                                                               class="img-responsive" alt="Responsive image"></a>
                <a class="navbar-brand" href="index.html"><img src="/img/logo_2.png" height="61" width="250"
                                                               class="visible-xs hidden-sm hidden-md"
                                                               class="img-responsive" alt="Responsive image"></a>
                <a class="navbar-brand" href="index.html"><img src="/img/logo_3.png" height="61" width="40"
                                                               class="visible-sm visible-md hidden-xs item-img"
                                                               class="img-responsive" alt="Responsive image"></a>


            </div>
            <div class="navbar-collapse collapse" id="responsive-menu">
                <ul id="menu-main-menu" class="nav navbar-nav navbar-right">
                    <li id="" class="item-menu"><a href="price.html">Цены</a></li>
                    <li id="" class="item-menu"><a href="children_celebration.html">Детские праздники</a></li>
                    <li id="" class="item-menu"><a href="/schedule/">Запись на тренировку</a></li>
                    <li id="" class="item-menu"><a href="coaches.html">Тренеры</a></li>
                    <li id="" class="item-menu"><a href="maps.html">Схема проезда</a></li>
                </ul>

            </div>
        </div>
    </div>
        <!-- Конец основного меню -->
    <div style="min-height: 400px">