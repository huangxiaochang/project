<?php
defined('AJ_ADMIN') or exit('Access Denied');
include tpl('header');
if(!$itemid) show_menu($menus);
?>
<?php if (empty($id)) {?>
<div class="tt">客源管理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th align="center">姓名</th>
<th align="center">手机</th>
<th align="center">客源状态</th>
<th align="center">房型</th>
<th align="center">面积(平方米)</th>
<th align="center">价格(万)</th>
<th align="center">地区</th>
<th align="center">委托日期</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr>
<td align="center"><a href="<?php echo $AJ_URL.'&id='.$v['userid']; ?>"><?php echo $v['name'];?></a></td>
<td align="center"><?php echo $v['mobile'];?></td>
<td align="center"><?php echo $v['kyzt'];?></td>
<td align="center"><?php echo $v['shi'].' 室 '.$v['ting'].' 厅 '.$v['wei'].' 卫 '.$v['chu'].' 厨 '.$v['yangtai'].' 阳台 ';?></td>
<td align="center"><?php echo reset($v['mianji']).'-'.end($v['mianji']);?></td>
<td align="center"><?php echo reset($v['jiage']).'-'.end($v['jiage']);?></td>
<td align="center"><?php echo $v['quxian'];?></td>
<td align="center"><?php echo $v['wttime'];?></td>
</tr>
<?php }?>
</table>
<div class="pages"><?php echo $pages;?></div>
<?php }?>

<?php if (!empty($id)) {?>
<table cellpadding="2" cellspacing="1" class="tb">
	<tr>
		<td colspan="6" align="center"><b>客源信息</b></td>
	</tr>
	<tr>
		<td>姓名：<?php echo $content['name'] ?></td>
		<td>手机：<?php echo $content['mobile'] ?></td>
		<td>固话：<?php echo $content['phone'] ?></td>
		<td>邮箱：<?php echo $content['email'] ?></td>
		<td colspan="2">地址：<?php echo $content['address'] ?></td>
	</tr>
	<tr>
		<td>公/私：<?php if($content['ke'] == 1) echo '公客'; else echo '私客'; ?></td>
		<td>客源状态：<?php echo $content['kyzt'] ?></td>
		<td>客源类型：
			<?php 
				if($content['kylx'] == 1) echo '公务员';
				if($content['kylx'] == 2) echo '教师';
				if($content['kylx'] == 3) echo '学生';
				if($content['kylx'] == 4) echo '做生意';
				if($content['kylx'] == 5) echo '国企上班';
				if($content['kylx'] == 6) echo '私企上班';
				if($content['kylx'] == 7) echo '自由职业';
				if($content['kylx'] == 8) echo '其他';
			?>	
		</td>
		<td>客源等级：
			<?php
				if($content['kydj'] == 1) echo '一般';
				if($content['kydj'] == 2) echo '特殊';
				if($content['kydj'] == 3) echo '急租';
				if($content['kydj'] == 4) echo '急买';
			?>
		</td>
		<td>委托日期：<?php echo $content['wttime'] ?></td>
		<td>所属经纪人：<?php echo $content['sujjr'] ?></td>
	</tr>
	<tr>
		<td colspan="1">标签：<?php echo $content['biaoqian'] ?></td>
		<td colspan="5">备注：<?php echo $content['beizhu'] ?></td>
	</tr>
	<tr>
		<td colspan="6" align="center"><b>客源要求</b></td>
	</tr>
	<tr>
		<td>房型：<?php echo $content['shi'].' 室 '.$content['ting'].' 厅 '.$content['wei'].' 卫 '.$content['chu'].' 厨 '.$content['yangtai'].' 阳台 ';?></td>
		<td>朝向：
			<?php
				if($content['chaoxiang'] == 1) echo '东';
				if($content['chaoxiang'] == 2) echo '西';
				if($content['chaoxiang'] == 3) echo '南';
				if($content['chaoxiang'] == 4) echo '北';
				if($content['chaoxiang'] == 5) echo '东西';
				if($content['chaoxiang'] == 6) echo '南北';
				if($content['chaoxiang'] == 7) echo '西北';
				if($content['chaoxiang'] == 8) echo '西南';
				if($content['chaoxiang'] == 9) echo '东南';
				if($content['chaoxiang'] == 10) echo '东北';
				if($content['chaoxiang'] == 0) echo '不限';
			?>
		</td>
		<td>房龄： <?php echo reset($content['fangling']).'-'.end($content['fangling']);?> 年</td>
		<td>面积： <?php echo reset($content['mianji']).'-'.end($content['mianji']);?> 平方米</td>
		<td>价格： <?php echo reset($content['jiage']).'-'.end($content['jiage']);?> 万</td>
		<td>楼层： <?php echo reset($content['louceng']).'-'.end($content['louceng']);?> 层</td>
	</tr>
	<tr>
		<td>装饰要求：
			<?php
				if($content['zsyq'] == 1) echo '毛坯';
				if($content['zsyq'] == 2) echo '简装';
				if($content['zsyq'] == 3) echo '新装';
				if($content['zsyq'] == 4) echo '中装';
				if($content['zsyq'] == 5) echo '精装';
				if($content['zsyq'] == 6) echo '豪装';
				if($content['zsyq'] == 7) echo '婚装';
			?>
		</td>
		<td>用途：
			<?php
				if($content['yongtu'] == 1) echo '住宅';
				if($content['yongtu'] == 2) echo '写字楼';
				if($content['yongtu'] == 3) echo '商住两用';
				if($content['yongtu'] == 4) echo '商铺';
				if($content['yongtu'] == 5) echo '厂房';
				if($content['yongtu'] == 6) echo '土地';
				if($content['yongtu'] == 7) echo '车库';
				if($content['yongtu'] == 8) echo '合作建房';
				if($content['yongtu'] == 9) echo '集资房';
				if($content['yongtu'] == 10) echo '屋连地';
				if($content['yongtu'] == 11) echo '内部';
				if($content['yongtu'] == 0) echo '不限';
			?>
		</td>
		<td>物业类型：
			<?php
				if($content['wylx'] == 1) echo '高层';
				if($content['wylx'] == 2) echo '小高层';
				if($content['wylx'] == 3) echo '复式';
				if($content['wylx'] == 4) echo '私房';
				if($content['wylx'] == 5) echo '别墅';
				if($content['wylx'] == 6) echo '多层';
				if($content['wylx'] == 7) echo '公寓';
				if($content['wylx'] == 8) echo '顶+阁';
				if($content['wylx'] == 9) echo '底+院';
				if($content['wylx'] == 0) echo '不限';
			?>
		</td>
		<td>区县：<?php echo $content['quxian']; ?></td>
		<td colspan="2">地段要求： <?php echo $content['ddyq']; ?></td>
	</tr>
	<tr>
		<td colspan="3">房屋设施：
			<?php
				if(in_array(1,$content['fwsb'])) echo '水、';
				if(in_array(2,$content['fwsb'])) echo '电、';
				if(in_array(3,$content['fwsb'])) echo '天然气、';
				if(in_array(4,$content['fwsb'])) echo '暖气、';
				if(in_array(5,$content['fwsb'])) echo '电话、';
				if(in_array(6,$content['fwsb'])) echo '电视、';
				if(in_array(7,$content['fwsb'])) echo '空调、';
				if(in_array(8,$content['fwsb'])) echo '床、';
				if(in_array(9,$content['fwsb'])) echo '电风扇、';
				if(in_array(10,$content['fwsb'])) echo '太阳能、';
				if(in_array(11,$content['fwsb'])) echo '洗衣机、';
				if(in_array(12,$content['fwsb'])) echo '热水器、';
				if(in_array(13,$content['fwsb'])) echo '煤气灶、';
				if(in_array(14,$content['fwsb'])) echo '抽油烟机、';
				if(in_array(15,$content['fwsb'])) echo '电冰箱、';
				if(in_array(16,$content['fwsb'])) echo '微波炉、';
				if(in_array(17,$content['fwsb'])) echo '衣柜、';
				if(in_array(18,$content['fwsb'])) echo '书柜、';
				if(in_array(19,$content['fwsb'])) echo '橱柜、';
				if(in_array(20,$content['fwsb'])) echo '沙发、';
				if(in_array(21,$content['fwsb'])) echo '茶几、';
				if(in_array(22,$content['fwsb'])) echo '宽带、';
				if(in_array(23,$content['fwsb'])) echo '电梯、';
				if(in_array(24,$content['fwsb'])) echo '停车位、';
				if(in_array(25,$content['fwsb'])) echo '储藏室、';
				if(in_array(26,$content['fwsb'])) echo '地下室、';
				if(in_array(27,$content['fwsb'])) echo '车库、';
			?>
		</td>
		<td colspan="3">周边环境：
			<?php
				if(in_array(1,$content['zbhj'])) echo '法院、';
				if(in_array(2,$content['zbhj'])) echo '火车站、';
				if(in_array(3,$content['zbhj'])) echo '医院、';
				if(in_array(4,$content['zbhj'])) echo '银行、';
				if(in_array(5,$content['zbhj'])) echo '小学校、';
				if(in_array(6,$content['zbhj'])) echo '中学、';
				if(in_array(7,$content['zbhj'])) echo '邮电局、';
				if(in_array(8,$content['zbhj'])) echo '图书馆、';
				if(in_array(9,$content['zbhj'])) echo '超市、';
				if(in_array(10,$content['zbhj'])) echo '公园、';
				if(in_array(11,$content['zbhj'])) echo '停车场、';
				if(in_array(12,$content['zbhj'])) echo '健身房、';
				if(in_array(13,$content['zbhj'])) echo '菜市场、';
				if(in_array(14,$content['zbhj'])) echo '商场、';
				if(in_array(15,$content['zbhj'])) echo '体育场、';
				if(in_array(16,$content['zbhj'])) echo '幼儿园、';
				if(in_array(17,$content['zbhj'])) echo '地铁、';
				if(in_array(18,$content['zbhj'])) echo '汽车站、';
				if(in_array(19,$content['zbhj'])) echo '游泳池、';
			?>
		</td>
	</tr>
</table>
<?php }?>
<?php include tpl('footer');?>