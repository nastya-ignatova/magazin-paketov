<?php
      include "config.php";
      include "main_html.html";?>

     <div class="a3">
    Сортировать по <select name="sorting">
           <option value="Возрастанию цены">Возрастанию цены</option>
           <option value="Убыванию цены">Убыванию цены</option>
         </select>
         <button type="submit" name="submit_sorting">Ок</button>
  </div>

 <?php
 $submit_sorting=$_POST['submit_sorting'];
 $sorting= $_POST['sorting'];

 if (isset($submit_sorting))
 {
     if ($sorting=="Возрастанию цены")
     {
         $order_by="package_price ASC";
     }
     else{
         $order_by="package_price DESC";
     }
 }
 else
 {
     $order_by="package_id";
 }
 $cookie_order_id = $_COOKIE['cookie_order_id'];
// $link = @mysqli_connect(localhost, root,vigodu391, tipar);
 $sql = 'SELECT package_id, package_name, package_price,package_photo FROM package where package_count>0 order by '. $order_by;

 $result = mysqli_query($link, $sql);
$i=0;
$array_of_button=array();
$array_of_price=array();
$array_of_count=array();
 $count_of_all_packages=array();
 while ($row = mysqli_fetch_array($result))
        {
        $array_of_button[$i]=$row['package_id'];
        $array_of_price[$i]=$row['package_price'];

            $html=$html. "<div class='style_paket'> <img src= '";
            $html=$html.$row['package_photo'];
            $html=$html."' width=100 height=100 align='left'> <center>";
            $html=$html. $row['package_name'];
            $html=$html." <div class ='a4'> ";
            $html=$html.$row['package_price'];

            $sql5 = "select package_count from package where package_id=$array_of_button[$i]";//достаем общее количество пакетов на складе
            $result5 = mysqli_query($link, $sql5);
            $row5 = mysqli_fetch_array($result5);
            $count_of_all_packages[$i]=$row5['package_count'];

            $count_selected_packages = 0;// по умолчаю количество выбранных пакетов везде 0
            if ($cookie_order_id!=null) {
                $order_id = $cookie_order_id;
                $sql1 = "SELECT package_count FROM package_set where package_id='$array_of_button[$i]' and order_id='$order_id'";// смотрим количество выбранных пакетов в заказе
                $result1 = mysqli_query($link, $sql1);

                if ($result1 == true) {
                    while ($row = mysqli_fetch_array($result1)) {
                        $count_selected_packages = (int)($count_selected_packages + $row['package_count']);
                    }
                }
            }
           $html=$html."p.  <p>    <input type='number' step='1' min='0' max='$count_of_all_packages[$i]' name='mass[]' placeholder=' $count_selected_packages'> 
            <button type='submit' name='$array_of_button[$i]' width='120'height='35' padding-bottom='20'><img src='photo\in_bag.png' width='100'height='25' padding-bottom='20' ></img></button></div></div><br>";
             $i=$i+1;

         }//-------------------------------------------------Добавление пакета в корзину (то есть в заказ)

 $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
 $mass=array();

 if (isset($_POST['mass'])) {
     foreach ($_POST['mass'] as $k => $m) {
         if (!empty($m)) {
             $mass[$k] = $m;
         }

     }
 }

 for ($j=0   ;   $j  <   $i    ;   $j++ ) {//проверка- нажата ли хоть одна из кнопок (пакетов)?
    if (isset($_POST[$array_of_button[$j]])) {
        $db_table = "orders"; // Имя Таблицы БД
        //------------------------------------------------Формируем заказ
        if ($cookie_order_id==null )//если заказ не сформирован- сформировываем его
            {
                if ($_COOKIE[cookie_id]!=null) {//Если юзер авторизовался
                    $result3 = $mysqli->query(" select user_address from users where user_id = $_COOKIE[cookie_id]");//достаём адрес юзера, чтоб поместить его по дефолту в orders
                    $row3 = mysqli_fetch_array($result3);
                    $address = $row3['user_address'];
                    $date = strftime("%Y-%m-%d");
                    $result = $mysqli->query("INSERT INTO " . $db_table . " (user_id,order_date, address) VALUES ('$_COOKIE[cookie_id]','$date','$address')");
                    if ($result == true) {
                        $latest_id = mysqli_insert_id($mysqli);
                        $cookie_order_id = $latest_id;
                    } else {
                        $html = $html . "Не удалось сформировать заказ";
                    }
                }
                else {//если юзер не авторизовался, помещаем вместо адреса и id 0
                    $date = strftime("%Y-%m-%d");
                    $result = $mysqli->query("INSERT INTO orders (user_id,order_date, address) VALUES (0,'$date',0)");
                    if ($result == true) {
                        $latest_id = mysqli_insert_id($mysqli);
                        $cookie_order_id = $latest_id;
                    } else {
                        $html = $html . "Не удалось сформировать заказ";
                    }
                }

            }
//--------------------------------------------------------Добавление пакета в коризину, перед этим удаляем все пакеты с таким же id (именем) из корзины, если нажата хоть одна из кнопок
         $result2 = $mysqli->query(" delete from package_set where package_id=$array_of_button[$j] and order_id=$order_id");//обнуляем кол-во пакетов

        if ($mass[$j]!=0){//если пользователь ввёл ненулевое кол-во пакетов

            $type_price = $mass[$j] * $array_of_price[$j];
            $result = $mysqli->query("INSERT INTO package_set (order_id,package_id,package_count,type_price) VALUES ('$cookie_order_id','$array_of_button[$j]','  $mass[$j] ',$type_price)");
           /* if ($result == true) {
                $html = $html . " Пакет № " . $array_of_button[$j] . " в количестве " . $mass[$j] . " добавлен!!!";

            } else {
                $html = $html . "Пакет не добавлен";
            }*/
            $new_url = 'paketi.php';
           header('Location: ' . $new_url);
        }
    }

}
 if (isset($_POST['button_phone']))//если нажата кнопка позвонить
 {
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
 setcookie('cookie_order_id', $cookie_order_id);
 echo $html;
 ?>
 </form>

 </body>
</html>
