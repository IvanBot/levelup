<?php

function render_report($name, $params) {
  $request = $params;
  if (!get_magic_quotes_gpc()) {
    foreach ($request as $k => $v) $request[$k] = addslashes($v);
  }  
  $filename = "$name.php";
  if (file_exists($filename)) {
    $arr = require("$name.php");
  } else {
    $arr = Array("error", "no such report");
  }
  $json = json_encode($arr);
  //header("Content-type: text/json");
  echo $json;
}

function translate($word) {
  if(isset($_COOKIE['language'])) $library = "../helpers/i18n/".str_replace('"', "", $_COOKIE['language']).".php";
  else $library = "../helpers/i18n/en.php";

  include($library);
  
  $translation = (isset($translation_table[$word]) && $translation_table[$word]!="") ? $translation_table[$word] : $word;
  return $translation;
}
                                                                                                        //ALLOWED QUEUES
function allowed_queues() {
  $user_queues = array();
  $i = 0;
  $demand_user_defult_queues = "
		SELECT val
		FROM cfg_setting
		WHERE handle = 'cti.queues.allowed' ";
  $result_user_defult_queues = mysql_query($demand_user_defult_queues) or die();
  while ($myrow_user_defult_queues = mysql_fetch_array($result_user_defult_queues)) {
    if ($myrow_user_defult_queues['val'] != "") {
      $b = explode(",", $myrow_user_defult_queues['val']);
      $c = count($b);
      for ($j = 0; $j < $c; $j++) {
        $user_queues[$i] = $b[$j];
        $i++;
      };
    };
  };

  $demand_user_queues = "
        SELECT val
		FROM cfg_user_setting
		WHERE handle = 'cti.queues.allowed'
		AND acl_user_id = (SELECT acl_user_id FROM acl_auth_token WHERE token=".$_COOKIE['token']." AND ip = ".$_COOKIE['ip'].") ";
  $result_user_queues = mysql_query($demand_user_queues) or die();
  while ($myrow_user_queues = mysql_fetch_array($result_user_queues)) {
    if ($myrow_user_queues['val'] != "") {
      $b = explode(",", $myrow_user_queues['val']);
      $c = count($b);
      for ($j = 0; $j < $c; $j++) {
        $user_queues[$i] = $b[$j];
        $i++;
      };
    };
  };

  $demand_user_group_queues = "
		SELECT A.val
		FROM cfg_group_setting AS A
		LEFT JOIN acl_user_group_has_users AS B ON (A.acl_user_group_id = B.acl_user_group_id)
		LEFT JOIN acl_user AS C ON (B.acl_user_id = C.id)
		WHERE C.id = (SELECT acl_user_id FROM acl_auth_token WHERE token=".$_COOKIE['token']." AND ip = ".$_COOKIE['ip'].") 
		AND A.handle = 'cti.queues.allowed' ";
  $result_user_group_queues = mysql_query($demand_user_group_queues) or die();
  while ($myrow_user_group_queues = mysql_fetch_array($result_user_group_queues)) {
    if ($myrow_user_group_queues['val'] != "") {
      $b = explode(",", $myrow_user_group_queues['val']);
      $c = count($b);
      for ($j = 0; $j < $c; $j++) {
        $user_queues[$i] = $b[$j];
        $i++;
      };
    };
  };
  
  return $user_queues;
};
function sql_allowed_queues($user_queues) {
  $i = count($user_queues);
  $queues_d = "";
  if ($i != 0) {
    for ($h = 0; $h < $i; $h++) {
      $repeat = 0;
      for ($g = 0; $g < $h; $g++) {
        if ($user_queues[$h] == $user_queues[$g]) $repeat = 1;
      };
      if (!$repeat) {
        $queues_d .= "'" . $user_queues[$h] . "'";
        $queues_d .= ",";
      };
    };
    $queues_d = substr($queues_d, 0, -1);
    $queues = " AND queue IN (" . $queues_d . ") ";
  }
  else $queues = "";
  return $queues;
};
function sql_allowed_queues_n($user_queues) {
  $i = count($user_queues);
  $queues_d = "";
  if ($i != 0) {
    for ($h = 0; $h < $i; $h++) {
      $repeat = 0;
      for ($g = 0; $g < $h; $g++) {
        if ($user_queues[$h] == $user_queues[$g]) $repeat = 1;
      };
      if (!$repeat) {
        $queues_d .= "'" . $user_queues[$h] . "'";
        $queues_d .= ",";
      };
    };
    $queues_d = substr($queues_d, 0, -1);
    $queues = " AND queuename IN (" . $queues_d . ") ";
  }
  else $queues = "";
  return $queues;
};
                                                                                                         //ALLOW EXTENS
function allow_extens() {
  $user_extens = array();
  $i = 0;
  $demand_user_defult_extens = "
		SELECT val
		FROM cfg_setting
		WHERE handle = 'cti.extens.allow.mask' ";
  $result_user_defult_extens = mysql_query($demand_user_defult_extens) or die();
  while ($myrow_user_defult_extens = mysql_fetch_array($result_user_defult_extens)) {
    if ($myrow_user_defult_extens['val'] != "") {
      $b = explode(",", $myrow_user_defult_extens['val']);
      $c = count($b);
      for ($j = 0; $j < $c; $j++) {
        $search = 'X';
        $replace = '_';
        $user_extens[$i] = str_replace($search, $replace, $b[$j]);
        $i++;
      };
    };
  };

  $demand_user_extens = "
        SELECT val
		FROM cfg_user_setting
		WHERE handle = 'cti.extens.allow.mask'
		AND acl_user_id = (SELECT acl_user_id FROM acl_auth_token WHERE token=".$_COOKIE['token']." AND ip = ".$_COOKIE['ip'].") ";
  $result_user_extens = mysql_query($demand_user_extens) or die();
  while ($myrow_user_extens = mysql_fetch_array($result_user_extens)) {
    if ($myrow_user_extens['val'] != "") {
      $b = explode(",", $myrow_user_extens['val']);
      $c = count($b);
      for ($j = 0; $j < $c; $j++) {
        $search = 'X';
        $replace = '_';
        $user_extens[$i] = str_replace($search, $replace, $b[$j]);
        $i++;
      };
    };
  };

  $demand_user_group_extens = "
		SELECT A.val
		FROM cfg_group_setting AS A
		LEFT JOIN acl_user_group_has_users AS B ON (A.acl_user_group_id = B.acl_user_group_id)
		LEFT JOIN acl_user AS C ON (B.acl_user_id = C.id)
		WHERE C.id = (SELECT acl_user_id FROM acl_auth_token WHERE token=".$_COOKIE['token']." AND ip = ".$_COOKIE['ip'].") 
		AND A.handle = 'cti.extens.allow.mask' ";
  $result_user_group_extens = mysql_query($demand_user_group_extens) or die();
  while ($myrow_user_group_extens = mysql_fetch_array($result_user_group_extens)) {
    if ($myrow_user_group_extens['val'] != "") {
      $b = explode(",", $myrow_user_group_extens['val']);
      $c = count($b);
      for ($j = 0; $j < $c; $j++) {
        $search = 'X';
        $replace = '_';
        $user_extens[$i] = str_replace($search, $replace, $b[$j]);
        $i++;
      };
    };
  };
  return $user_extens;
};

function sql_allow_extens($user_extens) {
  $i = count($user_extens);
  if($i!=0) {
    $extens = " AND (";
    for ($h = 0; $h < $i; $h++) {
      $repeat = 0;
      for ($g = 0; $g < $h; $g++) {
        if ($user_extens[$h] == $user_extens[$g]) $repeat = 1;
      };
      if (!$repeat) {
        $extens .= "src LIKE '" . $user_extens[$h] . "' OR dst LIKE '" . $user_extens[$h] . "' ";
        $extens .= " OR ";
      };
    };
    $extens = substr($extens, 0, -4);
    $extens .= ") ";
  }
  else $extens = "";
  return $extens;
};
                                                                                                        //DENY NUMBERS
function deny_numbers()
{
  $user_deny = array();
  $i = 0;
  $demand_user_defult_deny = "
		SELECT val
		FROM cfg_setting
		WHERE handle = 'cti.spy_deny' ";
  $result_user_defult_deny = mysql_query($demand_user_defult_deny) or die();
  while ($myrow_user_defult_deny = mysql_fetch_array($result_user_defult_deny)) {
    if ($myrow_user_defult_deny['val'] != "") {
      $b = explode(",", $myrow_user_defult_deny['val']);
      $c = count($b);
      for ($j = 0; $j < $c; $j++) {
        $user_deny[$i] = $b[$j];
        $i++;
      };
    };
  };

  $demand_user_deny = "
        SELECT val
		FROM cfg_user_setting
		WHERE handle = 'cti.spy_deny'
		AND acl_user_id = (SELECT acl_user_id FROM acl_auth_token WHERE token=".$_COOKIE['token']." AND ip = ".$_COOKIE['ip'].") ";
  $result_user_deny = mysql_query($demand_user_deny) or die();
  while ($myrow_user_deny = mysql_fetch_array($result_user_deny)) {
    if ($myrow_user_deny['val'] != "") {
      $b = explode(",", $myrow_user_deny['val']);
      $c = count($b);
      for ($j = 0; $j < $c; $j++) {
        $user_deny[$i] = $b[$j];
        $i++;
      };
    };
  };

  $demand_user_group_deny = "
		SELECT A.val
		FROM cfg_group_setting AS A
		LEFT JOIN acl_user_group_has_users AS B ON (A.acl_user_group_id = B.acl_user_group_id)
		LEFT JOIN acl_user AS C ON (B.acl_user_id = C.id)
		WHERE C.id = (SELECT acl_user_id FROM acl_auth_token WHERE token=".$_COOKIE['token']." AND ip = ".$_COOKIE['ip'].") 
		AND A.handle = 'cti.spy_deny' ";
  $result_user_group_deny = mysql_query($demand_user_group_deny) or die();
  while ($myrow_user_group_deny = mysql_fetch_array($result_user_group_deny)) {
    if ($myrow_user_group_deny['val'] != "") {
      $b = explode(",", $myrow_user_group_deny['val']);
      $c = count($b);
      for ($j = 0; $j < $c; $j++) {
        $user_deny[$i] = $b[$j];
        $i++;
      };
    };
  };
  return $user_deny;
}

function sql_deny_numbers($user_deny)
{
  $i = count($user_deny);
  if($i!=0) {
    $deny = " AND src NOT IN (".implode(",", $user_deny).") ";
  }
  else $deny = "";
  return $deny;
}

?>