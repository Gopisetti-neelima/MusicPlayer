<?php 
	function getFileName($str){
		$s = explode(".", $str);
		return ucwords($s[0]);
	}
	$con = mysqli_connect("localhost","root","","mymusic");
	$myfiles = scandir('new_artist/');
	for ($i=2; $i < count($myfiles) ; $i++) { 
		$src = $myfiles[$i];
		$artistName = getFileName($src);
		$id = random_int(1147483647, 2147483647);
		$res = mysqli_query($con, "SELECT `artistId` from `artist` where `artistId`='$id'");
		while($res && mysqli_num_rows($res)>0 && mysqli_fetch_assoc($res)['artistId']==$id){
			$id = random_int(1147483647, 2147483647);
			$res = mysqli_query($con, "SELECT `artistId` from `artist` where `artistId`='$id'");
		}
		mysqli_query($con,"INSERT INTO `artist`(`artistId`, `artistName`, `joinYear`) VALUES ('$id','$artistName','2020')");
		echo "INSERT INTO `artist`(`artistId`, `artistName`, `joinYear`) VALUES ('$id','$artistName','2020')";
		copy('new_artist/'.$src,'../artist/'.$id.'.jpg');
		unlink('new_artist/'.$src);
	}
?>