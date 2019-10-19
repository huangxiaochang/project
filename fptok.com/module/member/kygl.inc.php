<?php 
defined('IN_AIJIACMS') or exit('Access Denied');
login();
require AJ_ROOT.'/module/'.$module.'/common.inc.php';
$MG['homepage'] && $MG['honor_limit'] > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require AJ_ROOT.'/include/post.func.php';
require MD_ROOT.'/kygl.class.php';
$do = new kygl();
switch($action) {
	case 'add':
		// if($MG['honor_limit']) {
		// 	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$AJ_PRE}kygl WHERE username='$_username' AND status>0");
		// 	if($r['num'] >= $MG['honor_limit']) dalert(lang($L['limit_add'], array($MG['honor_limit'], $r['num'])), 'goback');
		// }
		if($submit) {
			if($do->pass($post)) {
				$post['username'] = $_username;
				// $post['addtime'] = 0;
				// $need_check =  $MOD['credit_check'] == 2 ? $MG['check'] : $MOD['credit_check'];
				// $post['status'] = get_status(3, $need_check);
				// var_dump($post);die;
				$do->add($post);
				// dmsg($L['op_add_success'], '?status='.$post['status']);
				dmsg($L['op_add_success']);
			} else {
				message($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				$$v = '';
			}
			$content = '';	
			$today = timetodate($AJ_TIME, 'Ymd');
			$head_title = '添加客源';
		}
	break;
	case 'edit':
		$userid or message();
		$do->userid = $userid;
		$r = $do->get_one();
		if(!$r || $r['username'] != $_username) message();
		if(!$r) message();
		$r['fangling'] = explode(',',$r['fangling']);
		$r['mianji'] = explode(',',$r['mianji']);
		$r['jiage'] = explode(',',$r['jiage']);
		$r['louceng'] = explode(',',$r['louceng']);
		$r['fwsb'] = explode(',',$r['fwsb']);
		$r['zbhj'] = explode(',',$r['zbhj']);
		if($submit) {
			if($do->pass($post)) {
				$post['username'] = $_username;
				// $need_check =  $MOD['credit_check'] == 2 ? $MG['check'] : $MOD['credit_check'];
				// $post['status'] = get_status($r['status'], $need_check);
				$do->edit($post);
				dmsg($L['op_edit_success']);
			} else {
				message($do->errmsg);
			}
		} else {
			extract($r);
			// $addtime = timetodate($addtime);
			// $fromtime = timetodate($fromtime, 3);
			// $today = timetodate($totime, 'Ymd');
			// $totime = $totime ? timetodate($totime, 3) : '';
			$head_title = '编辑客源';
		}
		// var_dump($r);die;
	break;
	case 'delete':
		$userid or message($L['honor_msg_choose']);
		$userids = is_array($userid) ? $userid : array($userid);
		foreach($userids as $userid) {
			$do->userid = $userid;
			$item = $do->get_one();
			if(!$item || $item['username'] != $_username) message();

			$do->delete($userid);
		}
		dmsg($L['op_del_success'], $forward);
	break;
	default:
		$status = isset($status) ? intval($status) : 3;
		in_array($status, array(1, 2, 3, 4)) or $status = 3;
		// if($status == 3) $do->expire("AND username='$_username'");
		$condition = "username='$_username'";
		// $condition .= " AND status=$status";
		if($kw) $condition .= " AND name LIKE '%$kw%'";

		$lists = $do->get_list($condition);
		$head_title = '客源管理';
	break;
}
$nums = array();
$limit_used = 0;
for($i = 1; $i < 5; $i++) {
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$AJ_PRE}kygl WHERE username='$_username'");
	$nums[$i] = $r['num'];
	$limit_used += $r['num'];
}
$limit_free = $MG['honor_limit'] && $MG['honor_limit'] > $limit_used ? $MG['honor_limit'] - $limit_used : 0;
include template('kygl', $module);

function inarray($num,$arr) {
		if (in_array($num, $arr))
		{
		  return 'checked';
		}
	}
?>