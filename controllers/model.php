<?
require_once('db.php');

function checkToken($token,$ip) {
    $demand = "	SELECT *
				FROM acl_auth_token
				WHERE token=".$token." AND ip = ".$ip." ";
    $result = mysql_query($demand) or die();
    $myrow = mysql_fetch_array($result);
    if(!empty($myrow)) mysql_query("UPDATE acl_auth_token SET updated='".date("Y-m-d h:i:s")."' WHERE id=".$myrow['id']) or die();
    return $myrow;
}

function checkTokenRole($token,$ip) {
    $demand = "
      SELECT
		A.id AS id
	  FROM acl_user AS A
	  LEFT JOIN acl_user_has_rules AS B ON (A.id = B.acl_user_id)
	  LEFT JOIN acl_user_group_has_users AS D ON (A.id = D.acl_user_id)
	  LEFT JOIN acl_user_group_has_rules AS E ON (D.acl_user_group_id = E.acl_user_group_id)
	  LEFT JOIN acl_rule AS C ON ((C.id = B.acl_rule_id) OR (C.id = E.acl_rule_id))
	  LEFT JOIN acl_auth_token AS F ON (F.acl_user_id = A.id)
	  WHERE F.token = ".$token." AND F.ip = ".$ip."
	  AND C.handle = 'phc.reports' ";
    $result = mysql_query($demand) or die();
    $myrow = mysql_fetch_array($result);
    return $myrow;
}

function login($login,$password) {
    $demand = "
      SELECT id, name, fullname
	  FROM acl_user
	  WHERE name='".$login."' 
	  AND password = sha1(md5(concat(md5(md5('".$password."')), ';Ej>]sjkip')))";
    $result = mysql_query($demand) or die();
    if($myrow = mysql_fetch_array($result)) {
        $token_str = sha1(sprintf("%s%d", $myrow['name'], round(microtime(1) * 1000)));
        $token = substr($token_str, 0, 32);
        $ip = $_SERVER['REMOTE_ADDR'];

        $demand_token = " INSERT INTO acl_auth_token (acl_user_id,token,pcode,version,ip,hwid,issued,updated,expire) VALUES ('" . $myrow['id'] . "','" . $token . "','web','1.0.0.1','" . $ip . "','','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s",time() + 86400)."') ";
        $result_token = mysql_query($demand_token) or die();
        if ($result_token) $array = array(
            'result' => 0,
            'message' => "OK",
            'token' => $token,
            'ip' => $ip,
            'fullname' => $myrow['fullname']
        );
        else $array = array(
            'result' => 1,
            'message' => "Token wasn't saved"
        );
    } else {
        $array = array(
            'result' => 2,
            'message' => "Incorrect username or password"
        );
    };
    return $array;
}

function stream($file, $content_type = 'application/octet-stream') {
    @error_reporting(0);

    // Make sure the files exists, otherwise we are wasting our time
    if (!file_exists($file)) {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    // Get file size
    $filesize = sprintf("%u", filesize($file));

    // Handle 'Range' header
    if(isset($_SERVER['HTTP_RANGE'])){
        $range = $_SERVER['HTTP_RANGE'];
    }elseif($apache = apache_request_headers()){
        $headers = array();
        foreach ($apache as $header => $val){
            $headers[strtolower($header)] = $val;
        }
        if(isset($headers['range'])){
            $range = $headers['range'];
        }
        else $range = FALSE;
    } else $range = FALSE;

    //Is range
    if($range){
        $partial = true;
        list($param, $range) = explode('=',$range);
        // Bad request - range unit is not 'bytes'
        if(strtolower(trim($param)) != 'bytes'){
            header("HTTP/1.1 400 Invalid Request");
            exit;
        }
        // Get range values
        $range = explode(',',$range);
        $range = explode('-',$range[0]);
        // Deal with range values
        if ($range[0] === ''){
            $end = $filesize - 1;
            $start = $end - intval($range[0]);
        } else if ($range[1] === '') {
            $start = intval($range[0]);
            $end = $filesize - 1;
        }else{
            // Both numbers present, return specific range
            $start = intval($range[0]);
            $end = intval($range[1]);
            if ($end >= $filesize || (!$start && (!$end || $end == ($filesize - 1)))) $partial = false; // Invalid range/whole file specified, return whole file
        }
        $length = $end - $start + 1;
    }
    // No range requested
    else $partial = false;

    // Send standard headers
    header("Content-Type: $content_type");
    header("Content-Length: $filesize");
    header('Accept-Ranges: bytes');

    // send extra headers for range handling...
    if ($partial) {
        header('HTTP/1.1 206 Partial Content');
        header("Content-Range: bytes $start-$end/$filesize");
        if (!$fp = fopen($file, 'rb')) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
        if ($start) fseek($fp,$start);
        while($length){
            set_time_limit(0);
            $read = ($length > 8192) ? 8192 : $length;
            $length -= $read;
            print(fread($fp,$read));
        }
        fclose($fp);
    }
    //just send the whole file
    else readfile($file);
    exit;
}
?>