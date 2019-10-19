
$(function(){

   //降价通知
   $("#priceDialog").dialogAction();

   //开盘通知
   $("#openDialog").dialogAction();

   //提问
   $("#questionDialog").dialogAction();

   //团购弹窗
   $("#appointmentDialog").dialogAction();
   $(".appointmentDialog").dialogActions();

   //看房团弹窗
   $("#appointmentLook").dialogAction();

   //预约团购
   $("#exerciseApply").dialogAction();
   $("#exerciseApply_1").dialogAction();

   //活动报名弹窗
   $(".exerciseApply").dialogActions();

   //分享
   $("#shareBtn1").actionsheet({sheetContent : "#share", cancelBtn : "#shareCancelBtn", cssName : "show"});
   $("#shareBtn2").actionsheet({sheetContent : "#share", cancelBtn : "#shareCancelBtn", cssName : "show"});

   //收藏
   // $(".saveBtn").on("click", function(){
        // $(this).toggleClass("on");
   // });

   //户型图水平滚动
   if($("#pictureScroll").length !== 0){
     var picScroll = new fz.Scroll('#pictureScroll', {
        scrollY: false,
        scrollX: true
     });
   }

   //选项卡切换
   if($(".house-info-tabs").length !== 0){
     var tab = new fz.Scroll('.house-info-tabs', {
        role: 'tab',
        autoplay: false
     });
   }

   //楼盘导航固定
   var isApp = $('#house-tab-fixed').attr('data-isapp') || 0;
   if($("#house-tab-fixed").length !== 0 && isApp != 1){
     var navTop = parseInt($("#house-tab-fixed").offset().top)-44;

     $(window).scroll(function() {
      var scrolls = parseInt($(this).scrollTop()), navElem = $("#house-tab-fixed");
      //console.log(scrolls);
      if(scrolls > navTop) {
        if(!navElem.hasClass("fixed")){
          navElem.addClass("fixed");
        }
      }else{
       if(navElem.addClass("fixed")){
         navElem.removeClass("fixed");
       }
     }

     //头部
     if(scrolls > 44){
       $("#header-transparent").hide();
       $("#header-blue").show();
     }else{
       $("#header-transparent").show();
       $("#header-blue").hide();
     }
   });
   }


   //选项卡切换
   $(".subnav").on("click", function(){
      var id = $(this).attr("name");

      $(".subnav").removeClass("on");
      $(this).addClass("on");

      $(".subnav-content").hide();
      $("#"+id).show();
   });

   //返回顶部
   $("#goTop").backTop();
});