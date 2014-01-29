<?php

$time = $_GET['time'];
$current_time = floatval($time);
$xml=simplexml_load_file("AudioTimes.xml");

$timeNarrative =0;


  

	
		if($current_time <= floatval($xml->Narrative[$timeNarrative]->End))
		{
			echo $xml->Narrative[$timeNarrative]->Image;
		}
		else
		{
			while($current_time > floatval($xml->Narrative[$timeNarrative]->End)){
			$timeNarrative +=1;
			
		}
		echo $xml->Narrative[$timeNarrative]->Image;
	}


	
?>


