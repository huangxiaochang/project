<?php 
defined('IN_AIJIACMS') or exit('Access Denied');
login();
require AJ_ROOT.'/module/'.$module.'/common.inc.php';
require AJ_ROOT.'/include/post.func.php';
require MD_ROOT.'/qiandao.class.php';
$do = new qiandao();

$head_name = '每日签到';
$head_title = $head_name.$AJ['seo_delimiter'].$head_title;

switch($action) {
	case 'add':
	     if($_groupid  == '1') {
	     exit('{"error":"当前会员组不允许签到"}');
	     }
		$today = str_replace('-','',timetodate($AJ_TIME, 0));
		$condition = "username='$_username'";
        $r = $db->get_one("SELECT itemid,signdays,addtime FROM {$AJ_PRE}qiandao WHERE username='$_username' ORDER BY itemid DESC LIMIT 1");
		if($r){
		    $qdday = str_replace('-','',timetodate($r['addtime'], 0));
			if($qdday==$today){
				exit('{"error":"已经签到过了亲"}');
				}
			if(($today-$qdday)=='1'){
				$signdays = $r['signdays'] + 1;
				$fee_add = $MOD['credit_qiandaos'];
				}
				else{
				$signdays = '1';
				$fee_add = $MOD['credit_qiandao'];
				}
			
			$fee_add = $fee_add;
		    $signdays = $signdays;
			
			$post['userid'] =  $_userid;
		    $post['username'] = $_username;
			$post['signdays'] =  $signdays;
			$post['feeadd'] =  $fee_add;
			$post['ostype'] =  $UAType;
			$post['areaid'] =  '';
			$post['note'] =  '';
			$do->add($post);
			credit_add($_username, $fee_add);
			credit_record($_username, $fee_add, 'system', '签到奖励',$UAType);
			exit('{"error":"ok","signday":"'.$signdays.'","signget":"'.$fee_add.'","thisday":"'.$thisday.'"}');
			}
			else{
			$fee_add = $MOD['credit_qiandao'];
			$db->query("INSERT INTO {$AJ_PRE}qiandao (userid,username,signdays,feeadd,areaid,ostype,note,ip,addtime) VALUES ('$_userid','$_username','1','$fee_add','','$UAType','','$AJ_IP','$AJ_TIME')");
			credit_add($_username, $fee_add);
			credit_record($_username, $fee_add, 'system', '签到奖励',$UAType);
			exit('{"error":"ok","signday":"1","signget":"'.$fee_add.'","thisday":"'.$thisday.'"}');
				}
	break;
	case 'list':
		$back_link = $MYURL;
		if($uid){
		$condition = " WHERE userid='$_userid' ";
		}
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$AJ_PRE}qiandao");
		$items = $r['num'];
		$pages = mobile_pages($items, $page, $pagesize);
		$lists = array();
		if($items) {
			$result = $db->query("SELECT * FROM {$AJ_PRE}qiandao {$condition} ORDER BY itemid DESC LIMIT $offset,$pagesize");
			while($r = $db->fetch_array($result)) {
				$r['adddate'] = timetodate($r['addtime'], 5);
				$r['username'] = hideStar($r['username']);
				$lists[] = $r;
			}
		}
		exit(json_encode($lists));
		//var_dump($result);	
	break;
	case 'delete':
		$itemid or exit('ko');
		$do->itemid = $itemid;
		$r = $do->get_one();
		if(!$r || $r['username'] != $_username) exit('ko');
		$do->delete($itemid);
		exit('ok');
	break;
	default:
		$back_link = $MYURL;
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$AJ_PRE}qiandao WHERE addtime>$AJ_TIME-86400");
		$items = $r['num'];
		//echo $_username;
		$condition = "username='$_username'";
		$r = $db->get_one("SELECT * FROM {$AJ_PRE}qiandao WHERE $condition ORDER BY itemid DESC LIMIT 1");
		$today = str_replace('-','',timetodate($AJ_TIME, 0));
		$qdday = str_replace('-','',timetodate($r['addtime'], 0));
		if($r && $qdday==$today){
		$signed = 1;
		$todayget = $r['feeadd'];
		}
	break;
}
include template('qiandao', $module);
?>