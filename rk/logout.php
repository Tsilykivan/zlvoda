<?php
	require "../rk/db.php";
	unset($_SESSION['logged_user']);
	header('Location: main.php');
?>