<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Narrative_Model extends CI_Model
{
	private $table = 'narratives';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('narrative_model_helper');
	}

	/**
	 * Retrieve a narrative data structure by ID, or FALSE upon failure.
	 * @ingroup G-0014
	 */
	public function get($narrative_id)
	{
		$query = $this->db->get_where($this->table, array('narrative_id' => $narrative_id));
		$narrative = $query->row_array();
		return $narrative;
	}

	/**
	 * Retrieve a narrative data structure by ID, or FALSE upon failure.
	 * TODO: this could use some refactoring for better parameter order
	 * @ingroup G-0008
	 * @ingroup G-0015
	 */
	public function get_all($sort_by = 'id', $position = NULL, $sort_order = 'asc', $offset = 0, $limit = NULL)
	{
		// Get the sort column - this may seem redundant, but it is to prevent users
    // from sorting on authorized columns via URL manipulation.
		$sort_cols = array(
			'id' => 'narrative_id',
			'length' => 'audio_length',
			'language' => 'language',
			'age' => 'created',
			'uploaded' => 'uploaded',
			'flags' => 'flags',
			'status' => 'status',
			'agrees' => 'agrees',
			'disagrees' => 'disagrees',
			'position' => 'position'
		);
		if (!isset($sort_cols[$sort_by]))
		{
			// TODO: Error handling
			return array();
		}
		$sort_col = $sort_cols[$sort_by];


		$query = $this->db->from($this->table);
		if (!is_null($position))
		{
			$this->db->where('position', $position);
		}
		if ($limit)
		{
			$query->limit($limit, $offset);
		}
		$query = $this->db
			->order_by($sort_col, $sort_order)
			->get();
		$narratives = $query->result_array();
		return $narratives;
	}

	/**
	 * Returns the number of records.
	 * @ingroup G-0008
	 */
	public function get_total_count()
	{
		$query = $this->db->query('SELECT count(*) as count FROM ' . $this->table);
		$row = $query->row_array();
    return $row['count'];
	}

	/**
	 * XML parsing helper to retrieve narrative name given a SimpleXML object
	 * @ingroup G-0002
	 */
	public function get_XML_narrative_name($xml_r)
	{
		return $xml_r->narrativeName;
	}

	/**
	 * XML parsing helper to retrieve narrative language given a SimpleXML object
	 * @ingroup G-0002
	 */
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

	/**
	 * XML parsing helper to retrieve narrative submission date given a SimpleXML
	 * object
	 * @ingroup G-0002
	 */
	public function get_XML_narrative_submitDate($xml_r)
	{
		return $xml_r->submitDate;
	}

	/**
	 * XML parsing helper to retrieve submission time given a SimpleXML object
	 * @ingroup G-0002
	 */
	public function get_XML_narrative_submitTime($xml_r)
	{
		return $xml_r->time;
	}

	/**
	 * Determines if a filename has an audio type extension
	 * TODO: This is a candidate for extraction into a helper
	 * @ingroup G-0001
	 */
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

	/**
	 * Determines if a filename has an image type extension
	 * TODO: This is a candidate for extraction into a helper
	 * @ingroup G-0001
	 */
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

	/**
	 * Processes an image to JPG format.
	 * @ingroup G-0001
	 */
	function process_image($original_image, $original_image_name, $original_image_extension, $directory, $image_destroy)
	{
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
		if ($image_destroy)
		{
			unlink($directory . '/' . $original_image);
		}

		imagejpeg($image_container, $directory . '/' . $original_image_name . ".jpg", 100);
		imagedestroy($image_container);

		return true;
	}

	/**
	 * Processes a narrative in order to standardize its associated tracks
	 * (audio & images).
	 * @ingroup G-0001
	 * @ingroup G-0002
	 */
	public function process_narrative($narrative_path, $id = null)
	{
		// Get the absolute path
		$dir = realpath($narrative_path);

		//Variables we need to concatenate the audio file,
		//determine the type of narrative to upload, and create an XML file
		$startTimes = 0.0000;
		$endTimes = 0.000;
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
		$lastImage = -1;
		$file_scan = scandir($dir);
		foreach ($file_scan as $filecheck)
		{
			//Handling upload of preciously downloaded narrative
			if ($filecheck == 'audio_container.txt') {
				unlink($narrative_path . '/audio_container.txt');
			}
			else if ($filecheck == 'AudioTimes.xml') {
				unlink($narrative_path . '/AudioTimes.xml');
			}
			else if ($filecheck == 'combined.mp3') {
				unlink($narrative_path . '/combined.mp3');
			}
			else
			{
				$file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);

				//Handling of batch upload, ignoring directories '.' and '..'
				if ($file_extension == '' && $filecheck != '.' && $filecheck != '..' && $filecheck != 'deleted')
				{
					$isBatchUpload = TRUE;
					$newPath = $narrative_path.'/'.$filecheck;
					$data = $this->process_narrative($newPath);
					/*
					if ($data['error'] === 1)
					{
						$data['error_message'] = 'Processing failed, one of the narrative folders uploaded does not contain an XML file. Please attempt the upload again.';
						return $data;
					}
					*/
				}
				if ($this->is_image($file_extension))
				{
					$fname = pathinfo($filecheck, PATHINFO_FILENAME);
					//False variable in process_image controls whether we delete the original image or not (false = do not delete image)
					$this->process_image($filecheck, $fname, $file_extension, $dir, false);

					//New method, to be approved by TL
					$images[$fname] = $fname . '.' . $image_format;
					if ($fname > $lastImage) {
						$lastImage = $fname;
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
				//get the file name
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
							$file_input = "file " . "'" . $dir . "\\" .$file_name .".mp3'\r\n";
						}
						else
						{
							$command = realpath("../storage/ffmpeg"). " -i ". $dir . '/' .$file . " -n -f mp3 -ab 128k " . $dir . '/' .$file_name . ".mp3 2>&1";
							$file_input = "file " . "'" . $dir . "//" .$file_name .".mp3'\n";
						}
						$temp = shell_exec($command);

						//write the file name to audio_container.txt
						fwrite($file_concat, $file_input);
						preg_match("/Duration: (.*?),/", $temp, $matches);
						$raw_duration = $matches[1];

						//raw_duration is in 00:00:00.00 format. This converts it to seconds.
						$ar = array_reverse(explode(":", $raw_duration));
						$duration = floatval($ar[0]);
						if (!empty($ar[1]))
						{
							// Minutes
							$duration += intval($ar[1]) * 60;
						}
						if (!empty($ar[2]))
						{
							// Hours
							$duration += intval($ar[2]) * 60 * 60;
						}

						if (count($images) == 1) {
							$audio_image = $images[$lastImage];
						}
						else if (isset($images[$file_name])) {
							$audio_image = $images[$file_name];
						}
						else
						{
							for ($i = $file_name; $i <= $lastImage; $i++)
							{
								if (isset($images[$i]))
								{
									$audio_image = $images[$i];
									break;
								}
							}
						}

						//Get the time that the narrative end in the concatenated narrative
						$endTimes = $endTimes + floatval($duration);

						//REFACTORED: xml creation now in the narrative_model_helper
						$root->appendChild(create_narrative_xml($xml,$file, $startTimes, $duration, $endTimes, $audio_image));
						$startTimes = $startTimes + floatval($duration) ;  //get the starting time of the narrative in the concatenated narrative
					}
				}
			}
		}

		//change path of xml
		$xmlpath = $dir . "/AudioTimes.xml";
		$xml->save($xmlpath) or die("Error");

		// Pathing to ffmpeg is different depending on OS type
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

		//Only create new database row if it's a new narrative being uploaded, else update audio length
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
		else
		{
			$this->db->query('UPDATE narratives SET audio_length=? WHERE narrative_id=?;', array($endTimes, $id));
		}

		//creating the directory on the server
		$new_dir = $this->config->item('site_data_dir') . '/' . $id;
		if (!is_dir($new_dir))
		{
			rename($dir, $new_dir);
		}
		$data['error'] = 0;
		return $data;
	}

	/**
	 * Inserts a narrative structure into the database.
	 * @ingroup G-0001
	 */
	public function insert($narrative)
	{
		$this->db->insert($this->table, $narrative);
		return $this->db->insert_id();
	}

	/**
	 * Updates a narrative based on the passed narrative array.
	 * @ingroup G-0003
	 */
	public function update($narrative)
	{
		// Don't attempt to set the ID in the update string.
		$narrative_id = $narrative['narrative_id'];
		unset($narrative['narrative_id']);

		$this->db->where('narrative_id', $narrative_id);
		$this->db->update($this->table, $narrative);
		$this->set_modified_date($narrative_id);
	}

	/**
	 * Sets the modified date to the current timestamp.
	 */
	private function set_modified_date($narrative_id)
	{
		$this->db->query('UPDATE narratives SET modified=CURRENT_TIMESTAMP WHERE narrative_id=?;', array($narrative_id));
	}

	/**
	 * Deletes an narrative based on the conditions passed.
	 *
	 * Example:
	 *   $this->narrative_model->delete(array('narrative_id' => $narrative['narrative_id']));
	 * @ingroup G-0004
	 */
	public function delete($conditions)
	{
		$this->db->delete($this->table, $conditions);
	}

	/**
	 * Publishing the narrative
	 * @ingroup G-0004
	 */
	public function publish($id)
	{
		$this->db->query('UPDATE narratives SET status=1 WHERE narrative_id=?;', array($id));
		$this->set_modified_date($id);
	}

	/**
	 * Unpublishing the narrative
	 * @ingroup G-0004
	 */
	public function unpublish($id)
	{
		$this->db->query('UPDATE narratives SET status=0 WHERE narrative_id=?;', array($id));
		$this->set_modified_date($id);
	}

	/**
	 * Increment views of a narrative
	 * @ingroup G-0017
	 */
	public function increment_views($narrative_id)
	{
		$this->db->where('narrative_id', $narrative_id);
		$this->db->set('views', 'views+1', FALSE);
		$this->db->update('narratives');
	}

	/**
	 * Increment agree counter of a narrative
	 * @ingroup G-0017
	 */
	public function toggle_agrees($narrative_id, $operator)
	{
		$this->db->where('narrative_id', $narrative_id);
		$this->db->set('agrees', 'agrees' . $operator . '1', FALSE);
		$this->db->update('narratives');
	}

	/**
	 * Increment disagree counter of a narrative
	 * @ingroup G-0017
	 */
	public function toggle_disagrees($narrative_id, $operator)
	{
		$this->db->where('narrative_id', $narrative_id);
		$this->db->set('disagrees', 'disagrees' . $operator . '1', FALSE);
		$this->db->update('narratives');
	}

	/**
	 * Increment agree and decrement disagrees
	 * @ingroup G-0017
	 */
	public function increment_agree_decrement_disagree($narrative_id)
	{
		$this->db->where('narrative_id', $narrative_id);
		$this->db->set('agrees', 'agrees+1', FALSE);
		$this->db->set('disagrees', 'disagrees-1', FALSE);
		$this->db->update('narratives');
	}

	/**
	 * Increment disagree and decrement agrees counts
	 * @ingroup G-0017
	 */
	public function increment_disagree_decrement_agree($narrative_id)
	{
		$this->db->where('narrative_id', $narrative_id);
		$this->db->set('agrees', 'agrees-1', FALSE);
		$this->db->set('disagrees', 'disagrees+1', FALSE);
		$this->db->update('narratives');
	}

	/**
	 * Set narrative position as For, Neutral, or Against
	 * @see application/config/constants.php
	 * @ingroup G-0004
	 */
	public function set_position($narrative_id, $position)
	{
		$this->db->query("UPDATE narratives SET position=? WHERE narrative_id=?;", array($position, $narrative_id));
	}

	/**
	 * Increment narrative flag count
	 * @ingroup G-0004
	 */
	function flag($narrative_id)
	{
		$this->db->where('narrative_id', $narrative_id);
    $this->db->set('flags', 'flags+1', FALSE);
    $this->db->update('narratives');
	}

	/**
	 * Dismisses all flags set on the narrative
	 * @ingroup G-0003
	 */
	function dismiss_flags($narrative_id)
	{
		$this->db->where('narrative_id', $narrative_id);
    $this->db->set('flags', 0, FALSE);
    $this->db->update('narratives');
	}
}
