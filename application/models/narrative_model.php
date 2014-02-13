<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Narrative_Model extends CI_Model
{
	private $table = 'narratives';
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Retrieve a narrative data structure by ID, or FALSE upon failure.
	 */
	public function get($narrative_id)
	{
		$query = $this->db->get_where($this->table, array('narrative_id' => $narrative_id));
		$narrative = $query->row_array();
		return $narrative;
	}

	/**
	 * Retrieve a narrative data structure by ID, or FALSE upon failure.
	 */
	public function get_all($sortby = 'id', $position = NULL)
	{
		// Get the sort column
		$sort_cols = array(
			'id' => 'narrative_id',
			'age' => 'created',
			'agrees' => 'agrees',
			'disagrees' => 'disagrees',
		);
		if (!isset($sort_cols[$sortby]))
		{
			// TODO: Error handling
			return array();
		}
		$sort_col = $sort_cols[$sortby];


		$query = $this->db->from($this->table);
		if ($position)
		{
			$this->db->where('position', $position);
		}
		$query = $this->db
			->order_by($sort_col, 'desc')
			->get();
		$narratives = $query->result_array();
		return $narratives;
	}

	//following is for xml parsing
	public function get_XML_narrative_name($xml_r)
	{
		return $xml_r->narrativeName;
	}

	public function get_XML_narrative_language($xml_r)
	{
		$sql_entry = "";
		if ($xml_r->language == "English")
		{
			$sql_entry = "EN";
		}
		else
		{
			$sql_entry = "FR";
		}
		return $sql_entry;
	}

	public function get_XML_narrative_submitDate($xml_r)
	{
		return $xml_r->submitDate;
	}

	public function get_XML_narrative_submitTime($xml_r)
	{
		return $xml_r->time;
	}

	//determine if the file is an audio file
	public function is_audio($file_ext)
	{
		switch ($file_ext)
		{
			case "mp3":
			case "wav":
			case "mp4":
			case "m4a":
			case "aac":
			case "avi":
			case "3gp":
			case "ogg":
			case "mp2":
			case "ac3":
				return true;
			default:
				return false;
		}
	}

	//determine if the file is an image file
	public function is_image($file_ext)
	{
		switch ($file_ext)
		{
			case "jpg":
			case "jpeg":
			case "gif":
			case "bmp":
			case "png":
			case "tif":
				return true;
			default:
				return false;
		}
	}
	
	function process_image($original_image, $original_image_name, $original_image_extension, $directory, $image_destroy) {
		$image_container = null;
		//getimagesize will determine the type of image 
		$image_size = getimagesize($directory . '/' . $original_image);
		switch($image_size[2])
		{
			case 0: //Case 0 = gif type
				$image_container =  imagecreatefromgif($directory . '/' . $original_image);
				break;
			case 2: //Case 2 = jpg/jpeg type
				$image_container = imagecreatefromjpeg($directory . '/' . $original_image) or die("Cannot format");
				//echo "goes here <br><br>";
				break;
			case 3: //Case 3 = png type
				$image_container = imagecreatefrompng($directory . '/' . $original_image) or die("Cannot format");
				break;
			default:
				return false;
				break;
		}
		
		//deletes original image
		if($image_destroy)
		{
			unlink($directory . '/' . $original_image);
		}
		
		imagejpeg($image_container, $directory . '/' . $original_image_name . ".jpg", 100);
		imagedestroy($image_container);
		
		return true;
	}


	public function process_narrative($narrative_path, $id = null)
	{
		// Get the absolute path
		$dir = realpath(FCPATH . $narrative_path);

		//Variables we need to concatenate the audio file,
		//determine the type of narrative to upload, and create an XML file
		$startTimes = 0.0000;
		$endTimes = 0.000;
		$image_count = 0;
		$audio_image = "";
		$image_format = "jpg";
		$unique_id = "";
		$narrative_language = "";
		$narrative_submit_date = "";
		$narrative_submit_time = "";
		$found_first_image = false;
		
		$xml = new DOMDocument();
		$xml->formatOutput = true;
		$root = $xml->createElement("data");
		$xml->appendChild($root);

		//check the directory
		if (!is_dir($dir))
		{
			$data['error'] = 1;
			$data['error_message'] = 'Processing failed. Please attempt the upload again.';
			return $data;
		}

		//Scan the folder to determine the amount of pictures in a narrative
		$xmlExistence = FALSE; //Used for handling folder uploaded with no XML file
		$isBatchUpload = FALSE; //Used to handle batch uploading
		$file_scan = scandir($dir);
		foreach ($file_scan as $filecheck)
		{
			$file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);
			
			//Handling of batch upload, ignoring directories '.' and '..'
			if ($file_extension == '' && $filecheck != '.' && $filecheck != '..')
			{
				$isBatchUpload = TRUE;
				$newPath = $narrative_path.'/'.$filecheck;
				$data = $this->process_narrative($newPath);
				if ($data['error'] === 1)
				{
					$data['error_message'] = 'Processing failed, one of the narrative folders uploaded does not contain an XML file. Please attempt the upload again.';
					return $data;
				}
			}
			if($this->is_image($file_extension))
			{
				$fname = pathinfo($filecheck, PATHINFO_FILENAME);
				//False variable in process_image controls whether we delete the original image or not (false = do not delete image)
				$this->process_image($filecheck, $fname, $file_extension, $dir, false);
				$image_count++;
				
				//Once the first image in the folder is found, stop changing audio_image
				if($image_count == 1)
				{
					$found_first_image = true;
				}
				
				//First image in the folder is found, set audio_image
				if(!$found_first_image)
				{
					$audio_image = $dir . "/" . $filecheck;
				}
			}
			if ($file_extension == "xml")
			{
				$xmlExistence = TRUE;
				//read uploaded xml here and hash unique id
				$xml_reader = simplexml_load_file($dir . "/" . $filecheck);
				$narrative_name = $this->get_XML_narrative_name($xml_reader); //check if integer, check if RIGHT integer
				$narrative_language = $this->get_XML_narrative_language($xml_reader); //check if right language (string format)
				$narrative_submit_date = $this->get_XML_narrative_submitDate($xml_reader); //check if it is a date, check that it is in right format
				$narrative_submit_time = $this->get_XML_narrative_submitTime($xml_reader); //check that time format is correct
				str_replace("-", ":", $narrative_submit_time);
			}
		}
		//Handling error when folder does not contain XML file
		if ($xmlExistence == FALSE && $isBatchUpload == FALSE)
		{
			$data['error'] = 1;
			$data['error_message'] = 'Processing failed, the narrative folder uploaded does not contain an XML file. Please attempt the upload again.';
			return $data;
		}
		//Interrupting further action if batch upload
		if ($isBatchUpload)
		{
			$data['error'] = 0;
			return $data;
		}

		 //This is the txt file that will combine all the txt files with ffmpeg
		$file_concat = fopen($dir . "/audio_container.txt", "w+");

		if (is_dir($dir))
		{
			foreach (scandir($dir) as $file)
			{
				//get the file name plus it's extension
				$file_name = pathinfo($file, PATHINFO_FILENAME);

				//Check if the file is an mp3
				//if (pathinfo($file, PATHINFO_EXTENSION) == "mp3")
				if ($this->is_audio(pathinfo($file, PATHINFO_EXTENSION)))
				{
					//simple verification to see if the file is readable
					if (is_readable($dir . "/" .$file))
					{
						//Get the name of the audio file to combine

						if (PHP_OS == 'WINNT')
						{
							$command = realpath("../storage/ffmpeg.exe"). " -i ". $dir . '\\' .$file . " -n -f mp3 -ab 128k " . $dir . '\\' .$file_name . ".mp3 2>&1";
						}
						else
						{
							$command = realpath("../storage/ffmpeg"). " -i ". $dir . '/' .$file . " -n -f mp3 -ab 128k " . $dir . '/' .$file_name . ".mp3 2>&1";
						}
						$temp = shell_exec($command);

			//write the file name to audio_container.txt
						$file_input = "file " . "'" . $dir . "/" .$file_name .".mp3'\r\n";
						fwrite($file_concat, $file_input);

						preg_match("/Duration: (.*?),/", $temp, $matches);

						$raw_duration = $matches[1];

						//raw_duration is in 00:00:00.00 format. This converts it to seconds.
						$ar = array_reverse(explode(":", $raw_duration));
						$duration = floatval($ar[0]);
						if (!empty($ar[1]))
						{
							$duration += intval($ar[1]) * 60;
						}
						if (!empty($ar[2]))
						{
							$duration += intval($ar[2]) * 60 * 60;
						}

						if (file_exists($dir . "/" . $file_name . "." . $image_format))
						{
							$audio_image = $file_name . "." . $image_format;
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
						$imageNarrative = $xml->createTextNode($audio_image);
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
				}
			}
		}

		//change path of xml
		$xmlpath = $dir . "/AudioTimes.xml";
		$xml->save($xmlpath) or die("Error");
		fclose($file_concat);
		if (PHP_OS == 'WINNT')
		{
			$command_concatenation = realpath("../storage/ffmpeg.exe")." -f concat -i " . $dir . "\audio_container.txt -c copy " . $dir . "\combined.mp3 2>&1";
		}
		else
		{
			$command_concatenation = realpath("../storage/ffmpeg")." -f concat -i " . $dir . "/audio_container.txt -c copy " . $dir . "/combined.mp3 2>&1";
		}
		$temp2 = shell_exec($command_concatenation);
		//die("returned: " . $temp2 . "</br>");

		if ($id == null)
		{
			$database_data = array(
				'created' => $narrative_submit_date . " " . $narrative_submit_time,
				'audio_length' => $endTimes,
				'uploaded_by' => 1, // TODO hardcoded
				'language' => $narrative_language, // TODO hardcoded DONE 02/06/2014
				'views' => 0,
				'agrees' => 0,
				'disagrees' => 0,
				'shares' => 0,
				'flags' => 0
			);

			$id = $this->narrative_model->insert($database_data);
		}

		//creating the directory on the server
		$new_dir = "./uploads/" . $id;
		if (!is_dir($new_dir))
		{
			rename($dir, $new_dir);
		}
		$data['error'] = 0;
		return $data;
	}

	/**
	 * Inserts a narrative structure into the database.
	 */
	public function insert($narrative)
	{
		// TODO does this actuallreturn anything
		$this->db->insert($this->table, $narrative);
		return $this->db->insert_id();
	}

	/**
	 * Deletes an narrative based on the conditions passed.
	 *
	 * Example:
	 *   $this->narrative_model->delete(array('narrative_id' => $narrative->id));
	 */
	public function delete($conditions)
	{
		$this->db->delete($this->table, $conditions);
	}

	/**
	*	publishing the narrative
	*/
	public function publish($narrative)
	{
	}

	/**
	*	unpublishing the narrative
	*/
	public function unpublish($narrative)
	{
	}
}
