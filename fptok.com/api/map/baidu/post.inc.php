<?php
defined('IN_AIJIACMS') or exit('Access Denied');

preg_match("/^[0-9\.\,]{17,21}$/", $map) or $map = '';
?>
<style>
a.primary{display:inline-block;line-height:36px;padding:0px
20px;background-color:#008aff;color:#fff !important}a.primary:hover{filter:alpha(opacity=90);-moz-opacity:0.9;opacity:0.9}
</style>
  <script>document.domain = ""; var $STATIC = '';</script>   
<script src="<?php echo AJ_SKIN;?>js/baidumark.js" type="text/javascript"></script>

	<input  name="post[map]" type="text" size="30" id="map" value="<?php echo $map;?>"  class="txt" />
                      
                        <a class="primary" href="javascript:;" onclick="g.ui.dialog('<?php echo $MODULE[1][linkurl];?>api/map/baidu/mark.php?target=map', '地图坐标');">选取坐标</a>
                        <span class="help-block">标注房源地图，更能吸引网友关注.</span>

