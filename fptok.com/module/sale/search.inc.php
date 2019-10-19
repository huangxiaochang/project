<?php 
defined('IN_AIJIACMS') or exit('Access Denied');
if($AJ_BOT || $_POST) dhttp(403);
require AJ_ROOT.'/module/'.$module.'/common.inc.php';
if(!check_group($_groupid, $MOD['group_search'])) include load('403.inc');
require AJ_ROOT.'/include/post.func.php';
include load('search.lang');
$CP = $MOD['cat_property'] && $catid && $CAT['property'];
$list = isset($list) ? intval($list) : 0;
in_array($list, array(0, 1, 2)) or $list = 0;


$area_select = ajax_area_select('areaid', $L['all_area'], $areaid);



$thumb = isset($thumb) ? intval($thumb) : 0;
$price = isset($price) ? intval($price) : 0;
$vip = isset($vip) ? intval($vip) : 0;
$day = isset($day) ? intval($day) : 0;
$minprice = isset($minprice) ? dround($minprice) : '';
$minprice or $minprice = '';
$maxprice = isset($maxprice) ? dround($maxprice) : '';
$maxprice or $maxprice = '';
$typeid = isset($typeid) && isset($TYPE[$typeid]) ? intval($typeid) : 99;
$kw = $kw ? $kw : $k;


//echo $_GET['kw'];
	$condition = 'status=3';
	if($catid) $condition .= $CAT['child'] ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
	if($areaid) $condition .= $ARE['child'] ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";

	if($fromtime) $condition .= " AND edittime>=$fromtime";
	if($totime) $condition .= " AND edittime<=$totime";

if($kw) {
		$lst.= "-k".htmlentities(urlencode($kw));
	$condition.=" and (`title` like '%$kw%'  or `address` like '%$kw%' or `housename` like '%$kw%' or `telephone` like '%$kw%')";}
	$pagesize = $MOD['pagesize'];
	$offset = ($page-1)*$pagesize;
	$items = $db->count($table, $condition, $AJ['cache_search']);	
$pages = housepages($items, $page, $lst,$pagesize);
	if($items) {
		$order = $dorder[$order] ? " ORDER BY $dorder[$order]" : '';
		$result = $db->query("SELECT * FROM {$table} WHERE {$condition}{$order} LIMIT {$offset},{$pagesize}", ($AJ['cache_search'] && $page == 1) ? 'CACHE' : '', $AJ['cache_search']);
		if($kw) {
			$replacef = explode(' ', $kw);
			$replacet = array_map('highlight', $replacef);
		}
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = AJ_SKIN.'image/lazy.gif" original="'.$r['thumb'];
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			if($kw) $r['title'] = str_replace($replacef, $replacet, $r['title']);
			if($kw) $r['introduce'] = str_replace($replacef, $replacet, $r['introduce']);
			$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
			$tags[] = $r;
		}
		$db->free_result($result);
		if($page == 1 && $kw) keyword($kw, $items, $moduleid);
	}

$showpage = 1;
$datetype = 5;
$seo_file = 'search';
include AJ_ROOT.'/include/seo.inc.php';
if($EXT['mobile_enable']) $head_mobile = $EXT['mobile_url'].($kw ? 'index.php?moduleid='.$moduleid.'&kw='.encrypt($kw, AJ_KEY.'KW') : 'search.php?action=mod'.$moduleid);
include template($MOD['template_search'] ? $MOD['template_search'] : 'search', $module);
?>