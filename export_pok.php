<?php
$db = dbase_open('stret.dbf', 0);
	if ($db) 
	{
	  $record_numbers = dbase_numrecords($db);
	  for ($i = 1; $i <= $record_numbers; $i++) 
	  {
	      $row = dbase_get_record_with_names($db, $i);
	      if ($row['ismember'] == 1) 
	      {
	          echo "Member #$i: " . trim($row['name']) . "\n";
	      }
	  }
	}
?>