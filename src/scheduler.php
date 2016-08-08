<?php

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
        if (empty($data['phone']) or empty($data['name'])) return 0; // решить!!!
        //if ($data['user_id'] > 0) mysql_query('select * from users where deleted IS NULL and phone=' . $data['user_id']);
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        foreach (array_keys($data) as $key) {
            if (!in_array($key, ['phone', 'name', 'last_name', 'ip', 'email', 'userpassword', 'permission', 'usercomment']) or strlen($data[$key]) == 0) {
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

            if (!in_array($key, ['phone', 'name', 'last_name', 'cnt', 'user_id', 'schedule_id', 'activity_id', 'activitydate', 'starttime', 'recordcomment']) or strlen($data[$key]) == 0) {
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
        select ra.id,ra.user_id,ra.schedule_id,sa.activity_id,ra.activitydate,ra.starttime,ra.recordcomment,u.phone,u.name,u.last_name,ra.id,u.email,u.ip,u.usercomment,a.activityname
        from record_activity as ra
        left outer join users as u on ra.user_id = u.id
        left outer join schedule_activity as sa on ra.schedule_id = sa.id
        left outer join activity as a on sa.activity_id = a.id
        where ra.deleted IS NULL AND " . $between . "
        order by ra.activitydate,ra.starttime;";
        // echo $query;
        $res = mysql_query($query) or die(mysql_error());
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
                    'name' => $row['name'] ? $row['name'] : '',
                    'phone' => $row['phone'],
                    'activityname' => $row['activityname'] ? $row['activityname'] : '',
                    'last_name' => $row['last_name'] ? $row['last_name'] : '',
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

    public static function getAdminUsersRecords($d)
    {
        $date = $d['date'];
        if (!$date) $between = 'ra.activitydate BETWEEN "' . date('Y-m-d',strtotime("-1 month")) . '" AND "' . date('Y-m-d') . '"';
        else $between = "ra.activitydate BETWEEN '" . substr($date, 0, -3) . "-01' AND '" . substr($date, 0, -3) . "-" . cal_days_in_month(CAL_GREGORIAN, substr($date, 5, 2), substr($date, 0, 4)) . "'";
        $query = "
            SELECT ra.id,ra.user_id,ra.schedule_id,sa.activity_id,ra.activitydate,ra.starttime,ra.recordcomment,u.phone,u.name,u.last_name,ra.id,u.email,u.ip,u.usercomment,a.activityname
            FROM record_activity AS ra
            LEFT OUTER JOIN users AS u ON ra.user_id = u.id
            LEFT OUTER JOIN schedule_activity AS sa ON ra.schedule_id = sa.id
            LEFT OUTER JOIN activity AS a ON sa.activity_id = a.id
            WHERE ra.deleted IS NULL AND " . $between . "
            ORDER BY ra.activitydate,ra.starttime;";
        // echo $query;
        $res = mysql_query($query) or die(mysql_error());
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
                    'name' => $row['name'] ? $row['name'] : '',
                    'phone' => $row['phone'],
                    'activityname' => $row['activityname'] ? $row['activityname'] : '',
                    'last_name' => $row['last_name'] ? $row['last_name'] : '',
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
        order by name ;";
        //echo $query;
        $res = mysql_query($query) or die();

        if ($res) {
            $i = 1;
            while ($row = mysql_fetch_assoc($res)) {
                //echo '<pre>';print_r($row);
                $row_data = [
                    'user_id' => $row['id'],
                    'num' => $i,
                    'name' => $row['name'] ? $row['name'] : '',
                    'phone' => $row['phone'],
                    'last_name' => $row['last_name'] ? $row['last_name'] : ''
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
        else $between = "ra.activitydate = '$date'";//BETWEEN '" . substr($date, 0, -3) . "-01' AND '" . substr($date, 0, -3) . "-" . cal_days_in_month(CAL_GREGORIAN, substr($date, 5, 2), substr($date, 0, 4)) . "'";
        $query = "
        select  ra.id,ra.activity_id,ra.schedule_id,ra.starttime,ra.activitydate,ra.name,ra.phone,ra.cnt
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
                    'cnt' => $row['cnt'],
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

		    if (!strlen($date)) {
		    	$date = date("Y-m-d");
			}
			list($year,$month,$day) = explode("-", $date);
			$datetime = mktime(0, 0, 0, $month, $day, $year);

            $weekday = date('N', $datetime);

            $users = self::getUsersRecords($date);

            $cicle = self::getScheduleCicle(['cycleday' => $weekday]);
//            print_r($cicle);die();
            /*$n = 1;
            $wd = date('w', mktime(0, 0, 0,  (int)substr($date, 5, 2), $n, (int)substr($date, 0, 4)));
            for ($wd; $wd <= 7; $wd++) {
                $date = substr($date, 0, 4) . '-' . substr($date, 5, 2) . '-' . ($n <= 9 ? '0' . $n : $n);
                if (isset($cicle[$wd])) {*/
                    $data[$date] = $cicle[$weekday];

                    foreach (array_keys($cicle[$weekday]) as $time) {
                        $data[$date][$time]['activitydate'] = $date;
                        if ($users[$date][$time]) {
                            $u = [];
                            $cnt = 0;
                            foreach ($users[$date][$time] as $val) {//добавить проверку привязки по ид
                                $u[] = ["name" => $val['name'] . ($val['cnt'] > 1 ? " + ".($val['cnt']-1) : "") , "id" => $val['record_id']];
                                $cnt += $val['cnt'];
                            }
                            //$data[$date][$time]['name'] = $u;
                            $data[$date][$time]['registered'] = $u;
                            $data[$date][$time]['registered_count'] = $cnt;
                        }
                    }/*
                } else {
                    $data[$date] = [];
                }
                if ($wd == 7 and $number > $n)
                    $wd = 0;
                $n++;

            }*/
            return $data;
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
        select  sa.id,sa.activity_id,sa.trainer_id,sa.starttime,endtime,sa.activityduration,sa.activitydate,activityname,activitycomment,sa.mincount,sa.maxcount,ra.name, ra.cnt from
        schedule_activity as sa
        left outer join activity as a on sa.activity_id = a.id
        left outer join record_activity as ra on sa.id = ra.schedule_id
        left outer join users as u on ra.user_id = u.id
        where ".$del." AND " . $between . " AND (cycleday = 0 OR cycleday IS NULL)
        order by sa.starttime;";
        
        $res = mysql_query($query) or die(mysql_error());
//        die($query);
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
                        $u[] = [$val['name'], $val['record_id']];
                    $data[$row['activitydate']][substr($row['starttime'], 0, 2) . substr($row['starttime'], 3, 2)]['name'] = $u;
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
                    'name' => $u
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
                        $u[] = $val['name'] . ' ' . $val['last_name'];
                    }
                    $data[$d][$worktime.'00']['name'] = $u;
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
        //die($query);
        $res = mysql_query($query) or die(mysql_error());
        $data = [];
        $array_row_data = [];
        $row_data = [];
        if ($res) {
            $i = 1;
            while ($row = mysql_fetch_assoc($res)) {
                //echo '<pre>'; print_r($row);
                $row['starttime'] = substr($row['starttime'], 0, -3);
                $row['endtime'] = substr($row['endtime'], 0, -3);

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

                $data[$row_data['cycleday']][substr($row_data['starttime'], 0, 2) . substr($row_data['starttime'], 3, 2)] = $row_data;

                $i++;
            }

/*            if (($d['cycleday'] > -1 or $d['start'] > -1) and $row_data) {
                $data['data'][] = $row_data;
            } elseif ($row_data) {*/
//                $data[$row_data['cycleday']][substr($row_data['starttime'], 0, 2) . substr($row_data['starttime'], 3, 2)] = $row_data;
//            }
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

    public static function getScheduleWeekday($d)
    {
        $weekday = [1 => 'ПН', 2 => 'ВТ', 3 => 'СР', 4 => 'ЧТ', 5 => 'ПТ', 6 => 'СБ', 7 => 'ВС'];
        if ($d['cycleday'] > 0) $day = " AND A.cycleday=" . self::sqlInjection($d['cycleday']);
        else $day = "";
        if ($d['start'] > -1 and $d['count'] > 0) $limit = " LIMIT " . $d['start'] . "," . $d['count'];
        else $limit = "";

        $query =   "
        SELECT  A.id, A.activity_id, A.trainer_id, A.starttime, A.endtime, A.activityduration, A.activitydate, activityname, activitycomment, A.mincount, A.maxcount, A.cycleday, B.user_id
        FROM schedule_activity as A
        LEFT JOIN record_activity as B on A.id = B.schedule_id
        LEFT OUTER JOIN activity as C on A.activity_id = C.id
        WHERE A.deleted IS NULL
        AND A.cycleday > 0 " . $day . "
        AND (B.id IS NULL or (B.id IS not null and B.deleted IS NULL))
        GROUP BY A.id
        ORDER BY cycleday,A.starttime " . $limit . ";";

        $res = mysql_query($query) or die(mysql_error());
        $data = [];
        $row_data = [];
        $i = 1;
        while ($row = mysql_fetch_array($res)) {
            $row_data[] = [
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
        $data['data'] = $row_data;
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










    public static function addScheduleCicle($data)
    {
        $activity_query = mysql_query("SELECT id FROM activity WHERE activityname='".$data["activityname"]."'") or die(mysql_error());
        if(!$activity) {
            mysql_query("INSERT INTO activity (activityname) VALUES ('".$data["activityname"]."')") or die(mysql_error());
            $activity_query = mysql_query("SELECT id FROM activity WHERE activityname='".$data["activityname"]."'") or die(mysql_error());
        };
        $activity = mysql_fetch_array($activity_query);
        $demand = "
            INSERT INTO schedule_activity 
            (activity_id,trainer_id,starttime,endtime,cycleday,maxcount,mincount)
            VALUES
            ('".$activity['id']."','".$data["trainer_id"]."','".$data["starttime"]."','".$data["endtime"]."','".$data["cycleday"]."','".$data["maxcount"]."','".$data["mincount"]."')";
        $result = mysql_query($demand) or die(mysql_error());
        if($result) return array('result'=>0, 'message'=>'OK');
        else return array('result'=>1, 'message'=>'Error!');
    }

    public static function editScheduleCicle($data)
    {
        $activity_query = mysql_query("SELECT id FROM activity WHERE activityname='".$data["activityname"]."'") or die(mysql_error());
        if(!$activity) {
            mysql_query("INSERT INTO activity (activityname) VALUES ('".$data["activityname"]."')") or die(mysql_error());
            $activity_query = mysql_query("SELECT id FROM activity WHERE activityname='".$data["activityname"]."'") or die(mysql_error());
        };
        $activity = mysql_fetch_array($activity_query);
        $demand = "
            UPDATE schedule_activity SET
            activity_id = '".$activity['id']."',trainer_id = '".$data["trainer_id"]."',starttime = '".$data["starttime"]."',endtime = '".$data["endtime"]."',
            cycleday = '".$data["cycleday"]."',maxcount = '".$data["maxcount"]."',mincount = '".$data["mincount"]."'
            WHERE id=".$data['schedule_id'];
        $result = mysql_query($demand) or die(mysql_error());
        if($result) return array('result'=>0, 'message'=>'OK');
        else return array('result'=>1, 'message'=>'Error!');
    }

    public static function delScheduleCicle($data)
    {
        $result = mysql_query("DELETE FROM schedule_activity WHERE id=".$data['schedule_id']) or die(mysql_error());
        if($result) return array('result'=>0, 'message'=>'OK');
        else return array('result'=>1, 'message'=>'Error!');
    }
}
