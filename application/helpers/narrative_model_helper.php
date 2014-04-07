<?php

/**
 * Re-creates the narrative XML metadata file.
 * @ingroup G-0002
 */
function create_narrative_xml($xml, $file, $startTimes, $duration, $endTimes, $audio_image)
{
  $name = $xml->createElement("Mp3Name");
  $mp3Name = $xml->createTextNode($file);
  $name->appendChild($mp3Name);

  $start = $xml->createElement("Start");
  $startTime = $xml->createTextNode($startTimes);
  $start->appendChild($startTime);

  $length = $xml->createElement("Duration");
  $lengthTime = $xml->createTextNode($duration);
  $length->appendChild($lengthTime);

  $end = $xml->createElement("End");
  $endTime = $xml->createTextNode($endTimes);
  $end->appendChild($endTime);

  $image = $xml->createElement("Image");
  $imageNarrative = $xml->createTextNode($audio_image);
  $image->appendChild($imageNarrative);

  $narrative = $xml->createElement("Narrative");
  $narrative->appendChild($name);
  $narrative->appendChild($start);
  $narrative->appendChild($end);
  $narrative->appendChild($length);
  $narrative->appendChild($image);

  return $narrative;
}
