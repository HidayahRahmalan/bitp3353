<?php
session_start(); 

if (!isset($_SESSION['stud_id']))
{
  echo "<script type = \"text/javascript\">
  window.location = (\"index.php\");
  </script>";

}