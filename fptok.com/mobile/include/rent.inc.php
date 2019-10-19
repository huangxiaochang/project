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
	include template('rentdetail', 'mobile');
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
	$condition = "status=3";
	if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
	
	if(empty($kw)){
		$_GET = safe_replace($_GET);
$_POST = safe_replace($_POST);
$param = $_GET['param'];

	if(!empty($param)&&stristr($param,'-')!=false)
	{
		$param_arr = explode('-', $param);
	
		foreach($param_arr as $_v) {
				if($_v) 
				{
					if(preg_match ( '/([a-z])([0-9_]+)/', $_v, $matchs))
					{
						$$matchs[1] = trim($matchs[2]);
					}
				}
			}
		$areaid = $r;
		$bid = $b;
		$eprice = $e;
		$marea = $m;
		$range = $p;
		$catid = $t;
		$hu = $x;
		$zhuanxiu = $f;
		$year = $y;
		$source = $u;
		$floor = $l;
		$zu = $z;
		$toward = $s;
		$hot = $h;
		$ord = $n;
		$area = $c;
		$page = $g;
		
	}
	else
	{
 	$areaid = intval($_GET['r']);
	$bid = intval($_GET['b']);
	$eprice = trim($_GET['e']);
	$marea = trim($_GET['m']);
	$range = intval($_GET['p']);
	$catid = intval($_GET['t']);
	$hu = intval($_GET['x']);
	$zhuanxiu = intval($_GET['f']);
	$year = intval($_GET['y']);
	$floor = intval($_GET['l']);
	$source = intval($_GET['u']);
	$zu = intval($_GET['z']);
	$toward = intval($_GET['s']);
	$hot = intval($_GET['h']);
	$ord = intval($_GET['n']);
	$area = intval($_GET['c']);
	$page = intval($_GET['g']);
	//$keyword = trim($_GET['keyword']);
	
	}
	
	
	$condition = "status=3";
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
	if(!empty($catid))
	{
		$lst.= "-t".$catid;
		$condition .= " AND FIND_IN_SET('$catid',`catid`)" ;
		$lstaddr.= "<i>".get_cats($catid, '7')."<a href=\"list".deal_str($lst,'t').".html\"></a></i>";
	}

	
	if(!empty($range))
	{
		$lst.= "-p".$range;
			$mix=mixprice($range,'range','rent_7');
		$max=maxprice($range,'range','rent_7');
		if($mix){$mix=$mix;}
		else
		{$mix=0;}
		if($max){$condition.=" and $mix<=price AND price<$max ";}
		else
		{$condition.=" and $mix <= price ";}
		$range_arr = getbox_name('range','rent_7');
		$range_name=$range_arr[$range];
		$lstaddr.= "<i>".$range_name."<a href=\"list".deal_str($lst,'p').".html\"></a></i>";
	}

	if(!empty($hu))
	{
		$lst.= "-x".$hu;
		$condition.=" and `room`=$hu";
		if($hu==1)
		{
			$type_name="一室";
		}
		elseif($hu==2)
		{
			$type_name="二室";
		}
		elseif($hu==3)
		{
			$type_name="三室";
		}
		elseif($hu==4)
		{
			$type_name="四室";
		}
		else
		{
			$type_name="其他";
		}
		$lstaddr.= "<i>".$type_name."<a href=\"list".deal_str($lst,'x').".html\"></a></i>";
	}
	
	if(!empty($zhuanxiu))
	{
		$lst.= "-f".$fitment;
		$condition .= " AND FIND_IN_SET('$zhuanxiu',`zhuanxiu`)" ;
		$zhuanxiu_arr = getbox_name('zhuanxiu','rent_7');
		$zhuanxiu_name=$zhuanxiu_arr[$zhuanxiu];
		$lstaddr.= "<i>".$zhuanxiu_name."<a href=\"list".deal_str($lst,'f').".html\"></a></i>";
	}



	if(!empty($source))
	{
		$lst.= "-u".$source;
		if($source==1)
		{
			$source_name = "个人";
			$condition.=" and typeid=0";
		}
		elseif($source==2)
		{
			$source_name = "中介";
			$condition.=" and typeid=1";
		}
	$lstaddr.= "<i>".$source_name."<a href=\"list".deal_str($lst,'u').".html\"></a></i>";
	}


	if(!empty($area))
	{
		$lst.= "-c".$area;
		if($area==1)
		{   $condition.=' AND houseearm<40';
			$area_name="40平米以下";
		}
		elseif($area==2)
		{   $condition.=" AND houseearm>40  AND houseearm<60";
			$area_name="40-60平米";
		}
		elseif($area==3)
		{   $condition.=' AND 60<=houseearm AND houseearm<80';
			$area_name="60-80平米";
		}
		elseif($area==4)
		{   $condition.=' AND 80<=houseearm AND houseearm<100';
			$area_name="80-100平米";
		}
		elseif($area==5)
		{	$condition.=' AND 100<=houseearm AND houseearm<120';
			$area_name="100-120平米";
		}
		elseif($area==6)
		{   $condition.=' AND 120<=houseearm AND houseearm<150';
			$area_name="120-150平米";
		}
		elseif($area==7)
		{   $condition.=' AND 150<=houseearm';
			$area_name="150平米以上";
		}
		$lstaddr.= "<i>".$area_name."<a href=\"list".deal_str($lst,'c').".html\"></a></i>";
	}
	
	if(!empty($zu))
	
	{
		$condition.=" and `renttype`=$zu";
		$lst.= "-z".$zu;
		}
		if(!empty($ord))
	{  
		if($ord=='1')
		{
		    $order = " order by addtime desc";	
		}
		if($ord=='2')
		{
			$order = " order by price ASC";
		}
		if($ord=='3')
		{
			$order = " order by price desc";
		}
		
		elseif($ord=='4')
		{
			 $order = " order by houseearm desc";
		}
		elseif($ord=='5')
		{
			$order = " order by houseearm ASC";
		}
	
		$lst.= "-n".$ord;
		
	}
	else
	{
		$order = " order by ".$MOD['order'];
		$order_name = "默认排序";
		
	}
	
}
	$areaids=$_GET['areaid'];
if($AJ['city'] && empty($areaid)){
	
	
	$ARE = $AREA[$cityid];
	
	$condition .= $ARE['arrchildid'] ? " and areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";}
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition", 'CACHE');
	$items = $r['num'];
	
	if($kw){
		
		$pages = mobile_pages($items, $page, $pagesize);}
	
	else
	{$page = max($page,1);
     $pagesize = $MOD['pagesize'];
     $offset = ($page-1)*$pagesize;
		$pages =mobilepages($items, $page,7, $lst,$pagesize);
		}
		
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
include AJ_ROOT.'/include/seo.inc.php';

?>