<?php
	if(isset($_POST['submit']))
	{
		$songName = $_POST["name"];
		$albumId = $_POST["album"];
		$artistId = $_POST["artist"]; 
		echo $songName."<br>".$albumId."<br>".$artistId."<br>";
		
	}
?>