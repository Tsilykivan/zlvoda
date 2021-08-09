<?php
// завантаження дбф бази абонент на сайт
ini_set('display_errors',1);
//(`NOM_AB`,`DATE`, `FIO`, `PROP`,`KOD_STR`,`HOUSE`,`FLAT`, `SUMA`,`LX1`,`LX2`,`LT1`,`LT2`,`PPP`,`KOD_US`) 
require "../rk/db.php";
$db = dbase_open('../rk/in/abonent.dbf', 0);
if (!isset($db)) {
	echo "Помилка дбф файлу. Зверніться до адміністратора";
	exit();
	}
	echo"
	<div id='progress' style='width:500px;border:1px solid #ccc;'></div>
	<div id='information' style='width'></div>
	";
	$sss=R::exec('delete  from abonent');
	if ($db) 
	{$record_numbers = dbase_numrecords($db);
	//	 $record_numbers = 5;
	  echo "Вчитуємо базу клієнтів зачекайте <br>";
	  for ($i = 1; $i <= $record_numbers; $i++) 
	  {
	  	$row = dbase_get_record_with_names($db, $i);

	  	//echo "<pre>".($row['NOM_AB']);
	  	//var_dump($row);
	  	if ($row['deleted']==0 and ($row['NOM_AB'])>0)
	  		{
	  		$fio1=iconv('CP866','utf-8',$row['FIO'] );
		  	$HOUSE1=iconv('CP866','utf-8',$row['HOUSE'] );
		  	$FLAT1=iconv('CP866','utf-8',$row['FLAT'] );

		  	$ab = R::dispense('abonent');
		  	$ab->nom_ab=$row['NOM_AB'];
		  	$ab->DATE=$row['DATE'];
		  	$ab->FIO=$fio1;
		  	$ab->PROP=$row['PROP'];
		  	$ab->KOD_STR=$row['KOD_STR'];
		  	$ab->HOUSE=$HOUSE1;
		  	$ab->FLAT=$FLAT1;
		  	$ab->SUMA=$row['SALDO'];
		  	$ab->LX1=$row['LX1_S'];
		  	$ab->LX2=$row['LX2_S'];
		  	$ab->LT1=$row['LT1_S'];
		  	$ab->LT2=$row['LT2_S'];	
		 	$ab->PPP=$row['PPP'];	
		  	$ab->KOD_US=$row['KOD_US'];
		  	R::store($ab);

		  	$percent = intval($i/$record_numbers * 100)."%";
		  	echo '<script language="javascript">
	    	    		document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
	    	    		document.getElementById("information").innerHTML="'.$i.' записів завантажено.";
	    			</script>';
	    	}		
	  }
	  echo "Вчитування закінчено <br>";
	  dbase_close($db);	  	
	}
	else
	{
		echo "Невдача доступу до дбф";
	}
?>