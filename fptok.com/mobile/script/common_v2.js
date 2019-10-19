/*
	[dtjia System] Copyright (c) 2016 www.dtjia.top
*/
var handler = function(e) { //禁止浏览器默认行为
	e.preventDefault();
};
var dialog = new auiDialog();
var toast = new auiToast();

var DMURL = document.location.protocol+'//'+location.hostname+(location.port ? ':'+location.port : '')+'/';
if(DTPath.indexOf(DMURL) != -1) DMURL = DTPath;
var AJPath = DMURL+'ajax.php';

function Dd(i) {return document.getElementById(i);}
function Ds(i) {Dd(i).style.display = '';}
function Dh(i) {Dd(i).style.display = 'none';}
function Go(u) {window.location = u;}
function gotoTop(obj){
    var _targetTop = $('#'+obj).offset().top-50;//获取位置
    jQuery("html,body").animate({scrollTop:_targetTop},300);//跳转
	popup.hide(document.getElementById("top-right"))
  }
var tID=0;
function Tab(ID) {
	var tTab = Dd('Tab'+tID); var tTabs = Dd('Tabs'+tID); var Tab = Dd('Tab'+ID); var Tabs = Dd('Tabs'+ID);
	if(ID!=tID)	{tTab.className='aui-tab-item'; Tab.className='aui-tab-item aui-active'; tTabs.style.display='none'; Tabs.style.display=''; tID = ID; try{Dd('tab').value=ID;}catch(e){}}
}
function durlarea(id) {window.location = 'index.php?moduleid='+moduleid+'&catid='+catid+'&areaid='+id+'';}
function durlcat(id) {window.location = 'index.php?moduleid='+moduleid+'&catid='+id+'&areaid='+areaid+'';}
function Dback(u, r, e) {
	var m = e ? '/'+e+'/' : '';
	if(r && m && r.match(eval(m))) {
		Go(u ? u : 'index.php');
	} else if(r) {
		window.history.back();
	} else if(document.referrer) {
		window.history.back();
	} else {
		Go(u ? u : 'index.php');
	}
}
function GoPage(max, url) {
	if(max < 2) return;
	var page = parseInt(prompt('Go to page of (1-'+max+')', ''));
	if(page >= 1 && page <= max) Go(url.replace(/\{aijiacms_page\}/, page));
}
function DTrim(s) {
	s = s.trim();
	var t = encodeURIComponent(s);
	if(t.indexOf('%E2%80%86') != -1) s = decodeURIComponent(t.replace(/%E2%80%86/g, ''));
	return s;
}


function laymsg(msg) {
	layer.open({
    content: msg,
	shade:false,
    style: 'text-align:left;background-color:rgba(0,0,0,.6);border:0; color:#FFF;font-size:15px;border-radius:50px;',
	className: 'msg-layer',
    time: 2
});
	}
	
function layopen(id,time) {
if(time){
  setTimeout(function(){
  layer.open({
  type: 1,
  content: $('#'+id+'').html(), //这里content是一个DOM
  style: 'position:absolute; left:0; bottom:0; width:100%; height:50%; border:none;background:none;'

});
}, time);
}else{
  layer.open({
  type: 1,
  content: $('#'+id+'').html(), //这里content是一个DOM
  style: 'position:absolute; left:0; bottom:0; width:100%; height:50%; border:none; background:none;'

});
}
}

/*禁用ios弹性滚动*/
function BlockMove(event) {
    event.preventDefault();
}

function loginCallBack(rsp) {
	if (rsp.userId) {userid =rsp.userId;}
}

/*过滤*/
function strFilter1(suc) {
    var re = /\b(and|or|exec|execute|insert|select|delete|update|alter|create|drop|count|\*|chr|char|asc|mid|substring|master|truncate|declare|xp_cmdshell|restore|backup|net +user|net +localgroup +administrators)\b/;
    return suc.replace(re, '').replace(/</gi, "＜").replace(/>/gi, "＞");
}

//友好的时间返回函数
function friendly_time(time_stamp)
{
	//time_stamp = strtotime(time_stamp);	// 把日期时间解析为 Unix 时间戳 
    var now_d = new Date();
    var now_time = now_d.getTime() / 1000; //获取当前时间的秒数
    var f_d = new Date();
    f_d.setTime(time_stamp * 1000);
    var f_time = f_d.toLocaleDateString();

    var ct = now_time - time_stamp;
    var day = 0;
    if (ct < 0)
    {
        f_time = "【预约】" + f_d.toLocaleString();
    }
    else if (ct < 60)
    {
        f_time = Math.floor(ct) + '秒前';
    }
    else if (ct < 3600)
    {
        f_time = Math.floor(ct / 60) + '分钟前';
    }
    else if (ct < 86400)//一天
    {
        f_time = Math.floor(ct / 3600) + '小时前';
    }
    else if (ct < 604800)//7天
    {
        day = Math.floor(ct / 86400);
        if (day < 2)
            f_time = '昨天';
        else
            f_time = day + '天前';
    }
    else
    {
        day = Math.floor(ct / 86400);
        f_time = day + '天前';
    }
    return f_time;
}
	
function openDialog(type,title,msg,func){
	if(title==""||title==undefined) var title = '弹出提示';
        switch (type) {
            case "text":
                dialog.alert({
                    title:title,
                    msg:msg,
                    buttons:['取消','确定']
                },function(ret){
                })
                break;
            case "callback":
                dialog.alert({
                        title:title,
                        msg:msg,
                        buttons:['取消','确定']
                    },function(ret){
                        if(ret){
                            dialog.alert({
                                title:"提示",
                                msg:"您点击了第"+ret.buttonIndex+"个按钮",
                                buttons:['确定']
                            });
                        }
                    })
                break;
            case "goto":
                dialog.alert({
                        title:title,
                        msg:msg,
                        buttons:['取消','确定']
                    },function(ret){
                        if(ret){
                            if(ret.buttonIndex == 2){
								if(func) Go(func);
							}
                        }
                    })
                break;
            case "input":
                dialog.prompt({
                    title:title,
                    text:'默认内容',
                    buttons:['取消','确定']
                },function(ret){
                    if(ret.buttonIndex == 2){
                        dialog.alert({
                            title:"提示",
                            msg: "您输入的内容是："+ret.text,
                            buttons:['确定']
                        });
                    }
                })
                break;
            default:
                break;

        }
    }

    function showDefault(type){
        switch (type) {
            case "success":
                toast.success({
                    title:"提交成功",
                    duration:2000
                });
                break;
            case "fail":
                toast.fail({
                    title:"提交失败",
                    duration:2000
                });
                break;
            case "custom":
                toast.custom({
                    title:"提交成功",
                    html:'<i class="aui-iconfont aui-icon-laud"></i>',
                    duration:2000
                });
                break;
            case "loading":
                toast.loading({
                    title:"加载中",
                    duration:2000
                },function(ret){
                    //console.log(ret);
                    setTimeout(function(){
                        toast.hide();
                    }, 3000)
                });
                break;
            default:
                // statements_def
                break;
        }
    }

function set_cookie(n, v, d) {
	var e = ''; 
	var f = d ? d : 365;
	e = new Date((new Date()).getTime() + f * 86400000);
	e = "; expires=" + e.toGMTString();
	document.cookie = CKPrex + n + "=" + v + ((CKPath == "") ? "" : ("; path=" + CKPath)) + ((CKDomain =="") ? "" : ("; domain=" + CKDomain)) + e; 
}

function get_cookie(n) {
	var v = ''; var s = CKPrex + n + "=";
	if(document.cookie.length > 0) {
		o = document.cookie.indexOf(s);
		if(o != -1) {	
			o += s.length;
			end = document.cookie.indexOf(";", o);
			if(end == -1) end = document.cookie.length;
			v = unescape(document.cookie.substring(o, end));
		}
	}
	return v;
}

function Innerc(i,s) {try {Dd(i).innerHTML = s;}catch(e){}}

function Vcomm(id, op, nm) {
$("v_19").html(5);
	if(get_cookie('comment_vote_'+mid+'_'+itemid+'_'+id)) {
	laymsg('您已经对此评论表过态了');
	return;
	}
	$.get(AJPath+'?action=loadpl',{"job":"vote","moduleid":3,"mid":mid,"itemid":itemid,"cid":id,"op":op}, function(ret){
		if(ret.status=='ok'){
		Innerc('v_'+id+'', ++nm);
		laymsg('表态成功');
		//$('.pl').find("em").html(itemNum);
		//$('.my-cart').html('<em>'+(t+1)+'</em>');
		}else{
		laymsg(ret.status);
		}
	},'json'); 

}

function towindow(action, cancel, msg){
	if(action==0){
		$(".am-window").removeClass("am-modal-active");	
			setTimeout(function(){
				$(".windowbg-active").removeClass("windowbg-active");	
				$(".windowbg").remove();
				$('.am-window').html('');	
			},300);
			return false;
		}
		var arr = action.split('|');
		var htm = '<div class="am-window-con">';
		if(msg) htm += '<em>'+msg+'</em>';
		htm += '<ul class="am-window-sns">';
		for(var i=0;i<arr.length;i++) {
			if(i > 4) break;
			htm += '<li>'+arr[i]+'</li>';
		}
		htm += '</ul></div>';
		if(cancel) htm += '<p id="closeam">'+cancel+'</p>';
		$('.am-window').html(htm);

		$(".am-window").addClass("am-modal-active");	
		if($(".windowbg").length>0){
			$(".windowbg").addClass("windowbg-active");
		}else{
			$("body").append('<div class="windowbg"></div>');
			$(".windowbg").addClass("windowbg-active");
		}
		$(".windowbg-active,#closeam").click(function(){
			$(".am-window").removeClass("am-modal-active");	
			setTimeout(function(){
				$(".windowbg-active").removeClass("windowbg-active");	
				$(".windowbg").remove();
				$('.am-window').html('');	
			},300);
		})
	}	
