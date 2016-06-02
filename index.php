<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Батутный центр LevelUP</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">

    <link rel="shortcut icon" href="img/ico.ico">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<? $image = "/www/image.php"; ?>
<div class="wrapper">
    <a href="#" id="uptocall-mini"><div class="uptocall-mini-phone"></div></a>
    <a href="#" class="scrollup">Наверх</a>
    <!-- Линия для связи -->
    <div class="communication hidden-xs hidden-sm">
        <div class="container">
            <div class="row">

                <div class="social-ic pull-left">
                    <a href="https://vk.com/levelup76" class = "social-icon si-border si-vk"><i class="fa fa-vk"></i><i class="fa fa-vk"></i></a>
                    <a href="#" class = "social-icon si-border si-instagram"><i class="fa fa-instagram"></i><i class="fa fa-instagram"></i> </a>
                    <a href="#" class = "social-icon si-border si-tripadvisor"><i class="fa fa-tripadvisor"></i><i class="fa fa-tripadvisor"></i> </a>
                    <a href="#" class = "social-icon si-border si-facebook"><i class="fa fa-facebook"></i><i class="fa fa-facebook"></i></a>
                </div>

                <div class="contact pull-right">
                    <i class="fa fa-home"></i> &nbsp;г. Ярославль, ул. Свободы, д.46/3 (территория Казарм)&nbsp;&nbsp;
                    <i class="fa fa-phone"></i> &nbsp;+7 (4852) 33-72-00&nbsp;&nbsp;
                    <i class="fa fa-envelope"></i> &nbsp;levelup76@yandex.ru
                </div>
            </div>
        </div>
    </div>

    <!-- Основное меню -->

    <div class="navbar navbar-default" data-spy="affix" data-offset-top="52" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#responsive-menu">
                    <span class="sr-only">Открыть меню</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img src="img/logo.png" height="110" width="453" class="img-responsive" alt="Responsive image"></a>

            </div>
            <div class="navbar-collapse collapse" id="responsive-menu">
                <ul id="menu-main-menu" class="nav navbar-nav navbar-right">
                    <li id="" class="item-menu"><a href="price.html">Цены</a></li>
                    <li id="" class="item-menu"><a href="children_celebration.html">Детские праздники</a></li>
                    <li id="" class="item-menu"><a href="#">Спортивная школа</a></li>
                    <li id="" class="item-menu"><a href="coaches.html">Тренеры</a></li>
                    <li id="" class="item-menu"><a href="maps.html">Схема проезда</a></li>
                </ul>

            </div>
        </div>
    </div>


    <!-- Карусель фотографий -->
    <div id="carousel" class="carousel slide" data-ride="carousel">
        <!-- Индикаторы -->
        <ol class="carousel-indicators">
            <li data-target="#carousel" data-slide-to="0" class="active"></li>
            <li data-target="#carousel" data-slide-to="1"></li>
            <li data-target="#carousel" data-slide-to="2"></li>
            <li data-target="#carousel" data-slide-to="3"></li>
            <li data-target="#carousel" data-slide-to="4"></li>
        </ol>

        <!-- Слайды и подписи -->
        <div class="carousel-inner">
            <div class="active item"> <img src="img/001.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="img/002.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="img/003.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="img/004.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="img/005.jpg" class="img-responsive" alt="Responsive image"> </div>
        </div>

        <!-- Контроллеры -->
        <a class="left carousel-control" href="#carousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#carousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>

    <!-- Заголовок и контент -->
    <div class="container">
        <div class="row">
            <div class="content">
                <div class="name">БАТУТНЫЙ ЦЕНТР Level<span class="UP">UP</span></div>
                <p class="name-little">ЯРКИЕ ЭМОЦИИ И НОВЫЕ ОЩУЩЕНИЯ ОТ ЗАНЯТИЙ СПОРТОМ!</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-xs-1">

            </div>
            <div class="col-lg-8 col-xs-10">
                <hr id="cherta_ind">
            </div>
            <div class="col-lg-2 col-xs-1">

            </div>
        </div>
    </div>



    <!-- Заметки -->
    <div class="container">
        <div class="row notes">
            <div class="col-lg-3 col-sm-6">
                <div class="thumbnail">
                    <div class="caption">
                        <h3 id="bold-name">Прыжки на батуте</h3>
                        <p id="regular-name">Думаете, что батутный парк –  это лишь развлечения? Попрыгать на батуте в течение 10 минут равносильно 30-ти минутам бега. Кроме того, занятия на батуте укрепляют вестибулярный аппарат и способствуют уменьшению головных болей.</p>
                        <p id="more-name"><a href="#">Подробнее...</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="thumbnail">
                    <div class="caption">
                        <h3 id="bold-name">Мероприятия</h3>
                        <p id="regular-name">...</p>
                        <p id="more-name"><a href="#">Подробнее...</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="thumbnail">
                    <div class="caption">
                        <h3 id="bold-name">Дни рождения</h3>
                        <p id="regular-name">...</p>
                        <p id="more-name"><a href="#">Подробнее...</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="thumbnail">
                    <div class="caption">
                        <h3 id="bold-name">Групповые занятия</h3>
                        <p id="regular-name">...</p>
                        <p id="more-name"><a href="#">Подробнее...</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Видеотрансляция -->
    <div class="onlain-tr">
        <div class="container">
            <div class="row ">

                <h1 id="on-name">LevelUP online</h1>
                <div class="col-lg-2 col-sm-1"></div>
                <div class="col-lg-8 col-sm-10">
                    <div class="embed-responsive embed-responsive-16by9">
                        <img id="camera_hidden" hidden src="<?=$image?>"/>
                        <img id="camera_online" src="<?=$image?>"/>
                    </div>
                </div>
                <div class="col-lg-8 col-sm-1"></div>
            </div>
        </div>
    </div>



    <!-- Партнеры -->
    <div id="slideout">
        <img src="img/partners.png" alt="Партнеры" />
        <div id="slideout_inner">
            <p class="logo_position"><a href="http://top-drive.org/"><img src="img/topdrive-logo-skal.png"></a></p>
            <p class="logo_position"><a href="http://top-drive.org/bassejn-svobodnoe-plavanie.html"><img src="img/topdrive-logo-bas.png"></a></p>
            <p class="logo_position"><a href="http://yarvzlet.ru/"><img src="img/vzlet.png"></a></p>
            <p class="logo_position"><a href="http://www.parkzabava.ru/"><img src="img/zabava.png"></a></p>
            <p class="logo_position"><a href="http://capitansclub.ru/"><img src="img/capitan-club.png"></a></p>
        </div>
    </div>
    <!-- Конец блока Партнеры -->

    <!-- Подвал -->
    <footer>
        <div class="foot-pic">
            <div class="col-lg-2 col-xs-2">
                <img src="img/footer_1.png" height="150" width="325" class="img-responsive" alt="Responsive image">
            </div>
            <div class="col-lg-8 col-xs-8 foot-text">



                <div class="col-lg-8 col-xs-8">
                    <p id="company_f">
                        &copy; ООО “Топ Драйв” 2016 / Тел.:+7 (4852) 33-72-00 <span id="company">&Iota; Разработка:<a href="#"> “NameCompany”</a></span>
                    </p>
                </div>
                <div class="col-lg-4 col-xs-4 vac">
                    <p>
                                        <span id="vacancy"><a href="#">Вакансии</a>&nbsp;&nbsp;
                                        <a href="#">Сотрудничество</a></span>
                    </p>
                </div>


            </div>
            <div class="col-lg-2 col-xs-2">
                <img src="img/footer_2.png" height="150" width="325" class="img-responsive" alt="Responsive image">
            </div>
        </div>
    </footer>


</div>





<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        });

        $('.scrollup').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });

    });
</script>


<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.js"></script>
<script>
    function new_photo(){
        var camera_hidden = document.getElementById("camera_hidden");
        camera_hidden.src = "<?=$image?>?"+Math.random();
        camera_hidden.onload = function(){
            document.getElementById("camera_online").src = camera_hidden.src;
            setTimeout(new_photo,100);
        };
        //camera_hidden.onerror = setTimeout(new_photo,3000);
    };
    setTimeout(new_photo,1);
</script>

</body>
</html>