<?php
	if(isset($_GET['key'])){
		$key = $_GET['key'];
		$con = mysqli_connect("localhost","root","","mymusic");	
		$res = mysqli_query($con,"SELECT * FROM `song` where `songId` REGEXP '^$key';");
		if($res && mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$songId = $row['songId'];
			$songName = $row['songName'];
			$albumId = $row['albumId'];
			$res = mysqli_query($con,"SELECT * FROM `album` where `albumId` REGEXP '^$albumId';");
			if($res && mysqli_num_rows($res)){
				$row = mysqli_fetch_assoc($res);
				$albumName = $row['albumName'];
				echo '[{
					"songId":"'.$songId.'","songName":"'.$songName.'",
					"albumName":"'.$albumName.'",
					"albumId":"'.$albumId.'"
				}]';
			}
		}
	}
?>