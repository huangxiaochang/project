function getLocation(){
  if (navigator.geolocation)
    {
    navigator.geolocation.getCurrentPosition(showPosition);
    }
  else{x.innerHTML="浏览器不支持获取地理位置信息";}
  }
function showPosition(position){
   var longx = position.coords.longitude; //经度
   var laty = position.coords.latitude; //维度
   $.post("/ajax.php", { "action": "gllocal","job": "initial","x": longx,"y": laty },
            function(data){
				var html = "";
				var arealist = data.arealist;
                for(var i in arealist){
                html += '<div class="aui-col-xs-3" onclick=\"Setmycity('+arealist[i].areaid+')\"><div class="aui-grid-label dt-text-ellipsis">'+arealist[i].areaname+'</div></div>';
				
                  };
				if(data.areaname){
				var areaname = data.areaname;
				}else{
				var areaname = bdcity
					} 
				$('#bdcity').html(areaname);
				localStorage.setItem("mbcity",areaname);//设置手机版城市地区为areaname
				var localaddress = data.localaddress;
				localStorage.setItem("mbaddress",localaddress);	
                $("#citylist").prepend(html);
				//$("#citylist").empty().prepend(html);
            }, "json");

}
getLocation();