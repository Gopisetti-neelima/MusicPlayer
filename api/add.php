<?php

	function findArtist($name){
		$con = mysqli_connect("localhost","root","","mymusic") or die("Could'nt connect to database");
		$res = mysqli_query($con, "SELECT `artistId` from `artist` where `artistName`='$name' LIMIT 1");
		if($res && mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			return $row['artistId'];
		}
		return 0;
	}

	function getArtistIdList($artistNames){
		$id_list = array();
		for($i=0;$i<count($artistNames);$i++){
			push_array($id_list, findArtist($artistNames[$i]));
		}
		$id_list = join(',',$id_list);
		return $id_list;
	}

	if(isset($_GET["type"])){
		$con = mysqli_connect("localhost","root","","mymusic") or die("Could'nt connect to database");
		if($_GET["type"] == "track"){
			$name = $_GET["name"];
			$aname = $_GET["aname"];
			$artistNames = explode(",",$_GET["artistNames"]);
			$tcno = $_GET["tcno"];

			$id_list = getArtistIdList($artistNames);

			$id = random_int(1100000001,1147483646);
			$res = mysqli_query($con, "SELECT `songId` from `song` where `songId`='$id'");
			while($res && mysqli_num_rows($res)>0 && mysqli_fetch_assoc($res)['artistId']==$id){
				$id = random_int(1100000001,1147483646);
				$res = mysqli_query($con, "SELECT `songId` from `song` where `songId`='$id'");
			}

			$query = "INSERT INTO `song`(`songId`, `trackNo`, `songName`, `albumId`, `artistList`) VALUES ('$id','$tcno','name','aid','id_list')";
			mysqli_query($con,$query);
		}
		else if($_GET["type"] == "album"){
			$name = $_GET["name"];
			$year = $_GET["year"];
			$artistNames = explode(",",$_GET["artistNames"]);
			$n = $_GET["nSongs"];
			$popularity = $_GET["pop"];
			$genre = $_GET["genre"];
			$imgUrl = $_GET["imgUrl"];

			$id = random_int(1000000001,1100000000);
			$res = mysqli_query($con, "SELECT `albumId` from `album` where `albumId`='$id'");
			while($res && mysqli_num_rows($res)>0 && mysqli_fetch_assoc($res)['artistId']==$id){
				$id = random_int(1000000001,1100000000);
				$res = mysqli_query($con, "SELECT `albumId` from `album` where `albumId`='$id'");
			}

			$query = "INSERT INTO `INSERT INTO `album`(`albumId`, `albumName`, `albumYear`, `artistNames`, `totalSongs`, `popularity`, `genre`, `imgUrl`) VALUES ('$id','$name','$year','$artistNames','$n','$popularity','$genre','$imgUrl')";
			mysqli_query($con,$query);
		}
		else if($_GET["type"] == "artist"){
			$name = $_GET["name"];
			$popularity = $_GET["pop"];
			$genre = $_GET["genre"];
			$imgUrl = $_GET["imgUrl"];

			if(findArtist($name)==0){
				$id = random_int(1147483647, 2147483647);
				$res = mysqli_query($con, "SELECT `artistId` from `artist` where `artistId`='$id'");
				while($res && mysqli_num_rows($res)>0 && mysqli_fetch_assoc($res)['artistId']==$id){
					$id = random_int(1147483647, 2147483647);
					$res = mysqli_query($con, "SELECT `artistId` from `artist` where `artistId`='$id'");
				}
				$query = "INSERT INTO `artist`(`artistId`,`artistName`,`popularity`,`genre`,`imgUrl`) VALUES ('$id',\"$name\",'$popularity','$genre','$imgUrl')";
				$res = mysqli_query($con,$query);
				if($res)
					echo "Success";
				else
					echo "Failure";
			}
			else
				echo "Already exists";

			
		}
	}
	else{
		echo "invalid request";
	}
?>