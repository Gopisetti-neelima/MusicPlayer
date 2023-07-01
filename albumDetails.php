<?php
	if(isset($_GET['key'])){
		$key = $_GET['key'];
		$con = mysqli_connect("localhost","root","","mymusic");	
		$res = mysqli_query($con,"SELECT * FROM `album_list` where `album_id` REGEXP '^$key';");
		$list = array();
		if($res && mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$albumId = $row['album_id'];
			$albumName = $row['album_name'];
			$res = mysqli_query($con,"SELECT * FROM `songs_list` where `song_album` REGEXP '^$albumId';");
			if($res && mysqli_num_rows($res)){
				while($row = mysqli_fetch_assoc($res))
				{
					$songId = $row['song_id'];
					$songName = $row['song_name'];
					array_push($list, "{
						'songId':'$songId',
						'songName':'$songName',
						'albumName':'$albumName',
						'albumId':'$albumId'
					}");
				}
				echo "[".join(",",$list)."];";
			}
		}
	}
?>