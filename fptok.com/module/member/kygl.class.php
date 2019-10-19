<?php 
defined('IN_AIJIACMS') or exit('Access Denied');
class kygl {
	var $userid;
	var $db;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		global $db, $AJ_PRE;
		$this->table = $AJ_PRE.'kygl';
		$this->db = &$db;
		$this->fields = array('username','name','mobile','phone', 'ke','address','email','kyzt','kylx','kydj','wttime','biaoqian','sujjr','beizhu','shi','ting','wei','chu','yangtai','chaoxiang','fangling','mianji','jiage','louceng','zsyq','yongtu','wylx','quxian','ddyq','fwsb','zbhj');
    }

    function kygl() {
		$this->__construct();
    }

	function pass($post) {
		global $AJ_TIME, $L;
		if(!is_array($post)) return false;
		if(!$post['name']) return $this->_($L['yanzheng']='请填写姓名');
		if(!$post['mobile']) return $this->_($L['yanzheng']='请填写手机号码');
		if(!$post['address']) return $this->_($L['yanzheng']='请填写地址');
		if(!$post['email']) return $this->_($L['yanzheng']='请填写邮箱');
		if(!$post['fangling']) return $this->_($L['yanzheng']='请填写房龄');
		if(!$post['mianji']) return $this->_($L['yanzheng']='请填写面积');
		if(!$post['jiage']) return $this->_($L['yanzheng']='请填写价格');
		if(!$post['louceng']) return $this->_($L['yanzheng']='请填写楼层');
		if(!$post['quxian']) return $this->_($L['yanzheng']='请填写区县');
		if(!$post['ddyq']) return $this->_($L['yanzheng']='请填写地段要求');

		// if(!$post['authority']) return $this->_($L['honor_pass_authority']);
		// if(!$post['thumb']) return $this->_($L['honor_pass_thumb']);
		// if(!$post['fromtime'] || !is_date($post['fromtime'])) return $this->_($L['honor_pass_fromdate']);
		// if(datetotime($post['fromtime'].' 00:00:00') > $AJ_TIME) return $this->_($L['honor_pass_fromdate_error']);
		// if($post['totime']) {
		// 	if(!is_date($post['totime'])) return $this->_($L['honor_pass_todate']);
		// 	if(datetotime($post['totime'].' 23:59:59') < $AJ_TIME) return $this->_($L['honor_pass_todate_error']);
		// }
		// if(AJ_MAX_LEN && strlen($post['content']) > AJ_MAX_LEN) return $this->_(lang('message->pass_max'));
		return true;
	}

	function set($post) {
		global $MOD, $AJ_TIME, $_username, $_userid;
		$post['addtime'] = (isset($post['addtime']) && $post['addtime']) ? datetotime($post['addtime']) : $AJ_TIME;
		$post['edittime'] = $AJ_TIME;
		$post['fromtime'] = datetotime($post['fromtime'].' 00:00:00');
		$post['totime'] = $post['totime'] ? datetotime($post['totime'].' 23:59:59') : 0;
		clear_upload($post['content'].$post['thumb']);
		if($this->userid) {
			$post['editor'] = $_username;
			$new = $post['content'];
			if($post['thumb']) $new .= '<img src="'.$post['thumb'].'">';
			$r = $this->get_one();
			$old = $r['content'];
			if($r['thumb']) $old .= '<img src="'.$r['thumb'].'">';
			delete_diff($new, $old);
		}
		$content = $post['content'];
		unset($post['content']);
		$post = dhtmlspecialchars($post);
		$post['content'] = dsafe($content);
		if($MOD['credit_clear'] || $MOD['credit_save']) {
			$post['content'] = stripslashes($post['content']);
			$post['content'] = save_local($post['content']);
			if($MOD['credit_clear']) $post['content'] = clear_link($post['content']);
			if($MOD['credit_save']) $post['content'] = save_remote($post['content']);
			$post['content'] = addslashes($post['content']);
		}
		return array_map("trim", $post);
	}

	function get_one() {
        return $this->db->get_one("SELECT * FROM {$this->table} WHERE userid='$this->userid'");
	}

	function get_list($condition = '', $order = 'userid DESC') {
		global $MOD, $pages, $page, $pagesize, $offset, $L, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);

		if($items < 1) return array();

		$lists = array();
		$result = $this->db->query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");

		while($r = $this->db->fetch_array($result)) {
			// $r['adddate'] = timetodate($r['addtime'], 5);
			// $r['editdate'] = timetodate($r['edittime'], 5);
			// $r['fromdate'] = timetodate($r['fromtime'], 3);
			// $r['todate'] = $r['totime'] ? timetodate($r['totime'], 3) : $L['forever'];
			// $r['image'] = str_replace('.thumb.'.file_ext($r['thumb']), '', $r['thumb']);
			// $r['title'] = set_style($r['title'], $r['style']);
			$r['mianji'] = explode(',',$r['mianji']);
			$r['jiage'] = explode(',',$r['jiage']);
			$lists[] = $r;
		}
		return $lists;
	}

	function add($post) {
		global $MOD, $L;
		// $post = $this->set($post);
		$sqlk = $sqlv = '';

		//把数组元素组合为字符串
		if(is_array($post['fangling']))$post['fangling'] =implode(',',$post['fangling']);
		if(is_array($post['mianji']))$post['mianji'] =implode(',',$post['mianji']);
		if(is_array($post['jiage']))$post['jiage'] =implode(',',$post['jiage']);
		if(is_array($post['louceng']))$post['louceng'] =implode(',',$post['louceng']);
		if(is_array($post['fwsb']))$post['fwsb'] =implode(',',$post['fwsb']);
		if(is_array($post['zbhj']))$post['zbhj'] =implode(',',$post['zbhj']);

		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		$this->db->query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->userid = $this->db->insert_id();
		// if($post['username'] && $MOD['credit_add_credit']) {
		// 	credit_add($post['username'], $MOD['credit_add_credit']);
		// 	credit_record($post['username'], $MOD['credit_add_credit'], 'system', $L['honor_reward_reason'], 'ID:'.$this->userid);
		// }
		return $this->userid;
	}

	function edit($post) {
		// $post = $this->set($post);
		$sql = '';

		//把数组元素组合为字符串
		if(is_array($post['fangling']))$post['fangling'] =implode(',',$post['fangling']);
		if(is_array($post['mianji']))$post['mianji'] =implode(',',$post['mianji']);
		if(is_array($post['jiage']))$post['jiage'] =implode(',',$post['jiage']);
		if(is_array($post['louceng']))$post['louceng'] =implode(',',$post['louceng']);
		if(is_array($post['fwsb']))$post['fwsb'] =implode(',',$post['fwsb']);
		if(is_array($post['zbhj']))$post['zbhj'] =implode(',',$post['zbhj']);

		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) $sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    $this->db->query("UPDATE {$this->table} SET $sql WHERE userid=$this->userid");
		return true;
	}

	function recycle($userid) {
		if(is_array($userid)) {
			foreach($userid as $v) { $this->recycle($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=0 WHERE userid=$userid");
			return true;
		}		
	}

	function restore($userid) {
		if(is_array($userid)) {
			foreach($userid as $v) { $this->restore($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=3 WHERE userid=$userid");
			return true;
		}		
	}

	function delete($userid, $all = true) {
		global $MOD, $L;
		if(is_array($userid)) {
			foreach($userid as $v) { $this->delete($v); }
		} else {
			$this->userid = $userid;
			// $r = $this->get_one();
			// $userid = get_user($r['username']);
			// if($r['thumb']) delete_upload($r['thumb'], $userid);
			$this->db->query("DELETE FROM {$this->table} WHERE userid=$userid");
			// if($r['username'] && $MOD['credit_del_credit']) {
			// 	credit_add($r['username'], -$MOD['credit_del_credit']);
			// 	credit_record($r['username'], -$MOD['credit_del_credit'], 'system', $L['honor_punish_reason'], 'ID:'.$this->userid);
			// }
		}
	}

	function check($userid) {
		global $_username, $AJ_TIME;
		if(is_array($userid)) {
			foreach($userid as $v) { $this->check($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=3,editor='$_username',edittime=$AJ_TIME WHERE userid=$userid");
			return true;
		}
	}

	function reject($userid) {
		global $_username, $AJ_TIME;
		if(is_array($userid)) {
			foreach($userid as $v) { $this->reject($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=1,editor='$_username',edittime=$AJ_TIME WHERE userid=$userid");
			return true;
		}
	}

	function expire($condition = '') {
		global $AJ_TIME;
		$this->db->query("UPDATE {$this->table} SET status=4 WHERE status=3 AND totime>0 AND totime<$AJ_TIME $condition");
	}

	function clear() {		
		$result = $this->db->query("SELECT userid FROM {$this->table} WHERE status=0");
		while($r = $this->db->fetch_array($result)) {
			$this->delete($r['userid']);
		}
	}
	
	function order($listorder) {
		if(!is_array($listorder)) return false;
		foreach($listorder as $k=>$v) {
			$k = intval($k);
			$v = intval($v);
			$this->db->query("UPDATE {$this->table} SET listorder=$v WHERE userid=$k");
		}
		return true;
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>