//$("#uploading").hide();
var progress = $("#container").Progress({
	percent: 10,
	width: 180,
	height: 20,
	fontSize: 14
});
var filechooser = document.getElementById("uploadimg");
var maxsize = 100 * 1024;
var maxnums = 10;
var scriptArgs = document.getElementById('uploadimg').getAttribute('data');
$("#addpic").on("click", function() {
imgnums = $("#imgslist li:not(#addpicend)").size();
	  if(imgnums>=maxnums){
	   laymsg("您只能上传10张图片");
	  return false;
}

filechooser.click();
});
document.querySelector('#uploadimg').addEventListener('change', function () {
	if (!this.files.length) return;
    var files = Array.prototype.slice.call(this.files);

    var filenums = files.length;
	if (filenums>10) {

      laymsg("一次性最多上传10张图片");
      return false;
    }
    if (filenums + imgnums>10) {

      laymsg("最多只可上传10张图片");
      return false;
    }
    laymsg("图片压缩上传中...");
	$("#uploading").show();
	    files.forEach(function(file, i) {

      if (!/\/(?:jpeg|png|gif|jpg)/i.test(file.type)) return;
	  
	  	 var reader = new FileReader();
		 reader.onload = function() {
        var result = this.result;
		progress.percent(20);
		upload(result);
		 };
		 reader.readAsDataURL(file);
		});
});


function upload(files) {

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
            progress.percent(70);
			var from = 'photo';
			var theimg = rst.base64;
			$.ajax({
		   type: "POST",
		   url: "upload.php",
		   data: {"moduleid":moduleid,"from":from,"base64":rst.base64,"size":rst.fileLen,"width":thumb_width,"height":thumb_height,"itemid":itemid},
		   dataType:"json",
		   success: function(data){
			   progress.percent(100);
			   if (data.status == 0) {
				   
		$.post('user.php?mid=12&action=upsuccess', { "itemid": itemid, "thumb": data.url }, function(data) {
		if(data.error == 'ok') {
			var upitemid = data.itemid;
			var showImgHtml = '<li class="aui-list-view-cell aui-img aui-col-xs-6" id="thumb'+upitemid+'"><input type="hidden" name="post['+upitemid+'][thumb]" id="thumb'+upitemid+'" value="'+data.url+'"/><span class="photo-cover" onclick=\"Pcover('+upitemid+')\"><i class="aui-iconfont aui-icon-like aui-text-white fz22"></i></span><span class="photo-del" onclick=\"Delphoto('+upitemid+')\"><i class="aui-iconfont aui-icon-close aui-text-white fz22"></i></span><a href="'+data.url+'"><img class="aui-img-object" src="'+data.url+'"></a><div class="aui-img-body"><textarea name="post['+upitemid+'][introduce]" id="introduce" class="H-textarea H-form-text H-flex-item" style="max-height:50px !important; border: 1px solid #EBEBEB;" placeholder="简介" ></textarea></div></li>';
			$("#addpicend").before(showImgHtml);
			baguetteBox.run('#thumb'+upitemid+'','','#thumb'+upitemid+'');
			pitems = pitems + 1;
			$('#pitems').html(pitems);	
			$("#uploading").hide();
			progress.percent(10);
			laymsg('上传成功');
		} else {
			laymsg(data.error);
		}
	},'json');
			   //var showImgHtml = '<li><a href="'+data.url+'"><img src="'+data.url+'"></a></li>';
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
			   $("#uploading").hide();
			   progress.percent(10);
			   $("#uploadimg").val(null);
			}
		}); 
            return rst;
        })
}

function toFixed2 (num) {
    return parseFloat(+num.toFixed(2));
}

function Pcover(itemid) {
	
dialog.alert({
  title:"温馨提示",
  msg:'设置为封面',
  buttons:['取消','确定']
    },function(ret){
    if(ret){
	  if(ret.buttonIndex==2){
			laymsg('设置成功');
			var Pcoverimg = ($('#thumb'+itemid+'').find("img").attr("src"));
			$("#itemthumb").val(Pcoverimg);
			$("#coverthumb").attr('src',Pcoverimg);
	}
 }
})
}

function Delphoto(itemid) {
	
dialog.alert({
  title:"温馨提示",
  msg:'确定删除图片吗',
  buttons:['取消','确定']
    },function(ret){
    if(ret){
	  if(ret.buttonIndex==2){
		$.post('user.php?mid=12&action=item_delete', { "itemid": itemid}, function(data) {
		if(data.error == 'ok') {
			laymsg('狠心删除');
			$('#thumb'+itemid+'').remove();
			pitems = pitems - 1;
			$('#pitems').html(pitems);
		} else {
			laymsg(data.error);
		}
	},'json');
	}
 }
})
}