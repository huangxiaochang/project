<?php 
require 'myuser.php';
require MD_ROOT.'/member.class.php';
require AJ_ROOT.'/include/post.func.php';
$MOD['vmember'] or dheader($MOD['linkurl']);
$username = $_username;
$user = userinfo($username);
$step = isset($step) ? intval($step) : 0;
$could_email = $AJ['mail_type'] == 'close' ? 0 : 1;
$could_mobile = $AJ['sms'] ? 1 : 0;
switch($action) {
	case 'email':
		$MOD['vemail'] or dheader($MOD['linkurl']);
		$could_email or mobmsg($L['send_mail_close']);
		$head_name = $L['validate_email_title'];
		if($user['vemail']) {
			$action = 'v'.$action;
			include template('validate', $module);
			exit;
		}
		(isset($email) && is_email($email)) or $email = '';
		$session = new dsession();
		if($step == 2) {
			$email = $_SESSION['email_save'];
			is_email($email) or dheader('?action='.$action);
			$code = isset($code) ? trim($code) : '';
			(preg_match("/^[0-9a-z]{6,}$/i", $code) && $_SESSION['email_code'] == md5($email.'|'.$code.'|'.$username.'|VE')) or mobmsg($L['register_pass_emailcode']);
			$db->query("UPDATE {$AJ_PRE}member SET email='$email',vemail=1 WHERE userid=$_userid");
			userclean($username);
			$db->query("INSERT INTO {$AJ_PRE}validate (type,username,ip,addtime,status,title,editor,edittime) VALUES ('email','$username','$AJ_IP','$AJ_TIME','3','$email','system','$AJ_TIME')");
			unset($_SESSION['email_save']);
			unset($_SESSION['email_code']);
		} else if($step == 1) {
			captcha($captcha);
			is_email($email) or mobmsg($L['member_email_null']);
			if($email != $_email) {
				$r = $db->get_one("SELECT userid FROM {$AJ_PRE}member WHERE email='$email'");
				if($r) mobmsg($L['send_email_exist']);
			}
			$emailcode = random(8);
			$_SESSION['email_save'] = $email;
			$_SESSION['email_code'] = md5($email.'|'.$emailcode.'|'.$username.'|VE');
			$title = $L['register_msg_emailcode'];
			$content = ob_template('emailcode', 'mail');
			send_mail($email, $title, stripslashes($content));
		} else {
			$email or $email = $_email;
		}
		include template('validate', $module);
	break;
	case 'mobile':
		$MOD['vmobile'] or dheader($MOD['linkurl']);
		$could_mobile or mobmsg($L['send_sms_close']);
		$head_name = $L['validate_mobile_title'];
		if($user['vmobile']) {
			$action = 'v'.$action;
			include template('mvalidate', 'mobile');
			exit;
		}
		(isset($mobile) && is_mobile($mobile)) or $mobile = '';
		$_mobile = $user['mobile'];
		$session = new dsession();
		if($step == 2) {
			$mobile = $_SESSION['mobile_save'];
			is_mobile($mobile) or dheader('?action='.$action);
			$code = isset($code) ? trim($code) : '';
			(preg_match("/^[0-9a-z]{6,}$/i", $code) && $_SESSION['mobile_code'] == md5($mobile.'|'.$code.'|'.$username.'|VM')) or mobmsg($L['register_pass_mobilecode']);
			$db->query("UPDATE {$AJ_PRE}member SET mobile='$mobile',vmobile=1 WHERE userid=$_userid");
			userclean($username);
			$db->query("INSERT INTO {$AJ_PRE}validate (type,username,ip,addtime,status,title,editor,edittime) VALUES ('mobile','$username','$AJ_IP','$AJ_TIME','3','$mobile','system','$AJ_TIME')");
			unset($_SESSION['mobile_save']);
			unset($_SESSION['mobile_code']);
		} else if($step == 1) {
			captcha($captcha);
			if(!is_mobile($mobile)) mobmsg($L['member_mobile_null']);	
			$r = $db->get_one("SELECT userid FROM {$AJ_PRE}member WHERE mobile='$mobile' AND vmobile=1 AND userid<>$_userid");
			if($r) mobmsg($L['send_mobile_exist']);
			if(max_sms($mobile)) mobmsg($L['sms_msg_max']);
			$mobilecode = random(6, '0123456789');
			$_SESSION['mobile_save'] = $mobile;
			$_SESSION['mobile_code'] = md5($mobile.'|'.$mobilecode.'|'.$username.'|VM');
			$content = lang('sms->sms_code', array($mobilecode, $MOD['auth_days']*10)).$AJ['sms_sign'];
			send_sms($mobile, $content);
			//require AJ_ROOT.'/api/alidayu/sendsms.php';
            //alidayu_sms($mobile, $mobilecode,2);
		} else {
			$mobile or $mobile = $_mobile;
		}
		$foot='';		
		include template('mvalidate', 'mobile');
	break;
	case 'truename':
		$MOD['vtruename'] or dheader($MOD['linkurl']);
		$head_name = $L['validate_truename_title'];
		$foot = '';
		$va = $db->get_one("SELECT * FROM {$AJ_PRE}validate WHERE type='$action' AND username='$username'");
		if($user['vtruename'] || $va) {
			$action = 'v'.$action;
			include template('mvalidate', 'mobile');
			exit;
		}
		if(isset($_POST['ok'])) {
		$thumbs= explode(",", $thumbs);
		 $thumb = $thumbs[0];
		 $thumb1 = $thumbs[1];
		 $thumb2 = $thumbs[2];
			foreach($post as $k=>$v) {
				$post[$k] = convert(input_trim($v), 'UTF-8', AJ_CHARSET);
			}

			//captcha($captcha);
			if(!$truename) dtexit($L['validate_truename_name']);
			if(!$thumb) dtexit($L['validate_truename_image']);
			clear_upload($thumb.$thumb1.$thumb2);
			$truename = dhtmlspecialchars($truename);
			$thumb = dhtmlspecialchars($thumb);
			$thumb1 = dhtmlspecialchars($thumb1);
			$thumb2 = dhtmlspecialchars($thumb2);
			$db->query("INSERT INTO {$AJ_PRE}validate (type,username,ip,addtime,status,editor,edittime,title,thumb,thumb1,thumb2) VALUES ('$action','$username','$AJ_IP','$AJ_TIME','2','system','$AJ_TIME','$truename','$thumb','$thumb1','$thumb2')");
			dtexit('ok');
			dtexit($L['validate_truename_success'], '?action='.$action);
		} else {
			include template('mvalidate', 'mobile');
		}
	break;
	case 'company':
	    $head_name= $L['validate_company_title'];
		$MOD['vcompany'] or dheader($MOD['linkurl']);
		$foot = '';

		$va = $db->get_one("SELECT * FROM {$AJ_PRE}validate WHERE type='$action' AND username='$username'");
		if($user['vcompany'] || $va) {
			$action = 'v'.$action;
			include template('mvalidate', 'mobile');
			exit;
		}
		if(isset($_POST['ok'])) {
		$thumbs= explode(",", $thumbs);
		 $thumb = $thumbs[0];
		 $thumb1 = $thumbs[1];
		 $thumb2 = $thumbs[2];
			foreach($post as $k=>$v) {
				$post[$k] = convert(input_trim($v), 'UTF-8', AJ_CHARSET);
			}
			//captcha($captcha);
			if(!$company) dtexit($L['validate_company_name']);
			if(!$thumb) dtexit($L['validate_company_image']);
			clear_upload($thumb.$thumb1.$thumb2);
			$company = dhtmlspecialchars($company);
			$thumb = dhtmlspecialchars($thumb);
			$thumb1 = dhtmlspecialchars($thumb1);
			$thumb2 = dhtmlspecialchars($thumb2);
			$db->query("INSERT INTO {$AJ_PRE}validate (type,username,ip,addtime,status,editor,edittime,title,thumb,thumb1,thumb2) VALUES ('$action','$username','$AJ_IP','$AJ_TIME','2','system','$AJ_TIME','$company','$thumb','$thumb1','$thumb2')");
			dtexit('ok');
			dmsg($L['validate_company_success'], '?action='.$action);
		} else {
			include template('mvalidate', 'mobile');
		}
	break;
	case 'bank':
		$head_name = $L['validate_bank_title'];
		include template('validate', 'mobile');
	break;
	default:
		dheader($MYURL);
	break;
}

if(AJ_CHARSET != 'UTF-8') toutf8();
?>