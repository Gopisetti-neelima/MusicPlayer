<?php
	function addAlbum($con,$albumName,$artistId){
		$id = random_int(1000000001,1100000000);
		$res = mysqli_query($con, "SELECT `albumId` from `album` where `albumId`='$id'");
		while($res && mysqli_num_rows($res)>0 && mysqli_fetch_assoc($res)['artistId']==$id){
			$id = random_int(1147483647, 2147483647);
			$res = mysqli_query($con, "SELECT `albumId` from `album` where `albumId`='$id'");
		}
		$res = mysqli_query($con,'INSERT INTO `album`(`albumId`, `albumName`, `albumYear`, `artistId`, `totalSongs`) VALUES ("'.$id.'","'.$albumName.'","2020","'.$artistId.'","0")');
		if($res)
			return $id;
		else{
			echo 'INSERT INTO `album`(`albumId`, `albumName`, `albumYear`, `artistId`, `totalSongs`) VALUES ("'.$id.'","'.$albumName.'","2020","'.$artistId.'","0")';
			return 0;
		}
	}

	function getArtistId($con,$key){
		$res = mysqli_query($con, 'SELECT `artistId` from `artist` where `artistName`="'.$key.'"');
		if($res && mysqli_num_rows($res)>0){
			$row = mysqli_fetch_assoc($res);
			return $row["artistId"];
		}
		else{
			echo 'SELECT `artistId` from `artist` where `artistName`="'.$key.'"';
			return 0;
		}
	}

	function getAlbumId($con,$key){
		$res = mysqli_query($con, 'SELECT `albumId` from `album` where `albumName`="'.$key.'"');
		if($res && mysqli_num_rows($res)>0){
			$row = mysqli_fetch_assoc($res);
			return $row["albumId"];
		}
		return 0;
	} 

	$con = mysqli_connect("localhost","root","","mymusic");
	$list = scandir('files/');
	for ($i=2; $i < count($list); $i++) { 
		$src = "files/".strval($i).".mp3";
		rename("files/".$list[$i], $src);
		$id = random_int(1100000001,1147483646);
		$res = mysqli_query($con, "SELECT `songId` from `song` where `songId`='$id'");
		while($res && mysqli_num_rows($res)>0 && mysqli_fetch_assoc($res)['artistId']==$id){
			$id = random_int(1147483647, 2147483647);
			$res = mysqli_query($con, "SELECT `songId` from `song` where `songId`='$id'");
		}

		$command = escapeshellcmd("python extract.py ".$src." songImg/".$id.".jpg");
		$res = shell_exec($command);
		if($res!=""){
			$json = json_decode($res);
			$songName = ucwords(trim($json->songName));
			$artistName = ucwords(trim($json->artistName));
			$albumName = ucwords(trim($json->albumName));
			$songTrackId = explode("/",trim($json->songTrackId))[0];

			$artistId = getArtistId($con,$artistName);
			$albumId = 	getAlbumId($con,$albumName);
			if($albumId==0){
				if($artistId!=0){
					$albumId = addAlbum($con,$albumName,$artistId);
					copy("songImg/".$id.".jpg","../album/".$albumId.".jpg");
				}
				else{
					echo "artist_not _found";
					copy($src,"noartist/".$list[$i]);
				}
			}
			unlink("songImg/".$id.".jpg");
			if($albumId!=0 && artistId!=0){
				$res = mysqli_query($con, 'INSERT INTO `song`(`songId`, `trackNo`, `songName`, `albumId`, `artistList`) VALUES ("'.$id.'","'.$songTrackId.'","'.$songName.'","'.$albumId.'","'.$artistId.'")');
				if($res){
					echo "<br>done:::".$songName;
					copy($src, "../musicFiles/".$id.".mp3");
				}
				else{
					echo "<br>error:::".'INSERT INTO `song`(`songId`, `trackNo`, `songName`, `albumId`, `artistList`) VALUES ("'.$id.'","'.$songTrackId.'","'.$songName.'","'.$albumId.'","'.$artistId.'")';
				}	
			}
		}
		else{
			echo "python error";
			copy($src,"noimg/".$list[$i]);
		}
		unlink($src);
		echo "<br>";
	}
?>