<?php
      include "config.php";
      include "main_html.html";?>


   <div class="a3">
   Введите данные для регистрации:
       <p> <input type="text" size="40" name="user_name">   ФИО
       <p> <input type="text" size="40" name = "user_login">    Логин / адрес эл. почты
       <p> <input type="password" size="40" name="user_password">   Пароль
       <p> <input type="text" size="40" name="user_address">    Адрес доставки
       <p>     <button type="submit" name="submit">Зарегистрироваться </button>
  </div> 
 <?php
 if (isset($_POST['submit']) ){//если нажата кнопка
     if( isset($_POST['user_name']) && isset($_POST['user_login'])&&isset($_POST['user_password'])&&isset($_POST['user_address']) && $_POST['user_name']!='' && $_POST['user_login']!='' && $_POST['user_address']!='' && $_POST['user_password']!=''){
     $user_name = $_POST['user_name'];
     $user_login = $_POST['user_login'];
     $user_password = $_POST['user_password'];
     $user_address = $_POST['user_address'];

     // Параметры для подключения
     $db_table = "users"; // Имя Таблицы БД

     // Подключение к базе данных
     $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);

     // Если есть ошибка соединения, выводим её и убиваем подключение
     if ($mysqli->connect_error) {
         die('Ошибка : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
     } else {//если подключение сработало
         $result2 = $mysqli->query("SELECT  user_name,user_id,user_address,status FROM  users  WHERE (user_login= '$user_login')");
         $count_of_rows= mysqli_num_rows($result2);
        if ($count_of_rows>0)//если пользователь с таким логином уже сущетсвует
        {
            echo "<script>alert('Ошибка! Такой логин уже существует')</script>";
        }
        else {
            $result = $mysqli->query("INSERT INTO " . $db_table . " (user_name,user_login,user_password,user_address) VALUES ('$user_name','$user_login','$user_password','$user_address')");
            if ($result == true) {
                $row = mysqli_fetch_array($result);
                setcookie("cookie_name", $user_name, "/");
                $latest_id = mysqli_insert_id($mysqli);
                setcookie("cookie_id", $latest_id, "/");
                setcookie("cookie_status", 0, "/");
                //    $new_url = 'lk.php';
                //  header('Location: '.$new_url);
            } else {
                echo "<script>alert('Ошибка!')</script>";
            }
            if ($_COOKIE[cookie_order_id] != null)//если человек сделал заказз до входа
            {
                setcookie("cookie_status", 0, "/");
                $result = $mysqli->query("UPDATE orders SET user_id=$latest_id, address='$user_address' where order_id=$_COOKIE[cookie_order_id]");
            }
            $new_url = 'lk.php';
            header('Location: '.$new_url);
        }
     }

     }
     else {//если какое-то из полей не заполнено
         echo "<script>alert('Какое-то из полей не заполнено')</script>";
     }


 }
 if (isset($_POST['button_phone']))//если нажата кнопка позвонить
 {
     $html="";
     if (isset( $_POST['phone'])&& $_POST['phone']!='')
     {
         $phone=$_POST['phone'];
         if ((strlen($phone)==11 && $phone[0]==8)|| (strlen($phone)==11 && $phone[0]==7))
         {
             $sql1 = "insert into phones (phone) values (".$phone.")";
             $result1 = mysqli_query($link, $sql1);
             $html=$html. "<script>alert('Ожидайте звонка!')</script>";
         }
         else if (strlen($phone)==12 && $phone[0]=='+' && $phone[1]=='7')
         {
             $sql2 = "insert into phones (phone) values (".$phone.")";
             $result2 = mysqli_query($link, $sql2);
             $html=$html. "<script>alert('Ожидайте звонка!')</script>";
         }
         else{
             $html=$html. "<script>alert('Телефон введён в неправильном формате!')</script>";
         }
     }
     else{
         $html=$html. "<script>alert('Не заполнено поле номера телефона!')</script>";
     }
 }
 echo $html;

 ?>
  
</p>
 </form>
 </body>
</html>