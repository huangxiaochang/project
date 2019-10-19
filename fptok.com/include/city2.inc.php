<?php
/*
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_AIJIACMS') or exit('Access Denied');
$AREA = cache_read('area.php');
$cf = array();
$city = get_cookie('city');
$http_host = get_env('host');
if($city) {
	list($cityid, $city_domain) = explode('|', $city);
	$cityid = intval($cityid);
	if(strpos(AJ_PATH, $http_host) === false && strpos($city_domain, $http_host) === false) {
		$cf = $db->get_one("SELECT * FROM {$AJ_PRE}city WHERE domain='http://".$http_host."/'");
		if($cf) {
			//set_cookie('city', $cf['areaid'].'|'.$cf['domain'], $AJ_TIME + 30*86400);
			$cityid = $cf['areaid'];
		}
	}
	if($city_domain && $AJ_URL == AJ_PATH) dheader($city_domain);
} else {
	if(strpos(AJ_PATH, $http_host) === false) {
		$cf = $db->get_one("SELECT * FROM {$AJ_PRE}city WHERE domain='http://".$http_host."/'");
		if($cf) {
			set_cookie('city', $cf['areaid'].'|'.$cf['domain'], $AJ_TIME + 30*86400);
			$cityid = $cf['areaid'];
		}
	} else {
		if($AJ['city_ip'] && !defined('AJ_ADMIN') && !$AJ_BOT) {
			$iparea = ip2area($AJ_IP);
			$result = $db->query("SELECT * FROM {$AJ_PRE}city ORDER BY areaid");
			while($rs = $db->fetch_array($result)) {
				if(preg_match("/".$rs['name'].($rs['iparea'] ? '|'.$rs['iparea'] : '')."/i", $iparea)) {
					//set_cookie('city', $rs['areaid'].'|'.$rs['domain'], $AJ_TIME + 30*86400);
					$cityid = $rs['areaid'];
					if($rs['domain']) dheader($r['domain']);
					$cf = $r;
					break;
				}
			}
		}
	}
}
if($cityid) {
	$cf or $cf = $db->get_one("SELECT * FROM {$AJ_PRE}city WHERE areaid=$cityid");
	if(!defined('AJ_ADMIN')) {
		if($cf['seo_title']) {		
			$AJ['seo_title'] = $city_sitename = $cf['seo_title'];
		} else {
			$citysite = lang($L['citysite'], array($cf['name']));
			$AJ['seo_title'] = $citysite.$AJ['seo_delimiter'].$AJ['seo_title'];
			$city_sitename = $citysite.$AJ['seo_delimiter'].$AJ['sitename'];
		}
		if($c['seo_keywords']) $AJ['seo_keywords'] = $cf['seo_keywords'];
		if($c['seo_description']) $AJ['seo_description'] = $cf['seo_description'];
	}
	$map_mid = $cf['map_mid'];
	$city_name = $cf['name'];
	$city_domain = $cf['domain'];
	$city_template = $cf['template'];
}
else
{
$map_mid=$AJ['map_mid'];}
//默认城市

	$cf or $cf = $db->get_one("SELECT * FROM {$AJ_PRE}city WHERE hits=1");
	if(!defined('AJ_ADMIN')) {
		if($cf ['seo_title']) {		
			$AJ['seo_title'] = $city_sitename = $cf ['seo_title'];
		} else {
			$citysite = lang($L['citysite'], array($cf ['name']));
			$AJ['seo_title'] = $citysite.$AJ['seo_delimiter'].$AJ['seo_title'];
			//$AJ['sitename'] = $citysite.$AJ['seo_delimiter'].$AJ['sitename'];
			$city_sitename = $citysite.$AJ['seo_delimiter'].$AJ['sitename'];
		}
		if($cf ['seo_keywords']) $AJ['seo_keywords'] = $cf ['seo_keywords'];
		if($cf ['seo_description']) $AJ['seo_description'] = $cf ['seo_description'];
	}
	$city_namem = $cf ['name'];
	$cityid = $cf ['areaid'];
	$map_mid = $cf ['map_mid'];
	$city_domain = $cf ['domain'];
	$city_template = $cf ['template'];
if($city_domain) {
	foreach($MODULE as $i=>$v) {
		if($v['islink']) continue;
		$MODULE[$i]['linkurl'] = $i == 1 ? $city_domain : $city_domain.$v['moduledir'].'/';
	}
	$MOD['linkurl'] = $MODULE[$moduleid]['linkurl'];
	foreach($EXT as $i=>$v) {
		if(strpos($i, '_url') !== false) {
			$EXT[$i] = $city_domain.str_replace('_url', '', $i).'/';
		}
	}
}
//$AJ_IP = '182.129.52.235';


?>