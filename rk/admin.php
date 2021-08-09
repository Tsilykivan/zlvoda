<?
//include "ddd.php";
require "../rk/db.php";
include "ddd1.php";
if(isset($_POST)){
	$data=$_POST;
	$maxdate=R::getRow('SELECT max(date1) as maxdate1  FROM conf  LIMIT 1');
	$maxdate1=$maxdate['maxdate1'];	
}

echo " <h2>Доброго дня сьогодні ".date("Y-m-d H:i:s")."</h2><BR>";

echo "<h2> Поточний робочий період $maxdate1 </h2><BR>";
echo " Тут можна по змінити місяць, зформувати базу телефонів з сайту, вивантаження показників у файл<BR><hr>";

echo " <a href='main2a.php'>Покази по абонентах</a><BR>";
echo " <a href='main3.php'>Перегляд абонента через сайт пароль 1111</a><BR>";
echo " <a href='abdel.php'>Відкріплення абонента від ел. пошти</a><BR>";


echo " <a href='ex_t.php'>Вивантеження телефонів</a><BR><BR><HR>";
echo " Роботи в кінці місяця панове <BR><HR>";
echo " <a href='ex_base.php'>Вивантеження показів лічильника</a><BR>";
echo " <a href='zmm.php'>1.Зміна місяця</a><BR>";
echo " <a href='f3serv.php'>2.Завантаження файлу на сервер бази абонент</a><BR>";
echo " <a href='imp_dbf.php'>3.Вчитування даних в таблицю абонент</a><BR>";
echo " <a href='f3serv.php'>4.Завантаження файлу на сервер бази tmp r_k_opl</a><BR>";
echo " <a href='imp_opl.php'>5.Вчитування  оплат на сайт</a><BR>";

//{DELETE FROM `usersnew` WHERE id=1181
//SELECT * FROM `userab` WHERE `nom_ab`=5952}
?>