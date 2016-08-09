<?php
// Routes

$app->get('/getSchedule[/{date}[/]]', function ($request, $response, $args) {
  $response = json_encode(scheduler::getSchedule($args));
  return $response;
});

$app->get('/getTimeTable[/{date}[/]]', function ($request, $response, $args) {
  $args['data'] = scheduler::getSchedule($args);
  return $this->renderer->render($response, 'timetable.phtml', $args);
});

$app->post('/delRecord', function ($request, $response, $args) {
  $response = json_encode(scheduler::delRecordByPhone($_POST));
  return $response;
});

$app->post('/addRecord', function ($request, $response, $args) {
  if (!$_POST['user_id']) {//?setUser=1&phone=206607-0788&username=Kesha&surname=Popkin&email=ke@popkin.ru&usercomment=Kakadu
    $user_id = scheduler::setUser($_POST);
    if(is_array($user_id)) return json_encode($user_id);
  } else {
    $user_id = $_POST['user_id'];
  }
    $date = $_POST['schedule_date'];
    $plaintime = str_replace(":", "", $_POST['schedule_time']);

    $schedule = scheduler::getSchedule($args);

    $avaiable = $schedule[$date][$plaintime]["maxcount"] - $schedule[$date][$plaintime]["registered_count"];

  if($avaiable==1) $message_word_place = " место";
  if($avaiable>1 && $avaiable<5) $message_word_place = " места";
  if($avaiable>4) $message_word_place = " мест";
    if ($_POST['cnt'] > $avaiable) {
      return json_encode(["result"=>3, "message"=>"Извините, мы не можем записать на занятие ".$_POST['cnt']." человек, потому что свободно только ".$avaiable.$message_word_place.".\nПожалуйста, уменьшите количество человек."]);
    } else {
      $rec_id = '';
//    if ($_POST['username'] || $_POST['surname']) $_POST['name'] = $_POST['username'].' '.$_POST['surname'];
      if ($_POST['schedule_time']) $_POST['starttime'] = $_POST['schedule_time'];
      if ($_POST['schedule_date']) $_POST['activitydate'] = $_POST['schedule_date'];
      if ($_POST['schedule_id'] == 0 and $_POST['schedule_date'] and $_POST['schedule_time'])
          $_POST['schedule_id'] = scheduler::setSchedule(array('activity_id' => 0, 'starttime' => $_POST['schedule_time'], 'activitydate' => $_POST['schedule_date'], 'activityduration' => 1)); // добавим в график занятие
      if ($user_id) {$_POST['user_id'] = $user_id;

      $rec_id = scheduler::setRecord($_POST);}
      if ($rec_id <= 0) return json_encode(["result"=>4, "message"=>"Произошла ошибка при сохранении записи. Попробуйте повторить попытку позднее." ]);
    }

    return json_encode(["result"=>0, "message"=>"OK" ]);
});

/*
if ($_POST['delUsers'] > 0) //?delUsers=1&user_id=1
    print_r(json_encode(scheduler::delUsers($_POST['user_id'])));
if ($_POST['getUsers'] > 0) //?getUsers=1
    print_r(json_encode(scheduler::getUsers()));
if ($_POST['getAdmUsersRecords'] > 0) //?getUsersRecords=1
    print_r(json_encode(scheduler::getAdmUsersRecords($_POST)));
if ($_POST['getScheduleCicle'] > 0) //?getScheduleCicle=1
    print_r(json_encode(scheduler::getScheduleCicle($_POST)));

if (($_POST['setUser'] > 0 or ($_POST['setUser'] > 0 and $_POST['user_id'])) && !$_POST['user_sess']) {//?setUser=1&phone=206607-0788&username=Kesha&surname=Popkin&email=ke@popkin.ru&usercomment=Kakadu
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
} */


$app->get('/camera.jpg', function ($request, $response, $args) {
  require_once(__DIR__ . "/camera.php");
  global $filename,$file2name,$file3name,$url;

  $url = "http://192.168.40.9/cgi-bin/images_cgi?channel=0&user=admin&pwd=admin&".rand();
  $filename = "/tmp/file.jpg";
  $file2name = "/tmp/file2.jpg";
  $file3name = "/tmp/file3.lock";

  if (file_exists($filename)) {
    $file_time = date(strtotime("now")) - filemtime($filename);
    if ($file_time>0) {
      if (!file_exists($file3name)) {
          getfromcamera();
      }
    }
  } else {
    getfromcamera();
  };

  return stream($filename, 'image/jpeg');
});

$app->get('/[{page}[/]]', function ($request, $response, $args) {

    $content_file = $this->get('settings')['pages_path'].$args['page'].".phtml";

    if (!file_exists($content_file)) {
      $content_file = $this->get('settings')['pages_path']."index.phtml";      
    }

    ob_start();
    include $content_file;
    $args['content'] = ob_get_clean();
    ob_end_clean();
//    $args['content'] = file_get_contents($content_file);

    $args['menu'] = Array("price" => "Цены",
                          "playday" => "Детские праздники",
                          "schedule" => "Запись на тренировку",
                          "coaches" => "Тренеры",
                          "map" => "Схема проезда");
    
    // Render index view
    return $this->renderer->render($response, 'main.phtml', $args);
});
