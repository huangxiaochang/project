var loadMore = {
   init : function( url, load, listBox){
     if($(window).scrollTop()>=$(document).height()-$(window).height() && $(load).css("display") === "none"){
              var pageNum = $(load).data("pageNum");

              $(load).css("display", "block");
              pageNum += 1;

              loadMore.getList( url, pageNum, function(houseData){
                $(load).data({"pageNum" : pageNum}).css("display", "none");
                setTimeout(function(){
                  loadMore.buildList(houseData, listBox);
                }, 200);
              });
       }
   },
   getList : function( _url, pageNum, callback){
    var url = window.location.href.split("/")[4], _condition = {outType:"jsonp", page: pageNum};

    //关键词
    if($.getUrlParam("keyword")){
        $.extend(_condition, {"keyword": $.getUrlParam("keyword")});
    }

    if(!!url){
       var con = url.split(/([0-9]+)/);
       $.each(con, function(k, v){
         //
         if((k+1)%2 === 0){
            return;
         }

         //
         switch(v){
            case "q" : $.extend(_condition, {areaId: con[k+1]});break; //区域
            case "s" : $.extend(_condition, {schoolId: con[k+1]});break; //学区
            case "l" : $.extend(_condition, {propertyType: con[k+1]});break; //类型
            case "j" : $.extend(_condition, {price: con[k+1]});break; //价格
            case "p" : $.extend(_condition, {order: con[k+1]});break; //排序
            case "t" : $.extend(_condition, {lpts: con[k+1]});break; //楼盘特色
            case "h" : $.extend(_condition, {houseType: con[k+1]});break; //户型
            case "m" : $.extend(_condition, {areaSize: con[k+1]});break; //面积
            case "z" : $.extend(_condition, {renovateState: con[k+1]});break; //装修状态
            case "x" : $.extend(_condition, {saleState: con[k+1]});break; //销售状态
            case "k" : $.extend(_condition, {opentime: con[k+1]});break; //开盘时间
         }
       });
     }

     $.ajax({
              type: 'get',
              url: _url,
              data: _condition,
              dataType: 'json',
              success: callback,
              error: function(xhr, type){
                //alert('Ajax error!');
              }
       });

   },
   buildList : function(houseData, box){
        var _this = $(this);
        var temp = "";

        $.each(houseData.data.list, function(k, v){
                  var tempd = v.propertyType.split("#");
                  var tempe = '';
				  var num = '1';
                   $.each(tempd, function(a, b){
                     if(b!=''){
                        tempe += '<i class="hf-badge-green">'+b+'</i>';
						if (a == num) {
                             return false;
                         }
                     }
                   });
				   
				  var tempz = v.projectFeature.split("#");
                  var tempy = '';
				      var nums = '2';
					  $.each(tempz, function(h, j){
                      if(j!=''){
                        tempy += j+'&nbsp;';
						if (h == nums) {
                             return false;
                         }
                      }
                      });
				  if(v.haveVideo=='1'){
					  var tempa = '<li class="ui-border-t">'
                          + '<a href="/house/'+ v.projectId+'/"><div class="hf-house-box">'
                          + '<div class="ui-list-img">'
                          + '<img class="lazyload" src="/data/assets/default/js/lazyLoad/loading.png" data-original="'+v.effectPhoto+'">'
                          + '<i class="icon-house-video"></i>'
                          + '</div>'
                          + '<div class="ui-list-info">'
                          + '<h4 class="ui-nowrap hf-badge-wrap" >'+v.projectName+'<span class="hf-badge-pos">';
                   }else{
                	   var tempa = '<li class="ui-border-t">'
                           + '<a href="/newhouse/'+ v.projectId+'/"><div class="hf-house-box">'
                           + '<div class="ui-list-img">'
                           + '<img class="lazyload" src="/data/assets/default/js/lazyLoad/loading.png" data-original="'+v.effectPhoto+'">'
                           + '</div>'
                           + '<div class="ui-list-info">'
                           + '<h4 class="ui-nowrap hf-badge-wrap" >'+v.projectName+'<span class="hf-badge-pos">';
                   }
	                   
                   
				   if(v.haveCommission=='1'){
                      var tempf =  '<i class="hf-badge-yellow"></i></span></h4>';
                   }else{
                     var tempf =  '</span></h4>';
                   }	
                     var tempg = '<div class="hf-f12 hf-badge-wrap">'
                               + v.areaName+'<span class="hf-f15 hf-badge-pos hf-fred">'+v.averagePrice+'</span></div>'
                               + '<div class="hf-f12 hf-fgray">类型：'+v.houseType+'</div>';

                   if(v.groupDiscount=='' && v.projectFeature==''){
					    var tempb =  '<div class="ui-nowrap hf-fyellow hf-f13">暂无优惠<i class="hf-badge-blue tip" style=" float: right;">'+v.saleState+'</i></div></div></div>';
                   }else{
						var tempb =  '<div class="ui-nowrap hf-fyellow hf-f13">'+tempy+'<i class="hf-badge-blue tip"  style=" float: right;">'+v.saleState+'</i></div></div></div>';
				   }

                  
                      if(v.juniorSchool!=''){
                      var tempc =  '</a></li>';
                    }else{
                      var tempc =  '</a></li>';
                    }
                  

                   temp += tempa+tempf+tempg+tempb+tempc;
               });
        console.log(temp);
              $(box).append(temp);

   }

}

$(function(){
     //返回顶部
     $("#goTop").backTop();

	   //导航
     $(".hf-icon-daohang").click(function(){
        var t = $(".pop-layer");
        t.toggleClass("show");
     });

    //区域
    $("#filter-area").firstFliter();
    $(".area-type").secondFliter($(".filter-area-type"));
    var txt1 = $(".area-list.on").text();
    if(txt1 !== "不限"){
      $("#filter-area").find("span").text(txt1);
    }

  //区域
    $("#filter-bid").firstFliter();
    $(".bid-type").secondFliter($(".filter-bid-type"));
    var txt1 = $(".bid-list.on").text();
    if(txt1 !== "不限"){
      $("#filter-bid").find("span").text(txt1);
    }
    //类型
    $("#filter-type").firstFliter();
    var txt2 = $(".type-list.on").text();
    if(txt2 !== "不限"){
      $("#filter-type").find("span").text(txt2);
    }

    //价格
    $("#filter-price").firstFliter();
    var txt3 = $(".price-list.on").text();
    if(txt3 !== "不限"){
      $("#filter-price").find("span").text(txt3);
    }

    //更多
    $("#filter-more").firstFliter();
    $(".more-type").secondFliter($(".filter-more-type"));
    $("#loading").data({"pageNum" : 1});
    /*window.onscroll = throttle(loadMore, 300);*/
    window.onscroll = throttle(function(){
      loadMore.init( "http://localhost/api/house.php?action=waphouse", "#loading", "#newhouselist");
    }, 300);

    //图片懒加载
    picLazyLoad({
      className: "lazyload"
    });
});