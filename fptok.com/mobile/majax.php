<?php
/*
	[Aijiacms B2B System] Copyright (c) 2008-2016 www.aijiacms.com
	This is NOT a freeware, use is subject to license.txt
*/
require 'common.inc.php';
if($AJ_BOT) dhttp(403);
if($action != 'mobile') {
	check_referer() or exit;
}
require AJ_ROOT.'/include/post.func.php';
require 'user/extend.func.php';
(isset($job) && check_name($job)) or $job = '';
@include AJ_ROOT.'/mobile/ajax/'.$action.'.inc.php';
?>