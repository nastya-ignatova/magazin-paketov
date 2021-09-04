<?php
      include "config.php";
      include "main_html.html";?>

   <div class="a3">
    Ваш заказ № <?php echo $_COOKIE['cookie_order_id']; setcookie('cookie_order_id', null)?> успешно зарегестрирован. Вы можете просмотреть информацию о нём в личном кабинете.
  </div>

     <?php
     $html="";
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
</p>
 </form>
 </body>
</html>