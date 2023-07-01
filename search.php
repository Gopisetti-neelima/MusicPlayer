<?php
	function getTable($res,$thead,$title,$id){
		$string = "<h3 class='row my-4 mx-2'>$title</h3>";
		$counter = 0;
		while($row = mysqli_fetch_assoc($res)){
			if($counter%4==0)
				$string .= "<div class='row my-4 mx-2'>";
			$albumId = $row['albumId'];
			$resId = $row[$id];
			$string .= "<div id='$resId' class='row songs mw-25 align-items-center bg-grey-light bg-grey-light mx-2'  style='height:80px;'>
			<img class='h-100 p-0' src='album/$albumId.jpg'>
			<h6 class='p-3 flex-grow-1'>$row[$thead]</h6>	  						
			<button class='a_play_button btn text-white'><h1><i class='fa fa-play-circle'></i></h1></button>
			</div>";
			if($counter%4==3)
				$string .= "</div>";
			$counter+=1;
		}
		if($counter%4!=0)
			$string .= "</div>";
		return $string;
	}

	function getTableArtist($res,$thead,$title,$id){
		$string = "<h3 class='row my-4 mx-2'>$title</h3>";
		$counter = 0;
		while($row = mysqli_fetch_assoc($res)){
			if($counter%4==0)
				$string .= "<div class='row my-4 mx-2'>";
			$resId = $row[$id];
			$string .= "<div id='$resId' class='row songs mw-25 align-items-center bg-grey-light bg-grey-light mx-2'  style='height:80px;'>
			<img class='h-100 p-0' src='$resId' onerror=\"this.src='img/default.jpg'\">
			<h6 class='p-3 flex-grow-1'>$row[$thead]</h6>	  						
			<button class='a_play_button btn text-white'><h1><i class='fa fa-play-circle'></i></h1></button>
			</div>";
			if($counter%4==3)
				$string .= "</div>";
			$counter+=1;
		}
		if($counter%4!=0)
			$string .= "</div>";
		return $string;
	}

	if(isset($_GET['key'])){
		$key = $_GET['key'];
		$con = mysqli_connect("localhost","root","","mymusic");
		$res = mysqli_query($con,"SELECT * FROM `artist` where `artistName` REGEXP '^$key';");
		if($res && mysqli_num_rows($res)){
			echo getTableArtist($res,"artistName","Artists","imgUrl");
		}

		$res = mysqli_query($con,"SELECT * FROM `song` where `songName` REGEXP '^$key';");
		if($res && mysqli_num_rows($res)){
			echo getTable($res,"songName","Songs","imgUrl");
		}

		$res = mysqli_query($con,"SELECT * FROM `album` where `albumName` REGEXP '^$key';");
		if($res && mysqli_num_rows($res)){
			echo getTable($res,"albumName","Albums","imgUrl");
		}
	}
?>