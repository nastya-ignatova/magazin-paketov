<?php
     ini_set('display_errors', 0);
     ini_set('display_startup_errors', 0);
     error_reporting(0);
     
$db_host = "localhost";
 $db_user = "root"; // Логин БД
 $db_password = "vigodu391"; // Пароль БД
 $db_base = 'tipar'; // Имя БД
 $link = @mysqli_connect($db_host, $db_user, $db_password,  $db_base );