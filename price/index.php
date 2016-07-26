<?require_once($_SERVER['DOCUMENT_ROOT'].'/header.php') ?>
    <!-- Заголовок и контент -->

    <div class="content_price">
        <div class="container">
            <div class="row">
                <div class="name_price">Цены и действующие акции</div>
                <span class="cherta_ind hidden-xs"></span>
            </div>
        </div>
    </div>


    <br>

    <!-- Контент -->


    <!-- Таблица -->
    <div class="cont_price">
        <div class="container">
            <div class="row">


                <table class="table_posit">
                    <caption id="tab-h">Разовые посещения</caption>
                    <thead class="tab-block-b">
                    <tr>
                        <th id="tab_b"></th>
                        <th id="tab_b"><h4 id="tab-b-n">Будние дни до 18:00</h4></th>
                        <th id="tab_b"><h4 id="tab-b-n">Будние дни после 18:00,<br> выходные, праздничные</h4></th>
                    </tr>
                    </thead>
                    <tbody class="tab-block">
                    <tr>
                        <td id="tab_n_n">Взрослый тариф/1 час</td>
                        <td id="tab_n">200 <i class="fa fa-rub" aria-hidden="true"></i></td>
                        <td id="tab_n">400 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">Взорслый + ребенок/1 час</td>
                        <td id="tab_n">300 <i class="fa fa-rub" aria-hidden="true"></i></td>
                        <td id="tab_n">600 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">JAM (15:00-18:00)/3 часа</td>
                        <td id="tab_n">500 <i class="fa fa-rub" aria-hidden="true"></i></td>
                        <td id="tab_n">1 000 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">Вместе веселее (20:00-22:00)/1 час</td>
                        <td id="tab_n"></td>
                        <td id="tab_n">600 <i class="fa fa-rub" aria-hidden="true"></i>/2человека</td>

                    </tr>

                    <tr>
                        <td></td>
                        <td><button type="button" class="btn btn-secondary" onclick="window.location.href='https://pay.erpico.ru/pay.php?partner=1&amount=20000&refId=1&text=LevelUp разовое посещение в будний день'">Купить</button></td>
                        <td><button type="button" class="btn btn-secondary" onclick="window.location.href='https://pay.erpico.ru/pay.php?partner=1&amount=40000&refId=2&text=LevelUp разовое посещение в выходной день'">Купить</button></td>
                    </tr>


                    <tr>
                        <td colspan="3"><hr id="cherta_tab"></td>
                    </tr>
                    </tbody>
                </table>

                <div class="container">
                    <div class="row" id="tab_text_down">
                <span>Студентам и школьникам в будни после 18.00, в выходные и праздничные дни – 300 рублей/час<br>
                (при предъявлении документа, подтверждающего возраст/документа с места учёбы)
                </span>
                    </div>
                </div>

                <table class="table_posit">
                    <caption id="tab-h">Абонементы на свободное посещение</caption>
                    <thead class="tab-block-b">
                    <tr>
                        <th id="tab_b"></th>
                        <th id="tab_b"><h4 id="tab-b-n">Будние дни до 18:00</h4></th>
                        <th id="tab_b"><h4 id="tab-b-n">Будние дни после 18:00,<br> выходные, праздничные</h4></th>
                    </tr>
                    </thead>
                    <tbody class="tab-block">
                    <tr>
                        <td id="tab_n_n">Абонемент на 4 часа</td>
                        <td id="tab_n">700 <i class="fa fa-rub" aria-hidden="true"></i></td>
                        <td id="tab_n">1 400 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">Абонемент на 8 часов</td>
                        <td id="tab_n">1 300 <i class="fa fa-rub" aria-hidden="true"></i></td>
                        <td id="tab_n">2 500 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">Абонемент на месяц (безлимит)</td>
                        <td id="tab_n">2 000 <i class="fa fa-rub" aria-hidden="true"></i></td>
                        <td id="tab_n">4 000 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">Абонемент на 3 месяца (безлимит)</td>
                        <td id="tab_n">5 000 <i class="fa fa-rub" aria-hidden="true"></i></td>
                        <td id="tab_n">10 000 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">Абонемент на 6 месяцев (безлимит)</td>
                        <td id="tab_n">9 000 <i class="fa fa-rub" aria-hidden="true"></i></td>
                        <td id="tab_n">18 000 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">Абонемент на 1 год (безлимит)</td>
                        <td id="tab_n">15 000 <i class="fa fa-rub" aria-hidden="true"></i></td>
                        <td id="tab_n">30 000 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td><button type="button" class="btn btn-secondary">Купить</button></td>
                        <td><button type="button" class="btn btn-secondary">Купить</button></td>
                    </tr>

                    <tr>
                        <td colspan="3"><hr id="cherta_tab"></td>
                    </tr>
                    </tbody>
                </table>



                <table class="table_posit">
                    <caption id="tab-h">Индивидуальные занятия с инструктором</caption>

                    <tbody class="tab-block">
                    <tr>
                        <td id="tab_n_n">1 час</td>
                        <td id="tab_n">800 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">2 часа</td>
                        <td id="tab_n">1 500 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">4 часа</td>
                        <td id="tab_n">2 800 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">8 часов</td>
                        <td id="tab_n">5 200 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <!--<tr>
                    <td></td>
                    <td><button type="button" class="btn btn-secondary">Купить</button></td>
                    </tr>-->

                    <tr>
                        <td colspan="2"><hr id="cherta_tab"></td>
                    </tr>
                    </tbody>
                </table>


                <table class="table_posit">
                    <caption><h3 id="tab-h">Групповые занятия с инструктором</h3>
                        <p id="tab-h-p">(детские группы, акробатика, фристайл)</p>
                    </caption>

                    <tbody class="tab-block">
                    <tr>
                        <td id="tab_n_n">1 занятие</td>
                        <td id="tab_n">600 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">4 занятия</td>
                        <td id="tab_n">2 100 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">8 занятий</td>
                        <td id="tab_n">3 900 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <!--<tr>
                    <td></td>
                    <td><button type="button" class="btn btn-secondary">Купить</button></td>
                    </tr>-->

                    <tr>
                        <td colspan="2"><hr id="cherta_tab"></td>
                    </tr>
                    </tbody>
                </table>


                <table class="table_posit">
                    <caption><h3 id="tab-h">Фриран</h3>
                    </caption>

                    <tbody class="tab-block">
                    <tr>
                        <td id="tab_n_n">1 занятие</td>
                        <td id="tab_n">300 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <tr>
                        <td id="tab_n_n">8 занятий (месяц)</td>
                        <td id="tab_n">2 000 <i class="fa fa-rub" aria-hidden="true"></i></td>
                    </tr>

                    <!--<tr>
                    <td></td>
                    <td><button type="button" class="btn btn-secondary">Купить</button></td>
                    </tr>-->

                    <tr>
                        <td colspan="2"><hr id="cherta_tab"></td>
                    </tr>
                    </tbody>
                </table>



                <div class="container">
                    <div class="row" id="tab_text_down">
                <span>ВНИМАНИЕ: смена всех тарифов осуществляется в 18:00, все посетители батутного центра, пришедшие до 18:00, обязаны покинуть зал в 18:00 или произвести доплаты по тарифам, действующим с 18:00!
                <br>
                <br>
                Мы допускаем к занятиям детей согласно возрастов:<br>
                - индивидуальная тренировка – от 5 лет;<br>
                - групповая тренировка – от 6 лет;<br>
                - детские праздники – от 7 лет.
                </span>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!-- Конец таблицы -->

    <!-- Специальные предложения -->
    <div class="special_offer">
        <div class="container">
            <div class="row">
                <table class="table_posit">
                    <caption><h3 id="tab-h_invers">Подарочные карты</h3>
                    </caption>
                    <thead class="tab-block-b">

                    </thead>
                    <tbody class="tab-block">
                    <tr>
                        <td id="tab_n_n_invers">ВЗРОСЛЫЙ + РЕБЕНОК (занятие с инструктором)</td>
                        <td id="tab_n_invers"><button type="button" class="btn btn-secondary" onclick="window.location.href='https://pay.erpico.ru/pay.php?refId=3&partner=1&amount=1200000&text=Подарочный сертификат LevelUp на взрослого и ребенка (с инструктором)'">1 200 <i class="fa fa-rub" aria-hidden="true"></i></button></td>
                    </tr>
                    <tr>
                        <td id="tab_n_n_invers">ОДИН ВЗРОСЛЫЙ (занятие с инструктором)</td>
                        <td id="tab_n_invers"><button type="button" class="btn btn-secondary" onclick="window.location.href='https://pay.erpico.ru/pay.php?refId=4&partner=1&amount=80000&text=Подарочный сертификат LevelUp на одного взрослого (с инструктором)'">800 <i class="fa fa-rub" aria-hidden="true"></i></button></td>
                    </tr>
                    <tr>
                        <td id="tab_n_n_invers">ВЗРОСЛЫЙ + РЕБЕНОК (свободное посещение)</td>
                        <td id="tab_n_invers"><button type="button" class="btn btn-secondary" onclick="window.location.href='https://pay.erpico.ru/pay.php?refId=5&partner=1&amount=60000&text=Подарочный сертификат LevelUp на взрослого и ребенока (свободное посещение)'">600 <i class="fa fa-rub" aria-hidden="true"></i></button></td>
                    </tr>
                    <tr>
                        <td id="tab_n_n_invers">ОДИН ВЗРОСЛЫЙ (свободное посещение)</td>
                        <td id="tab_n_invers"><button type="button" class="btn btn-secondary" onclick="window.location.href='https://pay.erpico.ru/pay.php?refId=6&partner=1&amount=40000&text=Подарочный сертификат LevelUp на одного взрослого (свободное посещение)'">400 <i class="fa fa-rub" aria-hidden="true"></i></button></td>
                    </tr>


                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- Конец специальные предложения -->
<?require_once($_SERVER['DOCUMENT_ROOT'].'/footer.php') ?>