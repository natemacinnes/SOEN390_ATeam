<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commenting_Model extends CI_Model
{
	private $table = 'comments';
	public function __construct()
	{
		parent::__construct();
	}

	//get all the comments relating to this narrative
	public function get_all_non_parent($narrative_id)
	{
		$this->db->from($this->table);
		$this->db->where('parent_comment', NULL);
		$this->db->where('narrative_id', $narrative_id);
		$query = $this->db->get();
		$comments = array();
		foreach ($query->result_array() as $comment) {
			$comments[$comment['comment_id']] = $comment;
		}
		return $comments;
	}

	public function add_comment_to_database($narrative_id, $time_created, $body_of_text)
	{
		$data = array(
			'narrative_id' => $narrative_id,
			'created' => $time_created ,
			'parent_comment' => NULL,
			'body' => $body_of_text,
			'status' => 1,
		);

		$this->db->insert('comments', $data);
	}

	public function add_comment_with_parent_to_database($narrative_id, $parent_id, $time_created, $body_of_text)
	{
		$data = array(
			'narrative_id' => $narrative_id,
			'created' => $time_created ,
			'parent_comment' => $parent_id,
			'body' => $body_of_text,
			'status' => 1,
		);

		$this->db->insert('comments', $data);
	}
}

?>
