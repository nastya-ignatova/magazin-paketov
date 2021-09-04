<?php
      include "config.php";
      include "main_html.html";?>

     <div class="a3">
            Введите данные:
                <p>Логин  <input type="text" size="40" name="user_login">
                 <p>Пароль  <input type="password" size="40" name="user_password">
         <p>   <button type="submit" name="submit">Войти</button>


            <?php
            if ( isset($_POST['submit']))
                //isset($_POST['user_login'])&&isset($_POST['user_password']))
                { if ($_POST['user_login']!='' && $_POST['user_password']!='') {
                    $user_login = $_POST['user_login'];
                    $user_password = $_POST['user_password'];
                    // Параметры для подключения

                    // Подключение к базе данных
                    $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);

                    // Если есть ошибка соединения, выводим её и убиваем подключение
                    if ($mysqli->connect_error) {
                        die('Ошибка : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
                    } else {
                        $result = $mysqli->query("SELECT  user_name,user_id,user_address,`status` FROM  users  WHERE (user_login= '$user_login' and user_password= '$user_password')");
                        $row = mysqli_fetch_array($result);
                        $count_of_rows = mysqli_num_rows($result);
                        if (($result == true) && ($count_of_rows > 0) && ($row['status'] != 1)) {
                            $user_address = $row['user_address'];
                            $user_id = $row['user_id'];

                            $user_name = $row['user_name'];
                            setcookie('cookie_name',$user_name, time()+36000, "/");
                            setcookie('cookie_id',$user_id, time()+36000, "/");
                            $_COOKIE['cookie_name']=$user_name;
                            $_COOKIE['cookie_id']=$user_id;

                            if (!isset($_COOKIE["cookie_order_id"]))//если человек сделал заказз до входа
                            {
                                $result = $mysqli->query("UPDATE orders SET user_id=$user_id, address='$user_address' where order_id=$_COOKIE[cookie_order_id]");
                            }
                            //$new_url = 'lk.php';
                            //header('Location: ' . $new_url);
                            //header('Location: lk.php');
                            echo'<meta http-equiv="refresh" content="0;lk.php">';

                        } else if (($result == true) && ($count_of_rows > 0) && ($row['status'] = 1)) {
                            $new_url = 'admin.php';
                            header('Location: ' . $new_url);
                        } else {
                            echo "<script>alert('Ошибка! Неверные данные')</script>";

                        }
                    }
                }
                else
                {
                    echo "<script>alert('Ошибка! Какое-то из полей не заполнено!')</script>";
                }
                 }
            $html="";
            if (isset($_POST['button_phone']))//если нажата кнопка позвонить
            {
                if (isset( $_POST['phone'])&& $_POST['phone']!='')
                {
                    $phone=$_POST['phone'];
                    if ((strlen($phone)==11 && $phone[0]==8)|| (strlen($phone)==11 && $phone[0]==7))
                    {
                        $sql1 = "insert into phones (phone) values (".$phone.")";
                        $result1 = mysqli_query($mysqli, $sql1);
                        $html=$html. "<script>alert('Ожидайте звонка!')</script>";
                    }
                    else if (strlen($phone)==12 && $phone[0]=='+' && $phone[1]=='7')
                    {
                        $sql2 = "insert into phones (phone) values (".$phone.")";
                        $result2 = mysqli_query($mysqli, $sql2);
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
            </div>
</p>
 </form>
 </body>
</html>