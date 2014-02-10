<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class editing_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/**
	* Gathering all tracks, images, and info on a narrative
	*/
	public function gatherInfo($id)
	{
		$query = $this->db->query('SELECT * FROM narratives WHERE narrative_id=\''.$id.'\';');
		foreach($query->result() as $row)
		{
			//Getting info on the narrative
			$data['narrative_id'] = $row->narrative_id;
			$data['created'] = $row->created;
			$data['uploaded'] = $row->uploaded;
			$admin = $this->db->query('SELECT * FROM admins WHERE admin_id="'.$row->uploaded_by.'";');
			foreach ($admin->result() as $item)
			{
				$data['uploaded_by'] = $item->login;
			}
			$data['language'] = $row->language;
			$data['views'] = $row->views;
			$data['agrees'] = $row->agrees;
			$data['disagrees'] = $row->disagrees;
			$data['shares'] = $row->shares;
			$data['flags'] = $row->flags;
			
			//Getting the path and the number of tracks 
			$xml_reader = simplexml_load_file("./uploads/".$row->narrative_id."/AudioTimes.xml");
			$trackCtr = 0;
			$picCtr = 0;
			$lastPic = '';
			foreach($xml_reader->Narrative as $narrative)
			{
				//Getting track
				$trackCtr++;
				$data['trackName'][$trackCtr] = (string) $narrative->Mp3Name;
				$data['trackPath'][$trackCtr] = (string) ("/uploads/".$row->narrative_id."/".$narrative->Mp3Name);
				
				//Getting picture
				if(strcmp($lastPic, $narrative->Image))
				{
					$picCtr++;
					$lastPic = $narrative->Image;
					$data['picName'][$picCtr] = (string) $narrative->Image;
					$data['picPath'][$picCtr] = (string) ("/uploads/".$row->narrative_id."/".$narrative->Image);
				}
			}
			$data['trackCtr'] = $trackCtr;
			$data['picCtr'] = $picCtr;
			
			return $data;
		}
	}
	
	/**
	*	Deleting the tracks that are meant to be deleted
	*/
	public function deleteTracks($trackName, $trackPath, $newDir, $tracksToDelete = null)
	{
		$j = 0;
		for($i = 1; $i <= count($trackName); $i++)
		{
			if($tracksToDelete != null && $j < count($tracksToDelete) && $trackName[$i] == $tracksToDelete[$j])
			{
				unlink('.'.$trackPath[$i]);
				$j++;
			}
			else
			{
				rename('.'.$trackPath[$i], $newDir.$trackName[$i]);
			}
		}
	}
	
	/**
	*	Deleting the pics that are meant to be deleted
	*/
	public function deletePics($picName, $picPath, $newDir, $picsToDelete = null)
	{
		$j = 0;
		for($i = 1; $i <= count($picName); $i++)
		{
			if($picsToDelete != null && $j < count($picsToDelete) && $picName[$i] == $picsToDelete[$j])
			{
				unlink('.'.$picPath[$i]);
				$j++;
			}
			else
			{
				rename('.'.$picPath[$i], $newDir.$picName[$i]);
			}
		}
	}
	
	/**
	*	Moving XML file to new folder
	*/
	public function moveXML($id, $newDir)
	{
		$baseDir = './uploads/'.$id.'/';
		$file_scan = scandir($baseDir);
		foreach($file_scan as $filecheck)
		{
			$file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);
			//Finding the XML file to be moved
			if($file_extension == "xml" && $filecheck != 'AudioTimes.xml')
			{
				rename($baseDir.$filecheck, $newDir.$filecheck);
			}
		}
	}
	
	/**
	*	Creating new folder in tmp directory to hold the edited narrative and moving edited narrative to it
	*/
	public function moveDir($baseDir, $id)
	{
		$folder_name = time();
		$tmpPath = './uploads/tmp/'.$folder_name.'/'.$id.'/';
		echo $folder_name.'</br>';
		if(!is_dir($tmpPath))
		{
			rename($baseDir, $tmpPath);
		}
		return $tmpPath;
	}
	
	/**
	*	Deleting old folder
	*/
	public function deleteDir($path)
	{
		$file_scan = scandir($path);
		foreach($file_scan as $filecheck)
		{
			if($filecheck != '.' && $filecheck != '..')
				unlink($path.$filecheck);
		}
		rmdir($path);
	}
}
?>
