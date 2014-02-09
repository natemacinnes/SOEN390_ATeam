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
				$data['trackName'][$trackCtr] = $narrative->Mp3Name;
				$data['trackPath'][$trackCtr] = base_url().("/uploads/".$row->narrative_id."/".$narrative->Mp3Name);
				
				//Getting picture
				if(strcmp($lastPic, $narrative->Image))
				{
					$picCtr++;
					$lastPic = $narrative->Image;
					$data['picName'][$picCtr] = $narrative->Image;
					$data['picPath'][$picCtr] = base_url().("/uploads/".$row->narrative_id."/".$narrative->Image);
				}
			}
			$data['trackCtr'] = $trackCtr;
			$data['picCtr'] = $picCtr;
			
			return $data;
		}
	}
}
?>
