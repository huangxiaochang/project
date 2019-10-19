<?php
require '../../../common.inc.ajax.php';


$act	= $_REQUEST['act'];
$Mid	= $_REQUEST['mid'];
$Aid	= $_REQUEST['aid'];
$Page	= empty($_REQUEST['page']) ? 1 : $_REQUEST['page'];
$Size	= empty($_REQUEST['size']) ? 10 : $_REQUEST['size'];

// echo $act; exit;
if($act == 'Index'){
	$res = getIndexRoom();
	echo json_encode($res);exit;
}else if($act == 'getBannerList'){
	$result = $db->query('SELECT aid,image_src,title,areaid FROM '.$AJ_PRE.'ad WHERE pid = 14');
	while($a = $db->fetch_array($result)) {
		$list[$a['aid']] = $a;
	}
	echo json_encode($list);exit;
}else if($act == 'getRoomList'){
	$res = getRoomList($Mid,$Page,$Size);
	echo json_encode($res);exit;
}else if($act == 'getRoomInfo'){
//	$sql = 'SELECT * FROM '.$AJ_PRE.'module WHERE moduleid = '.$Aid;
//	$res = getModule($sql);
//	$res = getModuleTable($Mid);
//	$tab = getModuleTable($Mid);
	$row = getRoomInfo($Mid,$Aid);
//	echo $row;exit;
	echo json_encode($row);exit;
}


function getRoomInfo($Mid,$Aid){
	global $db,$AJ_PRE;
	$tab = getModuleTable($Mid);
	$Thumb = getThumbImg($Mid,$Aid);
	$row = $db->get_one('SELECT * FROM '.$AJ_PRE.$tab.' WHERE itemid = '.$Aid);
	$info['editdate'] = $row['editdate'];
	$info['price'] = (empty($row['price']) ? '面议' : $row['price'].'万元');
	$info['price2'] = (floor($row['price'] * 10000 / $row['houseearm']).'元/㎡');
	$info['hx'] = (empty($row['room']) ? '' : $row['price'].'室').(empty($row['hall']) ? '' : $row['hall'].'厅').(empty($row['toilet']) ? '' : $row['toilet'].'卫');
	$info['houseearm'] = $row['houseearm'].'㎡';
	$info['cx'] = getbox_diaoval('toward','checkbox',$row['toward'],$tab);
	$info['zx'] = getbox_diaoval('fix','checkbox',$row['fix'],$tab);
	$info['lc'] = $row['floor1'].'/'.$row['floor2'];
	$info['fyts'] = $row['fyts'];
	$info['areaid'] = area_pos($row['areaid'], ' ');
	$info['cqxz'] = cat_pos2($row['catid']);
	$info['houseyear'] = $row['houseyear'];
	$info['title'] = $row['title'];
	$info['address'] = $row['address'];
	$info['housename'] = $row['housename'];
	$info['bus'] = $row['bus'];
	$info['peitao'] = $row['peitao'];
	$info['thumb'] = $Thumb;
	return $info;
}


function getRoomList($Mid,$Page,$Size){
	global $db,$AJ_PRE;
	$tab = getModuleTable($Mid);
	$res['info'] = getModuleInfo($Mid);
	$Page = ($Page == 1) ? '0,20' : 10 + (($Page - 1) * $Size).','.$Size;
	$result = $db->query('SELECT * FROM '.$AJ_PRE.$tab.' ORDER BY addtime desc Limit '.$Page);
	$i = 0;
	while($val = $db->fetch_array($result)) {
		$i ++;
		$res['list'][$i]['itemid'] = $val['itemid'];
		$res['list'][$i]['title'] = $val['title'];
		$res['list'][$i]['thumb'] = imgurl($val['thumb']);
		$res['list'][$i]['areaid'] = area_poss($val['areaid'], '');
		$res['list'][$i]['housename'] = $val['housename'];
		$res['list'][$i]['price'] = (!empty($val['price'])) ? $val['price'].'万' : '面议';
		$res['list'][$i]['buildtype'] = getbox_diaoval('buildtype','checkbox',$val['buildtype'],$tab);
		$res['list'][$i]['catid'] = search_cats($val['catid'], '5');
		$res['list'][$i]['size'] = ((!empty($val['room'])) ? $val['room'].'室' : '').((!empty($val['hall'])) ? $val['hall'].'厅' : '').$val['houseearm'].'㎡';

	}
  	return $res;
}


function getIndexRoom(){
	global $db,$AJ_PRE;

	$result = $db->query('SELECT * FROM '.$AJ_PRE.'module WHERE moduleid in(6,5,7,13,19) ORDER BY listorder ASC');
	while($a = $db->fetch_array($result)) {
		$tab[$a['moduleid']]['tab'] = $a['module'].'_'.$a['moduleid'];
		$tab[$a['moduleid']]['name'] = $a['name'];
		$tab[$a['moduleid']]['moduleid'] = $a['moduleid'];
	}
	foreach($tab as $k => $v){
		$result = array();
		$result = $db->query('SELECT * FROM '.$AJ_PRE.$v['tab'].' ORDER BY addtime desc Limit 8');
		$list[$k]['name'] = $v['name'];
		$list[$k]['moduleid'] = $v['moduleid'];
		while($val = $db->fetch_array($result)) {
			$list[$k]['list'][$val['itemid']]['itemid'] = $val['itemid'];
			$list[$k]['list'][$val['itemid']]['title'] = $val['title'];
			$list[$k]['list'][$val['itemid']]['moduleid'] = $v['moduleid'];
			$list[$k]['list'][$val['itemid']]['thumb'] = imgurl($val['thumb']);
			$list[$k]['list'][$val['itemid']]['areaid'] = area_poss($val['areaid'], '');
			$list[$k]['list'][$val['itemid']]['housename'] = $val['housename'];
			$list[$k]['list'][$val['itemid']]['price'] = (!empty($val['price'])) ? $val['price'].'万' : '面议';
			$list[$k]['list'][$val['itemid']]['buildtype'] = getbox_diaoval('buildtype','checkbox',$val['buildtype'],$tab[5]);
			$list[$k]['list'][$val['itemid']]['catid'] = search_cats($val['catid'], '5');
			$list[$k]['list'][$val['itemid']]['size'] = ((!empty($val['room'])) ? $val['room'].'室' : '').((!empty($val['hall'])) ? $val['hall'].'厅' : '').$val['houseearm'].'㎡';
		}
	}
  	return $list;
}

function getThumbImg($Mid,$Aid){
	global $db,$AJ_PRE;
	$sql = 'SELECT * FROM '.$AJ_PRE.'house_pic WHERE item = '.$Aid.' AND mid = '.$Mid.' ORDER BY listorder desc';
	$result = $db->query('SELECT itemid,thumb FROM '.$AJ_PRE.'house_pic WHERE item = '.$Aid.' AND mid = '.$Mid.' ORDER BY listorder desc');
	while($val = $db->fetch_array($result)) {
		$ThumbImg[] = $val;
	}
	return $ThumbImg; 
}

function getModuleTable($Mid){
	global $db,$AJ_PRE;
	$sql = 'SELECT * FROM '.$AJ_PRE.'module WHERE moduleid = '.$Mid;
	$row = $db->get_one($sql);
	$tab = $row['module'].'_'.$row['moduleid'];
	return $tab; 
}

function getModuleInfo($Mid){
	global $db,$AJ_PRE;
	$sql = 'SELECT * FROM '.$AJ_PRE.'module WHERE moduleid = '.$Mid;
	$row = $db->get_one($sql);
//	$tab = $row['module'].'_'.$row['moduleid'];
	return $row; 
}
?>
