<?php
/*
	 
	This is NOT a freeware, use is subject to license.txt
*/
require 'common.inc.php';
$head_name = '贷款计算器';
	$back_link = '';
	$foot = '';
	$head_title = '贷款计算器'.$AJ['seo_delimiter'].$head_title;
include template('tool', 'mobile');
if(AJ_CHARSET != 'UTF-8') toutf8();
?>