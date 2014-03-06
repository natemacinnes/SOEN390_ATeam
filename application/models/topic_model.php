<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topic_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function get_topic()
	{	
		$topic = "";
		$query = $this->db->from("variables")
					->where('key', "portal_topic")
					->get();
		foreach ($query->result_array() as $row)
		{
			$topic = $row['value'];
		}
		return $topic;
	}

	public function change_topic($new_topic)
	{
		$data = array('value' => $new_topic);
		$this->db->where('key', "portal_topic");
		$this->db->update('variables', $data);
	}
	
}
?>