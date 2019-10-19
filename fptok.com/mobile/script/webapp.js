var Loadpic = '../image/loading.png';
var blankgif = '../image/blank.gif';
var loadpage = '<div class=""> <span class="h5">数据加载中...</span></div>';
var dialog = new auiDialog();
var toast = new auiToast();
var DMURL = document.location.protocol+'//'+location.hostname+(location.port ? ':'+location.port : '')+'/';
if(DTPath.indexOf(DMURL) != -1) DMURL = DTPath;
var MAJPath = DMURL+'mobile/majax.php';
function laymsg(msg,f) {
	layer.open({
    content: msg,
	shade:false,
    style: 'text-align:left;background-color:rgba(0,0,0,.6);border:0; color:#FFF;font-size:15px;border-radius:50px;-webkit-animation-timing-function: ease-in-out; animation-timing-function: ease-in-out; -webkit-animation-name: headShake; animation-name: headShake;',
    time: 2
});
	}
	
function layopen(id,time) {
if(time){
  setTimeout(function(){
  layer.open({
  type: 1,
  content: $('#'+id+'').html(), //这里content是一个DOM
  style: 'position:absolute; left:0; bottom:0; width:100%; height:50%; border:none;'

});
}, time);
}else{
  layer.open({
  type: 1,
  content: $('#'+id+'').html(), //这里content是一个DOM
  style: 'position:absolute; left:0; bottom:0; width:100%; height:50%; border:none;',

});
}
}

function Dd(i) {return document.getElementById(i);}
function Ds(i) {Dd(i).style.display = '';}
function Dh(i) {Dd(i).style.display = 'none';}
function Go(u) {window.location = u;}
var tID=0;
function Tab(ID) {
	var tTab = Dd('Tab'+tID); var tTabs = Dd('Tabs'+tID); var Tab = Dd('Tab'+ID); var Tabs = Dd('Tabs'+ID);
	if(ID!=tID)	{tTab.className='tab'; Tab.className='active'; tTabs.style.display='none'; Tabs.style.display=''; tID = ID; try{Dd('tab').value=ID;}catch(e){}}
}
function tabbar(i) { try { Dd(i).className = 'active'; } catch(e) {} }
function s(i) { try { Dd(i).className = 'active'; } catch(e) {} }
function v(i) { if(Dd(i).className == 'side_a') Dd(i).className = 'side_c'; }
function t(i) { if(Dd(i).className == 'side_c') Dd(i).className = 'side_a'; }
function c(i) {
	if(!$('#menu_0')[0]) return;
	for(var j = 0; j < 4; j++) {
		if(j == i) {
			Dd('menu_'+j).className = 'menu_1';
			Ds('sub_'+j);
		} else {
			Dd('menu_'+j).className = 'menu_2';
			Dh('sub_'+j);
		}
	}
	window.scrollTo(0,0);
}
function oh(o) {
	if(o.className == 'side_h') {
		Dh('side');o.className = 'side_s';
		set_local('m_side', 'Y');
	} else {
		Ds('side');o.className = 'side_h';
		set_local('m_side', 'N');
	}
}

function towindow(action, cancel, msg){
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

function delitems(pathrul, itemid) {
dialog.alert({
  title:"温馨提示",
  msg:'确定删除信息吗',
  buttons:['取消','确定']
    },function(ret){
    if(ret){
	  if(ret.buttonIndex==2){
$.post(pathrul, { action: "delete", itemid: itemid },function(data){
     		if(data.error == 'ok') {
				laymsg('狠心删除');
				window.location = data.path;
		} else {
			laymsg(data.error);
		}
	},'json');

	}
 }
})

}

function checkall(f, t) {
	var t = t ? t : 1;
	for(var i = 0; i < f.elements.length; i++) {
		var e = f.elements[i];
		if(e.type != 'checkbox' || e.name == 'msg' || e.name == 'eml' || e.name == 'sms' || e.name == 'wec') continue;
		if(t == 1) e.checked = e.checked ? false : true;
		if(t == 2) e.checked = true;
		if(t == 3) e.checked = false;	
	}
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
                        msg:'这里是内容',
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