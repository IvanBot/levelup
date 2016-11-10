<?php
/**
 * Created by PhpStorm.
 * User: m.zuev
 * Date: 11/8/2016
 * Time: 4:04 PM
 */

class User() {

    require_once("../../src/db.php");

    $query = mysql_query("SELECT name, userpassword FROM users WHERE name='" . $_POST['login'] . "' and userpassword=" . $_POST['passw']);
    $current_count = mysql_fetch_array($query);

    if ($current_count['name'])
    {
        // send role
        $status = 'SUCSSESS!';
        echo array('1', $status);
    }
    else {
        echo 'ERROR!';
    }
//}
?>