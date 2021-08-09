<?php
	require "../rk/db.php";
	echo "<link href='../rk/styl.css' rel='stylesheet'>";
	
	
	//unset($_POST['do_data']);
	if (isset($_POST['do_nom_ab']));
		{
			if (isset($_POST['do_nom_ab']))
		   {
		   		$fff= $_POST['nom_ab'];
				$poks=R::findLike('pok', array(						
									'nom_ab'=>$fff
									));
				//var_dump($poks);
				if ($poks==NULL)
				{
					
					echo "<span style='color:Blue;background-color: #3556; '>Абонет ще не подавав покази на сайті</span>";
					
				}
				echo "<table>";				
					echo "<tr><td>Абонентський номер ".$fff." </td></tr> ";
				 	$abonent=R::findOne('abonent','nom_ab=?',array($fff));
				 	echo '<tr><td>Абонент'.$abonent->fio.'  </td></tr>';
					echo '<tr><td>Зареєстровано:  '.$abonent->prop.' осіб </td></tr>';
					$vul=R::findOne('stret','kod_str='.$abonent->kod_str);
					echo '<tr><td>Адреса надання послуги ';
					$retVal = (($abonent->suma)>0) ? "Заборгованість " : "<tr><td>Переплата " ; 	
					$saldo=($abonent->suma)*-1;
					$retValSum = (($abonent->suma)>0) ? "<span style='color:RED'>-$abonent->suma</span>." : "<span style='color:GREEN'>$saldo</span>" ; 	
					if ($vul) {echo 'вулиця  '.$vul->name_str.'';}
					echo ' Будинок  '.$abonent->house.'';
					if (($abonent->FLAT)<>0) {echo 'Квартира  '.$abonent->flat.'';}
					echo '<tr><td>'.$retVal.' становить:'.$retValSum.' грн   </td></tr>';
					if (!empty($abonent->ppp)) 
					{echo '<tr><td>Останній отриманий показник:  станом на '.$abonent->date.' в установі </td></tr>';}	 
					if(!empty($abonent->lx1))
					{echo '<tr><td>Лічільник1:  '.$abonent->lx1.' м.куб</td></tr>';}
					if(!empty($abonent->lx2))
					{echo '<tr><td>Лічільник2:  '.$abonent->lx2.' м.куб</td></tr>';}
					if(!empty($abonent->lt1))
					{echo '<tr><td>Лічільник3:  '.$abonent->lt1.' м.куб</td></tr>';}
					if(!empty($abonent->lt2))
					{echo '<tr><td>Лічільник4:  '.$abonent->lt2.' м.куб</td></tr>';}
				    echo "</table>";

				    echo "<table>";
				    echo '<tr>';
				    	echo '<th>Дата подачі</th>';
				    	echo '<th>Період </th>';
				    	//echo '<th>Номер</th>';
				    	echo '<th>Лічильник</th>';

				    echo '</tr>';
				    $poks = array_slice($poks, -5, 5); 	
					foreach ($poks as $pok1)
					{
						//$i=1;
						echo '<tr>';
						echo '<td>'.date('Y/m/d',$pok1->pok_date).'</td>';
						echo '<td>'.$pok1->period.'</td>';

						if (!empty($pok1->lx1))
						{echo '<td>Ліч1 : '.$pok1->lx1.'  м.куб </td>';}
						if (!empty($pok1->lx2))
						{echo '<td>Ліч2:  '.$pok1->lx2.'  м.куб </td>';}	
						if (!empty($pok1->lt1))
						{echo '<td>Ліч3:  '.$pok1->lt1.'  м.куб </td>';}
						if (!empty($pok1->lt2)) 
						{echo '<td>Ліч4:  '.$pok1->lt2.'  м.куб </td>';}
						echo '<tr>';
					}
					echo "</table>";
			}
			
		}
	if (isset($_POST['do_log'])) 
	{
		if (($_POST['passw']!="")) {
			echo '<div style="color:red;">'.'пароль не вірний'.'</div><hr>';
			echo "
			 	<form action='../rk/main3.php' method='POST'>
			 	<p><strong>Введіть пароль для перегляду абонента </strong></p>
				<input type='password' name='passw' value=''>
				<button type='submit' name='do_log'>Вхід</button>
				</form>";
		}
		else
		{
			$_SESSION['user']=1111;
			if (isset($_POST['do_log']))
		    { 	
		   		echo "
					 	<form action='../rk/main3.php' method='POST'>
					 	<p><strong>Ввідеть номер абонента</strong></p>
						<input type='nom_ab' name='nom_ab' value=''>
						<button type='submit' name='do_nom_ab'>Показати</button>
					 	</form>";		   
			}
		}	
	}
	else
	{
		echo "
			 	<form action='../rk/main3.php' method='POST'>
			 	<p><strong>Введіть пароль для перегляду абонента </strong></p>
				<input type='password' name='passw' value=''>
				<button type='submit' name='do_log'>Вхід</button>
				</form>";
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Перегляд даних абонента</title>
	<link rel="../rk/styl.css" type="text/css" href="">
</head>
<body>

</body>
</html>