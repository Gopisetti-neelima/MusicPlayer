<?php 
	if(isset($_GET['file'])){
		$file = $_GET["file"];
		$fhand = fopen('json/'.$file.'.json', 'r');
		$string = fread($fhand, filesize('json/'.$file.'.json'));
		echo $string;
		fclose($fhand);
	}
?>