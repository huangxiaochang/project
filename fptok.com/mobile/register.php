<?php
/*
	[Aijiacms System] Copyright (c) 2011-2015 www.aijiacms.com
*/
$moduleid = 2;
require 'common.inc.php';
require AJ_ROOT.'/module/'.$module.'/common.inc.php';
!$_userid or dheader('my.php?reload='.$AJ_TIME);
$MOD['enable_register'] or mobile_msg($L['register_msg_close']);
if($MOD['iptimeout']) {
	$timeout = $AJ_TIME - $MOD['iptimeout']*3600;
	$r = $db->get_one("SELECT userid FROM {$AJ_PRE}member WHERE regip='$AJ_IP' AND regtime>'$timeout'");
	if($r) mobile_msg(lang($L['register_msg_ip'], array($MOD['iptimeout'])));
}
require AJ_ROOT.'/include/post.func.php';
$session = new dsession();
$GROUP = cache_read('group.php');
if($MOD['question_register']) $MOD['captcha_register'] = 1;
if(!$AJ['sms']) {
	$MOD['welcome_sms'] = 0;
	$MOD['mobilecode_register'] = 0;
}
if($AJ['mail_type'] == 'close') {
	if($MOD['checkuser'] == 2) $MOD['checkuser'] = 1;
	$MOD['welcome_email'] = 0;
	$MOD['emailcode_register'] = 0;
}
$verify_type = '';
$need_check = 0;
if($MOD['mobilecode_register']) {
	$verify_type = 'mobile';
	$need_check = 1;
} else if($MOD['emailcode_register'] || $MOD['checkuser'] == 2) {
	$verify_type = 'email';
	$need_check = 1;
} else if($MOD['checkuser'] == 1) {
	$need_check = 1;
}
switch($action) {
	case 'detail':
		(isset($GROUP[$itemid]) && $GROUP[$itemid]['vip'] == 0 && $GROUP[$itemid]['reg'] == 1) or mobile_msg($L['register_group'], 'register.php?reload='.$AJ_TIME);
		$back_link = '?reload='.$AJ_TIME;
		$head_name = $GROUP[$itemid]['groupname'];
	break;
	case 'agreement':
		ob_start();
		include template('agreement', $module);
		$data = ob_get_contents();
		ob_clean();
		$t1 = explode('body>', $data);
		$t2 = trim(substr($t1[1], 0, -2));
		echo $t2;
		if(AJ_CHARSET != 'UTF-8') toutf8();
		exit;
	break;
	case 'success':
		(isset($_SESSION['m_name']) && check_name($_SESSION['m_name'])) or mobile_msg($L['msg_error']);
		if($verify_type == 'mobile') {
			$head_name = $L['register_mobile_title'];
		} else if($verify_type == 'email') {
			$head_name = $L['register_email_title'];
		} else {
			$username = $_SESSION['m_name'];
			unset($_SESSION['m_name']);
			if($need_check) {
				mobile_msg($L['register_check'], 'index.php?reload='.$AJ_TIME);
			} else {
				require AJ_ROOT.'/module/member/member.class.php';
				$do = new member;
				$user = $do->login($username, '', 0, true);
				if($user) {
					$post = $user;
					$post['password'] = $_SESSION['m_pass'];
					if($MOD['welcome_sms'] && is_mobile($post['mobile'])) {
						$message = lang('sms->wel_reg', array($post['truename'], $AJ['sitename'], $post['username'], $post['password']));
						$message = strip_sms($message);
						send_sms($post['mobile'], $message);
					}
					if($MOD['welcome_message'] || $MOD['welcome_email']) {
						$title = $L['register_msg_welcome'];
						$content = ob_template('welcome', 'mail');
						if($MOD['welcome_message']) send_message($username, $title, $content);
						if($MOD['welcome_email'] && $AJ['mail_type'] != 'close') send_mail($post['email'], $title, $content);
					}
					unset($_SESSION['m_name']);
					unset($_SESSION['m_pass']);
				}
				mobile_msg($L['register_success'], 'my.php?reload='.$AJ_TIME);
			}
		}
		$back_link = 'javascript:Dback(\'my.php\');';
	break;
	case 'send':
		(isset($_SESSION['m_name']) && check_name($_SESSION['m_name'])) or exit('ko');
		$username = $_SESSION['m_name'];
		if($verify_type == 'mobile') {
			$t = $db->get_one("SELECT mobile,groupid FROM {$AJ_PRE}member WHERE username='$username'");
			$t or exit('ko');
			$t['groupid'] == 4 or exit('ko');
			is_mobile($t['mobile']) or exit('ko');
			$mobile = $t['mobile'];
			isset($_SESSION['mobile_send']) or $_SESSION['mobile_send'] = 0;
			isset($_SESSION['mobile_time']) or $_SESSION['mobile_time'] = 0;
			if($_SESSION['mobile_time'] && $AJ_TIME - $_SESSION['mobile_time'] < 60) exit('ko');
			if($_SESSION['mobile_send'] > 4) exit('max');
			if(max_sms($mobile)) exit('max');
			$mobilecode = random(6, '0123456789');
			$_SESSION['mobile'] = $mobile;
			$_SESSION['mobile_code'] = md5($mobile.'|'.$mobilecode.'|RM');
			$_SESSION['mobile_time'] = $AJ_TIME;
			$_SESSION['mobile_send'] = $_SESSION['mobile_send'] + 1;
			$content = lang('sms->sms_code', array($mobilecode, $MOD['auth_days']*10)).$AJ['sms_sign'];
			//send_sms($mobile, $content);
			include AJ_ROOT.'/api/aliyun/SmsDemo.php';
			alisms($mobile,$mobilecode);
			exit('ok');
		} else if($verify_type == 'email') {
			$t = $db->get_one("SELECT email,groupid FROM {$AJ_PRE}member WHERE username='$username'");
			$t or exit('ko');
			$t['groupid'] == 4 or exit('ko');
			is_email($t['email']) or exit('ko');
			$email = $t['email'];
			isset($_SESSION['email_send']) or $_SESSION['email_send'] = 0;
			isset($_SESSION['email_time']) or $_SESSION['email_time'] = 0;
			if($_SESSION['email_time'] && $AJ_TIME - $_SESSION['email_time'] < 60) exit('ko'.($AJ_TIME - $_SESSION['email_time']));
			if($_SESSION['email_send'] > 9) exit('max');
			$emailcode = random(6, '0123456789');
			$_SESSION['email'] = $email;
			$_SESSION['email_code'] = md5($email.'|'.$emailcode.'|RE');
			$_SESSION['email_time'] = $AJ_TIME;
			$_SESSION['email_send'] = $_SESSION['email_send'] + 1;
			$title = $L['register_msg_emailcode'];
			$content = ob_template('emailcode', 'mail');
			send_mail($email, $title, stripslashes($content));
			exit('ok');
		}
		exit('ko');
	break;
	case 'verify':
		(isset($_SESSION['m_name']) && check_name($_SESSION['m_name'])) or exit('ko');
		$username = $_SESSION['m_name'];
		isset($code) or $code = '';
		preg_match("/^[0-9]{6}$/", $code) or exit('ko');
		$t = $db->get_one("SELECT email,mobile,groupid,regid FROM {$AJ_PRE}member WHERE username='$username'");
		$t or exit('ko');
		$t['groupid'] == 4 or exit('ko');
		if($verify_type == 'mobile') {
			$_SESSION['mobile_code'] == md5($t['mobile'].'|'.$code.'|RM') or exit('ko');
		} else if($verify_type == 'email') {
			$_SESSION['email_code'] == md5($t['email'].'|'.$code.'|RE') or exit('ko');
		}
		$db->query("UPDATE {$AJ_PRE}member SET groupid='$t[regid]',".($verify_type == 'mobile' ? 'vmobile' : 'vemail')."=1 WHERE username='$username'");
		$db->query("UPDATE {$AJ_PRE}company SET groupid='$t[regid]' WHERE username='$username'");
		require AJ_ROOT.'/module/member/member.class.php';
		$do = new member;
		$user = $do->login($username, '', 0, true);
		if($user) {
			$post = $user;
			$post['password'] = $_SESSION['m_pass'];
			if($MOD['welcome_sms'] && is_mobile($post['mobile'])) {
				$message = lang('sms->wel_reg', array($post['truename'], $AJ['sitename'], $post['username'], $post['password']));
				$message = strip_sms($message);
				send_sms($post['mobile'], $message);
			}
			if($MOD['welcome_message'] || $MOD['welcome_email']) {
				$title = $L['register_msg_welcome'];
				$content = ob_template('welcome', 'mail');
				if($MOD['welcome_message']) send_message($username, $title, $content);
				if($MOD['welcome_email'] && $AJ['mail_type'] != 'close') send_mail($post['email'], $title, $content);
			}
			session_destroy();
		}
		exit('ok');
	break;
	case 'post':
		if($MOD['captcha_register']) {
			$captcha = isset($captcha) ? convert(input_trim($captcha), 'UTF-8', AJ_CHARSET) : '';
			$msg = captcha($captcha, $MOD['captcha_register'], true);
			if($msg) exit('captcha');
		}
		$post = array();
		$post['regid'] = isset($regid) ? intval($regid) : 0;
		$post['username'] = isset($username) ? input_trim($username) : '';
		$post['password'] = isset($password) ? input_trim($password) : '';
		$post['email'] = isset($email) ? input_trim($email) : '';
		$post['mobile'] = isset($mobile) ? input_trim($mobile) : '';
		$post['truename'] = isset($truename) ? convert(input_trim($truename), 'UTF-8', AJ_CHARSET) : '';
		$post['company'] = isset($company) ? convert(input_trim($company), 'UTF-8', AJ_CHARSET) : '';
		$post['passport'] = $post['username'];
		$post['cpassword'] = $post['password'];
		$RG = array();
		foreach($GROUP as $k=>$v) {
			if($k > 4 && $v['vip'] == 0) $RG[] = $k;
		}
		if(!in_array($post['regid'], $RG)) exit('group');
		if($MOD['passport'] == 'uc') {
			$passport = convert($post['passport'], AJ_CHARSET, $MOD['uc_charset']);
			require AJ_ROOT.'/api/uc.inc.php';
			list($uid, $rt_username, $rt_password, $rt_email) = uc_user_login($passport, $post['password']);
			if($uid == -2) exit('passport');
		}
		if($post['regid'] == 5) $post['company'] = $post['truename'];
		$post['groupid'] = $need_check ? 4 : $post['regid'];
		$post['content'] = $post['introduce'] = $post['thumb'] = $post['banner'] = $post['catid'] = $post['catids'] = '';
		$post['edittime'] = 0;
		$inviter = get_cookie('inviter');
		$post['inviter'] = $inviter ? decrypt($inviter, AJ_KEY.'INVITER') : '';
		check_name($post['inviter']) or $post['inviter'] = '';
		require AJ_ROOT.'/module/member/member.class.php';
		$do = new member;
		if($do->add($post)) {
			$note = timetodate($AJ_TIME, 5).'|system|'.$L['register_note'];
			$db->query("UPDATE {$AJ_PRE}member SET note='$note' WHERE userid='$do->userid'");
			$_SESSION['m_name'] = $post['username'];
			$_SESSION['m_pass'] = $post['password'];
			exit('ok');
		} else {
			echo $do->errmsg;
			if(AJ_CHARSET != 'UTF-8') toutf8();
			exit;
		}		
	break;
	default:
		$back_link = 'login.php';
		$head_name = $L['register_title'];
	break;
}
$head_title = $head_name.$AJ['seo_delimiter'].$head_title;
$foot = '';
include template('register', 'mobile');
if(AJ_CHARSET != 'UTF-8') toutf8();
?>