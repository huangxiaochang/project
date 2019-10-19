<?php
$moduleid = 2;
$_DPOST = $_POST;
$_DGET = $_GET;
require 'common.inc.php';
require 'user/extend.func.php';
require 'user/lang.inc.php';
require AJ_ROOT.'/module/'.$module.'/common.inc.php';
mobile_login();
?>