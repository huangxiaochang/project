var loadMore = {
   init : function(url, load, listBox){
     if($(window).scrollTop()>=$(document).height()-$(window).height() && $(load).css("display") === "none"){
              var pageNum = $(load).data("pageNum");

              $(load).css("display", "block");
              pageNum += 1;

              loadMore.getList(url, pageNum, function(data){
                if(data.length === 0 ){
                   if(!$(load).data("isOver")){
                     $(load).find("p").html("已经到底啦!");
                     $(load).data({"isOver" : true});
                   }
                   return;
                }
                
                $(load).data({"pageNum" : pageNum}).css("display", "none");
                setTimeout(function(){
                  loadMore.buildList(data, listBox);
                }, 200); 
              });
       } 
   },
   getList : function(url, pageNum, callback){
    var _url = url, pid = $("#pid").val();
    $.ajax({
              type: 'get',
              url: _url,
              data: {"pageNow" : pageNum, "pid" : pid},
              dataType: 'json',
              success: callback,
              error: function(xhr, type){
                //alert('Ajax error!');
              }
        });

   },
   buildList : function(data, box){
        var _this = $(this), temp = "";
                  
        $.each(data, function(k, v){
            temp += '<li class="ui-border-t">'
                +  '<div class="hf-wenda-q">'
                +  '<span>Q</span>'+v.question+' '
                +  '</div>'
                +  '<div class="hf-wenda-a">'
                +  '<span>A</span>'+v.reply+' '
                +  '</div>'
				+  '</li>';
			
                                
                            
                        
        });

        $(box).append(temp);

   }

}


$(function(){
   //加载更多
  $("#loading").data({"pageNum" : 1});
  window.onscroll = throttle(function(){
      loadMore.init("/Newhouse/Detail/getmorewenda", "#loading", "#listBox");
  }, 300);

});