<?php
function strmis($mis)	
{
	$month=array();
	$month[1]="Січень";
	$month[2]="Лютий";
	$month[3]="Березень";
	$month[4]="Квітень";
	$month[5]="Травень";
	$month[6]="Червень";
	$month[7]="Липень";
	$month[8]="Серпень";
	$month[9]="Вересень";
	$month[10]="Жовтень";
	$month[11]="Листопад";
	$month[12]="Грудень";


return $month[$mis] ;
}

?>