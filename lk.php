<script>
         function CookiesDelete() {
             var cookies = document.cookie.split(";");
             for (var i = 0; i < cookies.length; i++) {
                 var cookie = cookies[i];
                 var eqPos = cookie.indexOf("=");
                 var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                 document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;";
                 document.cookie = name + '=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
             }
         }
     </script>
<?php
      include "config.php";
      include "main_html.html";?>

   <div class="a3">
       
  Добро пожаловать,

       <?php
       $html="";
    echo $_COOKIE['cookie_name']."!";
       echo" <a href=tipar.php onclick='CookiesDelete()'>Выйти</a>";

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
       <p> <a href=korzina.php> Корзина </a>
       <p> <a href=orders.php> Заказы </a>
  </div> 

  
</p>
 </form>
 </body>
</html>