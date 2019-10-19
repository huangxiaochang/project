<?php
header("Content-Type: text/html;charset=utf-8");
function getname($exname){
   $dir = "/uploadfile/";
   $i=1;
   if(!is_dir($dir)){
      mkdir($dir,0777);
   }
   $name = 'v'.time().".".$exname;
   // while(true){
   //   if(!is_file($dir.$i.".".$exname)){
   //      $name=$i.".".$exname;
   //      break;
   //    }
   //   $i++;
   // }
   return $name;
}

$dir = "https://fptok.com/uploadfile/";
$dir1 = "./uploadfile/";

$exname = strtolower(substr($_FILES['spupfile']['name'],(strrpos($_FILES['spupfile']['name'],'.')+1)));
$uploadfile1 = $dir.getname($exname);

$uploadfile = $dir1.getname($exname);

if (move_uploaded_file($_FILES['spupfile']['tmp_name'], $uploadfile)) {
   echo json_encode($uploadfile1);
}

// echo json_encode($uploadfile);

?>
