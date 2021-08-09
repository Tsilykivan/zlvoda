<?php
//phpinfo();
//r_dump($_FILES);
if ($_SERVER['REQUEST_METHOD']=='POST'){
	if($_FILES['sss']['error']==0)
	{
		$tmpName=$_FILES['sss']['tmp_name'];
		$Name=$_FILES['sss']['name'];
		if(move_uploaded_file($tmpName,  'in/'.$Name))
		{
			echo "Файл збережено";
		}
		else
		{
			echo "Файл не збережено".$_FILES['sss']['error'];
		}	

	}
	else
	{
		echo "Помилка завантаження ".$_FILES['sss']['error'];	
	}
}	
?>
<form method="POST" enctype="multipart/form-data">
	<input type="file" name="sss">
	<button>Upload</button>
</form>	