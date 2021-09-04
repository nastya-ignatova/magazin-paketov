<?php
      include "config.php";
      include "main_html.html";?>

   <div class="a3">
       <ul>
           <li><i><b>03.03.20</b></i>  Не оставим и шанса распростанению коронавирусной инфекции! Дорогие покупатели, тщательно мойте руки с мылом, дезинфицируйте пакеты и реже посещайте общественные места!<p></li>
           <li><i><b>10.03.20</b></i>  В продажу поступили Авоськи! Скорее в каталог пакетов заказывать по выгодной цене<p></li>
           <li><i><b>20.03.2020</b></i>  Отличный солнечный денек, чтобы выгулять парочку новых пакетов! Забыли свой у друга? Бегом в каталог пакетов!<p></li>
           <li><i><b>1.04.2020</b></i>  У нас хорошая новость! В нашем магазине теперь можно приобрести гигантские пакеты! С ними перееезд не страшен!<p></li>
           <li><i><b>5.04.2020</b></i>  Теперь мы доставляем по всей России! Скорее за покупками!<p></li>
           <li><i><b>10.04.2020</b></i>  По многочисленным просьбам мы включили в каталог Русский пакет! Избавимся от коронавируса!<p></li>
           </ul>
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