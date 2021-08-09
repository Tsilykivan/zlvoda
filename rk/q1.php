<?php
include '../rk/lib.php';
$maxdate=R::getRow('SELECT max(date1) as maxdate1  FROM conf  LIMIT 1');
$maxdate1=$maxdate['maxdate1'];
$date = DateTime::createFromFormat('Y-m-d', $maxdate1);

$lRobPeriodPok='На даний момент показники приймаються за <ins>'.strmis(intval($date->format('m'))).' місяць </ins>'.$date->format('Y'). ' року';
$lRobPeriodPokSmall=' '.strmis(intval($date->format('m'))).' місяць </ins>'.$date->format('Y'). ' року';
$maxdate2=$maxdate1;
?>