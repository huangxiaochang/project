<?php
defined('IN_AIJIACMS') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
  <style type="text/css">
          
            /**********上传样式***********/

            .progress{position:relative;padding: 1px; border-radius:3px; margin:30px 0 0 0;}
            .bar{background-color: green; display:block; width:0%; height:20px; border-radius:3px;}
            .percent{position:absolute; height:20px; display:inline-block;top:3px; left:2%; color:#fff}
            .progress{
                height: 100px;
                padding: 30px 0 0;
                width:100px;
                border-radius: 0;
            }
            .btns{-webkit-border-radius:3px; -moz-border-radius:3px; -ms-border-radius:3px; -o-border-radius:3px; border-radius:3px;
                 background-color:#ff8400; color:#FFF; display:inline-block; height:28px; line-height:28px; text-align:center; padding:0 12px; 
                 transition:background-color .2s linear 0s; border:0; cursor:pointer;text-decoration: none}
            .btns:hover{
	background-color: #e95a00;
	text-decoration: none;
	color: #FFF;
}
            .photos_area .item {
                float: left;
                margin: 0 10px 10px 0;
                position: relative;
            }
            .photos_area .item{position: relative;float:left;margin:0 10px 10px 0;}
            .photos_area .item img{border: 1px solid #cdcdcd;}
            .photos_area .operate{background: rgba(33, 33, 33, 0.7) none repeat scroll 0 0; bottom: 0; padding:5px 0; left: 0; position: absolute; width: 102px; z-index: 5; line-height: 21px; text-align: center;}
            .photos_area .operate i{cursor: pointer; display: inline-block; font-size: 0; height: 12px; line-height: 0; margin: 0 5px; overflow: hidden; width: 12px; background: url("<?php echo $AJPath;?>api/plupload/icon_aijia.png") no-repeat scroll 0 0;}
            .photos_area .operate .toright{background-position: -13px -13px; position: relative;top:1px;}
            .photos_area .operate .toleft{background-position: 0 -13px; position: relative;top:1px;}
            .photos_area .operate .del{background-position: -13px 0; position: relative;top:0px;}
            .photos_area .preview{background-color: #fff; font-family: arial; line-height: 90px; text-align: center; z-index: 4; left: 0; position: absolute; top: 0; height: 90px; overflow: hidden; width: 90px;}
           
        </style>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="post[houseid]" value="<?php echo $houseid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>

<input name="post[username]" type="hidden"  size="20" value="<?php echo $username;?>" id="username"/> 

<div class="tt"><?php echo $action == 'add' ? '添加' : '修改';?>相册</div>
<table cellpadding="2" cellspacing="1" class="tb">

<tr>
<td class="tl"><span class="f_red">*</span> 标题</td>
<td><input name="post[title]" type="text" size="10" value="" id="title"/> <span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"> 户型编号</td>
<td><input name="post[bh]" type="text" size="10" value="<?php echo $bh;?>" id="bh"/> <span id="dbh" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">户  型</td>
<td><select class="select" name="post[shi]">
																		 						 <option value="1" <?php if($shi == 1) echo 'selected';?>>1</option>
						 												 						 <option value="2" <?php if($shi == 2) echo 'selected';?>>2</option>
						 												 						 <option value="3" <?php if($shi == 3) echo 'selected';?>>3</option>
						 												 						 <option value="4" <?php if($shi == 4) echo 'selected';?>>4</option>
						 												 						 <option value="5" <?php if($shi == 5) echo 'selected';?>>5</option>
						 																		
						 						</select> 室<select class="select" name="post[ting]">
																		 						 <option value="0" <?php if($ting == 0) echo 'selected';?>>0</option>
						 												 						 <option value="1" <?php if($ting == 1) echo 'selected';?>>1</option>
						 												 						 <option value="2" <?php if($ting == 2) echo 'selected';?>>2</option>
						 												 						 <option value="3" <?php if($ting == 3) echo 'selected';?>>3</option>
						 												 						 <option value="4" <?php if($ting == 4) echo 'selected';?>>4</option>
						 												 						 <option value="5" <?php if($ting == 5) echo 'selected';?>>5</option>
						 												</select> 厅<select class="select" name="post[wei]">
						 												 						 <option value="0" <?php if($wei == 0) echo 'selected';?>>0</option>
						 												 						 <option value="1" <?php if($wei == 1) echo 'selected';?>>1</option>
						 												 						 <option value="2" <?php if($wei == 2) echo 'selected';?>>2</option>
						 												 						 <option value="3" <?php if($wei == 3) echo 'selected';?>>3</option>
						 												 						 <option value="4" <?php if($wei == 4) echo 'selected';?>>4</option>
						 												 						 <option value="5" <?php if($wei == 5) echo 'selected';?>>5</option>
						 												 </select> 卫</td>
</tr>
<tr>
<td class="tl"> 面积</td>
<td><input name="post[mj]" type="text" size="10" value="<?php echo $mj;?>" id="mj"/> 单位：M2<span id="dbh" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"> 单价</td>
<td><input name="post[dj]" type="text" size="10" value="<?php echo $dj;?>" id="dj"/> <span id="dbh" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">物业类型</td>
<td><?php echo $_admin == 1 ? category_select('post[catid]', '选择分类', $catid, 6) : ajax_category_select('post[catid]', '选择分类', $catid, 6);?></td>
</tr>
 <tr id="logo_area_tr" class='logo_area_tr'><td class="tl"><span class="f_red">*</span> 图片上传</td>
                        <td class="td_left"><a class="btns" id="logo_upload_btn" href="javascript:;" ><span  style="color: white;">上传图片</span> </a>


                            <div id="logo_upload_area" style='width:80%'>
                                <div id='photos_area' class="photos_area clearfix">
                                    <?php
                                    if (isset($pics)) {
                                        foreach ($pics as $v) {
                                            ?>
                                            <div class='item'>
                                                <input type='hidden' name='post[thumb][]' value='<?php echo $v; ?>'/>
                                                <img src='<?php echo $v; ?>'  width='100px' height='100px'/>
                                                <div class='operate'><i class='toleft'>左移</i><i class='toright'>右移</i><i class='del'>删除</i></div>
                                            </div>
    <?php }
} ?>
                          </div>
      </td>
                    </tr>
                    <script type="text/javascript" src="<?php echo $AJPath;?>api/plupload/jquery.js"></script>
        <script type="text/javascript" src="<?php echo $AJPath;?>api/plupload/plupload.full.min.js"></script>
        <script type="text/javascript">

            var uploader = new plupload.Uploader({
                runtimes: 'gears,html5,html4,silverlight,flash',
                browse_button: 'logo_upload_btn',
                url: "<?php echo $AJPath;?>api/uploadajax.php",
                flash_swf_url: 'plupload/Moxie.swf',
                silverlight_xap_url: 'plupload/Moxie.xap',
                filters: {
                    max_file_size: '25mb',
                    mime_types: [
                        {title: "files", extensions: "jpg,png,gif,jpeg"}
                    ]
                },
                multi_selection: true,
                init: {
                    FilesAdded: function(up, files) {
                        $("#btn_submit").attr("disabled", "disabled").addClass("disabled").val("正在上传...");
                        var item = '';
                        plupload.each(files, function(file) { //遍历文件
                            item += "<div class='item' id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></div>";
                        });
                        $("#photos_area").append(item);
                        uploader.start();
                    },
                    UploadProgress: function(up, file) { //上传中，显示进度条 
                        var percent = file.percent;
                        $("#" + file.id).find('.bar').css({"width": percent + "%"});
                        $("#" + file.id).find(".percent").text(percent + "%");
                    },
                    FileUploaded: function(up, file, info) {
                        var data = eval("(" + info.response + ")");
                        $("#" + file.id).html("<input type=hidden name='post[thumb][]' value='" + data.src + "'><img src='" + data.src + "' alt='" + data.name + "' width='100px' height='100px'>\n\
            <div class='operate'><i class='toleft'>左移</i><i class='toright'>右移</i><i class='del'>删除</i></div>")

                        $("#btn_submit").removeAttr("disabled").removeClass("disabled").val("提 交");
                        if (data.error != 0) {
                            alert(data.error);
                        }
                    },
                    Error: function(up, err) {
                        if (err.code == -601) {
                            alert("请上传jpg,png,gif,jpeg,zip或rar！");
                            $("#btn_submit").removeAttr("disabled").removeClass("disabled").val("提 交");
                        }
                    }
                }
            });
            uploader.init();
            //左右切换和删除图片
            $(function() {
                $(".toleft").live("click", function() {
                    var item = $(this).parent().parent(".item");
                    var item_left = item.prev(".item");
                    if ($("#photos_area").children(".item").length >= 2) {
                        if (item_left.length == 0) {
                            item.insertAfter($("#photos_area").children(".item:last"));
                        } else {
                            item.insertBefore(item_left);
                        }
                    }

                })
                $(".toright").live("click", function() {
                    var item = $(this).parent().parent(".item");
                    var item_right = item.next(".item");
                    if ($("#photos_area").children(".item").length >= 2) {
                        if (item_right.length == 0) {
                            item.insertBefore($("#photos_area").children(".item:first"));
                        } else {
                            item.insertAfter(item_right);
                        }
                    }
                })
                $(".del").live("click", function() {
                    $(this).parent().parent(".item").remove();
                })
            })
        </script>
</tbody>
<tr>
<td class="tl"> 备注</td>
<td><input name="post[note]" type="text" size="60" value="<?php echo $note;?>" id="note"/></td>
</tr>
<tr>
<td class="tl">信息状态</td>
<td>
<input type="radio" name="post[status]" value="3" <?php if($status == 3) echo 'checked';?>/> 通过
<input type="radio" name="post[status]" value="2" <?php if($status == 2) echo 'checked';?>/> 待审
</td>
</tr>
<tr>
<td class="tl">销售状态</td>
<td>
<input type="radio" name="post[xszt]" value="1" <?php if($xszt == 1) echo 'checked';?>/> 在售
<input type="radio" name="post[xszt]" value="0" <?php if($xszt == 0) echo 'checked';?>/> 售完
</td>
</tr>
<tr>
<td class="tl">是否主力户型</td>
<td>
<input type="radio" name="post[zlhx]" value="1" <?php if($zlhx == 1) echo 'checked';?>/> 是
<input type="radio" name="post[zlhx]" value="0" <?php if($zlhx == 0) echo 'checked';?>/> 否
</td>
</tr>
<tr>
<td class="tl">增加时间</td>
<td><input type="text" size="22" name="post[addtime]" value="<?php echo $addtime;?>"/></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<?php load('guest.js'); ?>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'title';
	l = Dd(f).value.length;
	if(l < 1) {
		Dmsg('请填写标题', f);
		return false;
	}
	if(Dd('ismember_1').checked) {
		f = 'username';
		l = Dd(f).value.length;
		if(l < 2) {
			Dmsg('请填写会员名', f);
			return false;
		}
	} else {
		f = 'company';
		l = Dd(f).value.length;
		if(l < 2) {
			Dmsg('请填写公司名称', f);
			return false;
		}
		if(Dd('areaid_1').value == 0) {
			Dmsg('请选择所在地区', 'areaid');
			return false;
		}
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>