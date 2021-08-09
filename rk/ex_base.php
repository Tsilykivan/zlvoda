<?php
ini_set('display_errors',1);
require "../rk/db.php";
include "../rk/q1.php";
if (isset($_POST['do_file'])){header('Location: exp_f.php');}
if (isset($_POST['do_data']))
   {
   	if (file_exists("../rk/out/pok.dbf"))   	{unlink("../rk/out/pok.dbf");}
   		$fff= $_POST['date_pok'];
		$def = array(
		  array("date",     "D",),
		  array("nom_ab",      "N",   5, 0),
		  array("lx1",      "N",   10, 2),
		  array("lx2",      "N",   10, 2),
		  array("lt1",      "N",   10, 2),
		  array("lt2",      "N",   10, 2),
		  array("przn",     "C", 1)  
		);

		if (!dbase_create('out/pok.dbf', $def)) {
		  echo "Помилка, не вдається створити базу таблицю\n";
		  exit();
		}


		$db = dbase_open('out/pok.dbf', 2);
		//echo "Початок ".date("Y-m-d H:i:s")."___";
		$poks=R::findLike('pok', array(						
								'period'=>$fff
								));
		echo"
			<div id='progress' style='width:500px;border:1px solid #ccc;'></div>
			<div id='information' style='width'></div>
			";
			
			$i=1;
			$cn=count($poks);

		foreach ($poks as $pok1)
		{    
			 $date=date_create($pok1->period);
			 $jj=date_format($date,"Ymd");
		 	 $res=dbase_add_record($db, array(
				  $jj,
				  $pok1->nom_ab, 
			      $pok1->lx1, 
			      $pok1->lx2,
			      $pok1->lt1, 
			      $pok1->lt2, 
			      'S' 
			      ));	

			      $percent = intval($i/$cn * 100)."%";
			     		
			     		echo '<script language="javascript">
		    	    		document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
		    	    		document.getElementById("information").innerHTML="'.$i.' записів вивантажено.";
		    			</script>';
		    	$i=$i+1;	   
		}
		dbase_close($db);
		//echo "Кінець ".date("Y-m-d H:i:s")."___";
	}


{unset($_POST['do_data']);
	    echo "<form action='../rk/ex_base.php' method='POST'>
			 	<p><strong>Експорт показів споживання населення (завжди перше число місяця)(наприклад за червень (01/06/2019)</strong></p>
			 	<label for='start'>Start date:</label>
				<input type='date' name='date_pok' value=$maxdate1>
				<button type='submit' name='do_data'>Зформувати</button>
				<button type='submit' name='do_file'>Зберегти на комп'ютер</button>
			 	</form>";
}

?>
<??>