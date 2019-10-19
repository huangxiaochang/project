<?php 
require 'myuser.php';
require MD_ROOT.'/member.class.php';
require AJ_ROOT.'/include/post.func.php';
$do = new member;
$do->userid = $_userid;
$do->username = $_username;
$user = $do->get_one();
$MFD = cache_read('fields-member.php');
$CFD = cache_read('fields-company.php');
isset($post_fields) or $post_fields = array();
if($MFD || $CFD) require AJ_ROOT.'/include/fields.func.php';
$group_editor = $MG['editor'];
in_array($group_editor, array('Default', 'Aijiacms', 'Simple', 'Basic')) or $group_editor = 'Aijiacms';
$tab = isset($tab) ? intval($tab) : 0;
$is_company = $_groupid > 5 || ($_groupid == 4 && $user['regid'] > 5);
$_E = ($MOD['edit_check'] && $user['edittime'] > 0 && $is_company) ? explode(',', $MOD['edit_check']) : array();
if(in_array('capital', $_E)) $_E[] = 'regunit';
$content_table = content_table(4, $_userid, is_file(AJ_CACHE.'/4.part'), $AJ_PRE.'company_data');
$t = $db->get_one("SELECT * FROM {$content_table} WHERE userid=$_userid");
if($t) {
	$user['content'] = $content = $t['content'];
} else {
	$user['content'] = $content = '';
	$db->query("REPLACE INTO {$content_table} (userid,content) VALUES ('$_userid','')");
}
$head_name= $M['edit_mobile_info'];
if(isset($_POST['ok'])) {
			foreach($post as $k=>$v) {
				$post[$k] = convert(input_trim($v), 'UTF-8', AJ_CHARSET);
			}
	if($post['password'] && $user['password'] != dpassword($post['oldpassword'], $user['passsalt'])) dtexit($L['error_password'],'goback');
	if($post['payword'] && $user['payword'] != dpassword($post['oldpayword'], $user['paysalt'])) dtexit($L['error_payword'],'goback');
	$post['groupid'] = $user['groupid'];
	$post['email'] = $user['email'];
	$post['passport'] = $user['passport'];
	$post['company'] = $user['company'];
	$post['domain'] = $user['domain'];
	$post['icp'] = $user['icp'];
	$post['skin'] = $user['skin'];
	$post['template'] = $user['template'];
	$post['edittime'] = $AJ_TIME;
	$post['bank'] = $user['bank'];
	$post['banktype'] = $user['banktype'];
	$post['branch'] = $user['branch'];
	$post['account'] = $user['account'];
	$post['validated'] = $user['validated'];
	$post['validator'] = $user['validator'];
	$post['validtime'] = $user['validtime'];
	$post['vemail'] = $user['vemail'];
	$post['vmobile'] = $user['vmobile'];
	$post['vtruename'] = $user['vtruename'];
	$post['vbank'] = $user['vbank'];
	$post['vcompany'] = $user['vcompany'];
	$post['vtrade'] = $user['vtrade'];
	$post['trade'] = $user['trade'];
	$post['support'] = $user['support'];
	$post['inviter'] = $user['inviter'];
	if($post['vmobile']) $post['mobile'] = $user['mobile'];
	if($post['vtruename']) $post['truename'] = $user['truename'];
	
	if($user['edittime']){
	  $catid = $post['catid'];
	  if($post['catid']) {
		$icatid = $catids = explode(',', substr($catid, 1, -1));
		$cids = '';
		foreach($icatid as $catid) {
					$cids .= $catid.',';
			}
			
		$cids = array_unique(explode(',', substr(str_replace(',0,', ',', ','.$cids), 1, -1)));
		$post['catid'] = $icatid = ','.implode(',', $cids).',';
			
		}
	  }else{
		$post['catid'] = ','.$post['catid'].',';
	}
	//dtexit($post['content']);
	$post = dstripslashes($post);
	$post_check = array();
	if($_E) {
		if(in_array('thumb', $_E) || in_array('content', $_E)) clear_upload($post['thumb'].$post['content'], $_userid);
		foreach($_E as $k) {
			if($post[$k] != $user[$k]) {
				$post_check[$k] = $post[$k];
				$post[$k] = $user[$k];
				
			}
		}
	}
	$post = daddslashes($post);
	$post_check = daddslashes($post_check);
	if($MFD) mfields_check($post_fields, $MFD);
	if($CFD) mfields_check($post_fields, $CFD);

	if($do->edit($post)) {
		if($MFD) fields_update($post_fields, $AJ_PRE.'member', $do->userid, 'userid', $MFD);
		if($CFD) fields_update($post_fields, $AJ_PRE.'company', $do->userid, 'userid', $CFD);
		if($user['edittime'] == 0 && $user['inviter'] && $MOD['credit_user']) {
			$inviter = $user['inviter'];
			$r = $db->get_one("SELECT itemid FROM {$AJ_PRE}finance_credit WHERE note='$_username' AND username='$inviter'");
			if(!$r) {
				credit_add($inviter, $MOD['credit_user']);
				credit_record($inviter, $MOD['credit_user'], 'system', $L['edit_invite'], $_username);
			}
			
			$p = $db->get_one("SELECT inviter FROM {$AJ_PRE}member WHERE username='$inviter'");
			if($p) {
				credit_add($p['inviter'], $MOD['credit_ip']);
				credit_record($p['inviter'], $MOD['credit_ip'], 'system', $L['edit_invite'], $inviter);
			}
			set_cookie('inviter', '');
		}
		if($user['edittime'] == 0 && $MOD['credit_edit']) {
			credit_add($_username, $MOD['credit_edit']);
			credit_record($_username, $MOD['credit_edit'], 'system', $L['edit_profile'], $AJ_IP);
		}
		dtexit('ok');
		dtexit($L['edit_msg_success'], '?tab='.$tab);
	} else {
		dtexit($do->errmsg);
	}
} else {
	$COM_TYPE = explode('|', $MOD['com_type']);
	$COM_SIZE = explode('|', $MOD['com_size']);
	$COM_MODE = explode('|', $MOD['com_mode']);
	$MONEY_UNIT = explode('|', $MOD['money_unit']);
	$head_title = $L['edit_title'];
	$_U = $_E ? $do->check_get() : array();
	if($_U) {
		foreach($_U as $k=>$v) {
			$user[$k] = $v;
		}
	}
	extract($user);
	$mode_check = dcheckbox($COM_MODE, 'post[mode][]', $mode, 'onclick="check_mode(this, '.$MOD['mode_max'].');"', 0);
	$content_table = content_table(4, $userid, is_file(AJ_CACHE.'/4.part'), $AJ_PRE.'company_data');
	$d = $db->get_one("SELECT content FROM {$content_table} WHERE userid=$userid");
	$content = $d['content'];
	$cates = $catid ? explode(',', substr($catid, 1, -1)) : array();
	$tab = isset($tab) ? intval($tab) : -1;
	if($tab == 2 && $_groupid < 6) $tab = 0;
	$foot = '';
	include template('medit', 'mobile');
    if(AJ_CHARSET != 'UTF-8') toutf8();
}
?>