<?php 
defined('IN_AIJIACMS') or exit('Access Denied');
login();
require AJ_ROOT.'/module/'.$module.'/common.inc.php';

require AJ_ROOT.'/include/post.func.php';

require MD_ROOT.'/price.class.php';
$sfields = array( '会员', 'IP', '编辑', '备注');
	$dfields = array( 'username', 'ip', 'editor', 'note');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '更新时间降序', '更新时间升序', '报价降序', '报价升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC', 'price DESC', 'price ASC');
	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
	if($itemid) $condition .= " AND itemid=$itemid";
	 if($pid) $condition .= " AND pid=$pid";
$do = new price();
switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file.'&pid='.$post['pid']);
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				isset($$v) or $$v = '';
			}
			$username = $_username;
			$status = 3;
			$addtime = timetodate($AJ_TIME);
			$menuid = 0;
			include template('price_edit', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->pass($post)) {
				$do->edit($post);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			$addtime = timetodate($addtime);
			$menuid = 1;
			include template('price_edit', $module);
		}
	break;
	case 'delete':
		$itemid or msg('请选择信息');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	default:
		$item = 0;
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		
		$menuid = 1;
		include template('price', $module);
	break;
	}
?>