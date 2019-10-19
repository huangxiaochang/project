<?php
defined('IN_AIJIACMS') or exit('Access Denied');
$WX = cache_read('weixin.php');
if($WX['appid'] && $WX['appsecret'] && $WX['apptoken']){
define('GLWX_APPID', $WX['appid']);
define('GLWX_APPSECRET', $WX['appsecret']);
define('GLWX_APPTOKEN', $WX['apptoken']);

require 'gljssdk.php';
$gljssdk = new gljssdk;
$access_token = $dc->get('weixin_access_token');
if(!$access_token) {
	$access_token = $gljssdk->glgetAccessToken();
	$dc->set('weixin_access_token', $access_token, 7000);
}
$gljssdk->access_token = $access_token;

$jsapiTicket = $dc->get('weixin_jsapiTicket');
if(!$jsapiTicket) {
	$jsapiTicket = $gljssdk->glgetJsApiTicket();
	$dc->set('weixin_jsapiTicket', $jsapiTicket, 7000);
}
$gljssdk->jsapiTicket = $jsapiTicket;

$signPackage = $gljssdk->glGetSignPackage();
}
?>