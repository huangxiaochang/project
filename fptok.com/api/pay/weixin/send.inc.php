<?php
defined('IN_AIJIACMS') or exit('Access Denied');
if($AJ_MOB['browser'] == 'weixin' && $EXT['weixin']) {
	$openid = '';
	$t = $db->get_one("SELECT openid FROM {$AJ_PRE}weixin_user WHERE username='$_username'");
	if($t) {
		$openid = $t['openid'];
	} else {
		$openid = get_cookie('weixin_openid');
		if($openid) $openid = decrypt($openid, AJ_KEY.'WXID');
	}
	$t = explode('MicroMessenger/', $_SERVER['HTTP_USER_AGENT']);
	if(intval($t[1]) >= 5) {
		if(is_openid($openid)) {
			dheader(AJ_PATH.'api/pay/weixin/jsapi.php?auth='.encrypt($orderid.'|'.$charge_title.'|'.$AJ_IP.'|'.$openid, AJ_KEY.'JSPAY'));
		} else {
			dheader($EXT['mobile_url'].'weixin.php?url='.urlencode(AJ_PATH.'api/pay/weixin/openid.php?itemid='.$orderid));
		}
	}
}
dheader(AJ_PATH.'api/pay/weixin/qrcode.php?auth='.encrypt($orderid.'|'.$charge_title.'|'.$AJ_IP, AJ_KEY.'QRPAY'));
?>