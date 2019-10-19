<?php
/*
	[Aijiacms System] Copyright (c) 2011-2015 www.aijiacms.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_AIJIACMS') or exit('Access Denied');
if($itemid) {
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	($item && $item['status'] > 2) or mobile_msg($L['msg_not_exist']);
		if($item['groupid'] == 2) mobile_msg($L['msg_not_exist']);
	extract($item);
	$CAT = get_cat($catid);
	if(!check_group($_groupid, $MOD['group_show']) || !check_group($_groupid, $CAT['group_show'])) mobile_msg($L['msg_no_right']);
	$member = array();
	$fee = get_fee($item['fee'], $MOD['fee_view']);
	include 'contact.inc.php';
	$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
	$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
	$content = video5($t['content']);
	if($share_icon) $share_icon = share_icon($thumb, $content);
	$editdate = timetodate($edittime, 5);
	$could_purchase = (SELL_ORDER && $price > 0 && $minamount > 0 && $amount > 0 && $unit && $username && $username != $_username) ? 1 : 0;
	$could_inquiry = ($user_status == 3 && $username && $username != $_username) ? 1 : 0;
	$update = '';
	if(!$islink) include AJ_ROOT.'/include/update.inc.php';
	$seo_file = 'show';
	$head_title = $title.$AJ['seo_delimiter'].$MOD['name'].$AJ['seo_delimiter'].$head_title;
	$head_name = $title;
	$back_link = 'javascript:Dback(\''.mobileurl($moduleid, $catid).'\', \''.$AJ_REF.'\', \'share|comment|purchase\');';
	$foot = '';
	switch($at) {
	case 'xiangce':
	$head_title = $title.'-楼盘相册';
	include template('xiangce', 'mobile');
	break;
	case 'dongtai':
	$head_title = $title.'-最新动态';
	include template('dongtai', 'mobile');
	break;
	case 'xinxi':
	$head_title = $title.'-楼盘详细信息';
	$typeids=$typeid-1;
	include template('xinxi', 'mobile');
	break;
	default:
	
	$at='index';
	include template('housedetail', 'mobile');
	}
} else {
	if($kw) {
		check_group($_groupid, $MOD['group_search']) or mobile_msg($L['msg_no_search']);
	} else if($catid) {
		$CAT or mobile_msg($L['msg_not_cate']);
		if(!check_group($_groupid, $MOD['group_list']) || !check_group($_groupid, $CAT['group_list'])) {
			mobile_msg($L['msg_no_right']);
		}
	} else {
		check_group($_groupid, $MOD['group_index']) or mobile_msg($L['msg_no_right']);
	}
	$head_title = $MOD['name'].$AJ['seo_delimiter'].$head_title;
	if($kw) $head_title = $kw.$AJ['seo_delimiter'].$head_title;
	$param = $_GET['param'];//2012/8/7

if(!empty($param) && empty($kw))
	{
		
		$param_arr = explode('-', $param);
		foreach($param_arr as $_v) {
				if($_v) 
				{
				if(preg_match ( '/([a-z])([0-9A-Z_]+)/', $_v, $matchs))
					{
						$$matchs[1] = trim($matchs[2]);
					}
				}
			}
		$areaid = $r;
		$bid = $b;
		$range = $p;
		$catid = $t;
		$fitment = $f;
		$buildtype = $j;
		$lpts = $l;
		$ditie = $d;
		$letter = $e;
		$opentime = $o;
		$typeid = $h;
		$ord = $n;
		$page = $g;
		$k = $k;

		
	}
	else
	{
 	$areaid = intval($_GET['r']);
	$bid = intval($_GET['b']);
	$range = intval($_GET['p']);
	$catid = intval($_GET['t']);
	$fitment = intval($_GET['f']);
	$buildtype = intval($_GET['j']);
	$lpts = intval($_GET['l']);
	$ditie = intval($_GET['d']);
	$letter = trim($_GET['e']);
	$opentime = intval($_GET['o']);
	$typeid = intval($_GET['h']);
	$ord = intval($_GET['n']);
	$page = intval($_GET['g']);
	//$keyword = trim($_GET['keyword']);
	$k = trim($_GET['k']);
	
	}
	$condition = "status=3 and isnew=1";
	
	if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
	if(!empty($areaid))
	{
		$lst = "-r".$areaid;
	}
    if(!empty($bid))
	{
		$lst.= "-b".$bid;
	}
	if(!empty($areaid) && !empty($bid))
	{
		$condition .= $ARE['child'] ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$bid";
		$area_name=area_poss($bid, ' ');
	}
	elseif(!empty($areaid) && empty($bid))//区域顶级
	{
		$arrchildid = get_arrchildids($areaid);
		$condition.=" and areaid in (".$arrchildid.")";
		$area_name=area_pos($areaid, ' ');
	}
		$areaids=$_GET['areaid'];
if($AJ['city'] && empty($areaid)){
	$ARE = $AREA[$cityid];
	$condition .= $ARE['arrchildid'] ? " and areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";}
	if(!empty($range))
	{
		$lst.= "-p".$range;
		$mix=mixprice($range,'range','newhouse_6');
		$max=maxprice($range,'range','newhouse_6');
		if($mix){$mix=$mix;}
		else
		{$mix=0;}
		if($max){$condition.=" and $mix<=price AND price<$max ";}
		else
		{$condition.=" and $mix <= price ";}
		$range_arr = getbox_name('range','newhouse_6');
		$range_name=$range_arr[$range];
	}
	if(!empty($catid))
	{
		$lst.= "-t".$catid;
		$condition .= " AND FIND_IN_SET('$catid',`catid`)" ;
		$cat_name=search_cats($catid, '6');
		
	}
	if(!empty($fitment))
	{
		$lst.= "-f".$fitment;
		$condition .= " AND FIND_IN_SET('$fitment',`fitment`)" ;
		$fitment_arr = getbox_name('fitment','newhouse_6');
		$fitment_name=$fitment_arr[$fitment];
	}
	if(!empty($buildtype))
	{
		$lst.= "-j".$buildtype;
		$condition .= " AND FIND_IN_SET('$buildtype',`buildtype`)" ;
		$buildtype_arr = getbox_name('buildtype','newhouse_6');
		$buildtype_name=$buildtype_arr[$buildtype];
	}
	if(!empty($lpts))
	{
		$lst.= "-l".$lpts;
		$condition .= " AND FIND_IN_SET('$lpts',`lpts`)" ;
		$lpts_arr = getbox_name('lpts','newhouse_6');
		$lpts_name=$lpts_arr[$lpts];
	}
	if(!empty($ord))
	{
		if($ord=='1')
		{
			$order=" order by price desc";
		}
		elseif($ord=='2')
		{
			$order=" order by price asc";
		}
		elseif($ord=='3')
		{
			$order=" order by hits asc";
		}
		elseif($ord=='4')
		{
			$order="order by hits desc";
		}
		$lst.= "-n".$ord;
	}
	else
	{
		$order = " order by ".$MOD['order'];
	}

	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition ", 'CACHE');
	$items = $r['num'];
	

	if($kw){
		
		$pages = mobile_pages($items, $page, $pagesize);}
	
	else
	{$page = max($page,1);
$pagesize = $MOD['pagesize'];
$offset = ($page-1)*$pagesize;
		
		$pages =mobilepages($items, $page,6, $lst,$pagesize);}
	$lists = array();
	if($items) {
		//$order = $MOD['order'];
		$time = strpos($MOD['order'], 'add') !== false ? 'addtime' : 'edittime';

		$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE $condition  $order LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			if($kw) $r['title'] = str_replace($kw, '<b class="f_red">'.$kw.'</b>', $r['title']);
			$r['linkurl'] = mobileurl($moduleid, 0, $r['itemid']);
			$r['date'] = timetodate($r[$time], 5);
			$lists[] = $r;
		}
		$db->free_result($result);
	}
	$back_link = mobileurl($moduleid);
	if($kw) {
		$seo_file = 'search';
		$head_name = $MOD['name'].$L['search'];
	} else if($catid) {
		$seo_file = 'list';
		$head_title = $CAT['catname'].$AJ['seo_delimiter'].$head_title;
		$head_name = $CAT['catname'];
		if($CAT['parentid']) $back_link = mobileurl($moduleid, $CAT['parentid']);
	} else {
		$seo_file = 'index';
		$head_name = $MOD['name'];
	}
	include template($module, 'mobile');
}


?>