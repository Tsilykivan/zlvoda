<?php
require "../rk/db.php";

$data=$_POST;
$maxdate=R::getRow('SELECT max(date1) as maxdate1  FROM conf  LIMIT 1');
$maxdate1=$maxdate['maxdate1'];


$begin = new DateTime($maxdate1);
$end = $begin->modify('+1 month');
$end1=$end->format("Y-m-d");
echo "
	<form action='../rk/zmm.php' method='POST'>
			<p><strong>ВВЕДІТЬ НОВИЙ МІСЯЦЬ (ПЕРІОД ПОДАЧІ ЛІЧИЛЬНІКІВ )<br></strong></p>
			<a href='../rk/admin.php'>Повернутись<br></a>
			<p>Поточний робочий період $maxdate1<br></p>
			<label for='datepok'>Нова робоча дата:</label>
		<p><input type='date' name='datepok' value=$end1 min=$end1 max=$end1 ><br></p>	
		<p><strong>Ви впевнені у зміні місяця? 1-так 0 - ні</strong></p>
			<input type='number' name='nyesnozmm' value='0'><br>
			<button type='submit' name='do_zmm'>почати зміну місяця</button>
		</form>
	";
	
if (isset($data['do_zmm'])) 
	{
		if (isset($_POST['datepok']) && (($_POST['nyesnozmm']==1)))
		{	
			$conf1=R::findOrCreate('conf', array(
					'date1' => $_POST['datepok']));
			R::store($conf1);	
			echo "Вітаю, ви перейшли на нову дату робочого періоду на сайті ".$_POST['datepok']."*";
		}	
	}
		
?>