<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>HTML5 MediaElement</title>	
	
	<script src="SOEN390_ATeam/assets/js/jquery.min.js"></script>	
	<script src="SOEN390_ATeam/assets/mediaelement/mediaelement-and-player.min.js"></script>
	<script src="testforfiles.js"></script>
	<link rel="stylesheet" href="SOEN390_ATeam/assets/mediaelement/mediaelementplayer.min.css" />
</head>
<body>

<h1>MediaElementPlayer.js</h1>

<p>Audio player</p>
<?php 
	//This is the path to the directory we want to search
	$dir = "1";
	//This is the txt file that will combine all the txt files with ffmpeg
	$file_concat = fopen("audio_container.txt", "w+");
	
	$xml = new DOMDocument();
	$xml->formatOutput = true;
	$root = $xml->createElement("data");
	$xml->appendChild($root);
	
	
	$startTimes = 0.0000; 
	$endTimes = 0.000;
	//check the directory
	if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
			//get the file name plus it's extension
			$file_name = pathinfo($file, PATHINFO_FILENAME);
			$audio_jpg = $dir . "/generic.jpg";
           // echo "filename: " . $file_name . pathinfo($file, PATHINFO_EXTENSION) . "</br>";
			
			//Check if the file is an mp3
			if(pathinfo($file, PATHINFO_EXTENSION) == "mp3")
			{
				//simple verification to see if the file is readable
				if(is_readable($dir . "/" .$file))
				{
					//Get the name of the audio file to combine
					$file_input = "file " . "'" . $dir . "/" .$file ."'\r\n";
					fwrite($file_concat, $file_input);
					$command = "C:/wamp/bin/ffmpeg-20140123-git-e6d1c66-win64-static/bin/ffmpeg.exe -i C:/wamp/www/". $dir . '/' .$file . " 2>&1";
					$temp = shell_exec($command);
					
					preg_match("/Duration: (.*?), start:/", $temp, $matches);

					$raw_duration = $matches[1];

					//raw_duration is in 00:00:00.00 format. This converts it to seconds.
					$ar = array_reverse(explode(":", $raw_duration));
					$duration = floatval($ar[0]);
					if (!empty($ar[1])) {
						$duration += intval($ar[1]) * 60;
						}
					if (!empty($ar[2])) {
						$duration += intval($ar[2]) * 60 * 60;
						}
					//echo "duration: " . $duration . "</br>";
					
					if(file_exists($dir . "/" . $file_name . ".jpg"))
					{
						$audio_jpg = $dir . "/" . $file_name . ".jpg";
					//	echo $audio_jpg;
					}
					//Get the time that the narrative end in the concatenated narrative
					$endTimes = $endTimes + floatval($duration);
					
					//Add narrative node to XML file
					

					
					$name  = $xml->createElement("Mp3Name");
					$mp3Name = $xml->createTextNode($file);
					$name->appendChild($mp3Name);

					$start   = $xml->createElement("Start");
					$startTime = $xml->createTextNode($startTimes);
					$start->appendChild($startTime);

					$length   = $xml->createElement("Duration");
					$lengthTime = $xml->createTextNode($duration);
					$length->appendChild($lengthTime);

					$end   = $xml->createElement("End");
					$endTime = $xml->createTextNode($endTimes);
					$end->appendChild($endTime);

					$image  = $xml->createElement("Image");
					$imageNarrative = $xml->createTextNode($audio_jpg);
					$image->appendChild($imageNarrative);

					$narrative = $xml->createElement("Narrative");
					$narrative->appendChild($name);
					$narrative->appendChild($start);
					$narrative->appendChild($end);
					$narrative->appendChild($length);
					$narrative->appendChild($image);

					$root->appendChild($narrative);

					//echo "<xmp>". $xml->saveXML() ."</xmp>";

					//end of xml stuff
					$startTimes = $startTimes + floatval($duration) ;  //get the starting time of the narrative in the concatenated narrative
				}
				else{}
					
				
			}
        }
        closedir($dh);
    }
	$xml->save("AudioTimes.xml") or die("Error");
	fclose($file_concat);
	$command_concatenation = "C:/wamp/bin/ffmpeg-20140123-git-e6d1c66-win64-static/bin/ffmpeg.exe -f concat -i C:/wamp/www/audio_container.txt -c copy narrative1.mp3 2>&1";
	$temp2 = shell_exec($command_concatenation);
	//echo "returned: " . $temp2 . "</br>";
}
/*	
//echo "<p> Hey </p>" 
$xml=simplexml_load_file("AudioTimes.xml");
//echo $xml->getName() . "<br>";



//following with get the starting times from the xml
$p_cnt = count($xml->Narrative); 
for($i = 0; $i < $p_cnt; $i++) { 
  //$param = $xml->param[$i]; 
  echo floatval($xml->Narrative[$i]->End); 
  echo "<br>";
  }
  
  //following will get the images associated with each image, also if it has no image it will make it generic.jpg
  //$p_cnt = count($xml->Narrative); 
for($i = 0; $i < $p_cnt; $i++) { 

  if ($xml->Narrative[$i]->Image == "None")
  {
	  echo "Generic.jpg";
  }else {
  echo $xml->Narrative[$i]->Image; }
  echo "<br>";
  }
  //echo "1/" . $xml->Narrative[0]->Image;
  */
  
?>
<h2>MP3</h2>
<?php
if(file_exists('narrative1.mp3'))
{
echo "<img src='' id='audioImage' alt='audio_image' height='400' width='400'>
	  <audio id='narrative_audio' src='narrative1.mp3' type='audio/mp3' controls='controls'>		
	  </audio></br><span id='current-time'></span>";
}
else
{
echo "<img src='' id='audioImage' alt='audio_image' height='42' width='42'>
	  <audio id='narrative_audio' src='1/2.mp3' type='audio/mp3' controls='controls'>		
	  </audio></br><span id='current-time'></span>";
}









?>
<script>
$('audio,video').mediaelementplayer({
 // the order of controls you want on the control bar (and other plugins below)
   features: ['playpause','current','progress','duration','tracks','volume'],
// show framecount in timecode (##:00:00:00)
   showTimecodeFrameCount: true
   });
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

