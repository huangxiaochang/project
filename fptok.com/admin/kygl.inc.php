<?php
/*
	[Aijiacms System] Copyright (c) 2011-2015 www.aijiacms.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('AJ_ADMIN') or exit('Access Denied');
require AJ_ROOT.'/admin/kygl.class.php';
$do = new kygl;
$menus = array (
array('客源管理', '?file='.$file),
);

$id = $_GET['id'];

if(!empty($id))
{
	$content = $do->get_one($id);
	$content['fangling'] = explode(',',$content['fangling']);
	$content['mianji'] = explode(',',$content['mianji']);
	$content['jiage'] = explode(',',$content['jiage']);
	$content['louceng'] = explode(',',$content['louceng']);
	$content['fwsb'] = explode(',',$content['fwsb']);
	$content['zbhj'] = explode(',',$content['zbhj']);
}

$lists = $do->get_list();
include tpl('kygl');

?>