<?php
defined('IN_AIJIACMS') or exit('Access Denied');
include IN_ROOT.'/header.tpl.php';
?>

<div class="body">
<div>
	<strong style="font-size:14px;">恭喜！您已经成功安装爱家房产网站管理系统</strong><br/><br/>
	<fieldset>
	<legend>&nbsp;网站管理信息&nbsp;</legend>
	<table cellpadding="2" cellspacing="2" width="100%">
	<tr>
	<td width="100">&nbsp;&nbsp;网站后台地址：</td>
	<td><a href="<?php echo $url;?>admin.php"><?php echo $url;?>admin.php</a></td>
	</tr>
	<tr>
	<td>&nbsp;&nbsp;管理员户名：</td>
	<td><?php echo $username;?> </td>
	</tr>
	<tr>
	<td >&nbsp;&nbsp;管理员密码：  </td>
	<td><?php echo $password;?> (请妥善保存)</td>
	</tr>
	</table>
	</fieldset>
	<br/><br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;非常感谢选择爱家房产网站管理系统<br/><br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;更多系统相关信息，敬请关注 <a href="http://www.aijiacms.com" target="_blank">www.aijiacms.com</a>
</div>
</div>
   
  </div>
  </div>
  </div>
</div>
</body>
</html>

