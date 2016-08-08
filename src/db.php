<?

/*$fcfg = fopen("/etc/erpico.conf", "r");
while ($s = fgets($fcfg)) {
 list($key,$value) = explode("=", $s, 2);
 $key = trim($key);
 $value = trim($value, " \"\t\n\r\0\x0B");
 $config[$key] = $value;
};
fclose($fcfg);*/

/**/

$config['db_host'] = "127.0.0.1";
$config['db_user'] = "root";
$config['db_password'] = "";
$config['db_schema'] = "levelup76";


$db = mysql_connect($config['db_host'],$config['db_user'],$config['db_password']);
if (!$db) die(mysql_error());
mysql_select_db($config['db_schema'],$db);
mysql_query('set names utf8');
?>