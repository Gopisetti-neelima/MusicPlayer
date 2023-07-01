<?php
	$con = mysqli_connect("localhost","root","","mymusic");	
	$res = mysqli_query($con,"SELECT * FROM `album` ");
	$list = array();
	if($res && mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res))
		{
			$albumId = $row['albumId'];
			$albumName = $row['albumName'];
			array_push($list, '{
				"albumName":"'.$albumName.'",
				"albumId":"'.$albumId.'"
			}');
		}
		echo "[".join(",",$list)."]";
	}
	else
		echo "[]";
?>