<?php
 function trimbadchar ($string) {
  return str_replace (array ('\'','\\'),array ('',''),$string);
 }
 function trimall ($string) { 
  return preg_replace("/\s+/"," ",trim($string)); 
 }
 function magic ($path) {
  if (@get_magic_quotes_gpc()=='1') $path=stripslashes($path);
  return $path;
 }
 function add_in_file ($newstr,$scroll) {
  define ("LOGSIZE","100");
  $msgs = file ("log.html");
  if ($msgs===false) die ("Нет файла лога, обратитесь к администратору");
  else {
   $i=false;
   if (count($msgs)>0 and $newstr==$msgs[count($msgs)-1]) return;
   else if (count($msgs)>=LOGSIZE) {
    $msgs=array_slice ($msgs,-(LOGSIZE-1));
    array_push ($msgs, $newstr);
    $i=file_put_contents ("log.html",$msgs);
   }
   else {
    $fp = fopen("log.html", 'a');
    $i=fwrite ($fp, $newstr);
    fclose($fp);  
   }
   if (!$i) die ("Не могу записать файл лога, обратитесь к администратору");
   else if ($scroll) {
    echo '<script type="text/javascript">
     setTimeout(\'scrollLog()\', 50);
    </script>';
   }
  }
 }
 session_start();
 if (!isset($_SESSION['once'])) {
  ini_set('magic_quotes_runtime', '0');
  ini_set('magic_quotes_sybase', '0');
  $_SESSION['once']='1';
 }
?>
