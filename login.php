<?php
	require "db.php";
	$data=$_POST;
	if (isset($data['do_email'])) 
	{
		$errors = array();
		$user=R::findOne('usersnew','email=?',array($data['email']));
		if($user)
		{
			if (password_verify($data['password'], $user->password))
			{
				$_SESSION['logged_user']=$user;
				header('Location: main.php');
			}
			else
			{
				$errors[]='Пароль не вірний';
			}
		}
		else
		{
			$errors[]='Не знайдено пошти';
		}

		if (!empty($errors)) 
		{
			 echo '<div style="color:red;">'.array_shift($errors).'</div><hr><hr>';

		}
		else 
		{
			
		}	
	}
?>
<form action="login.php" method="POST">
	<p>
		<a href="main.php">На головну</a>
		<p><strong>Електронна пошта, email</strong></p>
		<input type="email" name="email" value="<?php echo(@$data['email']);?>">
	</p>
	<p>
		<p><strong>Пароль</strong></p>
		<input type="password" name="password" value=0>
		<?//<input type="password" name="password" value="<?php echo(@$data['password']);?>
	</p>
	<p>
		<button type="submit" name="do_email">Увійти</button> 
	</p>
</form>