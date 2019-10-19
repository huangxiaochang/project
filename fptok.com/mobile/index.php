<?php
/*
	 
	This is NOT a freeware, use is subject to license.txt
*/
require 'common.inc.php';

//二次开发代码开始
require 'user/lang.inc.php';
//微信jssdk分享
if($AJ_MOB[browser]=='weixin' && $itemid) {
	//require AJ_ROOT.'/api/weixin/wxshare.php';
}

/*定位开始
$m_history = get_cookie('mcity');
$history = explode(',', $m_history);
$cityid = $history[0];
if(!$AJ['city']) $cityid=0;
if($AJ['local_bdmap']==0 && $AJ['city']){
if($cityid==0 && !$m_history){
$bd_city = gl_bdcity($AJ_IP);
$result = $db->query("SELECT areaid,areaname FROM {$AJ_PRE}area ORDER BY areaid");
        while($r = $db->fetch_array($result)) {
	    if(preg_match("/".$bd_city."/i", $r['areaname'])) {
	    set_cookie('mcity', $r['areaid'], $AJ_TIME + 7*86400);
	    $cityid = $r['areaid'];
	    $cityname = $r['areaname'];
	    if($r['domain']) dheader($r['domain']);
	    $c = $r;
	    break;
	           }
	}
  }else{

	$pareaid = gl_get_parareaid($cityid);
	$areaname = gl_area_name($cityid);

    if(isset($areaid) || $areaid){
    $sql="select areaid,areaname from {$AJ_PRE}area where parentid='$pareaid'";
    $glarea = array();
    $res = $db->query($sql);
    while($r = $db->fetch_array($res)){
	$glarea[] = $r;
    }
  }
}
$areaname = isset($areaname)?$areaname:$M['local_national'];
}
*/
//二次开发代码结束

if(in_array($module, $mobile_modules)) {
	if($cityid && !$areaid) {
		$areaid = $cityid;
		$ARE = $AREA[$cityid];
	}
	$pages = '';
	if(isset($MOD['pagesize'])) {
		$pagesize = $MOD['pagesize'];
		$offset = ($page-1)*$pagesize;
	}
	require AJ_ROOT.'/module/'.$module.'/common.inc.php';
	include 'include/'.$module.'.inc.php';
} else {
	$ads = array();
	$pid = intval($EXT['mobile_pid']);
	if($pid > 0) {
		$result = $db->query("SELECT * FROM {$AJ_PRE}ad WHERE pid=$pid AND status=3 AND totime>$AJ_TIME ORDER BY listorder ASC,addtime ASC LIMIT 10", 'CACHE');
		while($r = $db->fetch_array($result)) {
			$r['image_src'] = linkurl($r['image_src']);
			$r['url'] = $r['stat'] ? AJ_PATH.'api/redirect.php?aid='.$r['aid'] : linkurl($r['url']);
			$ads[] = $r;
		}
	}
	$MOD_MY = array();
	$data = '';
	$local = get_cookie('mobile_setting');
	if($local) {
		$data = $local;
	} else if($_userid) {
		$data = file_get(AJ_ROOT.'/file/user/'.dalloc($_userid).'/'.$_userid.'/mobile.php');
		if($data) set_cookie('mobile_setting', $data, $AJ_TIME + 30*86400);
	}
	if($data) {
		$MOB_MOD = array();
		foreach($MOB_MODULE as $m) {
			$MOB_MOD[$m['moduleid']] = $m;
		}
		foreach(explode(',', $data) as $id) {
			if(isset($MOB_MOD[$id])) $MOD_MY[] = $MOB_MOD[$id];
		}
	}
	if(count($MOD_MY) < 2) $MOD_MY = $MOB_MODULE;
	$head_name = $EXT['mobile_sitename'] ? $EXT['mobile_sitename'] : $AJ['sitename'];
	$head_keywords = $AJ['seo_keywords'];
	$head_description = $AJ['seo_description'];
	$foot = 'home';
	include template('index', 'mobile');
}
if(AJ_CHARSET != 'UTF-8') toutf8();
?>