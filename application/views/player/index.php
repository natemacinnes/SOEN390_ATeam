<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Media Player</title>	
	
	<script src="SOEN390_ATeam/assets/js/jquery.min.js"></script>	
	<script src="SOEN390_ATeam/assets/mediaelement/mediaelement-and-player.min.js"></script>
	<link rel="stylesheet" href="SOEN390_ATeam/assets/mediaelement/mediaelementplayer.min.css" />
</head>
<body>
<h1>Media Player</h1>
<p>You selected narrative #<?php echo $narrative_id; ?>.</p>

<?php
//Need to add path to narrative here and in the source of the video
if(file_exists('narrative1.mp3'))
{
echo "<img src='' id='audioImage' alt='audio_image' height='400' width='400'>
	  <audio id='narrative_audio' src='narrative1.mp3' type='audio/mp3' controls='controls'>		
	  </audio></br><span id='current-time'></span>";
}
else
{
echo "Video does not exist";
}


?>
<script>
$('audio,video').mediaelementplayer({
 // the order of controls you want on the control bar (and other plugins below)
   features: ['playpause','current','progress','duration','tracks','volume'],
// show framecount in timecode (##:00:00:00)
   showTimecodeFrameCount: true
   });
   
   //AJAX function that changes the picture according to the time of the
   //the audio.
	myaudio=document.getElementById("narrative_audio");
	myaudio.addEventListener("timeupdate", function(e)
	{
		//document.getElementById('current-time').innerHTML = myaudio.currentTime;       
		var xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("audioImage").src = xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","getAudioImage.php?time=" + myaudio.currentTime,true);
		xmlhttp.send();
	}, false);

</script>
</body>
</html>
