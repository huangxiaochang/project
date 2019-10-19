<?php
defined('AJ_ADMIN') or exit('Access Denied');
$MCFG = array();
$MCFG['module'] = 'article';
$MCFG['name'] = '资讯';
$MCFG['author'] = 'AIJIACMS.COM';
$MCFG['homepage'] = 'www.aijiacms.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '资讯管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加资讯';
$RT['action']['index']['edit'] = '修改资讯';
$RT['action']['index']['delete'] = '删除资讯';
$RT['action']['index']['check'] = '审核资讯';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '资讯移动';
$RT['action']['index']['level'] = '信息级别';

$CT = true;
?>