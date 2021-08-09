<?php
	require "db.php";
?>
<?php

	if(isset($_GET['act'])) {
		$act = $_GET['act'];
		$act = stripslashes($act);
		$act = htmlspecialchars($act);
		 
		$login = $_GET['login'];
		$login = stripslashes($login);
		$login = htmlspecialchars($login);
		//echo "string11111".$act.'<br>';
	}
	else
	{
	exit("Ви зайшли на сторінку без коду підтверждення!");
	}
 
$log=$_SESSION['logged_user'];
$email=R::findOne( 'usersnew', 'email=?',array($login) );
$activation = md5($email->id);

if ($activation == $act) {//сравниваем полученный из url и сгенерированный код
	$email->activ=TRUE;
	$email->time_act=time();
	R::store($email);
	echo "Ваш кабінет <strong>".$login."</strong> успішно активований! <br><a href='index.php'>Головна сторінка</a>";
}
else {
	echo "Помилка! Ваш акаунт не активовано. Зверніться до адміністратора.<br><a href='index.php'>Головна сторінка</a>";
}

?>
