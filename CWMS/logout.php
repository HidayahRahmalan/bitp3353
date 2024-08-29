<?php
   session_start();
   unset($_SESSION["login_user"]);
   unset($_SESSION["role"]);
   unset($_SESSION["uid"]);
   // unset($_SESSION["password"]);
   if(session_destroy()) {
      echo "
      <script type='text/javascript'>
  window.location.href ='index.php';
</script>";
   }
?>