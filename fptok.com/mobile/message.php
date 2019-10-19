<?php
/*
	 
	This is NOT a freeware, use is subject to license.txt
*/
$moduleid = 2;
require 'common.inc.php';
require 'user/extend.func.php';
require 'user/lang.inc.php';
mobile_login();
switch($action) {
	case 'send':
		if(isset($_POST['ok'])) {
			require AJ_ROOT.'/module/member/message.class.php';
			require AJ_ROOT.'/include/post.func.php';
			$do = new message;
			$message = array();
			$message['typeid'] = 0;
			$message['touser'] = input_trim($touser);
			$message['title'] = convert($title, 'UTF-8', AJ_CHARSET);
			$message['content'] = convert($content, 'UTF-8', AJ_CHARSET);
			if($do->send($message)) {
				dtexit('ok');
			} else {
				dtexit($do->errmsg);
			}
		} else {
			$touser = isset($touser) ? trim($touser) : '';
			$title = isset($title) ? trim(decrypt($title, AJ_KEY.'SEND')) : '';
			$content = isset($content) ? trim(decrypt($content, AJ_KEY.'SEND')) : '';
			$typeid = isset($typeid) ? intval($typeid) : 0;
			$head_name = $L['message_send'];
			$head_title = $head_name.$AJ['seo_delimiter'].$head_title;
		}
	break;
	case 'delete':
		if($itemid) {
			require AJ_ROOT.'/include/post.func.php';
			require AJ_ROOT.'/module/member/message.class.php';
			$do = new message;			
			$do->itemid = $itemid;
			$do->delete();
			dtexit('ok', 'message.php?reload='.$AJ_TIME);
		} else {			
			dtexit($L['message_id']);
		}
	break;
	case 'show':
		if($itemid) {
			require AJ_ROOT.'/module/member/message.class.php';
			$do = new message;
			$do->itemid = $itemid;
			$message = $do->get_one();
			if(!$message) mobile_msg($L['msg_no_right']);
			extract($message);
			if($status == 4 || $status == 3) {
				if($touser != $_username) mobile_msg($L['msg_no_right']);
				if(!$isread) {
					$do->read();
					if($feedback) $do->feedback();
				}
			} else if($status == 2 || $status == 1) {
				if($fromuser != $_username) mobile_msg($L['msg_no_right']);
			}
			$adddate = timetodate($addtime, 5);
			$head_name = $L['message_detail'];
			$head_title = $title.$AJ['seo_delimiter'].$L['message_title'].$AJ['seo_delimiter'].$head_title;
		} else {			
			mobile_msg($L['not_message']);
		}
	break;
	default:
		$TYPE = $L['message_type'];
		$typeid = isset($typeid) ? intval($typeid) : -1;
		$lists = array();
		if($_userid) {
			$condition = "touser='$_username' AND status=3";
			if($typeid != -1) $condition .= " AND typeid=$typeid";
			if($keyword) $condition .= " AND title LIKE '%$keyword%'";
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$AJ_PRE}message WHERE $condition");
			$pages = mobile_pages($r['num'], $page, $pagesize);
			$result = $db->query("SELECT * FROM {$AJ_PRE}message WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
			while($r = $db->fetch_array($result)) {
				$r['adddate'] = timetodate($r['addtime'], 'Y/m/d H:i');
				$r['type'] = $TYPE[$r['typeid']];
				$lists[] = $r;
			}
		}
		$head_name = $kw ? $L['message_search'] : $L['message_title'];
		$head_title = $L['message_title'].$AJ['seo_delimiter'].$head_title;
	break;
}
$foot = 'my';
include template('message', 'mobile');
if(AJ_CHARSET != 'UTF-8') toutf8();
?>