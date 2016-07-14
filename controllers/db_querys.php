<?php
require_once('router.php');


if ($_GET['admo']) {
    if ($_GET['getSchedule'] > 0 and $_GET['date'] > 0) //?getSchedule=1&date=2016-07-07
        print_r(json_encode(admo::getSchedule($_GET['date'])));

} else {
    if ($_GET['getScheduleCicle'] > 0) //?getScheduleCicle=1
        print_r(json_encode(scheduler::getScheduleCicle()));
    if ($_GET['getSchedule'] > 0 and $_GET['date'] > 0) //?getSchedule=1&date=2016-07-07
        print_r(json_encode(scheduler::getSchedule($_GET['date'])));
    if ($_GET['setUser'] > 0)//?setUser=1&phone=206607-0788&username=Kesha&surname=Popkin&email=ke@popkin.ru&usercomment=Kakadu
        $user_id = scheduler::setUser($_GET);
    if ($_GET['setRecord'] > 0) {//?setRecord=1&user_id=1&schedule_id=1&recordcomment=First!&activity_id=1&activitydate=2016-07-16&starttime=10:15
        if ($_GET['schedule_time']) $_GET['starttime'] = $_GET['schedule_time'];
        if ($_GET['schedule_date']) $_GET['activitydate'] = $_GET['schedule_date'];
        if ($_GET['schedule_id'] == 0 and $_GET['schedule_date'] and $_GET['schedule_time'])
            $_GET['schedule_id'] = scheduler::setSchedule(array('activity_id' => 0, 'starttime' => $_GET['schedule_time'], 'activitydate' => $_GET['schedule_date'], 'activityduration' => 1)); // добавим в график занятие
        if ($user_id) $_GET['user_id'] = $user_id;
        $rec_id = scheduler::setRecord($_GET);
    }
    if ($_GET['setTrainer'] > 0) {//?setTrainer=1&phone=206607-0788&trainername=Bagira&trainersurname=Kitty&experience=2010-06-01&email=bad@kitty.u&photo=../photo.img&trainercomment=kis-kis
        $tr_id = scheduler::setTrainer($_GET);
    }
    if ($_GET['setActivity'] > 0) {//?setActivity=1&activityname=go-go dance&activitycomment=dance-dance&mincount=2&maxcount=6&activityduration=2
        if ($tr_id) $_GET['trainer_id'] = $tr_id;
        $act_id = scheduler::setActivity($_GET);
    }
    if ($_GET['setSchedule'] > 0) {//?setSchedule=1&activity_id=1&trainer_id=3&starttime=12&activityduration=3&activitydate=2016-07-10&maxcount=20&mincount=1
        if ($act_id) $_GET['activity_id'] = $act_id;
        scheduler::setSchedule($_GET);
    }


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
        if (!$_GET['cycleday']) $_GET['cycleday'] = 0;
        if ((empty($data['activity_id']) and $data['activity_id'] != 0) or empty($data['starttime']) or empty($data['activitydate'])) die(); // решить!!!
        if (empty($data['activityduration'])) $data['activityduration'] = 1;
        foreach (array_keys($data) as $key) {
            if (!in_array($key, ['activity_id', 'trainer_id', 'starttime', 'endtime', 'cycleday', 'activityduration', 'activitydate', 'maxcount', 'mincount'])) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['activity_id', 'trainer_id', 'maxcount', 'mincount', 'activityduration', 'cycleday']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
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
            if (!in_array($key, ['user_id', 'schedule_id', 'activity_id', 'activitydate', 'starttime', 'recordcomment'])) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['user_id', 'schedule_id', 'activity_id']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        $query = "insert into record_activity (" . implode(",", $field) . ") values (" . implode(",", $value) . ");";
        mysql_query($query) or die();
        return mysql_insert_id();
    }

    public static function getUsersRecords($date)
    {
        $query = "
        select  *  from record_activity as ra left outer join users as u on ra.user_id = u.id
        where activitydate BETWEEN '" . substr($date, 0, -3) . "-01' AND '" . substr($date, 0, -3) . "-31'
        order by activitydate,starttime;";
        $res = mysql_query($query) or die();
        if ($res) {
            while ($row = mysql_fetch_assoc($res)) {
                $row_data = [
                    'id' => $row['id'],
                    'activity_id' => $row['activity_id'],
                    'schedule_id' => $row['schedule_id'],
                    'starttime' => $row['starttime'],
                    'activitydate' => $row['activitydate'],
                    'username' => $row['username'],
                    'surname' => $row['surname']//,
                    //'phone' => [$row['phone']]

                ];
                $data[$row_data['activitydate']][substr($row_data['starttime'], 0, 2)][] = $row_data;
            }
        }
        return $data;
    }

    public static function getSchedule($date)
    {
        $number = cal_days_in_month(CAL_GREGORIAN, substr($date, 5, 2), substr($date, 0, 4));
        $date = self::sqlInjection($date);
        $users = self::getUsersRecords($date);

        $cicle = self::getScheduleCicle();
        $n = 1;
        $wd = date('N', mktime(0, 0, 0, $n, substr($date, 5, 2), substr($date, 0, 4)));
        for ($wd; $wd <= 7; $wd++) {

            $d = substr($date, 0, 4) . '-' . substr($date, 5, 2) . '-' . ($n <= 9 ? '0' . $n : $n);
            if (isset($cicle[$wd])) {
                //activitydate
                $data[$d] = $cicle[$wd];
                foreach (array_keys($cicle[$wd]) as $time) {
                    $data[$d][$time]['activitydate'] = $d;
                    if ($users[$d][$time]) {
                        $u = [];
                        foreach ($users[$d][$time] as $val)//добавить проверку привязки по ид
                            $u[] = $val['username'] . ' ' . $val['surname'];
                        $data[$d][$time]['username'] = $u;
                    }
                }
            } else {
                $data[$d] = [];
            }
            if ($wd == 7 and $number > $n)
                $wd = 1;
            $n++;
        }
        $query = "
        select  sa.id,sa.activity_id,sa.trainer_id,sa.starttime,endtime,sa.activityduration,sa.activitydate,activityname,activitycomment,sa.mincount,sa.maxcount,username  from
        schedule_activity as sa
        left outer join activity as a on sa.activity_id = a.id
        left outer join record_activity as ra on sa.id = ra.schedule_id
        left outer join users as u on ra.user_id = u.id
        where sa.activitydate BETWEEN '" . substr($date, 0, -3) . "-01' AND '" . substr($date, 0, -3) . "-" . $number . "' AND cycleday = 0
        order by sa.activitydate,sa.starttime;";

        $res = mysql_query($query) or die();
        if ($res) {
            while ($row = mysql_fetch_assoc($res)) {
                $row['starttime'] = substr($row['starttime'], 0, -3);
                $row['endtime'] = substr($row['endtime'], 0, -3);
                $u = [];
                if ($users[$row['activitydate']][substr($row['starttime'], 0, 2)]) {
                    foreach ($users[$row['activitydate']][substr($row['starttime'], 0, 2)] as $val)
                        $u[] = $val['username'] . ' ' . $val['surname'];
                    $data[$row['activitydate']][substr($row['starttime'], 0, 2)]['username'] = $u;
                }
                $row_data = [
                    'id' => $row['id'],
                    'activity_id' => $row['activity_id'],
                    'starttime' => $row['starttime'],
                    'endtime' => $row['endtime'],
                    'activityduration' => $row['activityduration'],
                    'activitydate' => $row['activitydate'],
                    'activityname' => $row['activityname'],
                    'maxcount' => $row['maxcount'],
                    'mincount' => $row['mincount'],
                    'cycleday' => $row['cycleday'],
                    'username' => $u
                ];
                $data[$row_data['activitydate']][substr($row_data['starttime'], 0, 2)] = $row_data;
            }
        }


        /*забивает пустыми данными таблицу*/
        for ($number; $number > 0; $number--) {
            $worktime = 21;
            $d = $number > 9 ? $number : '0' . $number;
            $d = substr($date, 0, 7) . '-' . $d;
            while (count($data[$d]) < 12) {
                if (!isset($data[$d][$worktime])) {
                    $data[$d][$worktime] = array(
                        'starttime' => $worktime . ':00',
                        'activitydate' => $d,
                        'activity_id' => 0,
                        'activityduration' => 1
                    );
                }
                if ($users[$d][$worktime]) {
                    $u = array();
                    foreach ($users[$d][$worktime] as $key => $val) {
                        $u[] = $val['username'] . ' ' . $val['surname'];
                    }
                    $data[$d][$worktime]['username'] = $u;
                }
                $worktime--;
            }

        }
        return $data;
    }

    public static function getScheduleCicle()
    {
        $query = "
        select  sa.id, sa.activity_id,sa.trainer_id, sa.starttime, sa.endtime,sa.activityduration,sa.activitydate,activityname,activitycomment,sa.mincount,sa.maxcount,cycleday  from
        schedule_activity as sa
        left outer join activity as a on sa.activity_id = a.id
        left outer join record_activity as ra on sa.id = ra.schedule_id
        where deleted IS NULL AND cycleday > 0
        order by cycleday,starttime;";
        $res = mysql_query($query) or die();
        $data = [];
        if ($res) {
            while ($row = mysql_fetch_assoc($res)) {
                $row['starttime'] = substr($row['starttime'], 0, -3);
                $row['endtime'] = substr($row['endtime'], 0, -3);
                $row_data = [
                    'id' => $row['id'],
                    'activity_id' => $row['activity_id'],
                    'starttime' => $row['starttime'],
                    'endtime' => $row['endtime'],
                    'activityduration' => substr($row['endtime'], 0, 2) - substr($row['starttime'], 0, 2),
                    'activityname' => $row['activityname'],
                    'maxcount' => $row['maxcount'],
                    'mincount' => $row['mincount'],
                    'cycleday' => $row['cycleday']
                ];
                $data[$row_data['cycleday']][substr($row_data['starttime'], 0, 2)] = $row_data;
            }
        }
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
}

class admo
{


    public static function getSchedule($date)
    {
        $date = scheduler::sqlInjection($date);
        $query = "
        select  sa.id,sa.activity_id,sa.trainer_id,starttime,endtime,sa.activityduration,sa.activitydate,sa.mincount,sa.maxcount,
        a.activityname,a.activitycomment,
        u.username,u.surname,u.phone,u.email
        from
        schedule_activity as sa
        left outer join activity as a on sa.activity_id = a.id
        left outer join record_activity as ra on sa.id = ra.schedule_id
        left outer join users as u on ra.user_id = u.id
        where activitydate BETWEEN '" . substr($date, 0, -3) . "-01' AND '" . substr($date, 0, -3) . "-31'
        order by activitydate,starttime;";
        $res = mysql_query($query) or die();
        $number = cal_days_in_month(CAL_GREGORIAN, substr($date, 5, 2), substr($date, 0, 4));
        $control[] = [];
        $id = 0;
        if ($res) {
            while ($row = mysql_fetch_assoc($res)) {
                if (strlen($row['username']) == 0) $row['username'];
                if ($id != $row['id']) {
                    $id = $row['id'];
                    //print_r($row);//id username
                    if (!empty($row_data))
                        $data['data'][] = $row_data;
                    $row_data = [
                        'id' => $row['id'],
                        'activity_id' => $row['activity_id'],
                        'starttime' => $row['starttime'],
                        'endtime' => $row['endtime'],
                        'activityduration' => $row['activityduration'],
                        'activitydate' => $row['activitydate'],
                        'activityname' => $row['activityname'],
                        'maxcount' => $row['maxcount'],
                        'mincount' => $row['mincount'],
                        'cicleday' => $row['cicleday'],
                        'user' => [['username' => $row['username'], 'usersurname' => $row['usersurname'], 'phone' => $row['phone'], 'email' => $row['email']]]
                    ];
                    $control[] = $row['activitydate'];
                } else {
                    $row_data['user'][] = ['username' => $row['username'], 'usersurname' => $row['usersurname'], 'phone' => $row['phone'], 'email' => $row['email']];
                }

            }
            if ($row_data) $data['data'][] = $row_data;
        }


        $data['pos'] = $_GET['start'] ? $_GET['start'] : 0;
        if ($data['pos'] == 0) {
            $total_count = "
            select count(*)
            from
            schedule_activity as sa
            left outer join activity as a on sa.activity_id = a.id
            left outer join record_activity as ra on sa.id = ra.schedule_id
            left outer join users as u on ra.user_id = u.id
            where activitydate BETWEEN '" . substr($date, 0, -3) . "-01' AND '" . substr($date, 0, -3) . "-31'
            order by activitydate,starttime;";
            $data['total_count'] = mysql_fetch_row(mysql_query($total_count))[0];

        }


        return $data;
    }

}


?>