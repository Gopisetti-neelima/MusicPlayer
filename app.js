//GENERAL FUNCTIONS

function shuffle(array) {
  var currentIndex = array.length, temporaryValue, randomIndex;
  while (0 !== currentIndex) {
		randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex = currentIndex - 1;
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }
  return array;
}

function toTime (sec_num) {
  var sec_num = parseInt(sec_num, 10); // don't forget the second param
  var hours   = Math.floor(sec_num / 3600);
  var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
  var seconds = sec_num - (hours * 3600) - (minutes * 60);

  // if (hours   < 10) {hours   = "0"+hours;}
  if (minutes < 10) {minutes = "0"+minutes;}
  if (seconds < 10) {seconds = "0"+seconds;}
  return minutes + ':' + seconds;
}

function update_music_attributes(){
	document.getElementById("song_thumbnail").src = "album/"+current_list[current_position]["albumId"]+".jpg";
	document.getElementById("player").src = "musicFiles/"+current_list[current_position]["songId"]+".mp3";
	document.getElementById("song_name").innerHTML = current_list[current_position]["songName"];
	document.getElementById("song_album").innerHTML = current_list[current_position]["albumName"];
	myAudio = document.getElementById("player");
	duration =  myAudio.duration;
	document.getElementById("total-duration").innerHTML = duration;
}

function refresh_player(){
	if(document.getElementById("play_pause").className=="fa fa-pause")
		status=1;
	else
		status=0;
	document.getElementById("player").pause();
	update_music_attributes();
	if(status==1)
		document.getElementById("player").play();
	else
		document.getElementById("player").pause();
}

function playSong(songId){
	alert('songDetails.php?key='+songId);
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    	alert(xhttp.responseText);
      songInfo = JSON.parse(xhttp.responseText);
      myAudio.pause();
      console.log(songInfo);
      alert(songInfo);
			list_of_songs = songInfo;
			current_position = 0;
			refresh_player();
    }
	};
	xhttp.open("GET", 'songDetails.php?key='+songId, true);
	xhttp.send();
}

function playAlbum(albumId){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      albumInfo = JSON.parse(xhttp.responseText);
      myAudio.pause();
      console.log(albumInfo);
      alert(albumInfo);
			list_of_songs = albumInfo;
			current_position = 0;
			refresh_player();
    }
	};
	xhttp.open("GET", 'albumDetails.php?key='+albumId, true);
	xhttp.send();
}

function load_page(page){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
       // Typical action to be performed when the document is ready:
       document.getElementById("target_view").innerHTML = xhttp.responseText;
    }
	};
	xhttp.open("GET", page+'.php', true);
	xhttp.send();
}

function wish_user(username){
	console.log(name);
	d = new Date();
  	h = d.getHours();
  	h = parseInt(h);
  	wish = "";
  	if(h<12)
  		wish = "Good Morning";
  	else if(h>11 && h<=16)
  		wish = "Good Afternoon";
  	else if(h>16 && h<23)
  		wish = "Good Evening";
  	document.getElementById("wish-card").innerHTML = wish + " " + username;
}

function trim(string){
	if(string.length>10)
		return string.substring(0,10)+"...";
	return string;
}

function recentPlayed(data){
	recentAlbum = data["albumName"];
	recentAlbumId = data["albumId"];
	recentSong = data["songName"];
	recentSongId = data["songId"];
	console.log("rp invoked");
	document.getElementById("recentAlbumPlay").setAttribute('onclick',"playAlbum('"+recentAlbumId+"')");
	document.getElementById("recentAlbumImage").src = "album/"+recentAlbumId+".jpg";
	document.getElementById("recentAlbumName").innerHTML = trim(recentAlbum);
	document.getElementById("recentSongPlay").setAttribute('onclick',"playSong('"+recentSongId+"')");
	document.getElementById("recentSongImage").src = "album/"+recentAlbumId+".jpg";
	document.getElementById("recentSongName").innerHTML = trim(recentSong);
}
			
function userData(data){
	username = data["userName"];
	wish_user(username);
	rp = data["recentlyPlayed"];
	recentPlayed(rp[0]);
	console.log("userdata retrived");
	list_of_songs = rp;
	current_list = rp;
	string = "";
	for(i=0;i<6;i++){
		albumName = rp[i]["albumName"];
		albumId = rp[i]["albumId"];
		songId = rp[i]["songId"];
		songName = rp[i]["songName"];
		string += "<div id='"+songId+"' class='songs flex-fill bg-grey-light p-2 mr-4' hover='add_play(this.id)'><div class='col-sm-12 p-2'><img class='col-sm-12 p-0' src='album/"+albumId+".jpg'><button class='btn play_button text-light'><h1><i class='fa fa-play-circle'></i></h1></button></div><div class='p-2'><h5>"+songName+"</h5><p>"+albumName+"</p></div></div>";
	}
	document.getElementById("rpTarget").innerHTML = string;
	update_music_attributes();
}

function getUserData(){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
       // Typical action to be performed when the document is ready:
      userData(JSON.parse(xhttp.responseText));
    }
	};
	xhttp.open("GET", "users/1.json", true);
	xhttp.send();
}

//Elements 

listButton = document.getElementById("list_view");
shuffleButton = document.getElementById("shuffle");
previewButton = document.getElementById("previous");
playPauseButton = document.getElementById("play_pause");
nextButton = document.getElementById("next");
repeatButton = document.getElementById("repeat");
volumeButton = document.getElementById("volume");
volumeBar = document.getElementById("volume_range");
myAudio = document.getElementById("player");
seekBar = document.getElementById("progress-amount");
currentTimeDiv = document.getElementById("current-time");
totalDurationDiv = document.getElementById("total-duration");
searchBar = document.getElementById("searchBar");
homeView = document.getElementById("home_view");
targetView = document.getElementById("target_view");

//EVENT LISTNERS
listButton.addEventListener('click',function(){
	if(homeView.style.display == "none"){
		homeView.style.display = "inline-block";
		targetView.innerHTML = "";
	}
	else{
		result = "<div class='table-responsive col-sm-12 p-4'><table class='table table-borderless text-light'>";
		result += "<tr class='row'><th class='col-sm-1'>#</th><th class='col-sm-4'>TITLE</th><th class='col-sm-3'>ARTISTS</th><th class='col-sm-3'>ALBUM</th><th class='col-sm-1'><i class='fa fa-heart-o'></i></th></tr>";
		for (i = 0; i < current_list.length; i++) {
			albumName = current_list[i]["albumName"];
			albumId = current_list[i]["albumId"];
			songId = current_list[i]["songId"];
			songName = current_list[i]["songName"];
			result += "<tr class='row'><td class='col-sm-1'>"+(i+1).toString()+"</td><td class='col-sm-4'>"+songName+"</td><td class='col-sm-3'>"+"Artists"+"</td><td class='col-sm-3'>"+albumName+"</td>";
			result +="<td class='col-sm-1'><i class='fa fa-heart'></i></td></tr>";
		}
		result+="</table></div>";
		homeView.style.display = "none";
		targetView.innerHTML = result;
	}
});

shuffleButton.addEventListener('click', function() { 
	ob=shuffleButton;
	if(ob.style.color == "blue"){
		ob.style.color = "white";
		current_list = shuffle(list_of_songs);
	}
	else{
		ob.style.color = "blue";
		current_list = list_of_songs;
	}
});

previewButton.addEventListener('click', function() {
	if(current_position-1<0){
		current_position = current_list.length-1;
	}
	else{
		current_position = current_position - 1;
	}
	refresh_player();
});

playPauseButton.addEventListener('click', function() {
	if(playPauseButton.className == "fa fa-play"){
		myAudio.play();
		playPauseButton.className = "fa fa-pause";
	}
	else{
		myAudio.pause();
		playPauseButton.className = "fa fa-play";
	}
});

nextButton.addEventListener('click', function() {
	if(current_position+1 >= current_list.length){
		current_position = 0;
	}
	else{
		current_position = current_position + 1;
	}
	refresh_player();
});

repeatButton.addEventListener('click', function() {
	if(repeatButton.style.color == "blue"){
		repeatButton.style.color = "white";
	}
	else{
		repeatButton.style.color = "blue";
	}
});

volumeButton.addEventListener('click', function() {
	if(volumeButton.className == "fa fa-volume-off"){
		if(previous_volume == 0){
			myAudio.volume = 0.1;
			current_volume = 0.1;
			volumeBar.value = 0.1;
			volumeButton.className = "fa fa-volume-down";
		}
		else {
			myAudio.volume = previous_volume;
			current_volume = previous_volume;
			volumeBar.value = previous_volume;
			if(previous_volume >= 0.5)
				volumeButton.className = "fa fa-volume-up";
			else
				volumeButton.className = "fa fa-volume-down";
		}
	}
	else{
		previous_volume = myAudio.volume;
		myAudio.volume = 0;
		current_volume = 0;
		volumeBar.value = 0;
		volumeButton.className = "fa fa-volume-off";
	}
});

volumeBar.addEventListener('change', function(){
	current_volume = volumeBar.value;
	myAudio.volume = current_volume;
	if(current_volume == 0)
		volumeButton.className = "fa fa-volume-off";
	else if(current_volume >= 0.5)
		volumeButton.className = "fa fa-volume-up";
	else
		volumeButton.className = "fa fa-volume-down";
});

myAudio.addEventListener('timeupdate', function() {
  var duration =  myAudio.duration;
  if (duration > 0) {
    seekBar.value = ((myAudio.currentTime / duration)*100);
    currentTimeDiv.innerHTML = toTime(myAudio.currentTime);
  }
});

seekBar.addEventListener('input', function(){
	percent = seekBar.value;
	var duration =  myAudio.duration;
	percent = parseInt(percent);
	myAudio.pause();
	val = (percent / 100)*duration;
	myAudio.currentTime = val;
	if(playPauseButton.className=="fa fa-pause")
		status=1;
	else
		status=0;
	if(status==1)
		myAudio.play();
	else
		myAudio.pause();
});

myAudio.addEventListener("ended",function(){
	current_position +=1;
	refresh_player();
});

myAudio.addEventListener("loadedmetadata", function() {
	totalDurationDiv.innerHTML = toTime(myAudio.duration);
});

searchBar.addEventListener("click",()=>{
	homeView.style.display = "none";
});

searchBar.addEventListener("input",()=>{
	key = searchBar.value;
	if(key!=""){
		var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("target_view").innerHTML = xhttp.responseText;
    }
	};
	xhttp.open("GET", 'search.php?key='+key, true);
	xhttp.send();
	}
});

//END OF EVENT LISTENERS

//INITIALIZATION TASKS

getUserData();
list_of_songs = [];
current_list = [];
current_position = 0;
current_volume = 0.1;
previous_volume = 0;
previous_view = "home_view";
current_list = list_of_songs;