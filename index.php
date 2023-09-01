<?php
 require_once ("functions.php");
 if (isset($_GET['logout'])) { //Если пользователь покинул чат
  add_in_file ("<div class='msgln'><sup>(".date("H:i").")</sup> <i>Пользователь ".
   $_SESSION['name']." вышел из чата </i><br></div>\n",0);
  session_destroy();  
  header("Location: index.php");
 }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Simple JQuery Chat</title>
<link type="text/css" rel="stylesheet" href="style.css"/>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
 function copyNick (name) {
  document.message.usermsg.value+=name+', ';
  document.message.usermsg.focus();
 }
 function scrollLog () {
  var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
  $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
 }
</script>
</head>
<body> 

<?php
 if(isset($_POST['name'])){ //Если пользователь вошел в чат 
  if (trimall(trimbadchar($_POST['name']))!='') {
   $_SESSION['name']=magic(htmlspecialchars(trimall(trimbadchar($_POST['name']))));
   add_in_file ("<div class='msgln'><sup>(".date("H:i").")</sup> <i>Пользователь ".
    $_SESSION['name']." вошёл в чат</i><br></div>\n",1);
  }  
  else {
   echo '<span class="error">Пожалуйста, введите непустое имя</span>';  
  }  
 }
 if(!isset($_SESSION['name'])) { //Если не вошли в чат - форма для входа
  echo'
  <div id="loginform">
  <form action="index.php" method="post"> 
   <p>Введите имя, под которым хотите войти:</p> 
   <label for="name">Имя:</label> 
   <input type="text" name="name" id="name" maxlength="32"/> 
   <input type="submit" name="enter" id="enter" value="Войти!" /> 
  </form> 
  </div>';
 }  
 else { //Иначе - элементы чата
?>  
 <div id="wrapper">  
  <div id="menu">  
   <p class="welcome">Вы вошли как <b><?php echo $_SESSION['name']; ?></b></p>  
   <p class="logout" align="right"><a id="exit" href="#">Выйти</a></p>  
   <div style="clear:both"></div>
  </div>
  <div id="chatbox">
<?php  
   if (file_exists("log.html") && filesize("log.html")>0) { //Вывести содержимое, если оно есть
    $handle = fopen("log.html", "r");
    $contents = fread($handle, filesize("log.html"));  
    fclose($handle);  
    echo $contents;  
   } 
?>
  </div>
  <form name="message" id="message" action="" method="post">
   <input name="usermsg" type="text" id="usermsg" size="63" maxlength="1024" />
   <input name="submitmsg" type="submit"  id="submitmsg" value="Сказать" />
  </form>
 </div>
 <script type="text/javascript">
 //Собственно чат
 $(document).ready(function() {
  $("#exit").click(function() { //Если пользователь хочет выйти
   if (confirm("Вы уверены, что хотите выйти из чата?")==true) { window.location = 'index.php?logout=true'; }
  });
  $('#message').submit(function(e) { //Если пользователь отправил сообщение
   var clientmsg = $("#usermsg").val();
   $.post("post.php", {text: clientmsg});
   $("#usermsg").attr("value", "");
   return false;
  });
  function loadLog() { //Загрузить лог чата
   var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
   $.ajax ({ // ajax запрос
    url: "log.html",
    cache: false,
    success: function(html) {
     $("#chatbox").html(html); //Автопрокрутка
     var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
     if (newscrollHeight > oldscrollHeight) {
      $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
     }
    }
   });
  }
  //Обновление 
  setInterval (loadLog, 1000);
 });
 </script>
<?php
 }  
?>
</body></html>
