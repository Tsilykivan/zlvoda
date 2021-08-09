<?php
	require "libs/rb.php";
	R::setup( 'mysql:host=localhost;dbname=pipurhvd_wtr',
	'pipurhvd_admin', 'Host2821518674' ); //for both mysql or mariaDB
	if ( !R::testConnection() )
				{
				        //exit ('Немає зєдннання з базою даних11');
				}
	session_start();
?>
