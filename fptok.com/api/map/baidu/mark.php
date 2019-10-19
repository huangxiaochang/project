<?php
require '../../../common.inc.php';
include AJ_ROOT.'/api/map/baidu/config.inc.php';
$map = isset($map) ? $map : '';
preg_match("/^[0-9\.\,]{17,21}$/", $map) or $map = $map_mid;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
        <title></title>
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="renderer" content="webkit">
        
     
  <script>document.domain = ""; var $STATIC = '';</script>   
<script src="<?php echo AJ_SKIN;?>js/baidumark.js" type="text/javascript"></script>

<link rel="stylesheet" href="<?php echo AJ_SKIN;?>layer.css" id="layui_layer_skinlayercss" style="">
         
    <body>

<script src="http://api.map.baidu.com/api?v=2.0&ak=R4G0pYE9dB7H4P0TXId24oMF"></script>

<div style="width:100%; height: 100%;display: block;float: left;" id="container"></div>  

<div style="position:fixed;left:0;top:0;width:100%;padding: 5px 0;text-align: center;background-color: #fff;"> 
    <input type="text" class="input-text" id="suggestId" value="" placeholder="输入地址进行查找" style="width:300px;"  />
    <input type="button" value="查找" class="btn btn-success" onclick="dosearch($('#suggestId').val());" /> 
    <input type="button" value="取消" class="btn btn-grey" onclick="g.ui.closeSelf();"/> 
    <input type="button" value="刷新" class="btn btn-info" onclick="javascript:g.reload();"/> 
    <span class="c-999">提示:单击标注点,选取坐标(标注点可拖拽)</span>
</div>


<script  type="text/javascript">
    $('#container').height($(window).height());

    var map = new BMap.Map("container");// 创建Map实例
    map.enableScrollWheelZoom();//启用滚轮放大缩小
    map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
    
    
    var markpoint = parent.$('#map').val(); //获取当前的标注点
    if(!markpoint) {
        markpoint = "<?php echo $map;?>";
    }
    markarr = markpoint.split(',');
    
    var point = new BMap.Point(markarr[0], markarr[1]);//以当前坐标为中心创建
    addMarker(point);
    map.centerAndZoom(point, 15);//初始化地图,设置中心点坐标和地图级别。

    function addMarker(point) {
        var marker = new BMap.Marker(point);
        map.addOverlay(marker);    //添加标注
        marker.addEventListener("click", function(){ 
            var p = this.getPosition();
            g.ui.confirm("将此坐标作为标记:"+p.lng+","+p.lat+"?", function(){
                parent.$('#map').val(p.lng+","+p.lat);
                g.ui.closeSelf();
            });
        });
        marker.enableDragging();
    }

    var ac = new BMap.Autocomplete({  //建立一个自动完成的对象
        "input" : "suggestId",
        "location" : map
    });

    ac.addEventListener("onconfirm", function (e) {    //鼠标点击下拉列表后的事件
        var _value = e.item.value;
        var myValue = _value.province + _value.city + _value.district + _value.street + _value.business;
        
        if(typeof(e.item.value.city)!=='undefined') {
            map.centerAndZoom(e.item.value.city, 11);//初始化地图,设置中心点坐标和地图级别。
        }
        dosearch(myValue);
    });

    function dosearch(keyword) {
        if($.trim(keyword)=='') {
            g.ui.error("未输入搜索关键词");
            return false;
        }
        map.clearOverlays();    //清除地图上所有覆盖物
        var local = new BMap.LocalSearch(map, { //智能搜索
            onSearchComplete: function(results){
                // 判断状态是否正确
                if (local.getStatus() == BMAP_STATUS_SUCCESS){
                    for (var i = 0; i < results.getCurrentNumPois(); i ++){
                        addMarker(results.getPoi(i).point);
                    }
//                    document.getElementById("r-result").innerHTML = s.join("<br/>");
                } else {
                    
                }
                
            }
        });
        local.search(keyword);
    }
</script>


</body>
</html>