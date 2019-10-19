<?php
defined('AJ_ADMIN') or exit('Access Denied');
require MD_ROOT.'/qiandao.class.php';
$do = new qiandao();
$menus = array (
    array('签到管理', '?moduleid='.$moduleid.'&file='.$file),
);
if(in_array($action, array(''))) {
	$sfields = array('按条件', '时间', '收入', '会员名');
	$dfields = array('title', 'addtime', 'feeadd', 'username');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($username) $condition .= " AND username='$username'";
}
switch($action) {
	case 'delete':
		$itemid or msg('请选择证书');
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	default:
		$lists = $do->get_list('userid>0'.$condition, $dorder[$order]);
		$menuid = 1;
		include tpl('qiandao', $module);
	break;
}
?>