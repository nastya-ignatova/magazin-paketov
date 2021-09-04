<?php
     include "config.php";
     include "main_html.html";
 $html="";
 //достаём  пакеты, находящиеся в корзине (package_set)
 $sql = "select package.package_price, package_set.package_id, package.package_name,package.package_photo, package_set.package_count, package_set.type_price from orders join package_set on package_set.order_id=orders.order_id join package on package_set.package_id=package.package_id  where orders.order_id='$_COOKIE[cookie_order_id]'";

 $result = mysqli_query($link, $sql);
 $count_of_rows= mysqli_num_rows($result);
 if ($count_of_rows==0){
     $html = $html . "<div class='a3'><p> Корзина пуста</div>";
 }
 else {
     $i = 0;
     $array_of_button = array();
     $array_of_price = array();
     $array_of_count = array();
     $general_price = 0;// конечная сумма (будет вставляться в заказ)
     $count_of_all_packages=array();
     while ($row = mysqli_fetch_array($result)) {
         $array_of_button[$i] = $row['package_id'];
         $array_of_price[$i] = $row['package_price'];

         $html = $html . "<div class='style_paket'> <img src= '";
         $html = $html . $row['package_photo'];
         $html = $html . "' width=100 height=100 align='left'> <center>";
         $html = $html . $row['package_name'];
         $html = $html . " <div class ='a4'> ";
         $html = $html . "Подытог:  " . $row['type_price'];
         $sql5 = "select package_count from package where package_id=$array_of_button[$i]";//достаем общее количество пакетов на складе
         $result5 = mysqli_query($link, $sql5);
         $row5 = mysqli_fetch_array($result5);
         $count_of_all_packages[$i]=$row5['package_count'];

         $general_price = $general_price + $row['type_price'];// вычисляем сумму
         try {
             $order_id = $_COOKIE['cookie_order_id'];
             $sql1 = "SELECT package_count FROM package_set where package_id='$array_of_button[$i]' and order_id='$order_id'";// смотрим количество выбранных пакетов в заказе
             $result1 = mysqli_query($link, $sql1);
             $count_selected_packages = 0;
             if ($result1 == true) {
                 while ($row = mysqli_fetch_array($result1)) {
                     $count_selected_packages = (int)($count_selected_packages + $row['package_count']);
                 }
             }
         } catch (Exception $ex) {
             echo "Исключение";
         }
         $html = $html . "p.  <p>    <input type='number' step='1' min='0' max='$count_of_all_packages[$i]' name='mass[]' placeholder=' $count_selected_packages'> 
            <button type='submit' name='$array_of_button[$i]' width='120'height='35' padding-bottom='20'>Изменить</button></div></div><br>";
         $i = $i + 1;

     }//-------------------------------------------------Добавление пакета в корзину (то есть в заказ)

     $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
     $mass = array();

     if (isset($_POST['mass'])) {
         foreach ($_POST['mass'] as $k => $m) {
             if (!empty($m)) {
                 $mass[$k] = $m;
             }

         }
     }

     for ($j = 0; $j < $i; $j++) {//проверка- нажата ли хоть одна из кнопок (пакетов)?
         if (isset($_POST[$array_of_button[$j]])) {
             $db_table = "orders"; // Имя Таблицы БД

//--------------------------------------------------------Добавление пакета в коризину, перед этим удаляем все пакеты с таким же id (именем) из корзины, если нажата хоть одна из кнопок
             $result2 = $mysqli->query(" delete from package_set where package_id=$array_of_button[$j] and order_id=$order_id");//обнуляем кол-во пакетов

             if ($mass[$j] != 0) {//если пользователь ввёл ненулевое кол-во пакетов

                 $type_price = $mass[$j] * $array_of_price[$j];
                 $result = $mysqli->query("INSERT INTO package_set (order_id,package_id,package_count,type_price) VALUES ('$_COOKIE[cookie_order_id]','$array_of_button[$j]','  $mass[$j] ',$type_price)");
                 if ($result == true) {
                     $html = $html . " Пакет № " . $array_of_button[$j] . " в количестве " . $mass[$j] . " добавлен!!!";

                 } else {
                     $html = $html . "Пакет не добавлен";
                 }
             }
             $new_url = 'korzina.php';
             header('Location: ' . $new_url);
         }

     }
     $sql = "select user_address from users where user_id=$_COOKIE[cookie_id]";
     $result4 = mysqli_query($link, $sql);
     $row4 = mysqli_fetch_array($result4);

     $sql5 = "select address from orders where order_id=$_COOKIE[cookie_order_id]";
     $result5 = mysqli_query($link, $sql5);
     $row5 = mysqli_fetch_array($result5);
     $address_from_orders= $row5['address'];

     $html = $html . "<div style='position: absolute; left: 20%; bottom: 10% '>  <button name='checkout' type='submit'>Оформить заказ</button></div>";

     if ( $address_from_orders==null)
     {
         $html = $html . "<div style='position: absolute; left: 8%; top: 95%; '> Адрес:<p><textarea cols='40'  rows='5'  name='new_address' >".$row4['user_address']."</textarea>
        <p><button type='submit' name='change_address'>Внести изменения</button></div>";

     }
     else {
         $html = $html . "<div style='position: absolute; left: 8%; top: 95%; '> Адрес:<p><textarea cols='40'  rows='5'  name='new_address'>" .  $address_from_orders. "</textarea>
        <p><button type='submit' name='change_address'>Внести изменения</button></div>";
     }

     if (isset($_POST['change_address']) && ($_POST['new_address'])!="") {
        $new_address=$_POST['new_address'];
         $sql = "UPDATE orders SET address= '$new_address' where order_id=$_COOKIE[cookie_order_id]";
         $result = mysqli_query($link, $sql);
         $new_url = 'korzina.php';
         header('Location: ' . $new_url);
     }

     if (isset($_POST['checkout'])) {
         $sql2 = "select package_id, package_count from package_set where order_id='$_COOKIE[cookie_order_id]'";
         $result2 = mysqli_query($link, $sql2);
       while ( $row2 = mysqli_fetch_array($result2))//достаём каждый пакет из корзины
       {
           $sql3 = "select  package_count from package where package_id=".$row2['package_id'];//достаём кол-во пакетов ВСЕГО
           $result3 = mysqli_query($link, $sql3);
           $row3 = mysqli_fetch_array($result3);
           $total_count_of_package=$row3['package_count'];//достаём общее ко-во
           $count_in_basket=$row2['package_count'];//достаём кол-во в корзине
           $remaining_count=$total_count_of_package-$count_in_basket;//вычитаем из общего кол-ва пакетов коли-во в корзине
           $sql4 = "UPDATE package SET package_count=$remaining_count where package_id=".$row2['package_id'];//обновляем кол-во пакетв всего
           $result4 = mysqli_query($link, $sql4);
       }

         $sql = "UPDATE orders SET general_price=$general_price where order_id='$_COOKIE[cookie_order_id]'";
         $result = mysqli_query($link, $sql);


         $new_url = 'success.php';
         header('Location: ' . $new_url);
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
     echo $html;
     ?>

 </form>

 </body>
</html>
