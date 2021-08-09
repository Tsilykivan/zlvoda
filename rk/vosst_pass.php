<?php
	//ini_set('display_errors',1);
	require "../rk/db.php";
	$data=$_POST;
	if (isset($data['do_rec_email'])=='true')	{
		$errors=array();
		$email=R::findOne('usersnew','email=?',array($data['email']));
		if ($email) {
			//var_dump($email->id);
			//echo "string".$email->time_act;
			$activation = md5($email->time_act);
			$headers = "From: admin@zlvoda.com.ua\nContent-type:text/html;charset=utf-8\r\n";
			 $subject = "Зміна паролю! кабінет МКП Золочівводоканал ";
			 $subject = "=?utf-8?b?".base64_encode($subject)."?=";
			 $message = "Вітаємо !<br><br>Ви реєструвались в кабінеті МКП 'Золочівводоканал<br>Ваша пошта: ".$data['email']."<br>
			 Щоб змінити Ваш пороль, перейдіть за посиланням:<br> 
			 <a href='https://zlvoda.com.ua/rk/pass_new.php?login=".$data['email']."&act=".$activation."'>Перейти</a>
			 <br><br>З повагою <br>Адміністрація сайту https://zlvoda.com.ua";
			 $res=mail($data['email'], $subject, $message, $headers);
			// var_dump($res);
			 if ($res==true) {
			 	echo "Лист з інструкціями по зміні пароля відіслано на електронну адресу ".$data['email']."  <br> " ;
			 }else
			 {
			 	echo "Помилка відправки на пошту ".$data['email']."  <br> " ;
			 }
			 
			//echo "".$message;
		}
		else
		{	
		echo "Не зареєстрована електронна пошта ".$data['email'];
		}	
	}	
	

?>
<?php

?>
<?php
if (!isset($res)) 
//if (!$res) 
{ 
	echo
	"
	<a href='../rk/main.php'>На головну</a>
	<p>
	<a  > Відновлення через електронну пошту (email)</a>
	<form action='../rk/vosst_pass.php' method='POST'>
		<input type='text' name='email'>
		<button type='submit' name='do_rec_email'>Відправити листа ?</button>  
	</form>
	</p>

	";
}
?>
