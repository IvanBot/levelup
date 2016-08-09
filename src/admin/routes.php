<?php
require_once( __DIR__ . "/../db.php");
require_once( __DIR__ . "/../scheduler.php");

$app->get('/admin/getUsers', function ($request, $response, $args) {
  return json_encode(scheduler::getUsers());
});
$app->get('/admin/getAdmUsersRecords', function ($request, $response, $args) {
  return json_encode(scheduler::getAdminUsersRecords($_GET));
});
$app->get('/admin/getScheduleCicle', function ($request, $response, $args) {
  return json_encode(scheduler::getScheduleWeekday($_GET));
});
$app->get('/admin/addScheduleCicle', function ($request, $response, $args) {
  return json_encode(scheduler::addScheduleCicle($_GET));
});
$app->get('/admin/editScheduleCicle', function ($request, $response, $args) {
  return json_encode(scheduler::editScheduleCicle($_GET));
});
$app->get('/admin/delScheduleCicle', function ($request, $response, $args) {
  return json_encode(scheduler::delScheduleCicle($_GET));
});
$app->get('/admin/getSchedule', function ($request, $response, $args) {
  return json_encode(scheduler::getSchedule($args));
});
$app->get('/admin/delRecord', function ($request, $response, $args) {
  return json_encode(scheduler::delRecordById($_GET));
});
$app->get('/admin/editRecord', function ($request, $response, $args) {
  return json_encode(scheduler::editRecord($_GET));
});


/*if ($_POST['delUsers'] > 0) //?delUsers=1&user_id=1
    print_r(json_encode(scheduler::delUsers($_POST['user_id'])));
/*if ($_POST['getUsers'] > 0) //?getUsers=1
    print_r(json_encode(scheduler::getUsers()));
if ($_POST['getAdmUsersRecords'] > 0) //?getUsersRecords=1
    print_r(json_encode(scheduler::getAdmUsersRecords($_POST)));
if ($_POST['getScheduleCicle'] > 0) //?getScheduleCicle=1
    print_r(json_encode(scheduler::getScheduleCicle($_POST)));*/

/*if (($_POST['setUser'] > 0 or ($_POST['setUser'] > 0 and $_POST['user_id'])) && !$_POST['user_sess']) {//?setUser=1&phone=206607-0788&username=Kesha&surname=Popkin&email=ke@popkin.ru&usercomment=Kakadu
    $user_id = scheduler::setUser($_POST);
} else {
    $user_id = $_POST['user_id'];
}
if ($_POST['delRecordByPhone'] > 0) {//?delRecord=1&record_id=1
    print_r(json_encode(scheduler::delRecordByPhone($_POST)));
}
if ($_POST['setRecord'] > 0) {//?setRecord=1&user_id=1&schedule_id=1&recordcomment=First!&activity_id=1&activitydate=2016-07-16&starttime=10:15
    $rec_id = '';
    if ($_POST['username'] || $_POST['surname']) $_POST['name'] = $_POST['username'].' '.$_POST['surname'];
    if ($_POST['schedule_time']) $_POST['starttime'] = $_POST['schedule_time'];
    if ($_POST['schedule_date']) $_POST['activitydate'] = $_POST['schedule_date'];
    if ($_POST['schedule_id'] == 0 and $_POST['schedule_date'] and $_POST['schedule_time'])
        $_POST['schedule_id'] = scheduler::setSchedule(array('activity_id' => 0, 'starttime' => $_POST['schedule_time'], 'activitydate' => $_POST['schedule_date'], 'activityduration' => 1)); // добавим в график занятие
    if ($user_id) {$_POST['user_id'] = $user_id;
    $rec_id = scheduler::setRecord($_POST);}
    print_r($user_id.'-'.$rec_id);
}
if ($_POST['delRecord'] > 0){scheduler::delRecord($_POST);}
    if ($_POST['setTrainer'] > 0) {//?setTrainer=1&phone=206607-0788&trainername=Bagira&trainersurname=Kitty&experience=2010-06-01&email=bad@kitty.u&photo=../photo.img&trainercomment=kis-kis
    $tr_id = scheduler::setTrainer($_POST);
}
if ($_POST['setActivity'] > 0) {//?setActivity=1&activityname=go-go dance&activitycomment=dance-dance&mincount=2&maxcount=6&activityduration=2
    if ($tr_id) $_POST['trainer_id'] = $tr_id;
    $act_id = scheduler::setActivity($_POST);
}
if ($_POST['setSchedule'] > 0) {//?setSchedule=1&activity_id=1&trainer_id=3&starttime=12&activityduration=3&activitydate=2016-07-10&maxcount=20&mincount=1
    if ($act_id) $_POST['activity_id'] = $act_id;
    scheduler::setSchedule($_POST);
} 

$app->get('/rrr', function () {
  echo"rrrtrsiutgnxdilgnn";
});*/