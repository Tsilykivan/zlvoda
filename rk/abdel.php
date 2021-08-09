<?php
ini_set('display_errors',1);
if (isset($_POST['nom_ab_del'])) {
	$datan=$_POST['nom_ab_del'];
}

	require "../rk/db.php";
	if (isset($_POST['do_ad_del'])) {
		if (isset($_POST['nom_ab_del'])) {
			echo "Абонент №".$_POST['nom_ab_del'];
			$nom_abs=R::find('userab','nom_ab=?',array($_POST['nom_ab_del']));
			if (isset($nom_abs)) {
				foreach ($nom_abs as $nom_ab){
					echo "<br>Є такий запис ..".$nom_ab;
					echo "
			<form action='../rk/abdel.php' method='POST'>
			 <a href='../rk/admin.php'>Повернутись<br></a>
			
			<p><strong>Ви впевнені у відкріпленні абонента? 1-так 0 - ні</strong></p>
			<input type='hidden' name='nom_ab_del' value=$datan> 
			<input type='number' name='nyesnozmm' value='0'><br>
			<button type='submit' name='do_zmm_ab'>почати відкріплення</button>
			</form>
			";		
				}

			if ($nom_abs==false)	 {
					echo('<br>немає абонента для стирання ');
				}				
			}			
		}
	}
	if (isset($_POST['do_zmm_ab'])) {
		$sss=R::exec('delete  from userab where nom_ab=?',array($_POST['nom_ab_del']));
			
			if (isset($sss)) {
				echo "<br>Ви успішно стерли приэднану ел. пошту ".$_POST['nom_ab_del']."  ".$sss;
			}	
	}
	echo "<form action='abdel.php' method='POST'>
		<p> Вкажіть особовий рахунок для вилучення ел. пошти</p>
		<input type='text' name='nom_ab_del' > 
		<button type='submit' name='do_ad_del'>Стираємо</button>				   
		</form>";
?>