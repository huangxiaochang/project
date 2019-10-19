<?php
require '../../../common.inc.php';
($AJ_MOB['browser'] == 'weixin' && $EXT['weixin']) or dheader($EXT['mobile_url']);
$_userid or dheader($EXT['mobile_url']);
$itemid or dheader($EXT['mobile_url']);
$t = $db->get_one("SELECT * FROM {$AJ_PRE}finance_charge WHERE itemid=$itemid");
($t && $t['username'] == $_username && $t['status'] == 0) or dheader($EXT['mobile_url']);
$orderid = $t['itemid'];
$charge_title = $_username.'('.$orderid.')';
$t = $db->get_one("SELECT openid FROM {$AJ_PRE}weixin_user WHERE username='$_username'");
if($t && is_openid($t['openid'])) {
	$openid = $t['openid'];
	dheader(AJ_PATH.'api/pay/weixin/jsapi.php?auth='.encrypt($orderid.'|'.$charge_title.'|'.$AJ_IP.'|'.$openid, AJ_KEY.'JSPAY'));
}
dheader(AJ_PATH.'api/pay/weixin/qrcode.php?auth='.encrypt($orderid.'|'.$charge_title.'|'.$AJ_IP, AJ_KEY.'QRPAY'));
?>