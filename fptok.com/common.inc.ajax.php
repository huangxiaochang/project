<?php
/*   
  This is NOT a freeware, use is subject to license.txt
*/
// define('AJ_DEBUG', 0);
// if(AJ_DEBUG) {
//   error_reporting(E_ALL);
//   $mtime = explode(' ', microtime());
//   $debug_starttime = $mtime[1] + $mtime[0];
// } else {
//   error_reporting(0);
// }
// if(isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) exit('Request Denied');
// if(function_exists('set_magic_quotes_runtime')) @set_magic_quotes_runtime(0);
// $MQG = get_magic_quotes_gpc();
// foreach(array('_POST', '_GET') as $__R) {
//   if($$__R) { 
//     foreach($$__R as $__k => $__v) {
//       if(substr($__k, 0, 1) == '_') if($__R == '_POST') { unset($_POST[$__k]); } else { unset($_GET[$__k]); }
//       if(isset($$__k) && $$__k == $__v) unset($$__k);
//     }
//   }
// }
define('IN_AIJIACMS', true);
define('IN_ADMIN', defined('AJ_ADMIN') ? true : false);
define('AJ_ROOT', str_replace("\\", '/', dirname(__FILE__)));
if(defined('AJ_REWRITE')) include AJ_ROOT.'/include/rewrite.inc.php';
$CFG = array();
require AJ_ROOT.'/config.inc.php';
define('AJ_PATH', $CFG['url']);
define('AJ_STATIC', $CFG['static'] ? $CFG['static'] : $CFG['url']);
define('AJ_DOMAIN', $CFG['cookie_domain'] ? substr($CFG['cookie_domain'], 1) : '');
define('AJ_WIN', strpos(strtoupper(PHP_OS), 'WIN') !== false ? true: false);
define('AJ_CHMOD', ($CFG['file_mod'] && !AJ_WIN) ? $CFG['file_mod'] : 0);
define('AJ_LANG', $CFG['language']);
define('AJ_KEY', $CFG['authkey']);
define('AJ_EDITOR', $CFG['editor']);
define('AJ_CLOUD_UID', $CFG['cloud_uid']);
define('AJ_CLOUD_KEY', $CFG['cloud_key']);
define('AJ_CHARSET', strtoupper($CFG['charset']));
define('AJ_CHARLEN', AJ_CHARSET == 'GBK' ? 2 : 3);
define('AJ_CACHE', $CFG['cache_dir'] ? $CFG['cache_dir'] : AJ_ROOT.'/file/cache');
define('AJ_SKIN', AJ_STATIC.'skin/'.$CFG['skin'].'/');
define('VIP', $CFG['com_vip']);
define('errmsg', 'Invalid Request');
$L = array();
include AJ_ROOT.'/lang/'.AJ_LANG.'/lang.inc.php';
require AJ_ROOT.'/version.inc.php';
require AJ_ROOT.'/include/global.func.php';
require AJ_ROOT.'/include/safe.func.php';
require AJ_ROOT.'/include/cloud.func.php';
require AJ_ROOT.'/include/tag.func.php';
require AJ_ROOT.'/api/im.func.php';
require AJ_ROOT.'/api/extend.func.php';
require AJ_ROOT.'/api/plug.php';
// if(!$MQG) {
//   if($_POST) $_POST = daddslashes($_POST);
//   if($_GET) $_GET = daddslashes($_GET);
//   if($_COOKIE) $_COOKIE = daddslashes($_COOKIE);
// }
if(function_exists('date_default_timezone_set')) date_default_timezone_set($CFG['timezone']);
$AJ_PRE = $CFG['tb_pre'];
// $AJ_QST = addslashes($_SERVER['QUERY_STRING']);
// $AJ_TIME = time() + $CFG['timediff'];
// $AJ_IP = get_env('ip');
// $AJ_URL = get_env('url');
// $AJ_REF = get_env('referer');
// $AJ_MOB = get_env('mobile');
// $AJ_BOT = is_robot();
// $AJ_TOUCH = is_touch();
header("Content-Type:text/html;charset=".AJ_CHARSET);
require AJ_ROOT.'/include/db_'.$CFG['database'].'.class.php';
require AJ_ROOT.'/include/cache_'.$CFG['cache'].'.class.php';
require AJ_ROOT.'/include/session_'.$CFG['session'].'.class.php';
require AJ_ROOT.'/include/file.func.php';
if(!empty($_SERVER['REQUEST_URI'])) strip_uri($_SERVER['REQUEST_URI']);
if($_POST) { $_POST = strip_sql($_POST); strip_key($_POST); }
if($_GET) { $_GET = strip_sql($_GET); strip_key($_GET); }
if($_COOKIE) { $_COOKIE = strip_sql($_COOKIE); strip_key($_COOKIE); }
if(!IN_ADMIN) {
  $BANIP = cache_read('banip.php');
  if($BANIP) banip($BANIP);
  $aijiacms_task = '';
}
if($_POST) extract($_POST, EXTR_SKIP);
if($_GET) extract($_GET, EXTR_SKIP);
$db_class = 'db_'.$CFG['database'];
$db = new $db_class;
$db->halt = (AJ_DEBUG || IN_ADMIN) ? 1 : 0;
$db->pre = $CFG['tb_pre'];
$db->connect($CFG['db_host'], $CFG['db_user'], $CFG['db_pass'], $CFG['db_name'], $CFG['db_expires'], $CFG['db_charset'], $CFG['pconnect']);
$dc = new dcache();
$dc->pre = $CFG['cache_pre'];
$AJ = $MOD = $EXT = $CSS = $JS = $AJMP = $CAT = $ARE = $AREA = array();
$CACHE = cache_read('module.php');

?>