/*浮点数相加*/
function accAdd(num1,num2){
    num1 = Number(num1);
    num2 = Number(num2);
    var r1,r2,m;
    try{
        r1 = num1.toString().split( '.')[1].length;
    }catch(e){
        r1 = 0;
    }
    try{
        r2=num2.toString().split( ".")[1].length;
    }catch(e){
        r2=0;
    }
    m=Math.pow(10,Math.max(r1,r2));
    // return (num1*m+num2*m)/m;
    return Math.round(num1*m+num2*m)/m;
}
 
 //浮点数相减
function accSub(num1,num2){
    num1 = Number(num1);
    num2 = Number(num2);
    var r1,r2,m;
    try{
        r1 = num1.toString().split( '.')[1].length;
    }catch(e){
        r1 = 0;
    }
    try{
        r2=num2.toString().split( ".")[1].length;
    }catch(e){
        r2=0;
    }
    m=Math.pow(10,Math.max(r1,r2));
    n=(r1>=r2)?r1:r2;
    return (Math.round(num1*m-num2*m)/m).toFixed(n);
}
 
 //两浮点数相除
function accDiv(num1,num2){
    num1 = Number(num1);
    num2 = Number(num2);
    var t1,t2,r1,r2;
    try{
        t1 = num1.toString().split( '.')[1].length;
    }catch(e){
        t1 = 0;
    }
    try{
        t2=num2.toString().split( ".")[1].length;
    }catch(e){
        t2=0;
    }
    r1=Number(num1.toString().replace( ".","" ));
    r2=Number(num2.toString().replace( ".","" ));
    return (r1/r2)*Math.pow(10,t2-t1);
}
 
 //浮点数相乘
function accMul(num1,num2){
    num1 = Number(num1);
    num2 = Number(num2);
    var m=0,s1=num1.toString(),s2=num2.toString();
    try{m+=s1.split( ".")[1].length;}catch(e){};
    try{m+=s2.split( ".")[1].length;}catch(e){};
    return Number(s1.replace("." ,"" ))*Number(s2.replace(".", ""))/Math.pow(10,m);
}

//全局变量
var defaultRate = '4.90',
    defaultRateList = ['4.35','4.75','4.75','4.75','4.75','4.90'];  //1,2,3,4,5,5年以上年基准利率

var defaultRates = '3.25',
    defaultRateLists = ['2.75','2.75','2.75','2.75','2.75','3.25'];  //1,2,3,4,5,5年以上年基准利率

$(function(){
    
    //控制输入
    $('.f-select-input').keyup(function(){
      if($(this).attr('max') == undefined || $(this).attr('max') == ''){
        return;
      }
      if(parseInt(this.value)>parseInt($(this).attr('max'))){
        this.value=parseInt($(this).attr('max'))
      }
    })
    //提交按钮
    $('.f-select-submit').on('click',function(){
      $(this).parent().parent().toggleClass('show');
    })
    //弹出
    !function(){
      var $selectRate= $('.select-rate');
      var $selectRates= $('.select-rate.two');
      var $selectYear= $('.select-year');
      var $selectYears= $('.select-year.two');

      $selectRate.each(function(){
        this.addEventListener('touchstart',function(){
            $this = this;
            showSelect({element:this,name:'rate',rate:defaultRate,rateLists:defaultRateList})
        })
      })

      $selectRates.each(function(){
        this.addEventListener('touchstart',function(){
            $this = this;
            showSelect({element:this,name:'rate',rate:defaultRates,rateLists:defaultRateLists})
        })
      })

      $selectYear.each(function(){
        this.addEventListener('touchstart',function(){
            $this = this;
            showSelect({element:this,name:'year',ratename:'defaultRate',rateLists:defaultRateList})
        })
      })
      $selectYears.each(function(){
        this.addEventListener('touchstart',function(){
            $this = this;
            showSelect({element:this,name:'year',ratename:'defaultRates',rateLists:defaultRateLists})
        })
      })

    }()

  })
//显示 select 下拉框
function showSelect(option,callback){
    if(option.element == undefined || $(option.element).length==0){
        return;
    }
    var $this = $(option.element),
        name = option.name,
        rateLists = option.rateLists,
        ratename = option.ratename,
        html = '',
        rateList = [['基准利率',1],['7折',0.7],['85折',0.85],['1.1倍',1.1]],
        yearList = [],
        rate = 0;
    if(option.rate==undefined){
        rate= 4.9;
    }else{
        rate= option.rate;
    }
    $('.f-select-content').html('');
    if(name == 'rate'){
        for(var i=0;i<rateList.length;i++){
          html +='<li class="ui-row border-bottom mgnone plr15">'
               + ' <div class="ui-col ui-col-25">'
               + '   <span>'+rateList[i][0]+'</span>'
               + ' </div>'
               + ' <div class="ui-col ui-col-75">'
               + '   <div class="ui-col ui-col-100 text-right">'
               + '     <label class="ui-radio small" for="radio">'
               + '       <input type="radio" name="radio-select" value="'+accMul(rate,rateList[i][1])+'" checked="checked">'
               + '     </label>'
               + '   </div>'
               + ' </div>'
               + '</li>';
        }
        $('.f-select-top').show();
        //控制高度
        $('.f-select-content').html(html);
        $('.f-select-content').parent().css({
          height : $('.f-select-content li').height()*2.5
        })
        $('.f-select-content li').eq(0).find('input').prop('checked',true);
        $('.f-select-top').find('input').val(accMul(rate,rateList[0][1]));
        $('.f-select-content li').find('input').on('click',function(){
            $('.f-select-top').find('input').val(this.value);
        })

        $('.f-select-submit').off('click.value');
        $('.f-select-submit').on('click.value',function(){
            var val=$('.f-select-top').find('input').val();
            $this.empty();
            $this.html('<option value="'+val+'">'+val+' %</option>');
        });

    }
    if(name == 'year'){
        for(var i=1;i<31;i++){
          html +='<li class="ui-row border-bottom mgnone plr15">'
               + ' <div class="ui-col ui-col-50">'
               + '   <span>'+i+'年（'+i*12+'期）'+'</span>'
               + ' </div>'
               + ' <div class="ui-col ui-col-50">'
               + '   <div class="ui-col ui-col-100 text-right">'
               + '     <label class="ui-radio small" for="radio">'
               + '       <input type="radio" data-name="'+i+'年（'+i*12+'期）'+'" name="radio-select" value="'+i*12+'" checked="checked">'
               + '     </label>'
               + '   </div>'
               + ' </div>'
               + '</li>';
        }
        $('.f-select-top').hide();
        $('.f-select-content').html(html);
        $('.f-select-content').parent().css({
          height : $('.f-select-content li').height()*5
        })
        // $('.f-select-content li').eq(0).find('input').prop('checked',true);
        $('.f-select-submit').off('click.value');
        $('.f-select-submit').on('click.value',function(){
            var val=$('.f-select-content li').find('input:checked').val();
            var name=$('.f-select-content li').find('input:checked').attr('data-name');
            $this.empty();
            $this.html('<option value="'+val+'">'+name+'</option>');
        });
        $('.f-select-content li').find('input').on('click',function(){
          window[ratename]= rateLists[parseInt(this.value/12)-1] == undefined?rateLists[rateLists.length-1]:rateLists[parseInt(this.value/12)-1];
          // console.log($this.closest('.ui-panel'))
          $this.closest('.ui-panel').find('.select-rate').empty();
          $this.closest('.ui-panel').find('.select-rate').html('<option value="'+window[ratename]+'">'+window[ratename]+' %</option>');
        })
    }
    

    //
    $('.ui-actionsheet').addClass('show');
    var scroll = new fz.Scroll('.ui-scroller', {
        scrollY: true
    });
}


//计算公积金贷款
function count(option,callback){
  var $this = option.element,
      loanType = option.loanType,//还款方式
      loanNum = [],//贷款金额 数组
      loanMonth = option.loanMonth,//贷款月数  数组
      loanRate = [],//贷款年利率(需转换成月利率)  数组
      count = 0,// 贷款金额
      loancount = [],//还款总额
      locanaccrual = [],//利息
      loanmean = [],//平均月供
      loanfirst = [],//首月还款
      loanlessen = [];//每月递减

  // console.log($this,loanType,loanNum,loanMonth,loanRate)
  for(x in option.loanNum){
    loanNum.push(option.loanNum[x]*10000)   //转换为元
  }

  for(x in option.loanRate){
    loanRate.push(accDiv(option.loanRate[x],1200))  //转换为月利率
  }


  if(loanType==1){
    for(x in loanNum){
        count = accAdd(count,loanNum[x]);//贷款总额计算
    }

    for(x in loanRate){

        loanmean.push(accDiv(accMul(loanNum[x],accMul(loanRate[x],Math.pow(1+loanRate[x],loanMonth[x]))),accSub(Math.pow(1+loanRate[x],loanMonth[x]),1)));

        loancount.push(accMul(loanmean[x],loanMonth[x]));
        
        locanaccrual.push(accSub(loancount[x],loanNum[x]));
    }

    showCanvas({
        element:$this,
        loancount:loancount,
        count:count,
        locanaccrual:locanaccrual,
        loanmean:loanmean,
        loanMonth:loanMonth
      })

  }
  if(loanType==2){
    for(x in loanNum){
        count = accAdd(count,loanNum[x]);//贷款总额计算
    }
    // console.log(loanRate)
    for(x in loanRate){
        locanaccrual.push(accDiv(accMul(accMul((accAdd(loanMonth[x],1)),loanNum[x]),loanRate[x]),2));
        loancount.push(accAdd(locanaccrual[x],loanNum[x]));
        loanfirst.push(accAdd(accDiv(loanNum[x],loanMonth[x]),accMul(loanNum[x],loanRate[x])));
        loanlessen.push(accMul(accDiv(loanNum[x],loanMonth[x]),loanRate[x]));
    }

    showCanvas({
        element:$this,
        loancount:loancount,
        count:count,
        locanaccrual:locanaccrual,
        loanfirst:loanfirst,
        loanlessen:loanlessen,
        loanMonth:loanMonth
      })

  }

  // console.log($this,loancount,count,locanaccrual,loanmean,loanMonth)

  // console.log($this,loancount,count,locanaccrual,loanfirst,loanlessen)
  
}

function showCanvas(option,callback){
    var $this = option.element,
        loancount = option.loancount,//还款总额
        count = option.count,
        locanaccrual = option.locanaccrual,//利息
        loanmean = option.loanmean,//平均月供
        loanMonth = option.loanMonth,//贷款月数  数组
        loanfirst = option.loanfirst,//首月还款
        loanlessen = option.loanlessen,//每月递减
        html = "";

    var $canvas = $this.find(".count-canvas");
    var $tip = $this.find('.count-tips');
    var $bottom = $this.find('.cout-tips-bottom');

    if(loanfirst != undefined){
        html +=  '<p class="hf-f16">首月还款</p>'
                +'<p class="hf-f29 hf-fred loanfirst">'+countlist(loanfirst)+'</p>'
                +'<p class=" hf-f12 ">每月递减&nbsp;<span class="hf-f18 hf-fred loanlessen">'+countlist(loanlessen)+'</span> 元</p>'
        $canvas.find('.content').html(html);

        $tip.find('.loancount').html(countlist(loancount));
        $tip.find('.count').html(count);
        $tip.find('.locanaccrual').html(countlist(locanaccrual));

        if(minlist(loanMonth)!==false){
             $bottom.html('第<span class="hf-fred"> '+(Number(loanMonth[minlist(loanMonth)])+1)+' </span>月还款<span class="hf-fred"> '
                          +Number(accSub(loanfirst[maxlist(loanMonth)],accMul(loanlessen[maxlist(loanMonth)],loanMonth[maxlist(loanMonth)]))).toFixed(2)
                          +' </span><span class="hf-fgray">元</span>，此后每月递减<span class="hf-fred"> '
                          +Number(loanlessen[minlist(loanMonth)]).toFixed(2)+' </span><span class="hf-fgray">元</span>')
        }


        $canvas.show();
        $tip.show();
        canvas($canvas.find('canvas')[0]);

        return;
    }

    if(loanmean != undefined){
        html += '<p class="hf-f16">参考月供</p>'
               +'<p class="hf-f29 hf-fred loanmean">'+countlist(loanmean)+'</p>'
               +'<p class=" hf-f15 ">元/月 (<span class="loanMonth"> '+countlist(loanMonth)+' </span>期)</p>';
        $canvas.find('.content').html(html);

        $tip.find('.loancount').html(countlist(loancount));
        $tip.find('.count').html(count);
        $tip.find('.locanaccrual').html(countlist(locanaccrual));

        if(minlist(loanMonth)!==false){
             $bottom.html('从第<span class="hf-fred"> '+(Number(loanMonth[minlist(loanMonth)])+1)+' </span>月起，参考月供<span class="hf-fred"> '
                          +Number(loanmean[maxlist(loanMonth)]).toFixed(2)
                          +' </span><span class="hf-fgray">元</span>')
        }

        $canvas.show();
        $tip.show();
        canvas($canvas.find('canvas')[0]);
    }


}

function countlist(list){
    var count = 0;
    for(x in list){
        count += accAdd(count,list[x])
    }
    return count.toFixed(2)
}

function minlist(list){
    if(list.length !=2) return false;
    if(list[0]==list[1]) return false;
    return list[0]<list[1]?0:1;

}

function maxlist(list){
    if(list.length !=2) return false;
    if(list[0]==list[1]) return false;
    return list[0]>list[1]?0:1;
}


function canvas(name){
  // var range = document.getElementById("range");
  var rangeValue; // 滑动条获得的是度数值

  var circle = name;
  var circleWidth = circle.width;
  var circleHeight = circle.height;
  var circleContext = circle.getContext("2d");

  var circleValue = {
      x: 83,
      y: 83,
      r: 76,
      beginAngle:(120 / 360) * 2 * Math.PI,
      endAngle: 0
  };

  // 样式
  circleContext.lineWidth = 10;
  circleContext.strokeStyle = "#8cd2f0";

  // 描绘进度圆环
  function drawCircle(deg) {
      circleContext.restore();
      // 清空当前路径
      circleContext.clearRect(0, 0, circleWidth, circleHeight);
      // 把range的度数值换成Math.PI值 range.value
      rangeValue = Number(deg);
      // 滑动条获得的是度数值
      circleValue.endAngle = circleValue.beginAngle + (rangeValue / 360) * 2 * Math.PI;
      circleContext.beginPath();
      // 绘制圆弧
      circleContext.arc(circleValue.x, circleValue.y, circleValue.r, circleValue.beginAngle, circleValue.endAngle, false);
      circleContext.lineCap = "round";
      circleContext.stroke();
      circleContext.save();
  }
  // 滚动条滑动动画
  // range.oninput = drawCircle;
  drawCircle(0);

  var i=0;
  function step(timestamp) { 
    i+=10;
    if(i <= 300){
      drawCircle(i);
      requestAnimationFrame(step);
    }
  }
  requestAnimationFrame(step);
}