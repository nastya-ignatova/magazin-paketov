
 <?php
      include "config.php";
      include "main_html.html";
      ?>
<div class="a3">
    Контакты нашей компании:
<p>
    <i>Адрес: Московская область, город Егорьевск, 6 микрорайон, д.18, подъезд №2 (от начала)</i>
    <p>Первый раз сотрудничаете с нашей компанией и никогда не были в Егорьевске? Сейчас мы расскажем, как добраться до нашего рога изобилия пакетов!
        <p> <b>  Город находится всего в ста километрах от столицы
           <a href="https://yandex.ru/maps/10727/egorievsk/house/6_y_mikrorayon_18/Z0AYdgFiTkQHQFtvfX92eXVqYA==/?ll=39.062710%2C55.378995&pt=39.035833%2C55.38305&sll=39.096495%2C55.574942&source=entity_search&sspn=2.385176%2C0.756097&z=16.14"><center><img src="photo/Egorevsk.png" alt="Карта" width="650" height="350"></center> </a>
        <p> Далее вызываете такси или садитесь на автобус №11 до 6-го микрорайона, и вуаля!
           <center><img src="photo/home.png" alt="Дом" width="400" height="600"></center> </b>
     <p>По любым вопросам
       <br>  <i>+79167322140 - Игнатова Анастасия Ильинична
             <br> nastya.ignatova.99@gmail.com</i>


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