<?
require_once('model.php');
require_once('functions.php');
$res = routerData::inspect();
if($_GET['machine_lang']=='js'){
	echo $res;
}
class routerData {
	public static function inspect(){
		$token = "";
		$ip = "";
		if(isset($_COOKIE['token']) && isset($_COOKIE['ip'])) {
			$token = $_COOKIE['token'];
			$ip = $_COOKIE['ip'];
		}
		else if(isset($_GET['token'])) {
			$token = $_GET['token'];
			$ip = $_SERVER['REMOTE_ADDR'];
		};
		if($token!="" && $ip!="") {
			$check_token_array = checkToken($token,$ip);
			$user_role = checkTokenRole($token,$ip);
			if(!empty($check_token_array)) {
				if(isset($_GET['token'])) {
					$res = '{"result":0, "message":"User auth"}';
					$_COOKIE['auth']=1;
					//exit();
				}
				elseif(isset($_GET['user_role']) && empty($user_role)) {
					$res = '{"result":1,"message":"Access denied"}'; 
					$_COOKIE['auth']=0;
				}
				elseif(isset($_GET['user_role']) && !empty($user_role)) {
					$res = '{"result":0,"message":"OK"}'; 
					$_COOKIE['auth']=1;
				}
				elseif(!empty($user_role)) {
					/*if(isset($_GET['token'])) $res = '{"result":0, "message":"User auth"}';
					else*/ if(isset($_GET['billing_cdr_report'])) render_report("billing/cdr_report", $_GET);
					elseif(isset($_GET['billing_daily_report'])) render_report("billing/daily_report", $_GET);
					elseif(isset($_GET['billing_aggregate'])) render_report("billing/aggregate", $_GET);
					elseif(isset($_GET['billing_month_traffic'])) render_report("billing/month_traffic", $_GET);
					elseif(isset($_GET['billing_hourly_load'])) render_report("billing/hourly_load", $_GET);
					elseif(isset($_GET['billing_compare_calls'])) render_report("billing/compare_calls", $_GET);
					elseif(isset($_GET['billing_analysis_outgoing_calls'])) render_report("billing/analysis_outgoing_calls", $_GET);
					elseif(isset($_GET['call_recording'])) render_report("call_recording", $_GET);
					elseif(isset($_GET['reports_contact_cent_cdr_report'])) render_report("reports_contact_cent/cdr_report", $_GET);
					elseif(isset($_GET['reports_contact_cent_grouped_reports'])) render_report("reports_contact_cent/grouped_reports", $_GET);
					elseif(isset($_GET['reports_contact_cent_interval_reports'])) render_report("reports_contact_cent/interval_reports", $_GET);
					elseif(isset($_GET['reports_contact_cent_lost_calls'])) render_report("reports_contact_cent/lost_calls", $_GET);
					elseif(isset($_GET['reports_contact_cent_operators_work_report'])) render_report("reports_contact_cent/operators_work_report", $_GET);
					elseif(isset($_GET['record_contact_center'])) render_report("record_contact_center", $_GET);
				} else {
					$res = "Access denied";
					$_COOKIE['auth']=0;
				}
			} else {
				$res = '{"result":1, "message":"User no auth"}';
				$_COOKIE['auth']=0;
			}
		}
		else {
			if(isset($_GET['account'])) {
				render_report("account", $_POST);
			}
			else {
				$res = '{"result":1,"message":"No cookies: token and ip"}';
				$_COOKIE['auth']=0;
			} //No cookies: token and ip
		}
		return $res;
	}
}
?>