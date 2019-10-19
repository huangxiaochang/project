<?php
$moduleid = 3;
require 'common.inc.php';
$need_captcha = $MOD['guestbook_captcha'] ? 1 : 0;
if(isset($_POST['ok'])) {
	$captcha = isset($captcha) ? convert(input_trim($captcha), 'UTF-8', AJ_CHARSET) : '';
	$msg = captcha($captcha, $need_captcha, true);
	if($msg) exit('captcha');
	$TYPE = explode('|', trim($MOD['guestbook_type']));
	require AJ_ROOT.'/include/post.func.php';
	require AJ_ROOT.'/module/extend/guestbook.class.php';
	$do = new guestbook();
	$post = array();
	$content = convert($content, 'UTF-8', AJ_CHARSET);
	$post['content'] = $content."\n".$L['guestbook_from']."\n".$L['guestbook_info'];
	if($do->pass($post)) {
		$post['type'] = '';
		if($_userid) {
			$user = userinfo($_username);
			$post['truename'] = $user['truename'];
			$post['telephone'] = $user['telephone'] ? $user['telephone'] : $user['mobile'];
			$post['email'] = $user['mail'] ? $user['mail'] : $user['email'];
			$post['qq'] = $user['qq'];
			$post['msn'] = $user['msn'];
			$post['ali'] = $user['ali'];
			$post['skype'] = $user['skype'];
		}
		$post = daddslashes($post); $do->add($post); 
		exit('ok');
	}
	exit('ko');
} else {
	$head_title = $L['guestbook_title'].$AJ['seo_delimiter'].$head_title;
	$foot = 'more';
	include template('guestbook', 'mobile');
}
if(AJ_CHARSET != 'UTF-8') toutf8();
?>