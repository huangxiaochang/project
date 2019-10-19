<?php 
defined('IN_AIJIACMS') or exit('Access Denied');
require AJ_ROOT.'/module/'.$module.'/common.inc.php';
mobile_login();
$MG['photo_limit'] > -1 or dlayerm(lang('message->without_permission_and_upgrade'), 'goback');
require AJ_ROOT.'/include/post.func.php';
include load($module.'.lang');
include load('my.lang');
require MD_ROOT.'/photo.class.php';
$do = new photo($moduleid);

if(in_array($action, array('add', 'edit'))) {
	$FD = cache_read('fields-'.substr($table, strlen($AJ_PRE)).'.php');
	if($FD) require AJ_ROOT.'/include/fields.func.php';
	isset($post_fields) or $post_fields = array();
	$CP = $MOD['cat_property'];
	if($CP) require AJ_ROOT.'/include/property.func.php';
	isset($post_ppt) or $post_ppt = array();
}
$sql = $_userid ? "username='$_username'" : "ip='$AJ_IP'";
$limit_used = $limit_free = $need_password = $need_captcha = $need_question = $fee_add = 0;
if(in_array($action, array('', 'add'))) {
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql AND status>1");
	$limit_used = $r['num'];
	$limit_free = $MG['photo_limit'] > $limit_used ? $MG['photo_limit'] - $limit_used : 0;
}
switch($action) {
	case 'add':
	    $head_name = $MOD['name'].$M['add_mobile'];
		if($MG['photo_limit'] && $limit_used >= $MG['photo_limit']) dlayerm(lang($L['info_limit'], array($MG[$MOD['module'].'_limit'], $limit_used)));

		if($MG['day_limit']) {
			$today = $today_endtime - 86400;
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql AND addtime>$today");
			if($r && $r['num'] >= $MG['day_limit']) dlayerm(lang($L['day_limit'], array($MG['day_limit'])));
		}

		if($MG['photo_free_limit'] >= 0) {
			$fee_add = ($MOD['fee_add'] && (!$MOD['fee_mode'] || !$MG['fee_mode']) && $limit_used >= $MG['photo_free_limit'] && $_userid) ? dround($MOD['fee_add']) : 0;
		} else {
			$fee_add = 0;
		}
		$fee_currency = $MOD['fee_currency'];
		$fee_unit = $fee_currency == 'money' ? $AJ['money_unit'] : $AJ['credit_unit'];
		$need_password = $fee_add && $fee_currency == 'money';
		$need_captcha = $MOD['captcha_add'] == 2 ? $MG['captcha'] : $MOD['captcha_add'];
		$need_question = $MOD['question_add'] == 2 ? $MG['question'] : $MOD['question_add'];
		$could_color = check_group($_groupid, $MOD['group_color']) && $MOD['credit_color'] && $_userid;

		if(isset($_POST['ok'])) {
		$thumbs= explode(",", $thumbs);
		 $post['thumb'] = $thumbs[0];
		 $post['thumb1'] = $thumbs[1];
		 $post['thumb2'] = $thumbs[2];
		 $photoImgs= explode(",", $photoImgs);//count($photoImgs)

		 //dtexit($table_item);
			foreach($post as $k=>$v) {
				$post[$k] = convert(input_trim($v), 'UTF-8', AJ_CHARSET);
			}
			if($fee_add && $fee_add > ($fee_currency == 'money' ? $_money : $_credit)) dtexit($L['balance_lack']);
			if($need_password && !is_payword($_username, $password)) dtexit($L['error_payword']);

			if(!$_userid) {
				if(strlen($post['company']) < 4) dtexit($L['type_company']);
				if($AREA) {
					if(!isset($AREA[$post['areaid']])) dtexit($L['type_area']);
				} else {
					if(!$post['areaid']) dtexit($L['type_area']);
				}
				if(strlen($post['truename']) < 4) dtexit($L['type_truename']);
				if(strlen($post['mobile']) < 7) dtexit($L['type_mobile']);
			}

			if($MG['add_limit']) {
				$last = $db->get_one("SELECT addtime FROM {$table} WHERE $sql ORDER BY itemid DESC");
				if($last && $AJ_TIME - $last['addtime'] < $MG['add_limit']) dtexit(lang($L['add_limit'], array($MG['add_limit'])));
			}
			$msg = captcha($captcha, $need_captcha, true);
			//if($msg) dalert($msg);
			$msg = question($answer, $need_question, true);
			//if($msg) dalert($msg);
			if(isset($post['islink'])) unset($post['islink']);
			if($do->pass($post)) {
				$CAT = get_cat($post['catid']);
				if(!$CAT || !check_group($_groupid, $CAT['group_add'])) dtexit(lang($L['group_add'], array($CAT['catname'])));
				$post['addtime'] = $post['level'] = $post['fee'] = 0;
				$post['style'] = $post['template'] = $post['note'] = $post['filepath'] = '';
				$need_check =  $MOD['check_add'] == 2 ? $MG['check'] : $MOD['check_add'];
				$post['status'] = get_status(3, $need_check);
				$post['hits'] = 0;
				$post['save_remotepic'] = $MOD['save_remotepic'] ? 1 : 0;
				$post['clear_link'] = $MOD['clear_link'] ? 1 : 0;
				$post['username'] = $_username;
				$post['areaid'] = $cityid;
				if($FD) mfields_check($post_fields);
				if($CP) mproperty_check($post_ppt);
			    $session = new dsession();
			    $_SESSION['uploads'] = array();
				$do->add($post);
				
				foreach($photoImgs as $v) {
					$db->query("INSERT INTO {$table_item} (item,thumb) VALUES ('$do->itemid', '$v')");
					}

				if($FD) fields_update($post_fields, $table, $do->itemid);
				if($CP) property_update($post_ppt, $moduleid, $post['catid'], $do->itemid);				
				if($could_color && $color && $_credit > $MOD['credit_color']) {
					$post['style'] = $color;
					credit_add($_username, -$MOD['credit_color']);
					credit_record($_username, -$MOD['credit_color'], 'system', $L['title_color'], '['.$MOD['name'].']'.$post['title']);
				}
				if($MOD['show_html'] && $post['status'] > 2) $do->tohtml($do->itemid);
				if($fee_add) {
					if($fee_currency == 'money') {
						money_add($_username, -$fee_add);
						money_record($_username, -$fee_add, $L['in_site'], 'system', lang($L['credit_record_add'], array($MOD['name'])), 'ID:'.$do->itemid);
					} else {
						credit_add($_username, -$fee_add);
						credit_record($_username, -$fee_add, 'system', lang($L['credit_record_add'], array($MOD['name'])), 'ID:'.$do->itemid);
					}
				}
				
				$msg = $post['status'] == 2 ? $L['success_check'] : $L['success_add'];
				$js = '';
				if(isset($post['sync_sina']) && $post['sync_sina']) $js .= sync_weibo('sina', $moduleid, $do->itemid);
				if(isset($post['sync_qq']) && $post['sync_qq']) $js .= sync_weibo('qq', $moduleid, $do->itemid);
				if($_userid) {
					set_cookie('dmsg', $msg);
					$forward = $MODULE[2]['linkurl'].$AJ['file_my'].'?mid='.$mid.'&status='.$post['status'];
					$msg = '';
				} else {
					$forward = $MODULE[2]['linkurl'].$AJ['file_my'].'?mid='.$mid.'&action=add';
				}
				$js .= 'window.onload=function(){parent.window.location="'.$forward.'";}';

				dtexit('ok',$do->itemid);
				dtexit($msg, '', $js);
			} else {
				dtexit($do->errmsg, '', ($need_captcha ? reload_captcha() : '').($need_question ? reload_question() : ''));
			}
		} else {
			if($itemid) {
				$MG['copy'] && $_userid or dalert(lang('message->without_permission_and_upgrade'), 'goback');

				$do->itemid = $itemid;
				$item = $do->get_one();
				if(!$item || $item['username'] != $_username) message();
				extract($item);
				$thumb = '';
				$totime = $totime > $AJ_TIME ? timetodate($totime, 3) : '';
			} else {
				foreach($do->fields as $v) {
					$$v = '';
				}
				$open = 3;
				$content = '';
				$catid = 0;
				$areaid = $cityid;
			}
			$item = array();
		}
	break;
	case 'edit':
	    $head_name = $MOD['name'].$M['edit_mobile'];
		$itemid or dlayerm($L['select_info'],'goback');
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) dlayerm($L['select_info'],'goback');

		if($MG['edit_limit'] < 0) dlayerm($L['edit_refuse']);
		if($MG['edit_limit'] && $AJ_TIME - $item['addtime'] > $MG['edit_limit']*86400) dlayerm(lang($L['edit_limit'], array($MG['edit_limit'])));

		if(isset($_POST['ok'])) {
		 $thumbs= explode(",", $thumbs);
		 $post['thumb'] = $thumbs[0];
		 $post['thumb1'] = $thumbs[1];
		 $post['thumb2'] = $thumbs[2];
			foreach($post as $k=>$v) {
				$post[$k] = convert(input_trim($v), 'UTF-8', AJ_CHARSET);
			}
			if($do->pass($post)) {
				$CAT = get_cat($post['catid']);
				if(!$CAT || !check_group($_groupid, $CAT['group_add'])) dtexit(lang($L['group_add'], array($CAT['catname'])));
				$post['addtime'] = timetodate($item['addtime']);
				$post['level'] = $item['level'];
				$post['style'] = addslashes($item['style']);
				$post['template'] = addslashes($item['template']);
				$post['filepath'] = addslashes($item['filepath']);
				$post['note'] = addslashes($item['note']);
				$need_check =  $MOD['check_add'] == 2 ? $MG['check'] : $MOD['check_add'];
				$post['status'] = get_status($item['status'], $need_check);
				$post['hits'] = $item['hits'];
				$post['save_remotepic'] = $MOD['save_remotepic'] ? 1 : 0;
				$post['clear_link'] = $MOD['clear_link'] ? 1 : 0;
				$post['username'] = $_username;
				if($FD) mfields_check($post_fields);
				if($CP) mproperty_check($post_ppt);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				if($CP) property_update($post_ppt, $moduleid, $post['catid'], $do->itemid);
				$do->edit($post);
				set_cookie('dmsg', $L['success_edit']);
				dtexit('ok');
				dtexit('', '', 'parent.window.location="'.$forward.'"');
			} else {
				dtexit($do->errmsg);
			}
		} else {
			extract($item);
		}
	break;
	case 'upsuccess':
		$itemid or dtexit($L['select_info'],'goback');
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) dtexit($L['select_info'],'goback');
		$db->query("INSERT INTO {$table_item} (item,thumb) VALUES ('$itemid', '$thumb')");
		$do->itemid = $do->db->insert_id();
		exit('{"error":"ok","itemid":"'.$do->itemid.'","url":"'.$thumb.'"}');
	break;
	case 'item':
	    $head_name = $M['mobile_photo'].$M['add_mobile'];
		$foot = '';
		$itemid or dtexit($L['select_info'],'goback');
		$do->itemid = $itemid;
		
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) dtexit($L['select_info'],'goback');
		if(isset($update)) {
		    $itemthumb = $post['itemthumb'];
		    //dtexit($itemthumb);
		    $db->query("UPDATE {$table} SET thumb='$itemthumb' WHERE itemid=$itemid");
		
			$thumb = '';
			$do->item_update($post);			
			$need_check =  $MOD['check_add'] == 2 ? $MG['check'] : $MOD['check_add'];
			$status = get_status($item['status'], $need_check);
			if($status != $item['status']) $db->query("UPDATE {$table} SET status=$status WHERE itemid=$itemid");
			$session = new dsession();
			$_SESSION['uploads'] = array();
			if($MOD['show_html']) tohtml('show', $module);
			dtexit('ok', '?mid='.$mid.'&action='.$action.'&itemid='.$itemid);
		} else {
			$lists = $do->item_list("item=$itemid");
			$item = $do->get_one();
			if($items != $item['items']) $db->query("UPDATE {$table} SET items=$items WHERE itemid=$itemid");
		}
	break;
	case 'item_delete':
		$itemid or dtexit($L['select_info'],'goback');
		$item = $db->get_one("SELECT p.username FROM {$table} p,{$table_item} i WHERE p.itemid=i.item AND i.itemid=$itemid");
		if(!$item || $item['username'] != $_username) dtexit($L['select_info'],'goback');
		$do->item_delete($itemid);
		dtexit('ok', $forward);
	break;
	case 'delete':
	    $MG['delete'] or dtexit($M['delect_noyes']);
		$itemid or dtexit('no itemid');
		$itemids = is_array($itemid) ? $itemid : array($itemid);
		foreach($itemids as $itemid) {
			$do->itemid = $itemid;
			$item = $db->get_one("SELECT username FROM {$table} WHERE itemid=$itemid");
			if(!$item || $item['username'] != $_username) dtexit('no itemid');
			$do->recycle($itemid);
		}
		dtexit('ok');
		dmsg($L['success_delete'], $forward);
	break;
	default:
	    $head_name = $MOD['name'].$M['manage_mobile'];
		$status = isset($status) ? intval($status) : 3;
		in_array($status, array(1, 2, 3, 4)) or $status = 3;
		$condition = "username='$_username'";
		$condition .= " AND status=$status";
		if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
		if($catid) $condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
		$timetype = strpos($MOD['order'], 'add') !== false ? 'add' : '';
		$lists = $do->get_list($condition, $MOD['order']);
		foreach($lists as $k=>$v) {
			$lists[$k]['mycat'] = $v['mycatid'] && isset($MTYPE[$v['mycatid']]) ? set_style($MTYPE[$v['mycatid']]['typename'], $MTYPE[$v['mycatid']]['style']) : $L['type_default'];
		}
	break;
}
if($_userid) {
	$nums = array();
	for($i = 1; $i < 5; $i++) {
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE username='$_username' AND status=$i");
		$nums[$i] = $r['num'];
	}
}
//$foot = 'my';
include template('my_'.$module, 'mobile');
if(AJ_CHARSET != 'UTF-8') toutf8();
?>