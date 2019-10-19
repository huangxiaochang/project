/**
 * 合房网公共js
 * author: zhailulu
 *
 **/
;(function($) {

 $.extend($.fn, {
    //切换状态 (收藏)
    toggleStatus: function(opts) {
      var elem = $(this),
        input, status;

      //input位置
      switch (opts.position) {
        case "find":
          input = elem.find("input");
          break;
        case "prev":
          input = elem.prev("input");
          break;
        case "next":
          input = elem.next("input");
          break;
      }

      //点击事件
      elem.on("click", function() {
        status = input.val();
        $(opts.addElem).toggleClass(opts.cssName);
        !!parseInt(status) ? input.val(0).attr("value", 0) : input.val(1).attr("value", 1);

      });
    },

    //底部菜单
    actionsheet: function(opts) {
      var cancelBtn = opts.cancelBtn,
        sheetContent = opts.sheetContent,
        cssName = opts.cssName;

      //显示
      $(this).on("click", function() {
        $(sheetContent).addClass(cssName);
      });

      //隐藏
      $(cancelBtn).on("click", function() {
        $(sheetContent).removeClass(cssName);
      });

    },

    backTop : function(){
        var _this = $(this);

        _this.on("click",function(){
            $("body").scrollTop(0);
        });

        $(window).on('scroll', function() {
            if($(window).scrollTop() <= $(window).height()*2-60) {
                if(_this.css("display") === "block"){
                   _this.hide();
                }
            } else {
                if(_this.css("display") === "none"){
                   _this.show();
                }
            }
        });
    },

    firstFliter: function(){
       var _this = $(this), id = $(this).attr("id"), content = $("#"+id+"-content");
       //var h = $(document).height() - $('.hf-header').height() - $('.hf-subnav').height() - $('.hf-category').height();
        //遮罩层高度
        //content.height(h);

       _this.on("click", function(){
          //关闭其他浮层
          $(".hf-category-item").not($(this).parent("li")).removeClass("on");
          $(".hf-filter").not(content).hide();

          content.toggle();
          if(content.css('display') == 'block'){
            $("body,html").css({"overflow":"hidden"});
            $("body").addClass("positionFixed");//固定body
            $(".hf-filter-area").css("display","block");//add 控制遮罩
            $(".ui-container-cnt").css("position","fixed");
          }else{
            $("body,html").removeAttr('style');
            $("body").removeClass("positionFixed");//固定body
            $(".hf-filter-area").css("display","none");//add 控制遮罩
            $(".ui-container-cnt").css("position","absolute");
          };

          $(this).parent("li").toggleClass("on");

        });

      },

    secondFliter: function(listObj){
        //类型
        var _this = $(this);
        _this.on("click", function(){
            var index = $(this).index();
            _this.removeClass("on");
            $(this).addClass("on");
            listObj.eq(index).show();
            listObj.not(listObj.eq(index)).hide();
        });
    },

    //弹窗
    dialogAction: function(callback) {
        var dialogBtn = $(this), id = $(this).attr("id");
        //降价通知
        dialogBtn.on("click", function(){
           $("#"+id+"-content").addClass("show");
        });

        $("#"+id+"-cancel").on("click", function(){
           $("#"+id+"-content").removeClass("show");
        });

        /*$("#"+id+"-OK").on("click", function(){
           callback();
        });*/
    },

  dialogActions: function(callback) {
        var dialogBtn = $(this), id = $(this).attr("data-id");
        //降价通知
        dialogBtn.on("click", function(){
           $("#"+id+"-content").addClass("show");
        });

        $("#"+id+"-cancel").on("click", function(){
           $("#"+id+"-content").removeClass("show");
        });

        /*$("#"+id+"-OK").on("click", function(){
           callback();
        });*/
    },

    //线形图表
    lineChart : function(opts){
      var _this = $(this), id = _this.attr("id"), _width = parseInt($("body").width()); //页面宽度值

      if(id=="priceChart"){
         // _width=_width-22;
      }
      var _data = opts.data,  _labels = opts.labels;

      if(typeof(iChart) == "undefined") {
          return;
      }

      var chart = new iChart.LineBasic2D({
                render : id,
                data: _data,
                width : _width,
                height : 150,
                border : false,
                title:false,
                legend:{
                  enable:false  //关闭图例
                },
                crosshair:false,
                background_color:"#fff",
                coordinate:{ //
                    background_color : null,
                    grid_color : '#dddddd',
                    striped_factor:0.06,
                    height:'84%',
                    width:'90%',
                    valid_width:"100%",
                    axis:{
                            color:'#dddddd',
                            width:[0,0,1,1]
                        },
                    grids:{ //网格  取消垂直方向自定义
                            // vertical:{
                            //     way:false,
                            //     value:1
                            // },
                            horizontal:{
                                way:'share_alike',
                                value:1
                            }
                        },
                    gridHStyle:{
                            color:"#fff"
                        },
                    scale:[{//刻度线
                         position:'left',
                         scale_color:'#fff',
                         scale_enable:false,
                         //start_scale:0,
                         //scale_space:10,
                         label:{
                            color:'#666666'
                         },
                         listeners:{
                            parseText:function(t,x,y){
                            //自定义左侧坐标系刻度文本的格式。
                                return {text:''}
                            }
                         }
                    },{
                     position:'bottom',
                     scale_color:'#fff',
                     labels: _labels,
                     label:{
                        color:'#999999'
                     }
                    }]
                 },
                sub_option:{
                    hollow_inside:false,//设置一个点的亮色在外环的效果
                    point_size:12
                },
                labels: _labels
            });

           chart.draw();
    }
  });
})(Zepto)

//
var throttle = function(fn, delay){
  var timer = null;
  return function(){
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function(){
      fn.apply(context, args);
    }, delay);
  };
 };

//url参数
$.getUrlParam = function(name){
       var reg = new RegExp("(^|&)" + name +"=([^&]*)(&|$)" );
       var r = window.location.search.substr(1).match(reg);
       if (r!= null) return decodeURI(r[2]); return null;
};

var wait = 60;
function codes(btn) {
    if (wait == 0) {
        btn.removeAttribute("disabled");
        btn.value = "获取验证码";
        wait = 60;
    } else {
        btn.setAttribute("disabled", true);
        btn.value = "重新发送(" + wait+")";
        wait--;
        setTimeout(function () {
            codes(btn);
        },
        1000)
    }
};

// 点击其他透明区域收起
        $(document).on('touchstart','.hf-filter',function(e){
            e.preventDefault();
            $(this).hide().parents('.hf-category-item').removeClass('on');
            $(".hf-filter-area").css("display","none");//add 控制遮罩
            $("body,html").removeAttr('style');
        });

        
//add
         $(document).on('touchstart','.hf-filter-area',function(e){
            e.preventDefault();
            filterAreaHide();
        });

function filterAreaHide(){
    $(".hf-filter-area").css("display","none");//add 控制遮罩
    $("body").removeClass("positionFixed");//固定body移除
    $(".hf-filter").hide();
    $('.hf-category-item').removeClass('on');
    $("body,html").removeAttr('style');
    $(".ui-container-cnt").css("position","absolute");
}

        // 点击主区域阻上事件冒泡
        $('.hf-filter-main').on('touchstart',function(e){
            e.stopPropagation();
           // alert(1);
        });


   function unable(){
      document.addEventListener('touchmove',preventDefault);
  }
  function preventDefault(e){
      e.preventDefault();
  }
  function enable(){
      document.removeEventListener('touchmove',preventDefault);
  }
  //超出隐藏
function hideText(obj,num){

  var o = document.getElementById(obj);
  if(o){
    var s = o.innerHTML.replace(/<[^>]+>/g,"");
    var p = document.createElement("p");
    var n = document.createElement("span");
    var b = document.createElement("div");
    p.innerHTML = s.substring(0,num);
    n.innerHTML = s.length > num ? "..." : "";
    b.innerHTML = s.length > num ? "<div class='ui-border-t hf-tc' style='line-height:40px;'><span class='hf-fblue'>展开<i class='hf-icon-arrdown'></i></span></div>" : "";
    b.onclick = function(){
      if (n.innerHTML == "..."){
        n.innerHTML = "";
        b.innerHTML = "<div class='ui-border-t hf-tc' style='line-height:40px;'><span class='hf-fblue'>收起<i class='hf-icon-arrup'></i></span></div>";
        p.innerHTML = s;
      }else{
        n.innerHTML = "...";
        b.innerHTML = "<div class='ui-border-t hf-tc' style='line-height:40px;'><span class='hf-fblue'>展开<i class='hf-icon-arrdown'></i></span></div>";
        p.innerHTML = s.substring(0,num)+'<span>...</span>';
      }

    }
    o.innerHTML = "";
    o.appendChild(p);
    p.appendChild(n);
    o.appendChild(b);
  }

}

//关闭
function closes(obj){
  var a = document.getElementById(obj);
  a.style.display= 'none';
}