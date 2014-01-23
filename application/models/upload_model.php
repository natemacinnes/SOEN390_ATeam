<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class upload_model extends CI_Model {
  public function __construct() {
    parent::__construct();
    //$this->load->database();
  }

  /**
   * Retrieve a narrative object by ID, or FALSE upon failure.
   */
  public function submit() {
    if ($_FILES["file"]["error"] > 0)
    {
        echo "Error: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        echo "Type: " . $_FILES["file"]["type"] . "<br>";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "Stored in: " . $_FILES["file"]["tmp_name"] . "<br>";
       if(file_exists("/var/www/upload/" . $_FILES["file"]["name"]))
        {
			echo $_FILES["file"]["name"] . " already exists. ";
		}
		else
		{
			if(move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/upload/" . $_FILES["file"]["name"]))
			{
				echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
				//$output = shell_exec("unzip /var/www/upload/" . $_FILES["file"]["name"] . " -d /var/www/upload");
				//echo $output;
				$zip = new ZipArchive;
				if($zip->open("/var/www/upload/" . $_FILES["file"]["name"]) === TRUE)
				{
					$zip->extractTo("/var/www/upload");
					$zip->close();
					echo "<br>" . "complete";
					$name = substr($_FILES["file"]["name"], 0, -4);
					echo "<br>" . $name;
					$xml = simplexml_load_file("/var/www/upload/" . $name . "/" . $name . ".xml");
					echo $xml->submitDate;
				}
				else
				{
					echo "failure";
				}
			}
			else
			{
				echo "Can't understand why";
			}
		}
	}
  }
}
?>
