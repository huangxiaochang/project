$(function(){
          //高度
          var h = $("body").height();
          $(".ui-container").height(h-44);

          //样板房
          var Slider = new fz.Scroll('#photos-content', {
                role: 'slider',
                indicator: false,
                autoplay: false
          });

          //图片加载
          Slider.on('beforeScrollStart', function(from, to) {
             var next = $(".photos-img").eq(to).find("img");
             
             if(!next.attr("src")){
                var url = next.attr("data-url");
                var load = $.loading({
                    content:'加载中...',
                });
                next.attr("src", url);
                next.on("load", function(){
                   load.loading("hide");
                });
             }
          });


          //当前页数和文字内容显示
          Slider.on('scrollEnd', function(curPage) {
            //$("#curPagesNum").text(curPage+1);
            var Img = $(".hf-full-box").find(".current").find("img"), infos = Img.attr("alt").split(" "),
                num = infos[1].split("/"), type = Img.attr("name");

            $("#photos-info-name").text(infos[0]);
            $("#curPagesNum").text(num[0]).next("i").text(num[1]);

            
            if($(".hf-full-photos-nav-list.on").text() !== infos[0]){
                //$("#"+type).trigger("click");
                $(".hf-full-photos-nav-list").removeClass("on");
                $("#"+type).addClass("on");
            }
          });

          /* 水平滚动 */
          var scroll = new fz.Scroll('.ui-scroller', {
                scrollY: false,
                scrollX: true
          });

          //
          $(".hf-full-photos-nav-list").on("click", function(){
             var id = $(this).attr("id");

             $(".hf-full-photos-nav-list").removeClass("on");
             $(this).addClass("on");
             
             //
             $(".photos-img.current").removeClass("current");
             $(".photos-img img[name="+id+"]").eq(0).parent("li").addClass("current");


             //滚动对应的页数
             var index = $(".photos-img.current").index();
             Slider.scrollToElement("img[name='"+id+"']:nth-child(1)", 100);
             Slider.currentPage = index;
          });

        });