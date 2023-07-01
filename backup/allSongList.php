<?php
	$con = mysqli_connect("localhost","root","","mymusic");	
	$res = mysqli_query($con,"SELECT * FROM `song` ");
	$list = array();
	if($res && mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res))
		{
			$songId = $row['songId'];
			$songName = $row['songName'];
			array_push($list, "{
				'songName':'$songName',
				'songId':'$songId'
			}");
		}
		echo "[".join(",",$list)."];";
	}
	else
		echo "[]";
?>