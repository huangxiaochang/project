<?php
@set_time_limit(0);
//$img = $_POST['base64'];
//$imgsize = $_POST['size'];
require '../common.inc.php';
require AJ_ROOT.'/include/image.class.php';
$typeArr = array("jpg", "png", "gif", "jpeg"); //允许上传文件格式

$path = '../file/upload/'.timetodate($AJ_TIME, $AJ['uploaddir']).'/';

$path1 = 'file/upload/'.timetodate($AJ_TIME, $AJ['uploaddir']).'/';
is_dir(AJ_ROOT.'/'.$path1) or dir_create(AJ_ROOT.'/'.$path1);
$filename = date('His', $AJ_TIME).''.rand(10, 99).''.$_userid.'.'.$file_ext;
$target = '../'.$updir.$filename;
if (isset($_POST)) {
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $name_tmp = $_FILES['file']['tmp_name'];
    if (empty($name)) {
        echo json_encode(array("error" => "您还未选择图片"));
        exit;
    }
    $type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型

    if (!in_array($type, $typeArr)) {
        echo json_encode(array("error" => "清上传jpg,png或gif类型的图片！"));
        exit;
    }
    if ($size > (50000 * 1024)) { //上传大小
        echo json_encode(array("error" => "图片大小已超过50000KB！"));
        exit;
    }

    $pic_name = time() . rand(10000, 99999) . "." . $type; //图片名称
    $pic_url = $path . $pic_name; //上传后图片路径+名称
		
    if (move_uploaded_file($name_tmp, $pic_url)) { //临时文件转移到目标文件夹
	
	if($AJ['water_type']) {
					$image = new image($pic_url);
					if($AJ['water_type'] == 2) {
						$image->waterimage();
					} else if($AJ['water_type'] == 1) {
						$image->watertext();
					}
				}
        echo json_encode(array("error" => "0", "src" => $pic_url, "name" => $pic_name));
    } else {
        echo json_encode(array("error" => "上传有误，清检查服务器配置！"));
    }
}
?>