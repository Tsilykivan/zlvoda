<?php
//Вивантаження у дбф інф по телефони base_ab.dbf


ini_set('display_errors',1);
require "../rk/db.php";

if (isset($_POST['do_file'])){header('Location: exp_ft.php');}

if (isset($_POST['do_data']))
	{
	$result=R::getAll("SELECT userab.nom_ab,
	 	usersnew.telefon,usersnew.email,
	 	DATE_FORMAT(FROM_UNIXTIME(`date_reg`),'%Y%m%d') AS date_formatted
	 	 	FROM usersnew left join userab ON usersnew.email=userab.login where userab.nom_ab>0 order by date_reg");

		$def1 = array(
			  array("date",     "D",),
			  array("nom_ab",      "N",   4, 0),
			  array("telefon",     "C", 14),
			  array("email",     "C", 150)
			);
	if (!dbase_create('out/base_ab.dbf', $def1)) {
			  echo "Помилка, не вдається створити базу таблицю\n";
			}
		$db = dbase_open('out/base_ab.dbf', 2);	
			echo"
				<div id='progress' style='width:500px;border:1px solid #ccc;'></div>
				<div id='information' style='width'></div>
				";
		$i=1;
		$cn=count($result);
		foreach ($result as $result1)
		{
			//echo'<pre>';	
			//print_r($result1);
			//echo '_'.$result1['telefon'];
			 $res=dbase_add_record($db, array(
			 $result1['date_formatted'],
			 $result1['nom_ab'],
			 $result1['telefon'],
			 $result1['email']
			 ));
			 $percent = intval($i/$cn * 100)."%";
				     		
				     		echo '<script language="javascript">
			    	    		document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
			    	    		document.getElementById("information").innerHTML="'.$i.' записів сформовано довідника абонентів сайту.";
			    			</script>';
			    	$i=$i+1;	   

		}
		dbase_close($db);
	}
	{unset($_POST['do_data']);
	    echo "<form action='../rk/ex_t.php' method='POST'>
			 	<p><strong>Експорт телефонів з сайту</strong></p>
			 	<button type='submit' name='do_data'>Зформувати</button>
				<button type='submit' name='do_file'>Зберегти на комп'ютер</button>
			 	</form>";
}

//echo'<pre>';
//print_r($result);
?>