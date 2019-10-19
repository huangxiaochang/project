		var hammertime = null;
		var maxWidth = 0;
		var marginLeft = 0;
		function addPan(obj) {
			marginLeft = 0;
			// 获取最大的滑动值
			maxWidth = obj.siblings(".hidden-menu").width();
			hammertime = new Hammer(obj[0], {
				domEvents : true
			});
			// 开始拖动
			hammertime.on("panstart", function(e) {
				obj.css({
					"transition" : "margin-left 0s",
					"-webkit-transition" : "margin-left 0s",
				});
				marginLeft = parseInt(obj.css("margin-left"), 10);
				obj.parent().siblings().each(function() {
					$(this).children(".drop-div").css({
						"transition" : "margin-left 0.2s",
						"-webkit-transition" : "margin-left 0.2s",
						"margin-left" : "0px"
					});
				});
			});
			// 拖动过程
			hammertime.on("panmove", function(e) {
				var panRange = marginLeft + e.deltaX;
				if (panRange < 0 && panRange > -maxWidth) {
					obj.css({
						"margin-left" : panRange + "px"
					});
				}
			});
			// 拖动结束
			hammertime.on("panend", function(e) {
				marginLeft = parseInt(obj.css("margin-left"));
				if (e.deltaX < 0) {
					if (marginLeft > -30) {
						obj.css({
							"transition" : "margin-left 0.2s",
							"-webkit-transition" : "margin-left 0.2s",
							"margin-left" : "0px"
						});
					} else {
						obj.css({
							"transition" : "margin-left 0.2s",
							"-webkit-transition" : "margin-left 0.2s",
							"margin-left" : (-maxWidth) + "px"
						});
					}
				} else {
					if (marginLeft < -(maxWidth - 30)) {
						obj.css({
							"transition" : "margin-left 0.2s",
							"-webkit-transition" : "margin-left 0.2s",
							"margin-left" : (-maxWidth) + "px"
						});
					} else {
						obj.css({
							"transition" : "margin-left 0.2s",
							"-webkit-transition" : "margin-left 0.2s",
							"margin-left" : "0px"
						});
					}
				}
			});
		}

function delPress(obj,index) {
}

jQuery(document).ready(function() {
			$(".aui-in li").each(function() {
				// 添加手势
				addPan($(this).children(".drop-div"));
			});
			$(".drop-div").on("tap", function() {
				$(".drop-div").css({
					"transition" : "margin-left 0.2s",
					"-webkit-transition" : "margin-left 0.2s",
					"margin-left" : "0px"
				});
			});
			$(".drop-div").on("press", function() {
				$("#check-menu").show();
				if(moduleid==12){
					$('[id=picadds]').addClass("displaynone");
					}
				//$("#check-menu").removeClass("displaynone");
                $("#check-menu").addClass("div-fadeIn");
                setTimeout(function(){$("#check-menu").addClass("div-fadeOut");},100); 
				//$("#check-menu").fadeIn();
				$("input[id='itemids']").each(function () {
				$(this).removeClass("displaynone");
			});
			});
		});

function delitemid(mid,itemid,dresume,djob) {
dialog.alert({
  title:"温馨提示",
  msg:'确定删除信息吗',
  buttons:['取消','确定']
    },function(ret){
    if(ret){
	  if(ret.buttonIndex==2){
		 if(!dresume) dresume = 0;
        $.post("user.php", { action: "delete", mid: mid, itemid: itemid, resume: dresume,job:djob},function(data){
     	 if(data.error == 'ok') {
		 $('#'+itemid+'').remove();
		 laymsg('狠心删除');
		} else {
		 laymsg(data.error);
		}
	},'json');

	}
 }
})
}

function delitemall(mid,dresume) {
var arrids = [];  
        $('input[name=itemid]:checked').each(function(i){ 
                arrids.push($(this).val());  
        }); 
		 
	len = arrids.length;
	if(len==0) {
		laymsg('未选择任何信息');
		return false;
	}
	
dialog.alert({
  title:"温馨提示",
  msg:'确定删除信息吗',
  buttons:['取消','确定']
    },function(ret){
    if(ret){
	  if(ret.buttonIndex==2){
	  if(!dresume) dresume = 0;
$.post("user.php", { action: "delete", mid: mid, itemid: arrids, resume: dresume},function(data){
     		if(data.error == 'ok') {
				laymsg('狠心删除');
				location.reload();
		} else {
			laymsg(data.error);
		}
	},'json');

	}
 }
})
	
}

function refreshall(mid) {
var arrids = [];  
        $('input[name=itemid]:checked').each(function(i){ 
                arrids.push($(this).val());  
        }); 
		 
	len = arrids.length;
	if(len==0) {
		laymsg('未选择任何信息');
		return false;
	}

dialog.alert({
  title:"温馨提示",
  msg:'确定刷新信息吗',
  buttons:['取消','确定']
    },function(ret){
    if(ret){
	  if(ret.buttonIndex==2){
$.post("user.php", { action: "refresh", mid: mid, itemid: arrids },function(data){
     		if(data.error == 'ok') {
				laymsg('刷新成功');
				location.reload();
		} else {
			laymsg(data.error);
		}
	},'json');

	}
 }
})

}

function checkreset() {
	//$("#check-menu").fadeOut();
	$("#check-menu").removeClass("div-fadeOut");
	if(moduleid==12){
	 $('[id=picadds]').removeClass("displaynone");
	}
	setTimeout(function(){$("#check-menu").hide();},300);
	//$("#check-menu").addClass("displaynone");
	$("input[name='itemid']").each(function () {
	$(this).addClass("displaynone");
	checkall(this.form,3)
});
}