<?php
	$con = mysqli_connect("localhost","root","","mymusic");	
	$res = mysqli_query($con,"SELECT * FROM `artist` ");
	$list = array();
	if($res && mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res))
		{
			$artistId = $row['artistId'];
			$artistName = $row['artistName'];
			array_push($list, '{
				"artistName":"'.$artistName.'",
				"artistId":"'.$artistId.'"
			}');
		}
		echo "[".join(",",$list)."]";
	}
	else
		echo "[]";
?>