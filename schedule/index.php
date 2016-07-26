<?require_once($_SERVER['DOCUMENT_ROOT'].'/header.php') ?>
    <!-- Заголовок и контент -->


    <div class="content_price">
        <div class="container">
            <div class="row">
                <div class="name_price">Записаться на тренировку</div>
                <span class="cherta_ind hidden-xs"></span>
            </div>
        </div>
    </div>


    <!-- Контент -->
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <!--/ Codrops top bar -->
                <section class="main">
                    <div class="custom-calendar-wrap">
                        <div id="custom-inner" class="custom-inner">
                            <div class="custom-header clearfix">
                                <nav>
                                    <span id="custom-prev" class="custom-prev"></span>
                                    <span id="custom-next" class="custom-next"></span>
                                </nav>
                                <h2 id="custom-month" class="custom-month"></h2>
                                <h3 id="custom-year" class="custom-year"></h3>
                            </div>
                            <div id="calendar" class="fc-calendar-container"></div>
                        </div>
                    </div>
                </section>

            </div>

            <div class="col-lg-7" id="datatable_record">
                <table class="table_posit">
                    <caption><h3 id="tab-h">&nbsp;</h3>
                    </caption>

                    <tbody class="tab-block">
                    <tr>
                        <td id="write_n_n">10:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">11:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">12:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">13:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">14:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">15:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">16:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">17:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">18:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">19:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">20:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    <tr>
                        <td id="write_n_n">21:00</td>
                        <td id="write_n_b"></td>
                        <td id="write_n_f"></td>
                        <td id="write_n"></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="text">
            <p class="write_text">* Просьба записываться заранее. Запись доступна с 10:00 - 22:00.</p>
            <p class="write_text">* Записаться можно по телефону по тел. 33-72-00 и мы
                Вас запишем на тренировку.</p>
        </div>
    </div>
    <!-- Конец контента-->


    <br>
    <!-- Окно записи на тренировку -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formx">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Записаться на тренировку</h4>
                    </div>
                    <div class="modal-body">
                        <p class="error error_username hidden">Представьтесь, пожалуйста.</p>

                        <div class="form-group">
                            <label for="inputName">Имя</label>
                            <input name="username" type="name" class="form-control" id="inputName" placeholder="Имя">
                            <input type="hidden" name="setUser" type="name" class="form-control" value="1">
                            <input type="hidden" name="setRecord" type="name" class="form-control" value="1">
                            <input type="hidden" name="schedule_id" type="name" class="form-control" value="0" id="schedule_id">
                            <input type="hidden" name="schedule_date" type="name" class="form-control" value="0" id="schedule_date">
                            <input type="hidden" name="schedule_time" type="name" class="form-control" value="0" id="schedule_time">
                        </div>

                        <div class="form-group">
                            <label for="inputSurname">Фамилия</label>
                            <input name="surname" type="name" class="form-control" id="inputSurname"
                                   placeholder="Фамилия">
                        </div>
                        <p class="error error_userphone hidden">Пожалуйста, введите номер телефона.</p>
                        <div class="form-group">
                            <label for="inputPhone">Телефон</label>
                            <input name="phone" type="phone" class="form-control" id="inputPhone" placeholder="Телефон*">
                        </div>

                    </div>
                    <div class="modal-footer">

                        <input type="button" id="record" class="btn btn-secondary" value="Записаться">
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal recdel">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formd">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Удалить запись на тренировку</h4>
                    </div>
                    <div class="modal-body">
                        <p class="error error_userphone del hidden">Для удаления записи введите номер телефона.</p>
                        <p class="error error_userphone undel hidden">Введен неверный номер телефона.</p>
                        <div class="form-group">
                            <label for="inputDelPhone">Телефон</label>
                            <input name="phone" type="phone" class="form-control" id="inputDelPhone" placeholder="Телефон*">
                            <input type="hidden" name="record_id" type="name" id="inputRecord" class="form-control" value="0">
                        </div>

                    </div>
                    <div class="modal-footer">

                        <input type="button" id="recorddel" class="btn btn-secondary" value="Удалить">
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Конец окна записи на тренировку -->


    <!-- Специальные предложения -->
    <!-- Конец специальные предложения -->
<?require_once($_SERVER['DOCUMENT_ROOT'].'/footer.php') ?>