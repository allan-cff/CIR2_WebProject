<?php
  require_once(realpath(dirname(__FILE__) . '/header.php'));
  $_SESSION["user"] = NULL;
  session_destroy();
  header("Location: login.html");
  exit;
?>

