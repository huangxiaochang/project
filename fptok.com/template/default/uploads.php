<?php
header("Content-Type: text/html;charset=utf-8");
function getname($exname){
   $dir = "./uploadfile/";
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
   return $dir.$name;
}
$exname = strtolower(substr($_FILES['spupfile']['name'],(strrpos($_FILES['spupfile']['name'],'.')+1)));
$uploadfile = getname($exname);
echo json_decode($uploadfile);

?>
