<?php
/*
	 
	This is NOT a freeware, use is subject to license.txt
*/
require 'myuser.php';
require AJ_ROOT.'/include/post.func.php';
require MD_ROOT.'/qiandao.class.php';
$do = new qiandao();
$head_name = $L['qiandao_title'];
$head_title = $head_name.$AJ['seo_delimiter'].$head_title;

switch($action) {
	case 'add':
	     if($_groupid  == '1') {
		 $contqd = utftext($M['dont_qiandao']);
	     exit('{"status":1,"content":"'.$contqd.'"}');//$MOD['credit_user']
	     }
		$qd_dqy = isset($MOD['credit_qiandao'])?$MOD['credit_qiandao']:$MG['qd_dqy'];
		$qd_dqys = isset($MOD['credit_qiandaos'])?$MOD['credit_qiandaos']:$MG['qd_dqys'];
		$today = str_replace('-','',timetodate($AJ_TIME, 0));
		$condition = "username='$_username'";
        $r = $db->get_one("SELECT itemid,signdays,addtime FROM {$AJ_PRE}qiandao WHERE username='$_username' ORDER BY itemid DESC LIMIT 1");
		if($r){
		    $qdday = str_replace('-','',timetodate($r['addtime'], 0));
			if($qdday==$today){
				$signed = $M['qiandao_signed'];
				exit('{"status":1,"content":"'.$signed.'"}');
				}
			if(($today-$qdday)=='1'){
				$signdays = $r['signdays'] + 1;
				$fee_add = $qd_dqys;
				}
				else{
				$signdays = '1';
				$fee_add = $qd_dqy;
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
			credit_record($_username, $fee_add, 'system', $M['qiandao_reward'],$UAType);
			exit('{"status":0,"signday":"'.$signdays.'","signget":"'.$fee_add.'"}');
			}
			else{
			$fee_add = $qd_dqy;
			$db->query("INSERT INTO {$AJ_PRE}qiandao (userid,username,signdays,feeadd,areaid,ostype,note,ip,addtime) VALUES ('$_userid','$_username','1','$fee_add','','$UAType','','$AJ_IP','$AJ_TIME')");
			credit_add($_username, $fee_add);
			credit_record($_username, $fee_add, 'system', $M['qiandao_reward'],$UAType);
			exit('{"status":0,"signday":"1","signget":"'.$fee_add.'"}');
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
				$r['username'] = hideStars($r['username']);
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
		$head_name = $head_title = $M['mobile_qiandao'];
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$AJ_PRE}qiandao WHERE addtime>$AJ_TIME-86400");
		$items = $r['num'];
		//echo $_username;
		$condition = "username='$_username'";
		$r = $db->get_one("SELECT * FROM {$AJ_PRE}qiandao WHERE $condition ORDER BY itemid DESC LIMIT 1");
		$today = str_replace('-','',timetodate($AJ_TIME, 0));
		$qdday = str_replace('-','',timetodate($r['addtime'], 0));
		if($r && $qdday==$today){
		$signed = 1;
		}
	break;
}
$foot = '';
include template('mqiandao', 'mobile');
if(AJ_CHARSET != 'UTF-8') toutf8();
?>