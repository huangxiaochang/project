
// 导航浮动
var $fixed = $('.fixed')
, $navDt = $fixed.find('.nav-dt')
, isKeep = $navDt.hasClass('keep');

$fixed.length &&
$fixed
.on('scrollUp', function () {
    $fixed.removeClass('fixed-fixed-down')
})
.on('scrollDown', function () {
    $fixed.addClass('fixed-fixed-down')
})
.on('fixed', function () {
    $fixed.addClass('fixed-fixed');
    isKeep && $navDt.removeClass('keep');
})
.on('unfixed', function () {
    $fixed.removeClass('fixed-fixed');
    isKeep && $navDt.addClass('keep');
})
.on('click', '.close', function () {
    $fixed.removeClass('fixed-fixed-down').off('scrollUp scrollDown');
})
.jqFixed($fixed.data());
// 导航下拉列表
var $navHover = $('.nav').find('.hover');
$('.nav')
.on('mouseenter', 'li:not(".keep")', function () {
    var $this = $(this);
    if ($this.is($navHover)) return;
    $this.addClass('hover')
    $navHover.removeClass('hover')
})
.on('mouseleave', 'li:not(".keep")', function () {
    var $this = $(this);
    if ($this.is($navHover)) return;
    $this.removeClass('hover')
    $navHover.addClass('hover')
})
// 顶部下拉列表
var $TopHover = $('.top-nav').find('.hover');
$('.top-nav')
    .on('mouseenter', 'li:not(".keep")', function () {
        var $this = $(this);
        if ($this.is($TopHover)) return;
        $this.addClass('hover')
        $navHover.removeClass('hover')
    })
    .on('mouseleave', 'li:not(".keep")', function () {
        var $this = $(this);
        if ($this.is($TopHover)) return;
        $this.removeClass('hover')
        $navHover.addClass('hover')
    })
// 检索
$('.condition')
.on('mouseenter', 'dd', function () {
    $(this).addClass('hover')
})
.on('mouseleave', 'dd', function () {
    $(this).removeClass('hover')
})


/**
 * 格式化时间
 * @return {[type]} 2014-10-10 10:10:10
 */
function getLocalTime(nS,T) {
    var myDate = new Date(parseInt(nS) * 1000);
    var myDateStr = myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + (myDate.getDate() < 10 ? '0' : '') + myDate.getDate();
    if (T==1) {
        var _nS = parseInt(new Date().getTime()/1000) - nS;
        switch(true){
            case _nS < 60:
                return '才刚刚';
            break;
            case _nS < 1800:
                return Math.floor(_nS / 60) + '分钟前'
            break;
            case _nS < 3600:
                return '半小时前';
            break;
            case _nS < 86400:
                return Math.floor(_nS / 3660) + '小时前';
            break;
            case _nS < 86400 * 30:
                return Math.floor(_nS / 86400) + '天前';
            break;
            default :
                return Math.floor(_nS / 86400 / 30) + '个月前';
            break;
        }
    }else if(T==2){
        return myDateStr + ' ' + myDate.getHours() + ':' + myDate.getMinutes() + ':' + myDate.getSeconds();
    }else{
        return myDateStr ;
    }
}


