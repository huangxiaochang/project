<?php
defined('IN_AIJIACMS') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">客户处理</div>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 姓名</td>
<td><?php echo $truename;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 联系电话</td>
<td><?php echo $mobile;?></td>
</tr>

<tr>
<td class="tl"><span class="f_hid">*</span> 楼盘</td>
<td><?php echo $hname;?></td>
</tr>

<tr>
<td class="tl"><span class="f_hid">*</span> 红包金额</td>
<td><?php echo $money;?>元</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 提交时间</td>
<td><?php echo $addtime;?></td>
</tr>

<tr>
<td class="tl"><span class="f_hid">*</span> 客户状态</td>
<td>
<input type="radio" name="post[status]" value="0"<?php echo $status == 0 ? ' checked' : '';?> /> 待受理
<input type="radio" name="post[status]" value="1"<?php echo $status == 1 ? ' checked' : '';?> id="s_1" onclick="S(this.value);"/> 未领取
<input type="radio" name="post[status]" value="2"<?php echo $status == 2 ? ' checked' : '';?>  id="s_2" onclick="S(this.value);"/> 过期
<input type="radio" name="post[status]" value="3"<?php echo $status == 3 ? ' checked' : '';?>  id="s_3" onclick="S(this.value);"/> 已领取

</td>
</tr>

</tbody>

<tr>
<td class="tl"><span class="f_hid">*</span> 受理备注</td>
<td><textarea name="post[note]" rows="4" cols="60"><?php echo $note;?></textarea></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 受理人</td>
<td><?php echo $editor;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 受理时间</td>
<td><?php echo $edittime;?></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<script type="text/javascript">
function check() {
	return confirm('确定要执行此操作吗？');
}
function S(i) {
	if(i==1) {
		Dh('pass');Ds('send');
		try{Dd('content').value=Dd('c_1').value;}catch(e){}
	} else if(i==2) {
		Dh('pass');Ds('send');
		try{Dd('content').value=Dd('c_2').value;}catch(e){}
	} else if(i==3) {
		Dh('pass');Ds('send');
		try{Dd('content').value=Dd('c_3').value;}catch(e){}
	}
	 else if(i==4) {
		Ds('pass');Ds('send');
		try{Dd('content').value=Dd('c_4').value;}catch(e){}
	}
}
</script>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>