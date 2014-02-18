<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commenting_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	//get all the comments relating to this narrative
	public function get_all_non_parent($narrative_id)
	{
		$query = $this->db->get_where('comments', array('parent_comment' => NULL));
		$comments = $query->row_array();
		return $query;
	}
	
	public function add_comment_to_database($narrative_id, $time_created, $body_of_text)
	{
		$data = array(
			'narrative_id' => $narrative_id,
			'created' => $time_created ,
			'parent_comment' => NULL,
			'body' => $body_of_text,
			'flag' => 0
			);
		
		$this->db->insert('comments', $data); 
	}
	
	public function flag_comment_in_database($comment_id, $narrative_id)
	{
		$data = array('flag' => 1);
		
		//$this->db->where('narrative_id', $narrative_id);
		//$this->db->where('comment_id' , $comment_id);
		//Top part is in case the update to the database doesn't work properly
		$this->db->update('comments', $data, array('narrative_id' => $narrative_id, 'comment_id' =>$comment_id));
	}
	
	public function add_comment_with_parent_to_database($narrative_id, $parent_id, $time_created, $body_of_text)
	{
		$data = array(
		'narrative_id' => $narrative_id,
		'created' => $time_created ,
		'parent_comment' => $parent_id,
		'body' => $body_of_text,
		'flag' => 0
		);
		
		$this->db->insert('comments', $data); 
	}
}
	
?>