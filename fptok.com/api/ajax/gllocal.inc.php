<?php
defined('IN_AIJIACMS') or exit('Access Denied');
switch($job) {
	case 'gps':
	    $x = isset($x) ? $x : '';
        $y = isset($y) ? $y : '';
        $url = 'http://api.map.baidu.com/geoconv/v1/?coords='.$x.','.$y.'&from=1&to=5&ak='.$AJ['cloud_bdmap_ak'].'';
		$res = file_get_contents($url);
	    $location = json_decode($res,true);
		$location = $location['result'][0];
	    set_cookie('my_lng', $location[x], $AJ_TIME + 7*86400);
	    set_cookie('my_lat', $location[y], $AJ_TIME + 7*86400);
        exit(json_encode($location));
	break;
	case 'mylocal':
	set_cookie('my_lng', $my_lng, $AJ_TIME + 7*86400);
	set_cookie('my_lat', $my_lat, $AJ_TIME + 7*86400);
	break;
	case 'setcity':
		//set_cookie('mcity', $mcityid, $AJ_TIME + 7*86400);
		$history_number = 3;
        $m_history = get_cookie('mcity');
        if (!empty($m_history))
       {
        $history = explode(',', $m_history);
        array_unshift($history, $mcityid);
        $history = array_unique($history);
        while (count($history) > $history_number)
       {
        array_pop($history);
        }
       set_cookie('mcity', implode(',', $history), $AJ_TIME + 7*86400);
       }
       else
       {
        set_cookie('mcity', $mcityid, $AJ_TIME + 7*86400);
        }
		gl_exit('ok');
	break;
	case 'history':
		$m_history = get_cookie('mcity');
		$tags=array();
		$condition = 'areaid IN ('.$m_history.') ';
	    $result = $db->query("SELECT areaid,areaname FROM {$AJ_PRE}area WHERE {$condition}{$order} LIMIT {$offset},{$pagesize}");
	while($r = $db->fetch_array($result)) {
		$r['areaname'] = gl_utftext($r['areaname']);
		$tags[] = $r;
	}
   exit(json_encode($tags));
	break;
	case 'initial':
		$m_history = get_cookie('mcity');
		$history = explode(',', $m_history);
		//gl_exit($history[0]);
		$cityid = $history[0];
		
	    $x = isset($x) ? $x : '';
        $y = isset($y) ? $y : '';
		//$x = 119.651083;
		//$y = 29.083517;
        $url = 'http://api.map.baidu.com/geoconv/v1/?coords='.$x.','.$y.'&from=1&to=5&ak='.$AJ['cloud_bdmap_ak'].'';
		$res = file_get_contents($url);
	    $location = json_decode($res,true);

		$url = 'http://api.map.baidu.com/geocoder/v2/?ak='.$AJ['cloud_bdmap_ak'].'&location='.$location['result'][0][y].','.$location['result'][0][x].'&output=json&pois=0';
        $res = file_get_contents($url);
	    $location = json_decode($res,true);
		//gl_parray($location);
	    if($location['status']==0) {
         $arcity = $location['result']['addressComponent']['city'];
		 $bddistrict = $location['result']['addressComponent']['district'];
		 $formatted_address = $location['result']['formatted_address'];
		}else{
		}
        if($cityid==0 && !$m_history) {
		$areaname = $bddistrict ? : $arcity;
		$arcity = str_replace('市',"",$arcity);
        $result = $db->query("SELECT areaid,areaname FROM {$AJ_PRE}area ORDER BY areaid");
        while($r = $db->fetch_array($result)) {
	    if(preg_match("/".$arcity."/i", gl_utftext($r['areaname']))) {
	    set_cookie('mcity', $r['areaid'], $AJ_TIME + 7*86400);
	    $cityid = $r['areaid'];
	    $cityname = $r['areaname'];
	    //if($r['domain']) dheader($r['domain']);
	    $c = $r;
	    break;
	           }
		     }
        //$cityid = 12;
        }else{
        }
	//exit(json_encode($cityid));
	$areaid = gl_get_parareaid($cityid);
	$areaname = gl_area_name($cityid);
    $areaname = isset($areaname)?gl_utftext($areaname):'全国';
    if(!isset($areaid) || $areaid==''){exit;}
    $sql="select areaid,areaname from {$AJ_PRE}area where parentid='$areaid'";
    $param = array();
    $res = $db->query($sql);
    while($r = $db->fetch_array($res)){
	$r['areaname'] = gl_utftext($r['areaname']);
	$param[] = $r;
    }
	if($cityid==0) $param='';
    $datalist = array('arealist' => $param,'areaname' => $areaname,'localaddress' => $formatted_address);
	exit(json_encode($datalist));
    break;
}
?>