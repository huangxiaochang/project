<?php
/*
	 
	This is NOT a freeware, use is subject to license.txt
*/
defined('AJ_ADMIN') or exit('Access Denied');
$install = file_get(AJ_CACHE.'/install.lock');
$url = decrypt('9c86mN4yUrDymsHosfI6kag1Vdsj7767C5UD3GsPI1P668R7SnbeT3X4qgVLxH0VyC3IcilPVjRchf8M3Zjl0', 'AIJIACMS').'?action='.$action.'&product=house&version='.AJ_VERSION.'&release='.AJ_RELEASE.'&lang='.AJ_LANG.'&charset='.AJ_CHARSET.'&install='.$install.'&os='.PHP_OS.'&soft='.urlencode($_SERVER['SERVER_SOFTWARE']).'&php='.urlencode(phpversion()).'&mysql='.urlencode($db->version()).'&url='.urlencode($AJ_URL).'&site='.urlencode($AJ['sitename']).'&auth='.strtoupper(md5($AJ_URL.$install.$_SERVER['SERVER_SOFTWARE']));
if(isset($mfa)) $url .= '&mfa='.$mfa;
dheader($url);
?>