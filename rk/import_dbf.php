<?php
//ini_set('display_errors',1);
require "../kab/db.php";
$dir    = '../kab/in';
$files1 = scandir($dir);
//var_dump($files1);
$year=substr($files1[2],2,4);
$month=substr($files1[2], 6,2);

$rezult=$year.$month;
//var_dump($rezult);
$db = dbase_open('../kab/in/eb'.$rezult.'.dbf', 0);
if (!isset($db)) {
	echo "Помилка дбф файлу. Зверніться до адміністратора";
	exit();
	}
	echo "Вчитуємо базу клієнтів зачекайте <br>";
	//echo "<div id="information" style="width"></div>";
	echo"
	<div id='progress' style='width:500px;border:1px solid #ccc;'></div>
	<div id='information' style='width'></div>
	";
	$sss=R::exec('delete  from abonent');
	if ($db) 
		
	{
	  $record_numbers = dbase_numrecords($db);
	  //$record_numbers=20;
	  for ($i = 1; $i <= $record_numbers; $i++) 
	  {

	      $row = dbase_get_record_with_names($db, $i);
	       
	      		$pib=iconv('CP866','utf-8',$row['PIB'] );
	      		$pib1 = str_replace("?", "І", $pib);
				
	      		$adresa=iconv('CP866','utf-8',$row['ADRESA'] );
	      		$adresa = str_replace("?", "І", $adresa);
				
	     		$ab = R::dispense('abonent');	    		
	     		$ab->pib=$pib1;
	     		$ab->os_rah=$row['OS_RAH'];
	     		$ab->adresa=$adresa;
	     		$ab->NARAX=$row['NARAX'];
	     		$ab->SUMA=$row['SUMA'];
	     		$ab->datazal=$rezult;
	     		R::store($ab);

	     		$percent = intval($i/$record_numbers * 100)."%";
	     		
	     		echo '<script language="javascript">
    	    		document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
    	    		document.getElementById("information").innerHTML="'.$i.' записів завантажено.";
    			</script>';
	         //echo " #$i: " .($row['OS_RAH']) . "<br>";
	   }
	     echo "Вчитування закінчено <br>";
	}
	else
	{
		echo "Невдача доступу до дбф";
	}

?>