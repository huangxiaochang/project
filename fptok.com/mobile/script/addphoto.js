//$("#upphotoing").hide();
var progressp = $("#containerp").Progress({
	percent: 10,
	width: 180,
	height: 20,
	fontSize: 14
});
var filechoosers = document.getElementById("uploadphoto");
var maxsize = 100 * 1024;
var maxpnums = 5;
$("#addphoto").on("click", function() {
imgnums = $("#imgslists li:not(#addpicend)").size();
	  if(imgnums>=maxpnums){
	   laymsg("您只能上传5张图片");
	  return false;
}

filechoosers.click();
});
document.querySelector('#uploadphoto').addEventListener('change', function () {
	if (!this.files.length) return;
    var files = Array.prototype.slice.call(this.files);

    var filenums = files.length;
	if (filenums>5) {

      laymsg("一次性最多上传5张图片");
      return false;
    }
    if (filenums + imgnums>5) {

      laymsg("最多只可上传5张图片");
      return false;
    }

	$("#upphotoing").show();
	    files.forEach(function(file, i) {

      if (!/\/(?:jpeg|png|gif|jpg)/i.test(file.type)) return;
	  
	  	 var reader = new FileReader();
		 reader.onload = function() {
        var result = this.result;
		progressp.percent(20);
		uploadp(result);
		 };
		 reader.readAsDataURL(file);
		});
});


function uploadp(files) {

    // this.files[0] 是用户选择的文件
    lrz(files, {
        width: 880,
		quality: 0.5
    })
        .then(function (rst) {
            // 把处理的好的图片给用户看看呗
            var img = new Image();
            img.src = rst.base64;
			img.size = rst.fileLen;
			sourceSize = toFixed2(files.size / 1024),
            resultSize = toFixed2(rst.base64Len / 1024),
            img.onload = function () {
            };
            return rst;
        })
        .then(function (rst) {
            progressp.percent(70);
			var from = 'photo';
			var theimg = rst.base64;
			$.ajax({
		   type: "POST",
		   url: "upload.php",
		   data: {"moduleid":moduleid,"from":from,"base64":rst.base64,"size":rst.fileLen,"width":thumb_width,"height":thumb_height},
		   dataType:"json",
		   success: function(data){
			   progressp.percent(100);
			   if (data.status == 0) {
			delitems = delitems + 1;
			Pcovers = $("#imgslist li:not(#addpic)").size();	   
			var showImgHtmls = '<li class="aui-list-view-cell aui-img aui-col-xs-6" id="thumbid'+delitems+'"><span class="photo-cover" onclick=\"Pcover('+delitems+')\"><i class="aui-iconfont aui-icon-like aui-text-white fz22"></i></span><span class="photo-del" onclick=\"Delphotos('+delitems+')\"><i class="aui-iconfont aui-icon-close aui-text-white fz22"></i></span><a href="'+data.url+'"><img class="aui-img-object" src="'+data.url+'"></a><div class="aui-img-body"></div></li>';
			$("#addpicend").before(showImgHtmls);
			if(pitems == 0 && Pcovers == 0){
		    var showImgHtml = '<li><a href="'+data.url+'" id="coverthumba"><img src="'+data.url+'" id="coverthumb"></a></li>';
			$("#addpic").before(showImgHtml);
			addPress($("#imgslist a[href='" + data.url + "']").parent("li"));
}
			baguetteBox.run('#thumbid'+delitems+'','','#thumbid'+delitems+'','imgslists');
			pitems++;
			$('#pitems').html(pitems);	
			$("#upphotoing").hide();
			progressp.percent(10);
			laymsg('上传成功');

			return false;
			 }else{
				laymsg(data.content);
				//$(".imglist").append(attstr); 
			 }
		   }, 
			complete :function(XMLHttpRequest, textStatus){
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){ //上传失败 
			   laymsg('上传失败，请重新上传');
			   $("#delAlbum").css('display','none');
			   $("#upphotoing").hide();
			   progressp.percent(10);
			   $("#uploadphoto").val(null);
			}
		}); 
            return rst;
        })
}

function toFixed2 (num) {
    return parseFloat(+num.toFixed(2));
}

//$(".photo-del").click(function(){
//$(this).parents("li").remove();
//});

function Pcover(obj) {
	
dialog.alert({
  title:"温馨提示",
  msg:'设置为封面',
  buttons:['取消','确定']
    },function(ret){
    if(ret){
	  if(ret.buttonIndex==2){
			laymsg('设置成功');
			Pcovers = $("#imgslist li:not(#addpic)").size();
			var Pcoverimg = ($('#thumbid'+obj+'').find("img").attr("src"));
			if(Pcovers==0){
            var showImgHtml = '<li><a href="'+Pcoverimg+'" id="coverthumba"><img src="'+Pcoverimg+'" id="coverthumb"></a></li>';	
			$("#addpic").before(showImgHtml);
			addPress($("#imgslist a[href='" + Pcoverimg + "']").parent("li"));	
			}else{
			$("#coverthumba").attr('href',Pcoverimg);
			$("#coverthumb").attr('src',Pcoverimg);
			}
	}
 }
})
}

function Delphotos(obj) {
	
dialog.alert({
  title:"温馨提示",
  msg:'确定要删除吗',
  buttons:['取消','确定']
    },function(ret){
    if(ret){
	  if(ret.buttonIndex==2){
			laymsg('狠心删除');
			$('#thumbid'+obj+'').remove();
			pitems = pitems - 1;
			$('#pitems').html(pitems);
	}
 }
})
}