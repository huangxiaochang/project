<?php
/*
	[Aijiacms house System] Copyright (c) 2008-2013 Aijiacms.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_AIJIACMS') or exit('Access Denied');
require AJ_ROOT.'/module/member/common.inc.php';
include load($module.'.lang');
include load('my.lang');
$user = userinfo($_username);
if(!check_group($_groupid, $AJ['yuyue_group'])) dalert(lang('message->without_permission_and_upgrade'), 'goback');
$tiaoshu=floor($_credit/$AJ['yycredit_refresh']);
//生成24小时
for ($i = 0; $i < 24; $i++) {
    $hour[] = sprintf('%02d', $i);
}

//生成可预约的分钟
for ($i = 0; $i < 60; $i++) {
    if ($i % $AJ['appTime'] == 0) {
        $minute[] = sprintf('%02d', $i);
    }
}

//生成可提前预约的天数
for ($i = 0; $i <= $AJ['appNum']; $i++) {
    $day = $i+1;
$date[$i] = $i==0?'今天':$day.'天';
}




//获取每个时间段的次数
	$result = $db->query("SELECT count(update_time) AS part,update_time FROM {$AJ_PRE}appolist WHERE update_time >= '" . time() . "' GROUP BY update_time");
	while($r = $db->fetch_array($result)) {
    $datePart['update_time'] = $rel['part'];
}
$action = $_REQUEST['action'];
function house_id_exists($id,$site) {
        global $db;
		return $db->get_one("SELECT appo_list_id FROM {$AJ_PRE}appolist WHERE house_id = $id and appo_site='{$site}'");
	}

if($action == 'appoRefresh'){

	//$itemid or dalert($L['inquiry_itemid'], 'goback');
	$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;


   $ids=explode(",",$itemids);
     foreach ($ids as $id) {
	if(house_id_exists($id,$site))
        { continue;
        }  
		if($site=="sale"){$biao=$AJ_PRE.'sale_5';}
		else {$biao=$AJ_PRE.'rent_7';}
		$house[] = $db->get_one("select * from $biao  where itemid=" . $id);
		
         }
	
    if (empty($house) && !is_array($house)) {
        dalert('您没有选择房源或此房源已经预约过！', $forward);
    }

  
}

elseif($action == 'submitAppo') {
	
if($_credit<$AJ['yycredit_refresh']) dalert('你目前余额积分'.$_credit.',积分余额不足，请购买积分', 'credit.php?action=buy'); 
    foreach ($_POST['appo_house_id'] as $itemid) {
        if ($db->query("SELECT appo_list_id FROM {$AJ_PRE}appolist WHERE house_id = '{$itemid}' AND appo_site='{$site}'")) {
		}else {
            continue;
        }
		
        for ($i=0;$i<=$_POST['appo_date'][$itemid];$i++) {
            $appo_dates = date('Y-m-d', strtotime("+{$i} days"));
            list($year, $month, $day) = explode('-', $appo_dates);

            //获取小时和分钟
            $data = array();
            foreach ($_POST['appo_hours'][$itemid] as $key => $hour) {
                //检测是否正确时间
                if (!is_numeric($hour) && !is_numeric($_POST['appo_minute'][$itemid][$key])) {
                    continue;
                } else {
                    $data[$itemid][] = $hour . '-' . $_POST['appo_minute'][$itemid][$key];
                }
            }



            foreach ($data[$itemid] as $val) {
                list($hour, $minute) = explode('-', $val);
                $time = mktime($hour, $minute, 0, $month, $day, $year);
                //检测时间是否小于现在时间
                if ($time < time()) {
                    if (date('i', time()) < 30) {
                        $time = strtotime('+30 minute', strtotime(date('Y-m-d H:0:0', time())));
                    } else {
                        $time = strtotime('+1 hour', strtotime(date('Y-m-d H:0:0', time())));
                    }
                }


                //检测时间是否大于每个时间点可设置的次数
                while (1) {
                    if (array_key_exists($time, $datePart) && $datePart[$time] >= $AJ['appCountNum']) {
                        $time = strtotime('+' . $AJ['appTime'] . ' minutes ', $time);
                    } else {
                        break;
                    }
                }
             
                    $db->query("INSERT INTO {$AJ_PRE}appolist(username,house_id,house_title,update_time,appo_site)VALUES('$_username','{$itemid}','{$_POST[appo_house_title][$itemid]}','{$time}','{$site}')");
               
        }
    }
   
}
$forward= $MOD['linkurl'].'my.php?mid='.$mid;
dalert('设置预约刷新成功！',  $forward);
}
elseif($action == 'appoShowHouse'){
 $house_id = $_REQUEST['itemid'];
    $result = $db->query("SELECT * FROM {$AJ_PRE}appolist WHERE   house_id = '{$house_id}' AND appo_site='{$site}'");
   while($rel = $db->fetch_array($result)) {
        $house['house_title'] = $rel['house_title'];
        $house['update'][date('Y-m-d',$rel['update_time'])][$rel['appo_list_id']]['day'] = date('Y年m月d日',$rel['update_time']);
        $house['update'][date('Y-m-d',$rel['update_time'])][$rel['appo_list_id']]['hour'] = date('H',$rel['update_time']);
        $house['update'][date('Y-m-d',$rel['update_time'])][$rel['appo_list_id']]['minute'] = date('i',$rel['update_time']);
        $house['update_limit'][date('Y-m-d',$rel['update_time'])] = date('Y年m月d日',$rel['update_time']);
		
    }


    if(!is_array($house)&&empty($house)){
      dalert('您没有选择房源或此房源已经预约过！', $forward);
    }
    $house['house_id'] = $house_id;
	//$housear=$house['update_limit'];
    foreach($house['update_limit'] as $key=>$value){
        for($i = count($house['update'][$key]); $i <5; $i++) {
            $house['update'][$key][$i.'in'] = array('day'=>$value);
        }
    }
}
elseif($action == 'editAppo'&&$_SERVER['REQUEST_METHOD']=='POST'){
   
    $appo_hours = $_POST['appo_hours'];
    $appo_minute = $_POST['appo_minute'];
    $appo_date = $_POST['appo_date'];
    $house_id = $_POST['house_id'];
    $house_title = $_POST['house_title'];
    list($year, $month, $day) = explode('-', $appo_date);
    foreach ($appo_hours[$_POST['appo_date']] as $id => $hour) {
        if (is_numeric($hour) && is_numeric($appo_minute[$_POST['appo_date']][$id])) {
            $time = mktime($hour, $appo_minute[$_POST['appo_date']][$id], 0, $month, $day, $year);
            //检测时间是否小于现在时间
            if ($time < time()) {
                if (date('i', time()) < 30) {
                    $time = strtotime('+30 minute', strtotime(date('Y-m-d H:0:0', time())));
                } else {
                    $time = strtotime('+1 hour', strtotime(date('Y-m-d H:0:0', time())));
                }
            }
            //检测时间是否大于每个时间点可设置的次数
            while (1) {
                if (array_key_exists($time, $datePart) && $datePart[$time] >= $$AJ['appCountNum']) {
                    $time = strtotime('+' . $$AJ['appTime'] . ' minutes ', $time);
                } else {
                    break;
                }
            }
			
            try {
			
                if (!is_numeric($id)) {

                     $db->query("INSERT INTO {$AJ_PRE}appolist(username,house_id,house_title,update_time,appo_site)VALUES('{$_username}','{$house_id}','{$house_title}','{$time}','{$site}')");
                } else {
                     $db->query("UPDATE {$AJ_PRE}appolist SET update_time = '{$time}' WHERE appo_list_id = '{$id}' AND appo_site = '{$site}'");
                }
              
            } catch (Exception $e) {
                dalert('修改失败！', $forward);
            }
        } else {
             $db->query("DELETE FROM {$AJ_PRE}appolist WHERE appo_list_id = '{$id}'");
        }
    }

     dalert('修改成功！', $forward);
}
elseif($action == "appoDel"){
    $house_id = intval($_REQUEST['house_id']);
     $db->query("DELETE FROM {$AJ_PRE}appolist WHERE house_id = '{$house_id}'");
     dalert('取消预约！', $forward);
}
include template('appointment', 'member');
?>