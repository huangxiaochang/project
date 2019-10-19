<?php
defined('IN_AIJIACMS') or exit('Access Denied');


require MD_ROOT.'/hongbao.class.php';
$do = new hongbao;
$menus = array (
   // array('客户记录', '?moduleid='.$moduleid.'&file='.$file),
    array('待受理', '?moduleid='.$moduleid.'&file='.$file),
    array('未领取', '?moduleid='.$moduleid.'&file='.$file.'&action=wlq'),
	array('过期', '?moduleid='.$moduleid.'&file='.$file.'&action=guoqi'),
	array('已领取', '?moduleid='.$moduleid.'&file='.$file.'&action=ylq'),
	
);
$_status = array(
    '<span style="color:#0000FF;">待受理</span>',
	'<span style="color:#0000FF;">未领取</span>',
	'<span style="color:#FF6600;">过期</span>',
	'<span style="color:#FF0000;">已领取</span>',
	
);
if(in_array($action, array('', 'wlq', 'ylq','guoqi'))) {
	$sfields = array('姓名', '电话');
	$dfields = array('truename',  'mobile');
	$sorder  = array('结果排序方式', '时间降序', '时间升序', '受理时间降序', '受理时间升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($pid) $condition .= " AND house=$pid";
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
}
$menuon = array('0', '1', '2', '3');

	 
	

switch($action) {
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->edit($post)) {
				dmsg('操作成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			$user = $username ? userinfo($username) : array();
			$addtime = timetodate($addtime);
			$edittime = timetodate($edittime);
		
			
			include tpl('hongbao_edit', $module);
		}
	break;
	case 'delete':
		$itemid or msg('请选择记录');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'wlq':
		$status = 1;
		$lists = $do->get_list('status='.$status.$condition, $dorder[$order]);
		include tpl('hongbao', $module);
	break;
	
	case 'guoqi':
		$status = 2;
		$lists = $do->get_list('status='.$status.$condition, $dorder[$order]);
		include tpl('hongbao', $module);
	break;
	case 'ylq':
		$status = 3;
		$lists = $do->get_list('status='.$status.$condition, $dorder[$order]);
		include tpl('hongbao', $module);
	break;
	default:
		$status = 0;
		$lists = $do->get_list('status='.$status.$condition, $dorder[$order]);
		include tpl('hongbao', $module);
	break;
}
?>