<?php
//вкачка довідника оплат із стирання попереднії оплат
//ini_set('display_errors',1);
	require "../rk/db.php";
	include "../rk/q1.php";
R::freeze(true) ;// true не дає додавати поля
$data=$_POST;
$maxdate=R::getRow('SELECT max(date1) as maxdate1  FROM conf  LIMIT 1');
$maxdate1=$maxdate['maxdate1'];


$begin = new DateTime($maxdate1);
$end = $begin->modify('+1 month');
$end1=$end->format("Y-m-d");

$db = dbase_open('../rk/in/r_k_opl.dbf', 0);
if (!isset($db)) {
	echo "Помилка дбф файлу. Зверніться до адміністратора";
	exit();
	}
	//echo "Вчитуємо базу opl зачекайте <br>";
	echo " <h2>Початок вчитування ".date("Y-m-d H:i:s")."</h2><BR>";
	if ($db){
				echo "Ксть-записів".dbase_numrecords($db);
	}
	
	// echo"
	// <div id='progress' style='width:500px;border:1px solid #ccc;'></div>
	// <div id='information' style='width'></div>
	// ";
	echo "
	<form action='../rk/imp_opl.php' method='POST'>
			<p><strong>вчитування оплат<br></strong></p>
			<a href='../rk/admin.php'>Повернутись<br></a>
			<p>Поточний робочий період $maxdate1<br></p>
		<p><strong>Ви впевнені у вчитуванні оплат? 1-так 0 - ні</strong></p>
			<input type='number' name='nyesnoopl' value='0'><br>
			<button type='submit' name='do_zmmopl'>почати закачку</button>
		</form>
	";

	if (isset($data['do_zmmopl'])) 
	{
		//var_dump($data);
		if (isset($data['do_zmmopl']) && (($data['nyesnoopl']==1)))
		{	
			//$sss=R::exec('delete  from opl');
			if ($db) 		
			{
			  $record_numbers = dbase_numrecords($db);
			  //$record_numbers=20;
			  for ($i = 1; $i <= $record_numbers; $i++) 
			 	 {
				$row = dbase_get_record_with_names($db, $i);
		     		$opl = R::dispense('opl');	    		
		     		$opl->date=$row['DATE'];
		     		$opl->date_opl=$row['DATE_OPL'];
		     		$opl->nom_ab=$row['NOM_AB'];
		     		$opl->suma_k=$row['SUMA_K'];
		     		
		     		$opl->norg=$row['NORG'];
		     		
		     		R::store($opl);
		     		$percent = intval($i/$record_numbers * 100)."%";
   		
     	// 	echo '<script language="javascript">
   	  	//   	document.getElementById("progress").innerHTML="<div 	style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
   	  	//   	document.getElementById("information").innerHTML="'.$i.' записів завантажено.";
   			// </script>';
   			
        //echo " #$i: " .($row['OS_RAH']) . "<br>";
	  		 	}
	   	echo " <h2>Закінчено вчитування ".date("Y-m-d H:i:s")."</h2><BR>";
    		}
			else
			{
				echo "Невдача доступу до дбф";
			}
		}		
	}
	


?>