<?php
//主动推送24小时内最新链接列表至百度
$time = time();
$starttime = $time - 24*3600;//24小时
$query = "SELECT linkurl FROM {$AJ_PRE}article_8 WHERE edittime > $starttime ORDER BY itemid ASC";
$result = $db->query($query);
$urls="";
while ($r=$db->fetch_array(($result)))
{
$linkurl = $MODULE[8][linkurl].$r['linkurl'];
//修改域名
$urls.=$linkurl.",";
}
$urls=substr($urls,0,-1);
$urls = explode(",",$urls);
//修改下一行,请到百度推送那里获取代码复制到这里
$api = 'http://data.zz.baidu.com/urls?site=www.ncfc365.com&token=4U822KwLfZGXkX7Y';
$ch = curl_init();
$options =  array(
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => implode("\n", $urls),
    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
);
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
echo $result;
?>