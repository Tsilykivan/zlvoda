<?php
//ini_set('display_errors',1);
	require "../rk/db.php";
	include "../rk/q1.php";
	 

	function shownopl($what,$par1=-5){
		//require "../rk/db.php";
		$nom_abs=R::find('opl','nom_ab=?',array($what));
		if (empty($what)) {
			echo "<a href='tain.php'>Повернутись</a>";
		}
		echo "<div class='blokk3'>".'Розрахунки абонента '.$what.'<br>';
		//echo "<div class='blokk'>".'Розрахунки абонента '.$what.'<br>';
		
		echo "<table >";
	    echo "<tr>";
	    echo "<th>Період </th>";
	   	echo "<th>Дата оплати</th>";
	    echo "<th>Cума</th>";

	    echo "<th>Джерело платежу</th>";
	    echo '</tr>';
		$i=1;	
		$g=1;	
		
		$nom_abs = array_slice($nom_abs, $par1, 5);
		
		foreach ($nom_abs as $nom_ab) {
			echo "<tr><td> ".substr($nom_ab['date'],0,4).'.'.substr($nom_ab['date'],4,2)."</td>  ";
			echo "<td> ".substr($nom_ab['date_opl'],0,4).'.'.substr($nom_ab['date_opl'],4,2).'.'.substr($nom_ab['date_opl'],6,2)."</td>  ";
			echo "<td> ".number_format($nom_ab['suma_k'],2)."</td>  ";
			$norgbind=R::findOne('norg','id=?',array($nom_ab['norg']));
			echo "<td> ".$norgbind->name."</td></tr>  ";
			$i++;
		}
		echo "</table>";
		echo '</div>';
		echo "<div id='clear'> ";	
		$nom_abs=R::find('opl','nom_ab=?',array($what));
		foreach ($nom_abs as $nom_ab) {
			if(($i%5)==0){
				$k=$g*5;
				
				echo "
				<div class='navbtn'>
				<form action='../rk/tain.php' method='POST'>
				  <input type=hidden name='nom_ab_opl' value=$what>
				  <input type=hidden name='goto' value=-$k>
				  <button type=submit name=do_opl_next>$g</button>
				</form>
				</div>";
				$g++;				
			}			
			$i++;
		} 
		echo "<div id='clear'> ";	
	}	

	function shownoab1($what)	
	{
	 
		echo "<link href='../rk/styl.css' rel='stylesheet'>";
	echo "<div class='blokk'>";
	
	$nom_abs=R::find('userab','login=?',array($what->email));
	
		echo '<strong>Ваші номери абонентів для обслуговування</strong>';
		foreach ($nom_abs as $nom_ab) 
		{
			
		 	echo "<br>Абонентський номер ".$nom_ab['nom_ab']." <br> ";
		 	$abonent=R::findOne('abonent','nom_ab=?',array($nom_ab['nom_ab']));
		 	echo 'Абонент  '.$abonent->fio.'   <br>';
			echo 'Зареєстровано:  '.$abonent->prop.' осіб <br>';
			//echo 'Нарахування :  '.$abonent->narax.' грн. <br>';
			$vul=R::findOne('stret','kod_str='.$abonent->kod_str);
			echo 'Адреса надання послуги ';
			$retVal = (($abonent->suma)>0) ? "Заборгованість" : "Переплата" ; 	
			$saldo=($abonent->suma)*-1;
			$retValSum = (($abonent->suma)>0) ? "<span style='color:RED'>$abonent->suma</span>." : "<span style='color:GREEN'>$saldo</span>" ; 	
			if ($vul) {echo ' вулиця  '.$vul->name_str.' ';}
			echo 'Будинок  '.$abonent->house.' ';
			if (($abonent->FLAT)<>0) {echo 'Квартира  '.$abonent->flat.' ';}
			echo '<br><b>'.$retVal.' становить:'.$retValSum.' грн </b><br> ';
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
			echo "<hr>";
		}
		$nom_abuser=$nom_ab;
	echo '</div>';
	echo "<div id='clear'>";
	}	
	
?>
<?php 	
//$pperiod='2020-01-01';
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
			<form action=' tain.php' method='POST'>
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
	
	if(isset($data['do_opl_nom_ab']))
	{
		//echo'знімаємє2';
	//echo "<pre>";
	
//var_dump($nom_abuser); /*
	?>
		<form action='../rk/tain.php' method='POST'>
		<select size='1' name=nom_ab_opl>
		<p><strong>Вкажіть особовий рахунок для перегляду розрахунків</strong></p>
	<? $nom_abuser=R::find('userab','login=?',array($_SESSION['logged_user']->email)); 	 	
	foreach ($nom_abuser as $nom_ab) { echo '<option value='.$nom_ab['nom_ab'].'>'.$nom_ab['nom_ab'].'</option>' ;}?>
	 	</select>
		<button type='submit' name='do_opl_history'>ОК</button>
	 	</form>
	<?	 
	}

	if(isset($data['do_del_nom_ab']))
	{
		//echo'знімаємє2';
		$nom_abuser=R::find('userab','login=?',array($_SESSION['logged_user']->email)); 	 	
		
		 
		 //echo "
		?>

		 <form action='tain.php' method='POST'>
		  	<select size='1' name=nom_ab>
		 	<p><strong>Відключити особовий рахунок</strong></p>
		 <?	foreach ($nom_abuser as $nom_ab) { echo '<option value='.$nom_ab['nom_ab'].'>'.$nom_ab['nom_ab'].'</option>' ;}?>
			</select>
			<!--<input type='text' name='nom_ab' value=''>-->
			<button type='submit' name='do_del'>ОК</button>
		 </form>
       <?
	}
	if (isset($data['do_del'])) {
		//echo "тут стир";
		//провіряєм чи вірно ввели номер чи не чужий

		if (R::count('userab','nom_ab=? and login=?' ,array(
			intval($data['nom_ab']),
			$_SESSION['logged_user']->email
			))==0)
		{
		$errors[]='Абонента з таким номером і поштою не існує у Вас';
		}

		if (R::count('abonent','nom_ab=?',array(intval($data['nom_ab'])))==0)
		{
			$errors[]='Абонента з таким номером не існує';
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
		 <form action='../rk/tain.php' method='POST'>
		 	<p><strong>Додати особовий рахунок</strong></p>
			<input type='text' name='nom_ab' value=''>
			<button type='submit' name='do_add'>ОК</button>
		 </form>";			
	}

	if (R::count('userab','login=?',array($_SESSION['logged_user']->email))<=5)
	{echo " ";
		echo "<form action='../rk/tain.php' method='POST'>
		<button type='submit' name='do_add_nom_ab'>Додати особовий номер абонента</button> 
		<button type='submit' name='do_del_nom_ab'>Видалити особовий номер абонента</button> 
		<button type='submit' name='do_opl_nom_ab'>Розрахунки абонента</button> 
		
		</form><hr>	";
	}
	if(isset($data['do_add']))
    {
		if (R::count('userab','nom_ab=?',array(intval($data['nom_ab'])))>0)
		{
			$errors[]='Абонент з таким номером вже зареєстрований';
		}

		if (R::count('abonent','nom_ab=?',array(intval($data['nom_ab'])))==0)
		{
			$errors[]='Абонента з таким номером не існує';
		}	//var_dump($email);
		
		else
		{
			//ДОДАЄМО НОМЕР
			//shownoab($_SESSION['logged_user']->email);
			if ((R::count('userab','login=?',array($_SESSION['logged_user']->email))<=5)&&(empty($errors)))
			{
				//echo 'додали номер '.$data['nom_ab'];
				$userab=R::findOrCreate('userab', array(
					'login'=>$_SESSION['logged_user']->email,
					'nom_ab' => intval($data['nom_ab']),
					'date_reg'=> time()						
				 ));
				R::store($userab);

			}	
			else
			{
				$errors[]='Не більше 5-х реєстрацій абонента';
			}
			
		}
		if (count($errors)>0) 
		{
			echo "Помилка ! ".array_shift($errors);
		}
	}

	shownoab1($_SESSION['logged_user']);
	{ 			
		echo "<form action='../rk/tain.php' method='POST'>
		<button type='submit'  name='do_inp_pok'>Подати показник</button> 
		</form>";		
	}
	
	if (isset($data['do_opl_history'])) {
		//echo "тут стир";
		//провіряєм чи вірно ввели номер чи не чужий

		if (R::count('userab','nom_ab=? and login=?' ,array(
			intval($data['nom_ab_opl']),
			$_SESSION['logged_user']->email
			))==0)
		{
		$errors[]='Абонента з таким номером і поштою не існує у Вас';
		}

		if (R::count('abonent','nom_ab=?',array(intval($data['nom_ab_opl'])))==0)
		{
			$errors[]='Абонента з таким номером не існує';
		}	//var_dump($email);
		
		if (!empty($errors)){
			echo "Увага ".array_shift($errors);
		}
		else
		{	
			
			echo "<div id='clear'> ";	
			if(isset($_POST['do_opl_history'])){
				shownopl($_POST['nom_ab_opl']);
			}
			
			if(isset($_POST['do_opl_next'])){
				shownopl($_POST['nom_ab_opl'],$_POST['goto']);
			}
			
		}	
	}	
	if(isset($_POST['do_opl_next'])){
				shownopl($_POST['nom_ab_opl'],$_POST['goto']);
	}

	if (isset($data['do_pok'])=='true')	
		{
			if (isset($data['do_pok'])=='true')	
			{
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
				$kstkubiv=$llx1-intval($abonent->lx1)+
						$llx2-intval($abonent->lx2)+
						$llt1-intval($abonent->lt1)+	
						$llt2-intval($abonent->lt2);
				if ($kstkubiv>100)
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
				//2021-04-09
				//'period'=> '2021-04-01',

			$pok=R::findOrCreate('pok', array(
				'nom_ab' => $data['nom_ab_pok'],
				'period'=> $maxdate2,
			'login'=>$_SESSION['logged_user']->email
			 ));
			$period1=$pok['period'];
			//echo "".$pok;

			//echo "mes=".substr($pok['period'], 5,2)."";
			//echo "yea=".substr($pok['period'], 0,4)."";
			$mes=substr($pok['period'], 5,2);
			$yea=substr($pok['period'], 0,4);
			 $pok->pok_date=time();
			 $pok->lx1=(isset($data['lx1']))?($data['lx1']):0;
			 $pok->lx2=(isset($data['lx2']))?($data['lx2']):0;
			 $pok->lt1=(isset($data['lt1']))?($data['lt1']):0;
			 $pok->lt2=(isset($data['lt2']))?($data['lt2']):0;
			 $pok->ipadr=$_SERVER['REMOTE_ADDR'];					 
				 R::store($pok);
				echo "<hr>Показник отримано. Спожито станом на кінець ".$mes." місяця ".$yea." року ".$kstkubiv.' м.куб води'; 
			}
			else
			{
			//	echo ''.array_shift($errors);
			}
		}
			
		if (isset($data['do_inp_pok'])) 
		{   
			?>

		 	<form action=' tain.php' method='POST'>
		 	<select size='1' name=nom_ab_pok>	
		 	<p><strong>Вкажіть особовий рахунок для внесення показників</strong></p>

			<!--<input type='text' name='nom_ab_pok' value=''>-->
			<? $nom_abuser=R::find('userab','login=?',array($_SESSION['logged_user']->email)); 	 	
			foreach ($nom_abuser as $nom_ab) { echo '<option value='.$nom_ab['nom_ab'].'>'.$nom_ab['nom_ab'].'</option>' ;}?>
			</select>
			<button type='submit' name='do_inp'>Показати</button>
		 	</form>"
		 	<?		
		}		
		if (isset($data['do_inp'])) 
			{
				if (R::count('userab','nom_ab=? and login=?' ,array(
				intval($data['nom_ab_pok']),
				$_SESSION['logged_user']->email
				))==0)
				{
					$errors[]='Абонента з таким номером  не існує у Вас додайте';
				}
			if (R::count('abonent','nom_ab=?',array(intval($data['nom_ab_pok'])))==0)
				{
					$errors[]='Абонента з таким номером не існує';
				}	//var_dump($email);
			if (!empty($errors))
				{
					echo "Увага ".array_shift($errors);
				}
				else
				{	
					echo "<div class='history_pok'>Картка абонента №".intval($data['nom_ab_pok'])."<br>";

					 $abonent=R::findOne('abonent','nom_ab=?',array(intval($data['nom_ab_pok'])));
					 if (isset($abonent))
						{	echo '<strong>Попередній показник (установа) </strong>станом на '.$abonent->date.'';
						if (!empty($abonent->lx1) or ($abonent->ppp=1)) 
						{echo '<br>Лічильник1:  '.$abonent->lx1.' м.куб ';}
						if (!empty($abonent->lx2)) 
						{echo '<br>Лічильник2:  '.$abonent->lx2.' м.куб ';}
						if (!empty($abonent->lt1)) 
						{echo '<br>Лічильник3:  '.$abonent->lt1.' м.куб ';}
						if (!empty($abonent->lt2)) 
						{echo '<br>Лічильник4:  '.$abonent->lt2.' м.куб ';}
									    
						
						$poks=R::findLike('pok', array(
						'nom_ab'=>$abonent->nom_ab,
						'login'=>$_SESSION['logged_user']->login
						));
						echo '<br><strong>Історія показів (користувач)</strong>'.'<br>';
						echo "<table>";
					    echo "<tr>";
					    	echo "<th>Дата подачі</th>";
					    	echo "<th>Період </th>";
					    	//echo '<th>Номер</th>';
					    	echo "<th>Лічильник</th>";

					    echo '</tr>';
					   
						$poks = array_slice($poks, -5, 5); 	//зменшуємо вивід до 5 останніх показників
						foreach ($poks as $pok1)
						{   
							echo '<tr>';
							echo '<td>'.date('Y/m/d/ H:i:s',$pok1->pok_date).'  ';
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


							// echo 'Дата подання :'.date('Y/m/d/ H:i:s',$pok1->pok_date).'  ';
							// echo 'Період :'.$pok1->period.'  ';
							// if (!empty($pok1->lx1)  or ($abonent->ppp=1))
							// {echo 'Лічільник1 : '.$pok1->lx1.'  м.куб ';}
							// if (!empty($pok1->lx2))
							// {echo 'Лічільник2:  '.$pok1->lx2.'  м.куб ';}	
							// if (!empty($pok1->lt1))
							// {echo 'Лічільник3:  '.$pok1->lt1.'  м.куб ';}
							// if (!empty($pok1->lt2)) 
							// {echo 'Лічільник4:  '.$pok1->lt2.'  м.куб ';}
							// echo('<br>');									
						}
						echo "</table>";
				    	

						$lx1=$pok1->lx1>$abonent->lx1?$pok1->lx1:$abonent->lx1;
						$lx2=$pok1->lx2>$abonent->lx2?$pok1->lx2:$abonent->lx2;
						$lt1=$pok1->lt1>$abonent->lt1?$pok1->lt1:$abonent->lt1;
						$lt2=$pok1->lt2>$abonent->lt2?$pok1->lt2:$abonent->lt2;
						$nom_ab_pok=$data['nom_ab_pok'];
						echo('<strong>Для номера абонента № </strong>'.intval($data['nom_ab_pok']).'<br>');

						echo 
					    "<form action=' tain.php' method='POST'>";
					    //2021-04-09
					    //'04 місяць 2021'
						echo 
					    "<form action=' tain.php' method='POST'>
					    <div class='history_pok'>Період подачі за <input type='text ' name='period' readonly value=$lRobPeriodPokSmall><strong><br>   Номер: </strong><input type='text' name='nom_ab_pok' readonly value=".intval($data['nom_ab_pok']).">";
					    
					    if (!empty($lx1)  or ($abonent->ppp=1)) 
					    {
					    	echo "<strong><br> Лічільник1: </strong><input type='text' name='lx1' value=".$lx1.">";
					    }					    
					    if (!empty($lx2)) 
					    {
					    	echo "<strong><br> Лічільник2: </strong><input type='text' name='lx2' value=".$lx2.">";
					    }
					    if (!empty($lt1)) 
					    {
							echo("<strong><br> Лічільник3: </strong><input type='text' name='lt1' value=".$lt1.">");
					    }
						if (!empty($lt2)) 
						{
							echo "<strong><br> Лічільник4: </strong><input type='text' name='lt2' value=".$lt2."><br>";
						}
						if ($abonent->ppp==1) 
						{
							echo"<br><button type='submit' name='do_pok'>Записати показники </div></button> 
					    </form>";
					    echo "</div>";	
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
		

		echo "</div>";
		echo "<div id='clear'>";
 	
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
	<link href="../rk/styl.css" rel="stylesheet">
	
	<tain>
		<!--<div class="bg-grey">
			<div class="header1">
				<img src="img/embl.JPG">
				<h2>Вас вітає МКП "Золочівводоканал " м.Золочева Львівської області</h2>
		</div>

			--><h4> Персональний кабінет користувача (облік населення) <br></h4><hr>

			  <!-- <h3>На даний момент показники приймаються за <u>Квітень</u> 2021 року до кінця місяця<br></h3>-->
			   <?echo '<h3>'.$lRobPeriodPok.'<br></h3>';?>
			   <!--<h3>На даний момент показники приймаються за <u>Квітень</u> 2021 року до кінця місяця<br></h3>-->
			   <h3>Увага!  з 1 березня 2021 р зміна ціни на послуги для населення <br></h3>
			   <h3>Ціна з березня 2021 водопостачання 18,34 грн, водовідведення 18,61 грн разом 36,95 грн <br></h3>

			<hr>
			<a href="../rk/login.php"><h4> Увійти </h4></a>
			<a href="../rk/signup.php"><h4>Реєстрація </h4></a><br>
			<a href="../rk/vosst_pass.php">Відновити пароль через електронну пошту (email)<br><br><br></a>

			<h3>Наші реквізити<br></h3>
			<h4>Банківські реквізити: р/р формат IBAN UA273052990000026007031015521<br></h4>
			<h4>в ЗВ ЗГРУ “Приватбанк” МФО 305299, код ЄДРПОУ № 05514287. <br></h4>
			<a href="https://zolochivvodokanal.wordpress.com/ " target="blank"><h4>Більше інформації про нас на сайті https://zolochivvodokanal.wordpress.com/ </h4></a>
		</div>
	</tain>

	
<?php endif;?>
<!--<div id="footer">
        Знайшли помилку на сайті? Допоможіть нам її виправити! <br> 
	Відправте повідомлення на скриньку zlvoda@gmail.com
	<footer><hr>&copy; МКП Золочівводоканал '2019' </footer>
    </div>
 -->   
<?php
	//<a href="/vosst_pass.php">Відновити пароль</a>
	?>

