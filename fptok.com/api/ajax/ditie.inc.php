<?php
defined('IN_AIJIACMS') or exit('Access Denied');
$ditie_title = isset($ditie_title) ? strip_tags($ditie_title) : '';
$ditie_title = convert($ditie_title, 'UTF-8', AJ_CHARSET);
$ditie_extend = isset($ditie_extend) ? decrypt($ditie_extend, AJ_KEY.'ARE') : '';
$ditieid = isset($ditieid) ? intval($ditieid) : 0;
$ditie_deep = isset($ditie_deep) ? intval($ditie_deep) : 0;
$ditie_id= isset($ditie_id) ? intval($ditie_id) : 1;
echo get_ditie_select($ditie_title, $ditieid, $ditie_extend, $ditie_deep, $ditie_id);
?>