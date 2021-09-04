<?php
      include "config.php";
      include "main_html.html";?>

   <div class="a3">
       <?php
       $html="Ваши заказы:<p>";
       $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
       $result1 = $mysqli->query("select order_id,address,order_date,general_price from orders where user_id = $_COOKIE[cookie_id]");

       while( $row1 = mysqli_fetch_array( $result1) ) {
           $order_id = $row1['order_id'];
           $address = $row1['address'];
           $order_date = $row1['order_date'];
           $general_price = $row1['general_price'];

           if ($general_price != 0) {//если заказ уже сформирован (= есть окончательная сумма заказа)
               $result = $mysqli->query(" select  orders.address, orders.order_date, package.package_name, package_set.package_count, package_set.type_price from orders join package_set on package_set.order_id=orders.order_id join package on package_set.package_id=package.package_id  where orders.order_id=$order_id");

               $html = $html . "<div class='border'>Заказ №" . $order_id . ". Адрес: " . $address . ". Дата оформления: " . $order_date . "</div><p>";
               $html = $html . "<table border='2' style= 'background-color: white; color: #761a9b; margin: 0 auto;' >
          <thead>
           <tr>
               <th>Название пакета</th>
               <th>Количество</th>
               <th>Подытог</th>
           </tr>
           </thead>
           <tbody>";

               while ($row = mysqli_fetch_array($result)) {
                   $html = $html .
                       "<tr>
              <td>" . $row ['package_name'] . "</td>
              <td>" . $row['package_count'] . "</td>
              <td>" . $row['type_price'] . "р.</td>
            </tr>";
               }
               $html = $html . "</tbody>
       </table> <p>
       Сумма заказа: " . $general_price . "р. <p>";
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

  </div>

  
</p>
 </form>
 </body>
</html>