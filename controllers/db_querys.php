<?php
require_once('router.php');

if ($_GET['getSchedule'] > 0 and $_GET['date'] > 0) //?getSchedule=1&date=2016-07-07
    print_r(json_encode(scheduler::getSchedule($_GET['date'])));
if ($_GET['setUser'] > 0)//?setUser=1&phone=206607-0788&username=Kesha&surname=Popkin&email=ke@popkin.ru&usercomment=Kakadu
    $user_id = scheduler::setUser($_GET);
if ($_GET['setRecord'] > 0) {//?setRecord=1&user_id=1&schedule_id=1&recordcomment=First!
    if ($_GET['schedule_id'] == 0 and $_GET['schedule_date'] and $_GET['schedule_time'])
        $_GET['schedule_id'] = scheduler::setSchedule(array('activity_id' => 0, 'activitytime' => $_GET['schedule_time'], 'activitydate' => $_GET['schedule_date'], 'activityduration' => 1)); // добавим в график занятие
    if ($user_id) $_GET['user_id'] = $user_id;
    $rec_id = scheduler::setRecord($_GET);
    print_r($rec_id);
}
if ($_GET['setTrainer'] > 0) {//?setTrainer=1&phone=206607-0788&trainername=Bagira&trainersurname=Kitty&experience=2010-06-01&email=bad@kitty.u&photo=../photo.img&trainercomment=kis-kis
    $tr_id = scheduler::setTrainer($_GET);
}
if ($_GET['setActivity'] > 0) {//?setActivity=1&activityname=go-go dance&activitycomment=dance-dance&mincount=2&maxcount=6&activityduration=2
    if ($tr_id) $_GET['trainer_id'] = $tr_id;
    $act_id = scheduler::setActivity($_GET);
}
if ($_GET['setSchedule'] > 0) {//?setSchedule=1&activity_id=1&trainer_id=3&activitytime=12&activityduration=3&activitydate=2016-07-10&maxcount=20&mincount=1
    if ($act_id) $_GET['activity_id'] = $act_id;
    scheduler::setSchedule($_GET);
}



class scheduler
{
    public static function setUser($data, $id = 0)
    {
        $field = [];
        $value = [];
        if ($data['phone']) $data['phone'] = self::phoneCheck($data['phone']);
        if (empty($data['phone']) or empty($data['username'])) die(); // решить!!!
        if (!isset($data['permission']) or empty($data['permission'])) $data['permission'] = 0;
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        foreach (array_keys($data) as $key) {
            if (!in_array($key, ['phone', 'username', 'surname', 'ip', 'email', 'userpassword', 'permission', 'usercomment'])) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['phone', 'permission']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        $query = "insert into users (" . implode(",", $field) . ") values (" . implode(",", $value) . ");";
        mysql_query($query) or die();
        return mysql_insert_id();
    }

    public static function setActivity($data, $id = 0)
    {
        $field = [];
        $value = [];
        if (empty($data['activityname'])) die(); // решить!!!
        foreach (array_keys($data) as $key) {
            if (!in_array($key, ['trainer_id', 'activityname', 'activitycomment', 'mincount', 'maxcount', 'activityduration'])) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['mincount', 'maxcount', 'activityduration']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        $query = "insert into activity (" . implode(",", $field) . ") values (" . implode(",", $value) . ");";
        mysql_query($query) or die();
        return mysql_insert_id();
    }

    public static function setTrainer($data, $id = 0)
    {
        $field = [];
        $value = [];
        if (empty($data['trainername'])) die(); // решить!!!
        if (empty($data['permission'])) $data['permission'] = 0;
        if (empty($data['active'])) $data['active'] = 1;
        if (!empty($data['phone'])) self::phoneCheck($data['phone']); // решить!!!
        foreach (array_keys($data) as $key) {
            if (!in_array($key, ['phone', 'trainername', 'trainersurname', 'experience', 'email', 'photo', 'userpassword', 'permission', 'active', 'trainercomment'])) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['phone', 'permission', 'active']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        $query = "insert into trainers (" . implode(",", $field) . ") values (" . implode(",", $value) . ");";
        mysql_query($query) or die();
        return mysql_insert_id();
    }

    public static function setSchedule($data, $id = 0)
    {
        $field = [];
        $value = [];
        if ((empty($data['activity_id']) and $data['activity_id'] != 0) or empty($data['activitytime']) or empty($data['activitydate'])) die(); // решить!!!
        if (empty($data['activityduration'])) $data['activityduration'] = 1;
        foreach (array_keys($data) as $key) {
            if (!in_array($key, ['activity_id', 'trainer_id', 'activitytime', 'activityduration', 'activitydate', 'maxcount', 'mincount'])) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['activity_id', 'trainer_id', 'maxcount', 'mincount', 'activityduration', 'activitytime']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        $query = "insert into schedule_activity (" . implode(",", $field) . ") values (" . implode(",", $value) . ");";
        mysql_query($query) or die();
        return mysql_insert_id();
    }

    public static function setRecord($data, $id = 0)
    {
        $field = [];
        $value = [];
        if (empty($data['user_id']) or empty($data['schedule_id'])) die(); // решить!!!
        foreach (array_keys($data) as $key) {
            if (!in_array($key, ['user_id', 'schedule_id', 'recordcomment'])) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['user_id', 'schedule_id']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        $query = "insert into record_activity (" . implode(",", $field) . ") values (" . implode(",", $value) . ");";
        //echo $query;
        mysql_query($query) or die();
        return mysql_insert_id();
    }

    public static function getSchedule($date)
    {
        $date = self::sqlInjection($date);
        $query = "
        select  sa.id,activity_id,sa.trainer_id,activitytime,sa.activityduration,sa.activitydate,activityname,activitycomment,sa.mincount,sa.maxcount,username  from
        schedule_activity as sa
        left outer join activity as a on sa.activity_id = a.id
        left outer join record_activity as ra on sa.id = ra.schedule_id
        left outer join users as u on ra.user_id = u.id
        where activitydate BETWEEN '" . substr($date, 0, -3) . "-01' AND '" . substr($date, 0, -3) . "-31'
        order by activitydate,activitytime;";

        $res = mysql_query($query) or die();
        $number = cal_days_in_month(CAL_GREGORIAN, substr($date, 5, 2), substr($date, 0, 4));
        $control[] = [];
        $id = 0;
        if ($res) {
            //echo '<pre>';
            while ($row = mysql_fetch_assoc($res)) {
                if(strlen($row['username'])==0)$row['username'];
                if ($id != $row['id']) {
                    $id = $row['id'];
                    //print_r($row);//id username
                    if (!empty($row_data))
                        $data[$row_data['activitydate']][$row_data['activitytime']] = $row_data;
                    $row_data = [
                        'id' => $row['id'],
                        'activity_id' => $row['activity_id'],
                        'activitytime' => $row['activitytime'],
                        'activityduration' => $row['activityduration'],
                        'activitydate' => $row['activitydate'],
                        'activityname' => $row['activityname'],
                        'maxcount' => $row['maxcount'],
                        'mincount' => $row['mincount'],
                        'count' => $row['count'],
                        'username' => [$row['username']]
                    ];
                    $control[] = $row['activitydate'];
                } else {
                    $row_data['username'][] = $row['username'];
                }
            }
            $data[$row_data['activitydate']][$row_data['activitytime']] = $row_data;
        }

        for ($number; $number > 0; $number--) {
            //echo count($data[substr($date,0,7).'-'.$number]);
            $worktime = 21;
            $d = $number > 9 ? $number : '0' . $number;
            while (count($data[substr($date, 0, 7) . '-' . $d]) < 12) {
                if (isset($data[substr($date, 0, 7) . '-' . $d][$worktime])) $worktime--;
                else $data[substr($date, 0, 7) . '-' . $d][$worktime] = [
                    'activitytime' => $worktime,
                    'activitydate' => substr($date, 0, 7) . '-' . $d,
                    'activity_id' => 0,
                    'activityduration' => 1
                ];
            }
        }

       /* echo '<pre>';
        print_r($data);
        echo '</pre>';*/
        return $data;


    }

    public static function sqlInjection($data)
    {
        $data = str_replace('"', '', $data);
        $data = str_replace('`', '', $data);
        $data = str_replace("'", '', $data);
        $data = str_replace(";", '', $data);
        $data = str_replace("<script>", '', $data);
        $data = str_replace("</script>", '', $data);
        $data = str_replace("<?", '', $data);
        $data = str_replace("?>", '', $data);
        return $data;
    }

    public static function phoneCheck($data)
    {
        $data = str_replace('-', '', $data);
        $data = str_replace(' ', '', $data);
        $data = str_replace("+7", '8', $data);
        $data = str_replace("(", '', $data);
        $data = str_replace(")", '', $data);
        $data = str_replace("_", '', $data);
        $data = str_replace(":", '', $data);
        $data = str_replace("+", '', $data);
        $data = str_replace(",", '', $data);
        if (is_numeric($data))
            return $data;
        else
            return intval($data, 'integer');
    }
} ?>


