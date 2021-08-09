<?php
	require "db.php";
?>
<?php

if(isset($_GET['act'])) 
{
	$act = $_GET['act'];
	$act = stripslashes($act);
	$act = htmlspecialchars($act);
	 
	$login = $_GET['login'];
	$login = stripslashes($login);
	$login = htmlspecialchars($login);

	
}

//var_dump($_SESSION['loginP']);

if (empty($login)) 
	{
	$vybor=$_SESSION['loginP'];
	}
	else
	{
	$vybor=$login;	
	}
if (empty($vybor)) {
		exit('криво $vybor');
	}	

$email=R::findOne( 'usersnew', 'email=?',array($vybor));
	if (empty($email)) 
	{
		exit('кривий майл логін');
	}
	else
	{
	$activation = md5($email->time_act);
	$data=$_POST;		
	}
	

//var_dump($_SESSION['loginP']);
if (isset($data['do_pass_new'])) 
{
	if (isset($data['do_pass_new'])) 
	{
		if (($data['password'])=='')
		{
			$errors[]='Введіть пароль';
		}

		if (($data['password_2'])!=$data['password'])
		{
			$errors[]='Паролі не співпадають';
		}
		if (!empty($errors)) {
			echo "string".array_shift($errors);
		}	
		
		if (empty($errors)) 
		{
			$email->activ=TRUE;
			$email->time_act=time();
			$email->password=password_hash($data['password'], PASSWORD_DEFAULT) ;
			R::store($email);
			echo "Ваш пароль <strong> ".$login."</strong> успішно змінено! <br><a href='index.php'>Головна сторінка</a>";
		}
		else
		{echo "Помилка".$errors;}
	}
}
if ($activation == $act) 
{	
	echo "Ваш пошта ".$login;
	$_SESSION['loginP']=$login;
	echo "
		<form action='pass_new.php' method='POST'>
			<p><strong>Ваш новий пароль </strong></p>
				<input type='password' name='password' value=''>
			</p>
			<p>
				<p><strong>Введіть ваш пароль ще раз</strong></p>
				<input type='password' name='password_2' value=''>
			</p>
			<p>
				<button type='submit' name='do_pass_new'>Змінити пароль</button> 
			</p>
		</form>
	";
}
else
{
	if (!isset($_SESSION['loginP'])) {
		echo "Помилка! Ваш код з пошти не вірний. Зверніться до адміністратора.
	<br><a href='index.php'>Головна сторінка</a>";
	}

}
?>


