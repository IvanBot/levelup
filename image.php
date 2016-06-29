<?php
//function image(){
$url = "http://192.168.40.9/cgi-bin/images_cgi?channel=0&user=admin&pwd=admin&".rand();
//$filename = sys_get_temp_dir()."\\file.jpg";
$filename = "/tmp/file.jpg";
$file2name = "/tmp/file2.jpg";
$file3name = "/tmp/file3.lock";

function getfromcamera() {
    global $filename,$file2name,$file3name,$url;
    file_put_contents($file3name,"");
    copy($url,$file2name);
    copy($file2name,$filename);
    unlink($file3name);
    unlink($file2name);
}

if (file_exists($filename)) {
    $file_time = date(strtotime("now")) - filemtime($filename);
    if ($file_time>1) {
        if (!file_exists($file3name)) {
            getfromcamera();
        }
    }
} else {
    getfromcamera();
};

stream($filename, 'image/jpeg');

//};
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
    header("Content-Transfer-Encoding: binary");
    header('X-Pad: avoid browser bug');
    header('Cache-Control: no-cache');

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