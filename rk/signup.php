<?php
	require "../rk/db.php";
	$data=$_POST;
	if (isset($data['do_signup_new'])) 
	{
		$errors=array();

		if (trim($data['email'])=='')
		{
			$errors[]='Введіть email';
		}
		if (trim($data['telefon'])=='')
		{
			$errors[]='Введіть telefon';
		}
		if (trim($data['email'])=='')
		{
			$errors[]='Введіть email';
		}
		
		if (($data['password'])=='')
		{
			$errors[]='Введіть пароль';
		}

		if (($data['password_2'])!=$data['password'])
		{
			$errors[]='Паролі не співпадають';
		}		
		
		if (R::count('usersnew','email=?',array($data['email']))>0)
		{
			$errors[]='Користувач з таким email вже існує';
		}
					//провірка капчі
	    $answers = array(
	        1 => 'київ',
	        2 => 'вашингтон',
	        3 => '5',
	        4 => '29',
	        5 => '35',
	        6 => '30'
	    );
	    if ( $_SESSION['captcha'] != array_search( mb_strtolower($_POST['captcha']), $answers ) )
	    {
	        $errors[] = 'Відповідь не вірно!';
	    }


		if (empty($errors)) 
		{
			 $user = R::dispense('usersnew');
			 $user->email=$data['email'];
			 $user->telefon=$data['telefon'];
			 $user->ipadr=$_SERVER['REMOTE_ADDR'];
			 $user->password=password_hash($data['password'],PASSWORD_DEFAULT);
			 $user->activ=FALSE;
			 $user->join_date=time();
			 R::store($user);
			 echo '<div style="color:green;">"Ви успішно зареєструвались  <br> перейдіть на пошту для активації ел.пошти
			 		<a href="main.php">головну</a> сторінку</div><hr>';

			$posta=R::findOne('usersnew','email=?',array($data['email']));
			if (!empty($posta)) 
			{
				
				$activation = md5($posta->id);
				$headers = "From:admin@zlvoda.com.ua\r\nContent-type:text/html;charset=utf-8\r\n";
				$subject = "Підтвердження реєстрації! МКП Золочівводоканал";
				$subject = "=?utf-8?b?".base64_encode($subject)."?=";
				$message = "Вітаємо! \n<br>Ви зареєструвались в кабінеті МКП 'Золочівводоканал'.<br>
							Ваш пошта: ".$posta->email."<br> 
							Щоб актививувати Ваш кабінет, перейдіть за посиланням
							<a href='https://zlvoda.com.ua/rk/activation.php?login=$posta->email&act=$activation'>https://zlvoda.com.ua</a> 
							<br><hr>
				З повагою, Адміністрація сайту <br>";
				mail($posta->email, $subject, $message, $headers) ;
				//echo ''.$message;
				echo "Пошта відправлена на адресу ".$posta->email." вказану при реєстрації. <br> " ;
			}
		
				
		}
		else 
		{
			echo '<div style="color:red;">'.array_shift($errors).'</div><hr>';
		}	

	}
?>

<form action="signup.php" method="POST">
	<?php
		function captcha_show(){
		    $questions = array(
		        1 => 'Столиця України',
		        2 => 'Столиця США',
		        3 => '2 + 3',
		        4 => '15 + 14',
		        5 => '45 - 10',
		        6 => '33 - 3'
		    );
		    $num = mt_rand( 1, count($questions) );
		    $_SESSION['captcha'] = $num;
		    echo $questions[$num];
		}
	?>
	<p> <a href="main.php"> На головну </a>
	</p>
	
	<p>
		<p><strong>Ваш email</strong></p>
		<input type="email" name="email" value="<?php echo(@$data['email']);?>">
	</p>
	<p>
		<p><strong>Телефон</strong></p>
		<input type="text" name="telefon" value="<?php echo(@$data['telefon']);?>">
	</p>
	<p><strong>Ваш пароль</strong></p>
		<input type="password" name="password" value="<?php echo(@$data['password']);?>">
	</p>
	<p>
		<p><strong>Введіть ваш пароль ще раз</strong></p>
		<input type="password" name="password_2" value="<?php echo(@$data['password_2']);?>" >
	</p>

	<p>
		<strong>Підтвердження реєстрації (Капча)</strong><br>
		<?php captcha_show();?> 
		<strong> відповідь </strong>
    	<input type="text" name="captcha"><br>
    </p>
	<p>
		<button type="submit" name="do_signup_new">Зареєструватись</button> 
	</p>
</form>

