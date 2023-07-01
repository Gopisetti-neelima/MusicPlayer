<?php
	$artist_arr = array();
	$album_arr = array();
	$track_arr = array();

	for($n=1;$n<15;$n++){
		$fhand = fopen("json/st$n.json", 'r');
		$jdata = fread($fhand, filesize("json/st$n.json"));
		$jdata = str_replace("\r", "", $jdata);
		$json = json_decode($jdata);

		$json = $json->items;
		for($i=0;$i<count($json);$i++){
			$data = $json[$i]->track->album->artists;

			$album_name = $json[$i]->track->album->name;
			$album_id = $json[$i]->track->album->id;
			$x = array($album_name,$album_id);
			if(!in_array($x,$album_arr))
				array_push($album_arr,$x);

			$track_name = $json[$i]->track->name;
			$track_id = $json[$i]->track->id;
			$x = array($track_name,$track_id);
			if(!in_array($x,$track_arr))
				array_push($track_arr,$x);

			for($j=0;$j<count($data);$j++){
				$artist_name = $data[$j]->name;
				$artist_id = $data[$j]->id;	
				$x = array($artist_name,$artist_id);
				if(!in_array($x, $artist_arr)){
					array_push($artist_arr,$x);
				}
			}

			$data = $json[$i]->track->artists;
			for($j=0;$j<count($data);$j++){
				$artist_name = $data[$j]->name;
				$artist_id = $data[$j]->id;
				$x = array($artist_name,$artist_id);
				if(!in_array($x, $artist_arr)){
					array_push($artist_arr,$x);
				}
			}
		}
	}


	sort($album_arr);
	sort($artist_arr);
	sort($track_arr);

	$n = count($album_arr);
	$fhand = fopen("json/albums.json", 'w');
	fwrite($fhand, '{ "albums" : [ ');
	for($i=0;$i<$n-1;$i++){
		$album_arr[$i][0] =  str_replace("\"", "", $album_arr[$i][0]);
		fwrite($fhand, '{"name" : "'.$album_arr[$i][0].'" ,"id" : "'.$album_arr[$i][1].'"},');
	}
	fwrite($fhand, '{"name" : "'.$album_arr[$i][0].'" ,"id" : "'.$album_arr[$i][1].'"}]}');
	fclose($fhand);
	echo "<h1>$n Albums</h1>";
	
	$n = count($artist_arr);
	$fhand = fopen("json/artists.json", 'w');
	fwrite($fhand, '{ "artist" : [ ');
	for($i=0;$i<$n-1;$i++){
		fwrite($fhand, '{"name" : "'.$artist_arr[$i][0].'" ,"id" : "'.$artist_arr[$i][1].'"},');
	}
	fwrite($fhand, '{"name" : "'.$artist_arr[$i][0].'" ,"id" : "'.$artist_arr[$i][1].'"}]}');
	fclose($fhand);
	echo "<h1>$n Artists </h1>";

	$n = count($track_arr);
	$fhand = fopen("json/tracks.json", 'w');
	fwrite($fhand, '{ "tracks" : [ ');
	for($i=0;$i<$n-1;$i++){
		$track_arr[$i][0] =  str_replace("\"", "", $track_arr[$i][0]);
		fwrite($fhand, '{"name" : "'.$track_arr[$i][0].'" ,"id" : "'.$track_arr[$i][1].'"},');
	}
	fwrite($fhand, '{"name" : "'.$track_arr[$i][0].'" ,"id" : "'.$track_arr[$i][1].'"}]}');
	fclose($fhand);
	echo "<h1>$n tracks</h1>";
	
?>