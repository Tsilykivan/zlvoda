<?php
//ini_set('display_errors',1);
	require "db.php";
	function shownoab1($what)
	{echo "<link href='styl.css' rel='stylesheet'>";
	 echo "<div class='blokk'>";
		$nom_abs=R::find('userab','login=?',array($what->email));
		echo '<strong>Ваші номери абонентів для обслуговування</strong>';
		foreach ($nom_abs as $nom_ab) 
		{
		 	echo "<br>Абонентський номер ".$nom_ab['nom_ab']." <br> ";
		 	$abonent=R::findOne('abonent','nom_ab=?',array($nom_ab['nom_ab']));
		 	echo 'Абонент  '.$abonent->fio.'   <br>';
			echo 'Зареєстровано:  '.$abonent->prop.' осіб <br>';
			$vul=R::findOne('stret','kod_str='.$abonent->kod_str);
			echo 'Адреса надання послуги ';
			$retVal = (($abonent->suma)>0) ? "Заборгованість" : "Переплата" ; 	
			$saldo=($abonent->suma)*-1;
			$retValSum = (($abonent->suma)>0) ? "<span style='color:RED'>-$abonent->suma</span>." : "<span style='color:GREEN'>$saldo</span>" ; 			
			if ($vul) {echo ' вулиця  '.$vul->name_str.' ';}
			echo 'Будинок  '.$abonent->house.' ';
			if (($abonent->FLAT)<>0) {echo 'Квартира  '.$abonent->flat.' ';}
			echo '<br>'.$retVal.' становить:'.$retValSum.' грн <br> ';
			if (!empty($abonent->ppp)) 
			{echo 'Останній отриманий показник:  станом на '.$abonent->date.' в установі ';}	 
			if(!empty($abonent->lx1))
			{echo 'Лічільник1:  '.$abonent->lx1.' м.куб<br>';}
			if(!empty($abonent->lx2))
			{echo 'Лічільник2:  '.$abonent->lx2.' м.куб<br>';}
			if(!empty($abonent->lt1))
			{echo 'Лічільник3:  '.$abonent->lt1.' м.куб<br>';}
			if(!empty($abonent->lt2))
			{echo 'Лічільник4:  '.$abonent->lt2.' м.куб<br>';}
			
		}
	echo '</div>';	
	}	
	
?>
<?php 	
if (isset($_SESSION['logged_user'])): 	?> 
	Вітаємо,   
	<?php echo ' Ви успішно авторизовані: '.$_SESSION['logged_user']->email.'';?>
	<?php echo "дата реєстрації: ".date('Y/m/d', $_SESSION['logged_user']->join_date);?>
	<hr>
	<?php
	$data=$_POST;
	$errors=array();
	$email=R::findOne('usersnew','email=?',array($_SESSION['logged_user']->email));
	if (isset($email->email))	 
	{
		if ($email->activ==0) 
		{
			echo "Далі Вам потрібно активувати електронну пошту 
			<form action=' main.php' method='POST'>
			<button type='submit' name='do_act_email'>Відправити листа ?</button> 
			</form>"				;
		}
		else
		{
			//echo 'Ваша пошта: '.$_SESSION['logged_user']->email.'';
			//echo ' верифікована '.date('Y/m/d/ H:i:s',$email->time_act).'<br>';
		}
	}
	else
	{
		echo "незареєстрована електронна пошта";
	}
	
	
	 
	if(isset($data['do_del_nom_ab']))
	{
		//echo'знімаємє2';
		 echo "
		 <form action=' main.php' method='POST'>
		 	<p><strong>Відключити особовий рахунок</strong></p>
			<input type='text' name='nom_ab' value=''>
			<button type='submit' name='do_del'>ОК</button>
		 </form>";

	}
	if (isset($data['do_del'])) {
		//echo "тут стир";
		//провіряєм чи вірно ввели номер чи не чужий

		if (R::count('userab','nom_ab=? and login=?' ,array(
			intval($data['nom_ab']),
			$_SESSION['logged_user']->email
			))==0)
		{
		$errors[]='Абонета з таким номером і поштою не існує у Вас';
		}

		if (R::count('abonent','nom_ab=?',array(intval($data['nom_ab'])))==0)
		{
			$errors[]='Абонета з таким номером не існує';
		}	//var_dump($email);
		
		if (!empty($errors)){
			echo "Увага ".array_shift($errors);

		}
		else
		{
			R::exec('DELETE FROM userab WHERE nom_ab=? and login=?' ,array(
			$data['nom_ab'],
			$_SESSION['logged_user']->email
			));
			//shownoab1($_SESSION['logged_user']);
		}	

	}
	if(isset($data['do_add_nom_ab']))
	{
		 //echo 'Особовий рахунок: додаєм? ';
		 echo "
		 <form action=' main.php' method='POST'>
		 	<p><strong>Додати особовий рахунок</strong></p>
			<input type='text' name='nom_ab' value=''>
			<button type='submit' name='do_add'>ОК</button>
		 </form>";			
	}
	if (R::count('userab','login=?',array($_SESSION['logged_user']->email))<=3)
	{echo " ";
		echo "<form action=' main.php' method='POST'>
		<button type='submit' name='do_add_nom_ab'>Додати особовий номер абонента</button> 
		<button type='submit' name='do_del_nom_ab'>Видалити особовий номер абонента</button> 
		
		</form><hr>	";
	}

	shownoab1($_SESSION['logged_user']);
	{
		echo "<form action=' main.php' method='POST'>
		<button type='submit' name='do_inp_pok'>Подати показник</button> 
		</form>";
	}	
	
	if (isset($data['do_pok'])=='true')	
		{
			//провірка пок
			if (isset($data['do_pok'])=='true')	
			{
				//$errors=array();
				//перевірка чи показник не є меншим за попередній
				$abonent=R::findOne('abonent','nom_ab=?',array(intval($data['nom_ab_pok'])));
					
				if(isset($data['lx1']))
				{
					if ($abonent->lx1>$data['lx1'])
					{
					$errors[] = 'Увага показник менший за попередній Лічільник1';
					}
				}
				if(isset($data['lx2']))
				{
					if ($abonent->lx2>$data['lx2'])
					{
					$errors[] = 'Увага показник менший за попередній Лічільник2';
					}
				}
				
				if(isset($data['lt1']))
				{	
					if ($abonent->lt1>$data['lt1']) 
					{
					$errors[] = 'Увага показник менший за попередній Лічільник3';
					}
				}
				if(isset($data['lt2']))
				{		
					if ($abonent->lt2>$data['lt2']) 
					{
					$errors[] = 'Увага показник менший за попередній Лічільник4';
					}
				}	
				$llx1 = (isset($data['lx1'])) ? $data['lx1'] : 0;
				$llx2 = (isset($data['lx2'])) ? $data['lx2'] : 0;
				$llt1 = (isset($data['lt1'])) ? $data['lt1'] : 0;
				$llt2 = (isset($data['lt2'])) ? $data['lt2'] : 0 ;
//intval($data['lx1']+11)
				//if(!isset($data['lx2'])){$llx2=0;}else {$llx1=intval($data['lx2']);}
				//if(!isset($data['lt1'])){$llx1=0;}else {$llt1=intval($data['lt1']);}
				//if(!isset($data['lt2'])){$llt2=0;}else {$llx1=intval($data['lt2']);}
				
				//var_dump($llt2);
				$kstkubiv=$llx1-intval($abonent->lx1)+
						$llx2-intval($abonent->lx2)+
						$llt1-intval($abonent->lt1)+	
						$llt2-intval($abonent->lt2);
				if ($kstkubiv>200)
				{					
					$errors[] = ' ДУЖЕ ВЕЛИКА КІЛЬКІСТЬ КУБІВ СПОЖИВАННЯ  ! '.$kstkubiv;						
				}	
				if (!empty($errors))
				{					
				echo "УВАГА !".$errors[0].'<br>';
				}
			}	
			if (empty($errors)) 
			{
				//var_dump($data);
				
			$pok=R::findOrCreate('pok', array(
				'nom_ab' => $data['nom_ab_pok'],
				'period'=> '2019-10-01',
			'login'=>$_SESSION['logged_user']->email
			 ));
			 $pok->pok_date=time();
			 $mes=substr($pok['period'], 5,2);
			$yea=substr($pok['period'], 0,4);
			 $pok->lx1=(isset($data['lx1']))?($data['lx1']):0;
			 $pok->lx2=(isset($data['lx2']))?($data['lx2']):0;
			 $pok->lt1=(isset($data['lt1']))?($data['lt1']):0;
			 $pok->lt2=(isset($data['lt2']))?($data['lt2']):0;
			 $pok->ipadr=$_SERVER['REMOTE_ADDR'];					 
				 R::store($pok);
				echo "<hr>Показник отримано. Спожито станом на кінець ".$mes." місяця ".$yea." року ".$kstkubiv.' м.куб води'; 
				//echo "<hr>Показник отримано. Спожито станом на кінець   ".date(' m місяця Y ріку ',$pok->pok_date)."  ".$kstkubiv.' м.куб води'; 
			}
			else
			{
			//	echo ''.array_shift($errors);
			}
		}	
		if (isset($data['do_inp_pok'])) 
		{   echo "
		 	<form action=' main.php' method='POST'>
		 	<p><strong>Вкажіть особовий рахунок для внесення показників</strong></p>
			<input type='text' name='nom_ab_pok' value=''>
			<button type='submit' name='do_inp'>Показати</button>
		 	</form>";		
		}		
		if (isset($data['do_inp'])) 
			{
				if (R::count('userab','nom_ab=? and login=?' ,array(
				intval($data['nom_ab_pok']),
				$_SESSION['logged_user']->email
				))==0)
				{
					$errors[]='Абонета з таким номером  не існує у Вас додайте';
				}
			if (R::count('abonent','nom_ab=?',array(intval($data['nom_ab_pok'])))==0)
				{
					$errors[]='Абонета з таким номером не існує';
				}	//var_dump($email);
			if (!empty($errors))
				{
					echo "Увага ".array_shift($errors);
				}
				else
				{	
					$abonent=R::findOne('abonent','nom_ab=?',array(intval($data['nom_ab_pok'])));
					if (isset($abonent))
					{	echo '<strong>Попередній показник (установа) </strong>станом на '.$abonent->date.'';
						if (!empty($abonent->lx1)   or ($abonent->ppp=1)) 
						{echo '<br>Лічільник1:  '.$abonent->lx1.' м.куб ';}
						if (!empty($abonent->lx2)) 
						{echo '<br>Лічільник2:  '.$abonent->lx2.' м.куб ';}
						if (!empty($abonent->lt1)) 
						{echo '<br>Лічільник3:  '.$abonent->lt1.' м.куб ';}
						if (!empty($abonent->lt2)) 
						{echo '<br>Лічільник4:  '.$abonent->lt2.' м.куб ';}
									    
				
						$poks=R::findLike('pok', array(
						'nom_ab'=>$abonent->nom_ab,
						'login'=>$_SESSION['logged_user']->login
						));
						echo '<br><strong>Історія показів (користувач)</strong>'.'<br>';
						foreach ($poks as $pok1)
						{   
							echo 'Дата подання :'.date('Y/m/d/ H:i:s',$pok1->pok_date).'  ';
							echo 'Період :'.$pok1->period.'  ';
							if (!empty($pok1->lx1))
							{echo 'Лічільник1 : '.$pok1->lx1.'  м.куб ';}
							if (!empty($pok1->lx2))
							{echo 'Лічільник2:  '.$pok1->lx2.'  м.куб ';}	
							if (!empty($pok1->lt1))
							{echo 'Лічільник3:  '.$pok1->lt1.'  м.куб ';}
							if (!empty($pok1->lt2)) 
							{echo 'Лічільник4:  '.$pok1->lt2.'  м.куб ';}
							echo('<br>');									
						}

						$lx1=$pok1->lx1>$abonent->lx1?$pok1->lx1:$abonent->lx1;
						$lx2=$pok1->lx2>$abonent->lx2?$pok1->lx2:$abonent->lx2;
						$lt1=$pok1->lt1>$abonent->lt1?$pok1->lt1:$abonent->lt1;
						$lt2=$pok1->lt2>$abonent->lt2?$pok1->lt2:$abonent->lt2;
						$nom_ab_pok=$data['nom_ab_pok'];
						echo('<strong>Для номера абонента № </strong>'.$data['nom_ab_pok'].'<br>');

						echo 
					    "<form action=' main.php' method='POST'>";
					    
						echo 
					    "<form action=' main.php' method='POST'>
					    <strong>Номер: </strong><input type='text' name='nom_ab_pok' readonly value=".$data['nom_ab_pok'].">";
					    if (!empty($lx1)   or ($abonent->ppp=1)) 
					    {
					    	echo "<strong>Лічільник1: </strong><input type='text' name='lx1' value=".$lx1.">";
					    }
					    
					    if (!empty($lx2)) 
					    {
					    	echo "<strong>Лічільник2: </strong><input type='text' name='lx2' value=".$lx2.">";
					    }

					    if (!empty($lt1)) 
					    {
							echo("<strong>Лічільник3: </strong><input type='text' name='lt1' value=".$lt1.">");
					    }
						if (!empty($lt2)) 
						{
							echo "<strong>Лічільник4: </strong><input type='text' name='lt2' value=".$lt2."><br>";
						}
						if ($abonent->ppp==1) 
						{
							echo"<br><button type='submit' name='do_pok'>Записати показники </button> 
					    </form>";
						}		
					}		
				}
			}						
		if (isset($data['do_act_email'])&&($email->activ==0)) 
		{
			$activation = md5($email->id);
			$headers = "From:admin@zlvoda.com.ua\r\nContent-type:text/html;charset=utf-8\r\n";
			$subject = "Підтвердження реєстрації! МКП Золочівводоканал<br>";
			$subject = "=?utf-8?b?".base64_encode($subject)."?=";
			$message = "Вітаєм! Ви зареєструвались в кабінеті МКП 'Золочівводоканал'.\nВаша пошта: ".$_SESSION['logged_user']->email."\n
			Щоб актививувати Ваш кабінет, перейдіть за посиланням:<br>\n
			<a href='https://zlvoda.com.ua//activation.php?login=".$_SESSION['logged_user']->email."&act=".$activation."'>Перейти</a><br><br>\n\n
			З повагою, Адміністрація сайту https://zlvoda.com.ua";
			mail($_SESSION['logged_user']->email, $subject, $message, $headers) ;
			echo "Пошта відправлена на адресу ".$_SESSION['logged_user']->email." вказану при реєстрації. <br> " ;
		}
		
	   	if(isset($data['do_add']))
	    {
			if (R::count('userab','nom_ab=?',array(intval($data['nom_ab'])))>0)
			{
				$errors[]='Абонет з таким номером вже зареєстрований';
			}

			if (R::count('abonent','nom_ab=?',array(intval($data['nom_ab'])))==0)
			{
				$errors[]='Абонета з таким номером не існує';
			}	//var_dump($email);
			
			else
			{
				//ДОДАЄМО НОМЕР
				//shownoab($_SESSION['logged_user']->email);
				if ((R::count('userab','login=?',array($_SESSION['logged_user']->email))<=2)&&(empty($errors)))
				{
					echo 'додали номер '.$data['nom_ab'];
					$userab=R::findOrCreate('userab', array(
						'login'=>$_SESSION['logged_user']->email,
						'nom_ab' => intval($data['nom_ab']),
						'date_reg'=> time()						
					 ));
					R::store($userab);
				}	
				else
				{
					$errors[]='Не більше 3-х реєстрацій абонента';
				}
				
			}
			if (count($errors)>0) {
				echo "Помилка ! ".array_shift($errors);
			}
		}
 	
	 ?>

	 
	
	<?php //echo "щоб додати номер абонента нажміть додати не більше п'яти"?>
	<br>
	<br>
	<hr>
	<a href="logout.php">Вийти</a>
<?php  else:	?>
	<meta charset="utf-8">
	<title>МКП Золочівводоканал</title>
	<meta name="description" content="ввід показників Золочівводоканал">
	<meta name="keywords" content="Золочівводоканал, ввід показників,водоканал">
	<link href="styl.css" rel="stylesheet">
	
	<main>
		<!--<div class="bg-grey">
			<div class="header1">
				<img src="img/embl.JPG">
				<h2>Вас вітає МКП "Золочівводоканал " м.Золочева Львівської області</h2>
		</div>

			--><h4> Персональний кабінет користувача (облік населення) <br></h4><hr>
			   <h3>На даний момент показники приймаються за <u>жовтень</u> 2019 року до кінця місяця<br></h3>
			
			   <h4>Ціна на водопостачання 15,60 грн водовідведення 16,50 грн разом 32,10 грн <br></h4>
			<hr>
			<a href="/login.php"><h4> Увійти </h4></a>
			<a href="/signup.php"><h4>Реєстрація </h4></a><br>
			<a href="/vosst_pass.php">Відновити пароль через електронну пошту (email)<br><br><br></a>
		</div>
	</main>

	
<?php endif;?>
<!--<div id="footer">
        Знайшли помилку на сайті? Допоможіть нам її виправити! <br> 
	Відправте повідомлення на скриньку zlvoda@gmail.com
	<footer><hr>&copy; МКП Золочівводоканал 2019 </footer>
    </div>
 -->   
<?php
	//<a href="/vosst_pass.php">Відновити пароль</a>
	?>

