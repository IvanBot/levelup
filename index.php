<?require_once($_SERVER['DOCUMENT_ROOT'] .'/header.php') ?>

    <!-- Режим работы -->
    <div class="rezim_rabory">


        <h class="rez_r">Будни с 14:00 до 22:00,</h><br>
        <h class="rez_r">сб и вс 10:00-22:00</h><br>
        <h class="rez_b">тел. 33-72-00</h>

    </div>

    <!-- Конец режима работы -->


    <!-- Карусель фотографий -->
    <!-- Карусель 1 -->
    <div id="carousel" class="carousel slide hidden-xs" data-ride="carousel">
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





            <div class="active item"> <img src="/img/001.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="/img/002.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="/img/003.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="/img/004.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="/img/005.jpg" class="img-responsive" alt="Responsive image"> </div>
        </div>

        <!-- Контроллеры -->
        <a class="left carousel-control" href="#carousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#carousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>



    <!-- Карусель 2 -->
    <div id="carousel_2" class="carousel slide hidden-lg hidden-md  hidden-sm" data-ride="carousel">
        <!-- Индикаторы -->


        <!-- Слайды и подписи -->
        <div class="carousel-inner">

            <div class="active item"> <img src="/img/001.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="/img/002.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="/img/003.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="/img/004.jpg" class="img-responsive" alt="Responsive image"> </div>
            <div class="item">  <img src="/img/005.jpg" class="img-responsive" alt="Responsive image"> </div>
        </div>

        <!-- Контроллеры -->
        <a class="left carousel-control" href="#carousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#carousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>


    <!-- Конец Карусели фотографий -->

    <!-- Заголовок и контент -->
    <div class="content">
        <div class="container">
            <div class="row">

                <div class="name">БАТУТНЫЙ ЦЕНТР Level<span class="UP">UP</span></div>
                <p class="name-little">ЯРКИЕ ЭМОЦИИ И НОВЫЕ ОЩУЩЕНИЯ ОТ ЗАНЯТИЙ СПОРТОМ!</p>
                <div>
                    <span class="cherta_ind hidden-xs"></span>
                </div>

            </div>
        </div>
    </div>


    <!-- Заметки -->
    <div  class="notes_white">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-sm-6">
                    <img src="/img/up_ind.png" id="img_notes" class="img-responsive" alt="Responsive image">

                </div>
                <div class="col-lg-7 col-sm-6">
                    <p id="bold-name">ПРЫЖКИ НА БАТУТЕ - ЭТО ПОЛЕЗНО!</p>
                    <p id="regular-name">Прыжки на батуте дарят удивительное чувство бодрости, прилива сил и энергии. Это происходит потому, что во время
                        прыжков в кровь активно выделяются адреналин и эндорфины, точно так же как и при занятиях экстремальными и небезопасными видами спорта. Именно поэтому батут чрезвычайно полезен людям, находящимся в стрессовых состояниях и депрессии.</p>
                    <p id="more-name"></p>
                </div>
            </div>
        </div>
    </div>

    <div  class="notes_grey">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-sm-6">
                    <p id="bold-name">ХОТИТЕ ВЕСЕЛО ОТМЕТИТЬ ДЕНЬ РОЖДЕНИЯ?</p>
                    <p id="regular-name">Батутный центр «LevelUP» предлагает вам попробовать новый формат праздника – море положительных эмоций и яркие впечатления сделают любое мероприятие незабываемым. Подарите своему ребенку настоящую радость. Ваши дети и их друзья могут выплеснуть свою энергию, получая удовольствие, прыгая на наших батутных дорожках, взмывая вверх, мягко приземляясь в поролоновую яму.</p>
                    <p id="more-name"><a href="/playday/">Подробнее...</a></p>
                </div>
                <div class="col-lg-5 col-sm-6">
                    <img src="/img/children_ind.png" id="img_notes" class="img-responsive" alt="Responsive image">
                </div>
            </div>
        </div>
    </div>

    <div  class="notes_white">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-sm-6">
                    <img src="/img/coache_ind.png" id="img_notes" class="img-responsive" alt="Responsive image">
                </div>
                <div class="col-lg-7 col-sm-6">
                    <p id="bold-name">ПРОФЕССИОНАЛЫ СВОЕГО ДЕЛА!</p>
                    <p id="regular-name">В нашем центре работают только квалифицированные специалисты, прошедшие длинный путь в спорте и имеющие большой тренерский и педагогический опыт. Они дадут консультации по правильным тренировкам, помогут подобрать наиболее подходящую программу занятий.</p>
                    <p id="more-name"><a href="/coaches/">Подробнее...</a></p>
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
                        <img id="camera_hidden" class="img-responsive" alt="Responsive image" hidden src="/image.php">
                        <img id="camera_online" class="img-responsive" alt="Responsive image" src="/image.php">
                    </div>
                </div>
                <div class="col-lg-8 col-sm-1"></div>
            </div>
        </div>
    </div>


<?require_once($_SERVER['DOCUMENT_ROOT'] .'/footer.php') ?>