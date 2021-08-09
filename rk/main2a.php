<?php /*// покази по абонентах*/
	require "db.php"; 
	include "../rk/q1.php";
   if (isset($_POST['do_data']))
   {
		$fff= $_POST['date_pok'];
		$poks=R::findLike('pok', array(						
							'period'=>$fff
							));
		$i=1;
		foreach ($poks as $pok1)
		{
			echo ' '.$i.'  ';
			$i++;
			echo 'Дат:'.date('Y/m/d/ H:i:s',$pok1->pok_date).'  ';
			echo 'Пер:'.$pok1->period.'  ';
			echo 'Ном:'.$pok1->nom_ab.'  ';

		$abonent=R::findOne('abonent','nom_ab=?',array($pok1->nom_ab));
			 	echo ''.$abonent->fio.'  ';
				echo '_'.$abonent->prop.' осіб ';
				$vul=R::findOne('stret','kod_str=?',array($abonent->kod_str));
				if ($vul) {echo ' вул:'.$vul->name_str.' ';	}
				echo 'Буд'.$abonent->house.' _ ';
				if ($abonent->flat) {echo 'кварт '.$abonent->flat.' осіб ';} 

			if (!empty($pok1->lx1))
			{echo 'Ліч1 : '.$pok1->lx1.'  м.куб ';}
			if (!empty($pok1->lx2))
			{echo 'Ліч2:  '.$pok1->lx2.'  м.куб ';}	
			if (!empty($pok1->lt1))
			{echo 'Ліч3:  '.$pok1->lt1.'  м.куб ';}
			if (!empty($pok1->lt2)) 
			{echo 'Ліч4:  '.$pok1->lt2.'  м.куб ';}
			echo('<br>');
			
		}

	}
	 
	{
		unset($_POST['do_data']);
	    echo "
			 	<form action='../rk/main2a.php' method='POST'>
			 	<p><strong>Введіть період показників (завжди перше число місяця)(наприклад за червень (01/06/2019)</strong></p>
				<input type='date' name='date_pok' value=$maxdate1>
				<button type='submit' name='do_data'>Показати</button>
			 	</form>";	# code...
	}
?>