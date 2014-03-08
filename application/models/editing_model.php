<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Editing_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Gathering all tracks, images, and info on a narrative
	*/
	public function gatherInfo($id)
	{
		$query = $this->db->query('SELECT * FROM narratives WHERE narrative_id=\''.$id.'\';');
		foreach ($query->result() as $row)
		{
			//Getting info on the narrative
			$data['narrative_id'] = $row->narrative_id;
			$data['created'] = $row->created;
			$data['uploaded'] = $row->uploaded;
			$data['length'] = $row->audio_length;
			$admin = $this->db->query('SELECT * FROM admins WHERE admin_id="'.$row->uploaded_by.'";');
			foreach ($admin->result() as $item)
			{
				$data['uploaded_by'] = $item->login;
			}
			$data['modified'] = $row->modified;
			$data['language'] = $row->language;
			$data['views'] = $row->views;
			$data['agrees'] = $row->agrees;
			$data['disagrees'] = $row->disagrees;
			$data['shares'] = $row->shares;
			$data['flags'] = $row->flags;
			$data['status'] = $row->status;

			//Getting the path and the number of tracks
			$xml_reader = simplexml_load_file($this->config->item('site_data_dir') . '/' . $row->narrative_id . "/AudioTimes.xml");
			$trackCtr = 0;
			$picCtr = 0;
			$lastPic = '';
			foreach ($xml_reader->Narrative as $narrative)
			{
				//Getting track
				$trackCtr++;
				$data['trackName'][$trackCtr] = (string) $narrative->Mp3Name;
				$data['trackPath'][$trackCtr] = (string) ($this->config->item('site_data_dir') . '/' . $row->narrative_id . '/' . $narrative->Mp3Name);

				//Getting picture
				if (strcmp($lastPic, $narrative->Image))
				{
					$picCtr++;
					$lastPic = $narrative->Image;
					$data['picName'][$picCtr] = (string) $narrative->Image;
					$data['picPath'][$picCtr] = ($this->config->item('site_data_dir') . '/' . $row->narrative_id . '/' . $narrative->Image);
				}
			}
			$data['trackCtr'] = $trackCtr;
			$data['picCtr'] = $picCtr;

			return $data;
		}
	}
	
	/**
	*	Gathering deleted files
	*/
	public function gatherDeleted($path)
	{
		//Finding audio and images and storing their name and paths
		$audioCtr = 0;
		$imageCtr = 0;
		$file_scan = scandir($path);
		foreach ($file_scan as $filecheck)
		{
			$file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);
			
			if($filecheck != '.' && $filecheck != '..')
			{
				if($file_extension == 'mp3')
				{
					$audioCtr++;
					$data['deletedAudio'][$audioCtr] = $filecheck;
					$data['deletedAudioPath'][$audioCtr] = $path.$filecheck;
				}
				else
				{
					$imageCtr++;
					$data['deletedImage'][$imageCtr] = $filecheck;
					$data['deletedImagePath'][$imageCtr] = $path.$filecheck;
				}
			}
		}
		$data['audioCtr'] = $audioCtr;
		$data['imageCtr'] = $imageCtr;
		return $data;
	}

	/**
	*	Archiving the tracks that are meant to be archived
	*/
	public function deleteTracks($trackName, $trackPath, $newDir, $tracksToDelete = null)
	{
		$tracksLeft = count($trackName);
		$j = 0;
		for ($i = 1; $i <= count($trackName); $i++)
		{
			if ($tracksToDelete != null && $j < count($tracksToDelete) && $trackName[$i] == $tracksToDelete[$j])
			{
				$tracksLeft--;
				rename($trackPath[$i], $newDir.'deleted/'.$trackName[$i]);
				$j++;
			}
			else
			{
				rename($trackPath[$i], $newDir.$trackName[$i]);
			}
		}
		return $tracksLeft;
	}
	
	/**
	*	Restoring the tracks that are meant to be restored
	*/
	public function restoreTracks($trackName, $trackPath, $newDir, $tracksToRestore = null)
	{
		$j = 0;
		for ($i = 1; $i <= count($trackName); $i++)
		{
			if ($tracksToRestore != null && $j < count($tracksToRestore) && $trackName[$i] == $tracksToRestore[$j])
			{
				rename($trackPath[$i], $newDir.$trackName[$i]);
				$j++;
			}
			else
			{
				rename($trackPath[$i], $newDir.'deleted/'.$trackName[$i]);
			}
		}
	}

	/**
	*	Archiving the pics that are meant to be archived
	*/
	public function deletePics($picName, $picPath, $newDir, $picsToDelete = null)
	{
		$j = 0;
		for ($i = 1; $i <= count($picName); $i++)
		{
			if ($picsToDelete != null && $j < count($picsToDelete) && $picName[$i] == $picsToDelete[$j])
			{
				rename($picPath[$i], $newDir.'deleted/'.$picName[$i]);
				$j++;
			}
			else
			{
				rename($picPath[$i], $newDir.$picName[$i]);
			}
		}
	}
	
	/**
	*	Restoring the pics that are meant to be restored
	*/
	public function restorePics($picName, $picPath, $newDir, $picsToRestore = null)
	{
		$j = 0;
		for ($i = 1; $i <= count($picName); $i++)
		{
			if ($picsToRestore != null && $j < count($picsToRestore) && $picName[$i] == $picsToRestore[$j])
			{
				rename($picPath[$i], $newDir.$picName[$i]);
				$j++;
			}
			else
			{
				rename($picPath[$i], $newDir.'deleted/'.$picName[$i]);
			}
		}
	}

	/**
	*	Moving XML file to new folder
	*/
	public function moveXML($id, $newDir)
	{
		$baseDir = $this->config->item('site_data_dir') . '/' . $id . '/';
		$file_scan = scandir($baseDir);
		foreach ($file_scan as $filecheck)
		{
			$file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);
			//Finding the XML file to be moved
			if ($file_extension == "xml" && $filecheck != 'AudioTimes.xml')
			{
				rename($baseDir.$filecheck, $newDir.$filecheck);
			}
		}
	}
	
	/**
	*	Moving deleted files from old to new deleted directory
	*/
	public function moveFiles($oldDir, $delDir)
	{
		$file_scan = scandir($oldDir);
		foreach ($file_scan as $filecheck)
		{
			if($filecheck != '.' && $filecheck != '..') rename($oldDir . $filecheck, $delDir . $filecheck);
		}
	}
	
	/**
	*	Handling error of disappearing jpg
	*/
	public function purge($oldDir, $newDir)
	{
		echo 'Old dir: ' . $oldDir . '</br>';
		echo 'New dir: ' . $newDir . '</br>';
		$file_scan = scandir($oldDir);
		foreach ($file_scan as $filecheck)
		{
			echo 'Filecheck: ' . $filecheck . '</br>';
			$file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);
			if($file_extension == 'jpg')
			{
				echo 'This is being moved: ' . $filecheck . '</br>';
				rename($oldDir . $filecheck, $newDir . $filecheck);
			}
		}
	}

	/**
	*	Creating new folder in tmp directory to hold the edited narrative and moving edited narrative to it
	*/
	public function moveDir($baseDir, $id)
	{
		$folder_name = time();
		$tmpPath = $this->config->item('site_data_dir') . '/tmp/'.$folder_name.'/';
		if (!is_dir($tmpPath))
		{
			mkdir($tmpPath, 0775, TRUE);
		}
		$tmpPath = $tmpPath . '/' . $id . '/';

		if (!is_dir($tmpPath))
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
		foreach ($file_scan as $filecheck)
		{
			$file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);
			if ($filecheck != '.' && $filecheck != '..')
			{
				if ($file_extension == '')
				{
					$this->deleteDir($path.$filecheck.'/');
				}
				else
				{
					unlink($path.$filecheck);
				}
			}
		}
		rmdir($path);
	}

	public function updateModified($id)
	{
		$this->db->query('UPDATE narratives SET modified=CURRENT_TIMESTAMP WHERE narrative_id=' . $id . ';');
	}
}
?>
