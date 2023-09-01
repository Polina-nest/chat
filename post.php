<?php
 require_once ("functions.php");
 if (isset($_SESSION['name'])) {
  add_in_file ("<div class='msgln'><sup>(".date("H:i").")</sup> <b><a href=\"#\" onClick=\"copyNick('".
   $_SESSION['name']."')\">".$_SESSION['name']."</a></b>: ".magic(htmlspecialchars(trimall($_POST['text']))).
   "<br></div>\n",1);
 }  
?>
