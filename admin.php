<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
     <?php
     ini_set('display_errors', 0);
     ini_set('display_startup_errors', 0);
     error_reporting(0);
include "config.php";
     ?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>МосПакет</title>

  
  <style type="text/css">/*#24c7d6  -желтый цвет*/
  

         .a3 { 
    padding: 8px; /* Поля */
    background: #fff0f5; /* Цвет фона */
    border: 2px; /* Параметры рамки */
	margin-top: 10px;
	margin-right: 90px;
    margin-left: 90px;


	font-family:Tahoma;
	font-size: 20px;
   }
   
   li {
    list-style-type: none; /* Убираем маркеры */
   }
   ul {
    margin-left: 0; /* Отступ слева в браузере IE и Opera */
    padding-left: 0; /* Отступ слева в браузере Firefox, Safari, Chrome */
   }
   
  
   </style>
 </head>
 <body bgcolor="white"> <!--#fff44f-->
 <form method="POST" action="">
   <p>
       <center><img src="https://wmpics.pics/di-T507.png" alt="MosPaket"></center>

   <div class="a3">
       <center> <i>Информация для администратора.</i><p><?php
       $html="";
       $html= $html."<button type='submit' name='log_out'>Выйти</button></center>";
if (isset($_POST['log_out']))
{
    setcookie("cookie_id",null);
    $new_url = 'paketi.php';
    header('Location: '.$new_url);
}

       $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
       //-----------------------------------------------------------Вывод таблицы
       $result = $mysqli->query(" select  package_id,package_name,package_count,package_photo,package_price from package ");

        $html = $html . "<p><i>Все пакеты:</i><p><table border='2' style= 'background-color: white; color: #761a9b; margin: 0 auto;' >
           <thead>
           <tr>
               <th>ID пакета</th>
               <th>Название пакета</th>
               <th>Количество</th>
               <th>Фото</th>
               <th>Цена</th>
           </tr>
           </thead>
           <tbody>";

           while ($row = mysqli_fetch_array($result)) {
               $package_id=$row['package_id'];
               $package_name=$row['package_name'];
               $package_count=$row['package_count'];
               $package_photo=$row['package_photo'];
               $package_price=$row['package_price'];
           $html = $html .
           "<tr>
               <td>" . $package_id . "</td>
               <td>" .$package_name . "</td>
               <td>" . $package_count . "</td>
               <td>" . $package_photo . "</td>
               <td>" .  $package_price . "р.</td>               
           </tr>";
           }
           $html = $html . "</tbody>
       </table> <p>";

//-----------------------------------------------------------Изменение пакета
$html = $html . "<p><i>Таблица для изменения пакета (ID не менять):</i><p> <b>Внимание! Фото пакета вводить в формате photo\paket.png (предварительно положив фото в папку photo)</b><p><table border='2' style='background-color: white; color: #761a9b; margin: 0 auto; ' >
           <thead>
           <tr>
               <th>ID пакета</th>
               <th>Название пакета</th>
               <th>Количество</th>
               <th>Фото</th>
               <th>Цена</th>
           </tr>
           </thead>
           <tbody>
<tr>
               <td><input type='number' name='change_package_id'></td>
               <td><input type='text' name='change_package_name'></td>
               <td><input type='number' name='change_package_count'></td>
               <td><input type='text'name='change_package_photo'></td>
               <td><input type='number'name='change_package_price'></td>               
           </tr></tbody>
       </table> ";
        $html=$html."<p><button type='submit' name='change_package'>Изменить данные</button>";
        if (isset($_POST['change_package'])&&isset($_POST['change_package_id'])&&isset($_POST['change_package_name'])&&isset($_POST['change_package_count'])&&isset($_POST['change_package_photo'])&&isset($_POST['change_package_price']))
        {
            $change_package_id=$_POST['change_package_id'];
            $change_package_name=$_POST['change_package_name'];
            $change_package_count=$_POST['change_package_count'];
            $change_package_photo=$_POST['change_package_photo'];
            $change_package_price=$_POST['change_package_price'];
            $result1 = $mysqli->query(" UPDATE package SET package_name='$change_package_name',package_count=$change_package_count,package_photo='$change_package_photo', package_price=$change_package_price where package_id=$change_package_id");
         if ($result1 =true)
            {
                $new_url = 'admin.php';
                header('Location: ' . $new_url);
            }
            //  $html=$html.$package_id.$package_name.$package_count.$package_photo.$package_price;
        }
      // else{ echo "<script>alert('Ошибка! Попробуйте еще раз')</script>";}

        //----------------------------------------------------------------Удаление пакета
       $html = $html . "<p><i>ID для удаления пакета:</i>
          <input type='number' name='delete_package_id'>
            <p><button type='submit' name='delete_package'>Удалить данные</button>";
       if (isset($_POST['delete_package'])&&isset($_POST['delete_package_id']))
       {
           $delete_package_id=$_POST['delete_package_id'];
           $result1 = $mysqli->query(" DELETE FROM package where package_id=$delete_package_id");
           if ($result1 =true)
           {
               $new_url = 'admin.php';
               header('Location: ' . $new_url);
           }
           //  $html=$html.$package_id.$package_name.$package_count.$package_photo.$package_price;
       }
       //else{ echo "<script>alert('Ошибка! Попробуйте еще раз')</script>";}

       //------------------------------------------------------------------Добавление нового пакета
       $html = $html . "<p><i>Таблица для добавления пакета:</i><p><table border='2' style='background-color: white; color: #761a9b; margin: 0 auto; ' >
           <thead>
           <tr>
               <th>Название пакета</th>
               <th>Количество</th>
               <th>Фото</th>
               <th>Цена</th>
           </tr>
           </thead>
           <tbody>
<tr>
               <td><input type='text' name='add_package_name'></td>
               <td><input type='number' name='add_package_count'></td>
               <td><input type='text'name='add_package_photo'></td>
               <td><input type='number'name='add_package_price'></td>               
           </tr></tbody>
       </table> ";
       $html=$html."<p><button type='submit' name='add_package'>Ввести данные</button>";
       if (isset($_POST['add_package'])&&isset($_POST['add_package_name'])&&isset($_POST['add_package_count'])&&isset($_POST['add_package_photo'])&&isset($_POST['add_package_price']))
       {
           $add_package_name=$_POST['add_package_name'];
           $add_package_count=$_POST['add_package_count'];
           $add_package_photo=$_POST['add_package_photo'];
           $add_package_price=$_POST['add_package_price'];
           $result1 = $mysqli->query(" INSERT INTO package (package_name,package_count,package_photo,package_price) values('$add_package_name', $add_package_count,'$add_package_photo', $add_package_price)");
           if ($result1 =true)
           {
               $new_url = 'admin.php';
               header('Location: ' . $new_url);
           }
          /* if ($result1 !=true)
           {
               echo "<script>alert('Ошибка! Попробуйте еще раз')</script>";
           }*/
           //  $html=$html.$package_id.$package_name.$package_count.$package_photo.$package_price;
       }
      // else{ echo "<script>alert('Ошибка! Попробуйте еще раз')</script>";}

       echo $html;
           ?>
  </div> 

  
</p>
 </form>
 </body>
</html>