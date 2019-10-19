<?php 
defined('IN_AIJIACMS') or exit('Access Denied');
//S 自动生成tag
function substring($str, $lenth, $start=0) 
{ 
	$len = strlen($str); 
	$r = array(); 
	$n = 0;
	$m = 0;
	
	for($i=0;$i<$len;$i++){ 
		$x = substr($str, $i, 1); 
		$a = base_convert(ord($x), 10, 2); 
		$a = substr( '00000000 '.$a, -8);
		
		if ($n < $start){ 
            if (substr($a, 0, 1) == 0) { 
            }
            else if (substr($a, 0, 3) == 110) { 
              $i += 1; 
            }
            else if (substr($a, 0, 4) == 1110) { 
              $i += 2; 
            } 
            $n++; 
		}
		else{ 
            if (substr($a, 0, 1) == 0) { 
             	$r[] = substr($str, $i, 1); 
            }else if (substr($a, 0, 3) == 110) { 
             	$r[] = substr($str, $i, 2); 
            	$i += 1; 
            }else if (substr($a, 0, 4) == 1110) { 
            	$r[] = substr($str, $i, 3); 
             	$i += 2; 
            }else{ 
             	$r[] = ' '; 
            } 
            if (++$m >= $lenth){ 
              break; 
            } 
        }
	}
	return  join('',$r);
}
function convert_encoding($str,$nfate,$ofate){
	if ($ofate=="UTF-8"){ return $str; }
	if ($ofate=="GB2312"){ $ofate="GBK"; }
	if(function_exists("mb_convert_encoding")){
		$str=mb_convert_encoding($str,$nfate,$ofate);
	}
	else{
		$ofate.="//IGNORE";
		$str=iconv(  $nfate , $ofate ,$str);
	}
	return $str;
}
function getpage($url,$charset)
{
	$charset = strtoupper($charset);
	$content = "";
	if(!empty($url)) {
		if( function_exists('curl_init') ){
			$ch = @curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; )');
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_COOKIE, 'domain=www.baidu.com');
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			$content = @curl_exec($ch);
			curl_close($ch);
		}
		else if( ini_get('allow_url_fopen')==1 ){
			$content = @file_get_contents($url);
		}
		else{
			die('当前环境不支持采集【curl 或 allow_url_fopen】，请检查php.ini配置；');
		}
		$content = convert_encoding($content,"utf-8",$charset);
	}
	return $content;
}
function gettag($title,$content){
	$data = getpage('http://keyword.discuz.com/related_kw.html?ics=utf-8&ocs=utf-8&title='.rawurlencode($title).'&content='.rawurlencode(substring($content,500)),'utf-8');
	if($data) {
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $data, $values, $index);
		xml_parser_free($parser);
		$kws = array();
		foreach($values as $valuearray) {
			if($valuearray['tag'] == 'kw') {
				if(strlen($valuearray['value']) > 3){
					$kws[] = trim($valuearray['value']);
				}
			}elseif($valuearray['tag'] == 'ekw'){
				$kws[] = trim($valuearray['value']);
			}
		}
		return implode(' ',$kws);
	}
	return false;
}
//E 自动生成tag
/**
	 * 刷新房源
	 
	 */
	function yyrefresh($biao,$ids,$now_time='') {
	global $db, $dc, $CFG;
		if (is_array($ids)) {
			$ids = implode(',',$ids);
			$where = ' itemid in (' . $ids . ')';
		} else {
			$where = ' itemid =' . intval($ids);
		}
		//分开操作，保证今天发的房源排在刷新房源前面
		$to_day = mktime(0,0,0,date('m'),date('d'),date('Y'));
                if(empty($now_time)){
                    $now_time = time();
                }

		 $db->query("update {$db->pre}$biao set editdate = ".$now_time.",edittime = ".($now_time+14400)."  where " . $where." and adddate>=".$to_day);       
		 $db->query("update  {$db->pre}$biao set editdate = ".$now_time.",edittime = ".$now_time."  where " . $where." and adddate<".$to_day);
		return true;
	}
	function yycredit_add($username, $amount) {
	global $db;
	if($username && $amount) $db->query("UPDATE {$db->pre}member SET credit=credit+{$amount} WHERE username='$username'");
}
function yycredit_record($username, $amount, $editor, $reason, $note = '') {
	global $db, $AJ_TIME, $AJ;
	if($AJ['log_credit'] && $username && $amount) {
		$r = $db->get_one("SELECT credit FROM {$db->pre}member WHERE username='$username'");
		$balance = $r['credit'];
		$reason = addslashes(stripslashes(strip_tags($reason)));
		$note = addslashes(stripslashes(strip_tags($note)));
		$db->query("INSERT INTO {$db->pre}finance_credit (username,amount,balance,addtime,reason,note,editor) VALUES ('$username','$amount','$balance','$AJ_TIME','$reason','$note','$editor')");
	}
}
//统计积分
function get_jfen($username) {
	global $db;
	$buynum = $db->get_one("SELECT credit FROM {$db->pre}member WHERE username='$username'");
	$buynum = $buynum['credit'];
	return $buynum;
}

function ali_sms($mobile,$id,$data){
    global $AJ_TIME,$_username,$db,$AJ;
    date_default_timezone_set('PRC');
    include AJ_ROOT.'/api/alidayu/sendsms.class.php';
    $key = '54554545'; //App Key
    $secret = '12345445454'; //App Secret
	$sms = new SmsDemo($key, $secret);
	$data['product'] = $AJ['sitename'];
    $response = $sms->sendSms(
    "阿里云短信测试专用", // 短信签名
    'SMS_'.$id, // 短信模板编号
    $mobile, // 短信接收者
	$data,
    "123"
);

   
    if($response){
        $code = '成功';
        $status = 1;
    }else{
        $code = $sms->error;
        $status = 0;
    }
    $message = $data['code'] ? $data['code'] : $data['product'];
    $word = strlen($message);
    $code = 1;
    $db->query("INSERT INTO {$db->pre}sms (mobile,message,word,editor,sendtime,code) VALUES ('$mobile','$message','$word','$_username','$AJ_TIME','$code')");
    return $status;
}

//统计分类数量
function get_xiangcenum($itemid,$catid) {
	global $db;


$CAT=get_cat($catid);

if($catid) $condition .= $CAT['child'] ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";	 
     $r = $db->get_one("SELECT COUNT(*) AS num FROM {$db->pre}newhouse_xiangce    where houseid=$itemid {$condition} ");
	$categorynum = $r['num'];
	return $categorynum;
}
//统计分类数量
function get_huxingnum($itemid,$catid) {
	global $db;


$CAT=get_cat($catid);

if($catid) $condition .= $CAT['child'] ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";	 
     $r = $db->get_one("SELECT COUNT(*) AS num FROM {$db->pre}newhouse_huxing    where houseid=$itemid {$condition} ");
	$categorynum = $r['num'];
	return $categorynum;
}
//统计分类数量
function get_xccategory($itemid,$catid) {
	global $db;


$CAT=get_cat($catid);

if($catid) $condition .= $CAT['child'] ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";	 
     $r = $db->get_one("SELECT COUNT(*) AS num FROM {$db->pre}newhouse_xiangce    where houseid=$itemid {$condition} ");
	$categorynum = $r['num'];
	return $categorynum;
}
	//housepage
function mobilepages( $total, $page = 1 ,$moduleid,$lst, $perpage = 20) {
	global $AJ, $MOD, $L;
	if($total <= $perpage) return '';
	$items = $total;
	$total = ceil($total/$perpage);
	if($page < 1 || $page > $total) $page = 1;
	$urlrule='m'.$moduleid.'{$lst}-g{$page}.html';
	$urlrule = str_replace('{$lst}',$lst,$urlrule);
	$findme = '{$page}';
    $replaceme = '1';
     $demo_url = str_replace($findme,$replaceme,$urlrule);

	$pages = '';
	$_page = $page <= 1 ? $total : ($page - 1);
    $demo_url = str_replace(array('%7B', '%7D'), array('{', '}'), $demo_url);
    $url = $_page == 1 ? $demo_url : str_replace('{$page}', $_page, $urlrule);
   if($page >1)$pages .= '<a class="prev" href="'.$url.'"><i>&lt;</i>'.$L['prev_page'].'</a> ';
         if($total <= 5) { $min=1; $max=$total; }
		 if($total >6) {
		if($page < 5) {$min = 1; $max = 6;}
		if($page >= 5)  { $min=$page-3; $max=$page+3; }
		if($page >= $total-3)  { $min=$total-6; $max=$total; }
		}
		for($_page = $min; $_page <= $max; $_page++) {
			$url = $_page == 1 ? $demo_url : str_replace('{$page}', $_page, $urlrule);
			$pages .= $page == $_page ? '<span>'.$_page.'</span>' : ' <a href="'.$url.'">'.$_page.'</a>  ';
		}

$_page = $page >= $total ? 1 : $page + 1;
$url = $_page == 1 ? $demo_url : str_replace('{$page}', $_page, $urlrule);
if($page<>$total)$pages .= '<a  class="next" href="'.$url.'">'.$L['next_page'].'<i>&gt;</i></a>';

	return $pages;
}
function housedpages( $total, $page = 1 ,$lst, $perpage = 20) {
	global $AJ, $MOD, $L;
	if($total <= $perpage) return '';
	$items = $total;
	$total = ceil($total/$perpage);
	if($page < 1 || $page > $total) $page = 1;
	$urlrule='{$lst}-g{$page}.html';
	$urlrule = str_replace('{$lst}',$lst,$urlrule);
	$findme = '{$page}';
    $replaceme = '1';
     $demo_url = str_replace($findme,$replaceme,$urlrule);

	$pages = '';
	$_page = $page <= 1 ? $total : ($page - 1);
    $demo_url = str_replace(array('%7B', '%7D'), array('{', '}'), $demo_url);
    $url = $_page == 1 ? $demo_url : str_replace('{$page}', $_page, $urlrule);
    if($page >1)$pages .= '<a class="prev" href="'.$url.'"><i>&lt;</i>'.$L['prev_page'].'</a> ';
         if($total <= 7) { $min=1; $max=$total; }
		 if($total >7) {
		if($page < 5) {$min = 1; $max = 7;}
		if($page >= 5)  { $min=$page-3; $max=$page+3; }
		if($page >= $total-3)  { $min=$total-6; $max=$total; }
		}
		for($_page = $min; $_page <= $max; $_page++) {
			$url = $_page == 1 ? $demo_url : str_replace('{$page}', $_page, $urlrule);
			$pages .= $page == $_page ? '<span>'.$_page.'</span>' : ' <a href="'.$url.'">'.$_page.'</a>  ';
		}

$_page = $page >= $total ? 1 : $page + 1;
$url = $_page == 1 ? $demo_url : str_replace('{$page}', $_page, $urlrule);
if($page<>$total)$pages .= '<a  class="next" href="'.$url.'">'.$L['next_page'].'<i>&gt;</i></a>';

	return $pages;
}
function seo_cats($catid,  $moduleid = 1) {
	global $MODULE, $db;
	$condition = "moduleid=$moduleid AND parentid=0 AND catid IN ($catid)";
	$result = $db->query("SELECT catid,moduleid,catname FROM {$db->pre}category where $condition");
	
	while($c = $db->fetch_array($result)) {
		    
			 $html .= $c['catname'] . "";
			
		
		}
       $html = rtrim($html, ','); 
	return $html;
}

//判断magic_quotes_gpc状态  
if (@get_magic_quotes_gpc()) {  
    $_GET = sec($_GET);  
    $_POST = sec($_POST);  
    $_COOKIE = sec($_COOKIE);  
    $_FILES = sec($_FILES);  
}  
$_SERVER = sec($_SERVER);  
function sec(&$array) {  
    //如果是数组，遍历数组，递归调用  
    if (is_array($array)) {  
        foreach ($array as $k => $v) {  
            $array[$k] = sec($v);  
        }  
    } else if (is_string($array)) {  
        //使用addslashes函数来处理  
        $array = addslashes($array);  
    } else if (is_numeric($array)) {  
        $array = intval($array);  
    }  
    return $array;  
}  
//整型过滤函数  
function num_check($id) {  
    if (!$id) {  
        die('参数不能为空！');  
    } //是否为空的判断  
    else if (inject_check($id)) {  
        die('非法参数');  
    } //注入判断  
    else if (!is_numetic($id)) {  
        die('非法参数');  
    }  
    //数字判断  
    $id = intval($id);  
    //整型化  
    return $id;  
}  
//字符过滤函数  
function str_check($str) {  
    if (inject_check($str)) {  
        die('非法参数');  
    }  
    //注入判断  
    $str = htmlspecialchars($str);  
    //转换html  
    return $str;  
}  
function search_check($str) {  
    $str = str_replace("_", "\_", $str);  
    //把"_"过滤掉  
    $str = str_replace("%", "\%", $str);  
    //把"%"过滤掉  
    $str = htmlspecialchars($str);  
    //转换html  
    return $str;  
}  
//表单过滤函数  
function post_check($str, $min, $max) {  
    if (isset($min) && strlen($str) < $min) {  
        die('最少$min字节');  
    } else if (isset($max) && strlen($str) > $max) {  
        die('最多$max字节');  
    }  
    return stripslashes_array($str);  
}  
//防注入函数  
function inject_check($sql_str) {  
    return eregi('select|inert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|UNION|into|load_file|outfile', $sql_str);  
    // www.jb51.net 进行过滤，防注入  
      
}  
function stripslashes_array(&$array) {  
    if (is_array($array)) {  
        foreach ($array as $k => $v) {  
            $array[$k] = stripslashes_array($v);  
        }  
    } else if (is_string($array)) {  
        $array = stripslashes($array);  
    }  
    return $array;  
}  
?>