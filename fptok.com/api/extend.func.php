<?php
/*
	 www.aijiacms.com 爱家房产系统
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_AIJIACMS') or exit('Access Denied');
function verify()
{

}
// php函数开始
/**
 * 获取相应模块下对应id的评论数 商城模块不适用
 * $mid  模块id 例如二手房为5
 * $itemid  信息id 比如1
 */
function gl_get_comments($mid,$itemid) {
global $db;
$nums = $db->get_one("SELECT COUNT(*) AS num FROM {$db->pre}comment WHERE item_mid='$mid' and item_id='$itemid'");
return $nums['num'] ;
}

/**
 * 获取相应id下对应的分类名称
 */
function gl_cat_name($catid) {
	global $db;
	$catid = intval($catid);
	$catname = '';
    $r = $db->get_one("SELECT catname FROM {$db->pre}category WHERE catid=$catid");
    $catname = $r['catname'];
	return $catname;
}

/**
 * 获取相应id下对应的地区名称
 */
function gl_area_name($areaid) {
	global $db;
	$catid = intval($areaid);
	$areaname = '';
    $r = $db->get_one("SELECT areaid,areaname FROM {$db->pre}area WHERE areaid=$areaid");
    $areaname = $r['areaname'];
	return $areaname;
}

function gl_exit($error='error',$path='',$message='') {
  exit('{"error":"'.$error.'","path":"'.$path.'","message":"'.$message.'"}');
}

//获取分类父id
function gl_get_parcatid($catid) {
	global $db;
		$r = $db->get_one("SELECT parentid FROM {$db->pre}category WHERE catid=$catid");
		if($r['parentid']==0){
			$catid = $catid;
		}else{
		$catid = $r['parentid'];
		}

		return $catid;
}

//获取分类父id
function gl_get_parareaid($areaid) {
	global $db;
		$r = $db->get_one("SELECT parentid FROM {$db->pre}area WHERE areaid=$areaid");
		if($r['parentid']==0){
			$areaid = $areaid;
		}else{
		$areaid = $r['parentid'];
		}

		return $areaid;
}

function gl_bdcity($ip) {
	global $AJ;
	$ch = curl_init();
    $url = 'http://apis.baidu.com/apistore/iplookupservice/iplookup?ip='.$ip;
    $header = array(
        'apikey: '.$AJ['cloud_bdapi_key'].'',
    );
    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);
	$ipdata = json_decode($res,true);

		$data = $ipdata;
		if($data['errMsg']=='success') {
			$area = '';
			if(isset($ipdata['retData']['country']) && strpos($res, "\u4e2d\u56fd") === false) $area .= $ipdata['retData']['country'];
			if(isset($ipdata['retData']['province'])) $area .= $ipdata['retData']['province'].'&#x7701;';//省
			if(isset($ipdata['retData']['city'])) $area .= $ipdata['retData']['city'].'&#x5E02;';//市
			if(isset($ipdata['retData']['district'])) $area .= $ipdata['retData']['district'];//地区
			//if(isset($ipdata['retData']['carrier'])) $area .= '-'.$ipdata['retData']['carrier'];
			if(isset($ipdata['retData']['city'])) $mycity = $ipdata['retData']['city'];//市
			return $mycity ? convert($mycity, 'UTF-8', AJ_CHARSET) : '';
		}
		return 'API Error';
	}

//获取资讯详细内容中的图片地址 并输出
function gl_acontent_thumb($moduleid,$itemid,$nums = 0) {
	global $db, $AJ, $AJ_TIME;
	$$thumbs = '';
	$table = get_table($moduleid,1);
	$r = $db->get_one("SELECT content FROM {$table} WHERE itemid='$itemid'");
	$content = $r['content'];
	if(!$content) return '';
	$ext = 'jpg|jpeg|png|bmp';
	if(!preg_match_all("/src=([\"|']?)([^ \"'>]+\.($ext))\\1/i", $content, $matches)) return '';
	$mnums = count($matches[2]);
	if($mnums>=$nums) $thumbs = array_slice($matches[2], 0, $nums);
    return $thumbs;
}

//时间格式化 传入时间戳格式1464662723
function gl_format_date($time){
    $t = time()-$time;
	$timer = '前';
	if($t<0){
		$t = $time - time();
		$timer = '后';
	}
    $f=array(
        '31536000'=>'年',
        '2592000'=>'个月',
        '604800'=>'星期',
        '86400'=>'天',
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.$timer;
        }
    }
};



function gl_input_trim($wd) {
	return urldecode(str_replace('%E2%80%86', '', urlencode($wd)));
}

// php函数结束
?>