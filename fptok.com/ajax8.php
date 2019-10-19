<?php
/*
	[Aijiacms System] Copyright (c) 2011-2015 www.aijiacms.com

*/
require 'common.inc.php';
if($AJ_BOT) dhttp(403);

require AJ_ROOT.'/include/post.func.php';
(isset($job) && check_name($job)) or $job = '';
@include AJ_ROOT.'/api/ajax/'.$action.'.inc.php';
$act = $_REQUEST['act'];
$Mid = $_REQUEST['mid'];
$Aid = $_REQUEST['aid'];



?>