<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class narrative_model extends CI_Model {
  private $table = 'narratives';
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  /**
   * Retrieve a narrative data structure by ID, or FALSE upon failure.
   */
  public function get($narrative_id) {
    $query = $this->db->get_where($this->table, array('narrative_id' => $narrative_id));
    $narrative = $query->row_array();
    return $narrative;
  }

  /**
   * Retrieve a narrative data structure by ID, or FALSE upon failure.
   */
  public function get_all($sortby = 'id') {
    // Get the sort column
    $sort_cols = array(
      'id' => 'narrative_id',
      'age' => 'created',
      'agrees' => 'agrees',
      'disagrees' => 'disagrees',
    );
    if (!isset($sort_cols[$sortby])) {
      // TODO: Error handling
      return array();
    }
    $sort_col = $sort_cols[$sortby];


    $query = $this->db->from($this->table)
      ->order_by($sort_col, 'desc')
      ->get();
    $narratives = $query->result_array();
    return $narratives;
  }
  
  public function process_narrative($narrative_path)
  {
	//This is the path to the directory which contains all the audio and image files
	$dir = $narrative_path;
	
	//This is the txt file that will combine all the txt files with ffmpeg
	$file_concat = fopen("audio_container.txt", "w+");
	
	//Variables we need to concatenate the audio file, 
	//determine the type of narrative to upload, and create an XML file
	$startTimes = 0.0000; 
	$endTimes = 0.000;
	$image_count = 0;
	$audio_jpg = "";
	
	$xml = new DOMDocument();
	$xml->formatOutput = true;
	$root = $xml->createElement("data");
	$xml->appendChild($root);
	

	//check the directory
	if (is_dir($dir)) 
	{
		//Scan the folder to determine the amount of pictures in a narrative
		$file_scan = scandir($dir);
		foreach($file_scan as $filecheck)
		{
			$file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);
			if($file_extension == "jpg")
			{
				$image_count++;
				$audio_jpg = $dir . "/" . $filecheck;
			}
		}
		
    if ($dh = opendir($dir)) 
	{
        while (($file = readdir($dh)) !== false) {
			//get the file name plus it's extension
			$file_name = pathinfo($file, PATHINFO_FILENAME);
			//$audio_jpg = $dir . "/generic.jpg";
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
					
					if(file_exists($dir . "/" . $file_name . ".jpg"))
					{
						$audio_jpg = $dir . "/" . $file_name . ".jpg";
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

  /**
   * Inserts a narrative structure into the database.
   */
  public function insert($narrative) {
    $this->db->insert($this->table, $narrative);
  }

  /**
   * Deletes an narrative based on the conditions passed.
   *
   * Example:
   *   $this->narrative_model->delete(array('narrative_id' => $narrative->id));
   */
  public function delete($conditions) {
    $this->db->delete($this->table, $conditions);
  }
}
