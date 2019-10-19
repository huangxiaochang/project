<?php 
defined('IN_AIJIACMS') or exit('Access Denied');
require AJ_ROOT.'/module/'.$module.'/common.inc.php';
$MOD['announce_enable'] or dheader(AJ_PATH);
require AJ_ROOT.'/include/post.func.php';
$ext = 'announce';
$url = $EXT[$ext.'_url'];
$TYPE = get_type($ext, 1);
$_TP = sort_type($TYPE);
require MD_ROOT.'/'.$ext.'.class.php';
$do = new $ext();
$typeid = isset($typeid) ? intval($typeid) : 0;
$aijiacms_task = rand_task();
if($itemid) {
	$do->itemid = $itemid;
	$item = $do->get_one();
	$item or dheader($url);
	extract($item);
	$adddate = timetodate($addtime, 3);
	$fromdate = $fromtime ? timetodate($fromtime, 3) : $L['timeless'];
	$todate = $totime ? timetodate($totime, 3) : $L['timeless'];
	$db->query("UPDATE {$AJ_PRE}{$ext} SET hits=hits+1 WHERE itemid=$itemid");
	$head_title = $title.$AJ['seo_delimiter'].$L['announce_title'];
	if($EXT['mobile_enable']) $head_mobile = $EXT['mobile_url'].'announce.php?itemid='.$itemid;
	$template = $item['template'] ? $item['template'] : $ext;
	include template($template, $module);
} else {
	$head_title = $L['announce_title'];
	if($catid) $typeid = $catid;
	$condition = '1';
	if($typeid) {
		isset($TYPE[$typeid]) or dheader($url);
		$condition .= " AND typeid IN (".type_child($typeid, $TYPE).")";
		$head_title = $TYPE[$typeid]['typename'].$AJ['seo_delimiter'].$head_title;
	}
	if($keyword) $condition .= " AND title LIKE '%$keyword%'";

	if(!empty($areaid))
	{
		$lst = "-r".$areaid;
		$lstaddr.= "<i>".area_poss($areaid, ' ')."<a href=\"list".deal_str($lst,'r').".html\"></a></i>";
	}

              $areaid = intval($_GET['r']);

	if(!empty($areaid))
	{
		$lst = "-r".$areaid;
		$lstaddr.= "<i>".area_poss($areaid, ' ')."<a href=\"list".deal_str($lst,'r').".html\"></a></i>";
	}
 	if(!empty($bid))
	{
		$lst.= "-b".$bid;
		$lstaddr.= "<i>".area_poss($bid, ' ')."<a href=\"list".deal_str($lst,'b').".html\"></a></i>";
	}
	if(!empty($areaid) && !empty($bid))
	{
		$condition .= $ARE['child'] ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$bid";
	}
	elseif(!empty($areaid) && empty($bid))//区域顶级
	{
		$arrchildid = get_arrchildids($areaid);
		$condition.=" and areaid in (".$arrchildid.")";
	}
 
	$areaids=$_GET['areaid'];
	if($AJ['city'] && empty($areaid)){
	$ARE = $AREA[$cityid];
	$condition .= $ARE['child'] ? " and areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	}
	if($AJ['city']){
	$mainarea = get_mainarea($cityid);
	$mainareas = get_mainarea2($areaids);
	}else{
	$mainarea = get_mainarea(0);
	$mainareas = get_mainarea3($areaids);
	}

 
	//if($cityid) $condition .= ($AREA[$cityid]['child']) ? " AND areaid IN (".$AREA[$cityid]['arrchildid'].")" : " AND areaid=$cityid";

 
	$lists = $do->get_list($condition, 'listorder DESC,itemid DESC');

	
	if($EXT['mobile_enable']) $head_mobile = $EXT['mobile_url'].'announce.php'.($page > 1 ? '?page='.$page : '');
	include template($ext, $module);
}
?>