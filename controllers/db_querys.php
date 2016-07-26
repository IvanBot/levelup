<?php
require_once('router.php');

if ($_GET['delUsers'] > 0) //?delUsers=1&user_id=1
    print_r(json_encode(scheduler::delUsers($_GET['user_id'])));
if ($_GET['getUsers'] > 0) //?getUsers=1
    print_r(json_encode(scheduler::getUsers()));
if ($_GET['getAdmUsersRecords'] > 0) //?getUsersRecords=1
    print_r(json_encode(scheduler::getAdmUsersRecords($_GET)));
if ($_GET['getScheduleCicle'] > 0) //?getScheduleCicle=1
    print_r(json_encode(scheduler::getScheduleCicle($_GET)));
if ($_GET['getSchedule'] > 0) //?getSchedule=1&date=2016-07-07
    print_r(json_encode(scheduler::getSchedule($_GET)));

if (($_GET['setUser'] > 0 or ($_GET['setUser'] > 0 and $_GET['user_id'])) && !$_GET['user_sess']) {//?setUser=1&phone=206607-0788&username=Kesha&surname=Popkin&email=ke@popkin.ru&usercomment=Kakadu
    $user_id = scheduler::setUser($_GET);
} else {
    $user_id = $_GET['user_id'];
}
if ($_GET['delRecordByPhone'] > 0) {//?delRecord=1&record_id=1
    print_r(json_encode(scheduler::delRecordByPhone($_GET)));
}
if ($_GET['setRecord'] > 0) {//?setRecord=1&user_id=1&schedule_id=1&recordcomment=First!&activity_id=1&activitydate=2016-07-16&starttime=10:15
    $rec_id = '';
    if ($_GET['username']) $_GET['name'] = $_GET['username'].' '.$_GET['surname'];
    if ($_GET['schedule_time']) $_GET['starttime'] = $_GET['schedule_time'];
    if ($_GET['schedule_date']) $_GET['activitydate'] = $_GET['schedule_date'];
    if ($_GET['schedule_id'] == 0 and $_GET['schedule_date'] and $_GET['schedule_time'])
        $_GET['schedule_id'] = scheduler::setSchedule(array('activity_id' => 0, 'starttime' => $_GET['schedule_time'], 'activitydate' => $_GET['schedule_date'], 'activityduration' => 1)); // добавим в график занятие
    if ($user_id) {$_GET['user_id'] = $user_id;
    $rec_id = scheduler::setRecord($_GET);}
    print_r($user_id.'-'.$rec_id);
}
if ($_GET['delRecord'] > 0){scheduler::delRecord($_GET);}
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


class scheduler
{
    public static function setUser($data)
    {

        $field = [];
        $value = [];
        if ($data['phone']) $data['phone'] = self::phoneCheck($data['phone']);
        /*if ($data['phone']) { // обрабатывать повторные записи клиентов
            $del = 'update users set deleted = 1 where id = ';
            $res = mysql_query('select * from users where deleted IS NULL and phone='.$data['phone']);
            if ($res) {
                echo '<pre>';
                $i = preg_replace("/[^0-9]/", '', $res);
                while ($row = mysql_fetch_assoc($res)) {
                    if($i>1)
                        mysql_query($del.$row['id']);
                    else return $row['id'];
                //    mysql_query($del.$row['id']);
                    $i--;
                }
            }
        }*/
        if (empty($data['phone']) or empty($data['username'])) return 0; // решить!!!
        //if ($data['user_id'] > 0) mysql_query('select * from users where deleted IS NULL and phone=' . $data['user_id']);
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        foreach (array_keys($data) as $key) {
            if (!in_array($key, ['phone', 'username', 'surname', 'ip', 'email', 'userpassword', 'permission', 'usercomment']) or strlen($data[$key]) == 0) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['phone', 'permission']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        if ($value and count($data) > 1) {
            $query = "insert into users (" . implode(",", $field) . ") values (" . implode(",", $value) . ");";
            mysql_query($query) or die();
            return mysql_insert_id();
        }
    }

    public static function setActivity($data)
    {
        $field = [];
        $value = [];
        if ($data['activity_id'] > 0) mysql_query('update activity set deleted = 1 where id = ' . $data['activity_id']);
        foreach (array_keys($data) as $key) {
            if (!in_array($key, ['trainer_id', 'activityname', 'activitycomment', 'mincount', 'maxcount', 'activityduration']) or strlen($data[$key]) == 0) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['mincount', 'maxcount', 'activityduration']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        if ($value and strlen($data['activityname']) > 0) {
            $query = "insert into activity (" . implode(",", $field) . ") values (" . implode(",", $value) . ");";
            mysql_query($query) or die();
            return mysql_insert_id();
        }
    }

    public static function setTrainer($data)
    {
        $field = [];
        $value = [];

        if (empty($data['permission'])) $data['permission'] = 0;
        if (empty($data['active'])) $data['active'] = 1;
        if (!empty($data['phone'])) self::phoneCheck($data['phone']); // решить!!!
        if ($data['trainer_id'] > 0) mysql_query('update trainers set deleted = 1 where id = ' . $data['trainer_id']);

        foreach (array_keys($data) as $key) {
            if (!in_array($key, ['phone', 'trainername', 'trainersurname', 'experience', 'email', 'photo', 'userpassword', 'permission', 'active', 'trainercomment']) or strlen($data[$key]) == 0) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['phone', 'permission', 'active']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        if ($value and strlen($data['trainername']) > 0) {
            $query = "insert into trainers (" . implode(",", $field) . ") values (" . implode(",", $value) . ");";
            mysql_query($query) or die();
            return mysql_insert_id();
        }
    }

    public static function setSchedule($data)
    {
        $field = [];
        $value = [];
        if ($data['schedule_id'] > 0) mysql_query('update schedule_activity set deleted = 1 where id = ' . $data['schedule_id']);
        if ((empty($data['activity_id']) and $data['activity_id'] != 0) or empty($data['starttime']) or (empty($data['activitydate']) and $data['cycleday'] == 0)) die(); // решить!!!
        foreach (array_keys($data) as $key) {
            if (!in_array($key, [ 'activity_id', 'trainer_id', 'starttime', 'endtime', 'cycleday', 'activityduration', 'activitydate', 'maxcount', 'mincount', 'deleted']) or strlen($data[$key]) == 0) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['activity_id', 'trainer_id', 'maxcount', 'mincount', 'activityduration', 'cycleday']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        if ($value) {
            $query = "insert into schedule_activity (" . implode(",", $field) . ") values (" . implode(",", $value) . ")
            ON DUPLICATE KEY UPDATE starttime = VALUES(starttime),endtime = VALUES(endtime),activitydate = VALUES(activitydate),
            maxcount = VALUES(maxcount),mincount = VALUES(mincount),activity_id = VALUES(activity_id),cycleday = VALUES(cycleday),
            trainer_id = VALUES(trainer_id)

            ";
            // ON DUPLICATE KEY UPDATE field = VALUES(field);
            mysql_query($query) or die();
            return mysql_insert_id();
        }
    }

    public static function delRecord($data)
    {
        if ($data['record_id'] > 0) mysql_query('update record_activity set deleted = 1 where id = ' . $data['record_id']);
        return 1;
    }

    public static function setRecord($data)
    {

        $field = [];
        $value = [];

        if ($data['record_id'] > 0) mysql_query('update record_activity set deleted = 1 where id = ' . $data['record_id']);
        if (empty($data['user_id']) or empty($data['schedule_id'])) return 0; // решить!!!
        if($data['phone'])$data['phone'] = str_replace('-', '', $data['phone']);
        foreach (array_keys($data) as $key) {

            if (!in_array($key, ['phone', 'name', 'user_id', 'schedule_id', 'activity_id', 'activitydate', 'starttime', 'recordcomment']) or strlen($data[$key]) == 0) {
                unset($data[$key]);
            } else {
                $field[] = $key;
                $value[] = !in_array($key, ['phone', 'user_id', 'schedule_id', 'activity_id']) ? "'" . self::sqlInjection($data[$key]) . "'" : self::sqlInjection($data[$key]);
            }
        }
        if ($value) {
            $query = "insert into record_activity (" . implode(",", $field) . ") values (" . implode(",", $value) . ");";

            mysql_query($query) or die();
            return mysql_insert_id();
        }
        return 0;
    }

    public static function getAdmUsersRecords($d)
    {
        $date = $d['date'];
        if (!$date) $between = 'ra.activitydate BETWEEN "' . date('Y-m-d') . '" AND "2050-01-01"';
        else $between = "ra.activitydate BETWEEN '" . substr($date, 0, -3) . "-01' AND '" . substr($date, 0, -3) . "-" . cal_days_in_month(CAL_GREGORIAN, substr($date, 5, 2), substr($date, 0, 4)) . "'";
        $query = "
        select ra.id,ra.user_id,ra.schedule_id,sa.activity_id,ra.activitydate,ra.starttime,ra.recordcomment,u.phone,u.username,u.surname,ra.id,u.email,u.ip,u.usercomment,a.activityname
        from record_activity as ra
        left outer join users as u on ra.user_id = u.id
        left outer join schedule_activity as sa on ra.schedule_id = sa.id
        left outer join activity as a on sa.activity_id = a.id
        where ra.deleted IS NULL AND " . $between . "
        order by ra.activitydate,ra.starttime;";
        // echo $query;
        $res = mysql_query($query) or die();
        if ($res) {
            $i = 1;

            while ($row = mysql_fetch_assoc($res)) {
                //echo '<pre>';print_r($row);
                $row_data = [
                    'user_id' => $row['user_id'],
                    'record_id' => $row['id'],
                    'num' => $i,
                    'activitydate' => $row['activitydate'],
                    'starttime' => $row['starttime'],
                    'username' => $row['username'] ? $row['username'] : '',
                    'phone' => $row['phone'],
                    'activityname' => $row['activityname'] ? $row['activityname'] : '',
                    'surname' => $row['surname'] ? $row['surname'] : '',
                    'email' => [$row['email']],
                    'ip' => $row['ip'],
                    'cycleday' => $row['cycleday'],
                    'usercomment' => $row['usercomment']

                ];
                $data['data'][] = $row_data;
                $i++;
            }
            if (!$d['start'] or $d['start'] == 0) $data['total_count'] = mysql_fetch_row(mysql_query("
                select  count(*)  from record_activity as ra left outer join users as u on ra.user_id = u.id
                where ra.deleted IS NULL AND " . $between . ";"))[0];
            if ($data['total_count'] == 0) return [];
        }
        return $data;

    }

    public static function getUsers()
    {
        $query = "
        select * from users
        where deleted IS NULL
        order by username ;";
        //echo $query;
        $res = mysql_query($query) or die();

        if ($res) {
            $i = 1;
            while ($row = mysql_fetch_assoc($res)) {
                //echo '<pre>';print_r($row);
                $row_data = [
                    'user_id' => $row['id'],
                    'num' => $i,
                    'username' => $row['username'] ? $row['username'] : '',
                    'phone' => $row['phone'],
                    'surname' => $row['surname'] ? $row['surname'] : ''
                ];
                $data['data'][] = $row_data;
                $i++;
            }
            //if (!$d['start'] or $d['start'] == 0)
                $data['total_count'] = mysql_fetch_row(mysql_query("
                select count(*)  from users
                where deleted IS NULL;"))[0];
            if ($data['total_count'] == 0) return [];
        }
        return $data;

    }

    public static function delUsers($id)
    {
        $query = "update users set deleted = 1 where id =" . $id . ";";
        mysql_query($query) or die();
        $query = " update record_activity set deleted = 1 where user_id =" . $id . ";";
        mysql_query($query) or die();
        return 1;
    }

    public static function delRecordByPhone($data)
    {   $row = mysql_fetch_assoc(mysql_query("select * from record_activity where deleted IS NULL AND id =" . $data['record_id'] . " AND user_id=".$data['user_id'].";"));
        if(!$row) return 0;
        $query = "update record_activity set deleted = 1 where id =" . $data['record_id'] . " AND user_id=".$data['user_id'].";";
        $res = mysql_query($query) or die();
        return 1;
    }

    public static function getUsersRecords($date)
    {
        if (!$date) $between = 'ra.activitydate BETWEEN "' . date('Y-m-d') . '" AND "2050-01-01"';
        else $between = "ra.activitydate BETWEEN '" . substr($date, 0, -3) . "-01' AND '" . substr($date, 0, -3) . "-" . cal_days_in_month(CAL_GREGORIAN, substr($date, 5, 2), substr($date, 0, 4)) . "'";
        $query = "
        select  ra.id,ra.activity_id,ra.schedule_id,ra.starttime,ra.activitydate,ra.name,ra.phone
        from
        record_activity as ra
        where ra.deleted IS NULL AND " . $between . "
        order by ra.activitydate,ra.starttime;";
        //print_r($query);
        $res = mysql_query($query) or die();

        if ($res) {
            while ($row = mysql_fetch_assoc($res)) {
                $row_data = [
                    'record_id' => $row['id'],
                    'activity_id' => $row['activity_id'],
                    'schedule_id' => $row['schedule_id'],
                    'starttime' => $row['starttime'],
                    'activitydate' => $row['activitydate'],
                    'name' => $row['name'],
                    'phone' => [$row['phone']]

                ];
                $data[$row_data['activitydate']][substr($row_data['starttime'], 0, 2) . substr($row_data['starttime'], 3, 2)][] = $row_data;
            }
        }
        return $data;
    }


    public static function getSchedule($d)
    {
        $adm = $d['adm'];
        $date = $d['date'];
        $data = [];

        if (!$adm) {
            $number = cal_days_in_month(CAL_GREGORIAN, substr($date, 5, 2), substr($date, 0, 4));

            $date = self::sqlInjection($date);

            $users = self::getUsersRecords($date);

            $cicle = self::getScheduleCicle([]);

            $n = 1;
            $wd = date('w', mktime(0, 0, 0,  (int)substr($date, 5, 2), $n, (int)substr($date, 0, 4)));
            for ($wd; $wd <= 7; $wd++) {
                $date = substr($date, 0, 4) . '-' . substr($date, 5, 2) . '-' . ($n <= 9 ? '0' . $n : $n);
                if (isset($cicle[$wd])) {
                    $data[$date] = $cicle[$wd];

                    foreach (array_keys($cicle[$wd]) as $time) {
                        $data[$date][$time]['activitydate'] = $date;
                        if ($users[$date][$time]) {
                            $u = [];
                            foreach ($users[$date][$time] as $val)//добавить проверку привязки по ид
                                $u[] = [$val['name'] , $val['record_id']];
                            $data[$date][$time]['username'] = $u;
                        }
                    }
                } else {
                    $data[$date] = [];
                }
                if ($wd == 7 and $number > $n)
                    $wd = 0;
                $n++;

            }
            $between = "sa.activitydate BETWEEN '" . substr($date, 0, -3) . "-01' AND '" . substr($date, 0, -3) . "-" . $number . "'";
        } else {
            $users = self::getUsersRecords('');
            if(!$d['from']) $d['from'] = "1970-01-01";
            if($d['to'] < $d['from'] or !$d['to']) $d['to'] = "2050-01-01";
            if($d['from']) $between = 'sa.activitydate BETWEEN "' . $d['from'] . '" AND "'.$d['to'].'"';
            else $between = 'sa.activitydate BETWEEN "' . date('Y-m-d') . '" AND "2050-01-01"';
        }
        if($d['del']) $del = 'sa.deleted = 1';
        else $del = 'sa.deleted IS NULL';
        $query = "
        select  sa.id,sa.activity_id,sa.trainer_id,sa.starttime,endtime,sa.activityduration,sa.activitydate,activityname,activitycomment,sa.mincount,sa.maxcount,username  from
        schedule_activity as sa
        left outer join activity as a on sa.activity_id = a.id
        left outer join record_activity as ra on sa.id = ra.schedule_id
        left outer join users as u on ra.user_id = u.id
        where ".$del." AND " . $between . " AND (cycleday = 0 OR cycleday IS NULL)
        order by sa.starttime;";

       // print_r($query);
        $res = mysql_query($query) or die();
        if ($res) {
            $i = 1;
            $row_data = [];
            while ($row = mysql_fetch_assoc($res)) {
                $row['starttime'] = substr($row['starttime'], 0, -3);
                $row['endtime'] = substr($row['endtime'], 0, -3);
                if ($adm and $row_data) {
                    $starttime = $row['starttime'];
                    $activitydate = $row['activitydate'];
                    if ($row_data['starttime'] == $starttime and $row_data['activitydate'] == $activitydate) $row_data['dots'] = 'deact';
                    $data['data'][] = $row_data;
                } elseif ($row_data) {
                    $data[$row_data['activitydate']][substr($row_data['starttime'], 0, 2) . substr($row_data['starttime'], 3, 2)] = $row_data;
                }
                $u = [];
                if ($users[$row['activitydate']][substr($row['starttime'], 0, 2) . substr($row['starttime'], 3, 2)]) {
                    foreach ($users[$row['activitydate']][substr($row['starttime'], 0, 2) . substr($row['starttime'], 3, 2)] as $val)
                        $u[] = [$val['username'] . ' ' . $val['surname'], $val['record_id']];
                    $data[$row['activitydate']][substr($row['starttime'], 0, 2) . substr($row['starttime'], 3, 2)]['username'] = $u;
                }
                $row_data = [
                    'id' => $row['id'],
                    'num' => $i,
                    'dots' => '',
                    'activity_id' => $row['activity_id'],
                    'starttime' => $row['starttime'],
                    'endtime' => $row['endtime'],
                    'activitydate' => $row['activitydate'],
                    'activityname' => $row['activityname'],
                    'activityduration' => $row['endtime'] ? substr($row['endtime'], 0, 2) - substr($row['starttime'], 0, 2) : 1,
                    'maxcount' => $row['maxcount'],
                    'mincount' => $row['mincount'],
                    'activitycomment' => $row['activitycomment'],
                    'cycleday' => $row['cycleday'],
                    'username' => $u
                ];
                $i++;
            }

            if ($adm and $row_data) {
                $starttime = $row['starttime'];
                $activitydate = $row['activitydate'];
                if ($row_data['starttime'] == $starttime and $row_data['activitydate'] == $activitydate) $row_data['dots'] = 'deact';
                $data['data'][] = $row_data;
            } elseif ($row_data) {
                $data[$row_data['activitydate']][substr($row_data['starttime'], 0, 2) . substr($row_data['starttime'], 3, 2)] = $row_data;
            }
            if ($data) array_multisort($data);
            //echo '<pre>';print_r($data);
            if ($adm and (!$d['start'] or $d['start'] == 0)) {
                $data['total_count'] = mysql_fetch_row(mysql_query("
                select  count(*)  from
                schedule_activity as sa
                where " . $between . " AND ".$del." AND (cycleday = 0 OR cycleday IS NULL);"))[0];
                if ($data['total_count'] == false) $data = [];
            }
        }


        /*забивает пустыми данными таблицу*/
        /*for ($number; $number > 0; $number--) {
            //время работы
            $workfrom = 14;  //от
            $worktime = 22;  //до
            $wd = ($worktime + 1) - $workfrom;
            $worktime--;
            $d = $number > 9 ? $number : '0' . $number;
            $d = substr($date, 0, 7) . '-' . $d;
            while (count($data[$d]) < $wd) {
                if (!isset($data[$d][$worktime.'00'])) {
                    $data[$d][$worktime.'00'] = array(
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
                    $data[$d][$worktime.'00']['username'] = $u;
                }
                $worktime--;
            }

        }*/

        return $data;
    }

    public static function getScheduleCicle($d)
    {
        $weekday = [1 => 'ПН', 2 => 'ВТ', 3 => 'СР', 4 => 'ЧТ', 5 => 'ПТ', 6 => 'СБ', 7 => 'ВС'];
        if ($d['cycleday'] > 0) $day = " AND sa.cycleday=" . self::sqlInjection($d['cycleday']);
        else $day = "";
        if ($d['start'] > -1 and $d['count'] > 0) $limit = " LIMIT " . $d['start'] . "," . $d['count'];
        else $limit = "";

        $query =   "
        select  sa.id, sa.activity_id,sa.trainer_id, sa.starttime, sa.endtime,sa.activityduration,sa.activitydate,activityname,activitycomment,sa.mincount,sa.maxcount,sa.cycleday,ra.user_id
        from
        schedule_activity as sa
        left join record_activity as ra on sa.id = ra.schedule_id
        left outer join activity as a on sa.activity_id = a.id
        where
        sa.deleted IS NULL
        AND sa.cycleday > 0 " . $day . "
        AND (ra.id IS NULL or (ra.id IS not null and ra.deleted IS NULL /*and ra.activitydate>=CURDATE()*/))
        group by sa.id
        order by cycleday,sa.starttime " . $limit . ";";

        $res = mysql_query($query) or die();
        $data = [];
        $row_data = [];
        if ($res) {
            $i = 1;
            while ($row = mysql_fetch_assoc($res)) {
                //echo '<pre>'; print_r($row);
                $row['starttime'] = substr($row['starttime'], 0, -3);
                $row['endtime'] = substr($row['endtime'], 0, -3);
                if (($d['cycleday'] > -1 or $d['start'] > -1) and $row_data) {
                    $starttime = $row['starttime'];
                    if ($row_data['starttime'] == $starttime) $row_data['dots'] = 'deact';
                    $data['data'][] = $row_data;
                } elseif ($row_data) {
                    $data[$row_data['cycleday']][substr($row_data['starttime'], 0, 2) . substr($row_data['starttime'], 3, 2)] = $row_data;
                }
                $row_data = [
                    'num' => $i,
                    'dots' => '',
                    'id' => $row['id'],
                    'activity_id' => $row['activity_id'],
                    'starttime' => $row['starttime'],
                    'endtime' => $row['endtime'],
                    'activityduration' => $row['endtime'] ? substr($row['endtime'], 0, 2) - substr($row['starttime'], 0, 2) : 1,
                    'activityname' => $row['activityname'],
                    'maxcount' => $row['maxcount'],
                    'mincount' => $row['mincount'],
                    'activitycomment' => $row['activitycomment'],
                    'cycleday' => $row['cycleday'],
                    'cycledayname' => $weekday[$row['cycleday']],
                    'haverec' => ($row['user_id']>0?1:0)
                ];

                $i++;
            }

            if (($d['cycleday'] > -1 or $d['start'] > -1) and $row_data) {
                $data['data'][] = $row_data;
            } elseif ($row_data) {
                $data[$row_data['cycleday']][substr($row_data['starttime'], 0, 2) . substr($row_data['starttime'], 3, 2)] = $row_data;
            }
        }
        if ($d['start'] == 0) {
            $data['total_count'] = mysql_fetch_row(mysql_query("
                select  count(*) from
                schedule_activity as sa

                where sa.deleted IS NULL AND sa.cycleday > 0 " . $day))[0]." group by sa.id";
            if ($data['total_count'] == false) $data = [];
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

?>