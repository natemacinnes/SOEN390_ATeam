<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commenting_Model extends CI_Model
{
	private $table = 'comments';
	public function __construct()
	{
		parent::__construct();
	}

	//get all the comments relating to this narrative
	public function get_by_narrative_id($narrative_id)
	{
		$this->db->from($this->table);
		$this->db->where('narrative_id', $narrative_id);
		$query = $this->db->get();
		$comments = array();
		foreach ($query->result_array() as $comment) {
			$comments[$comment['comment_id']] = $comment;
		}
		return $comments;
	}

	public function add_comment($narrative_id, $body_of_text, $parent_id = NULL)
	{
		$data = array(
			'narrative_id' => $narrative_id,
			'parent_comment' => $parent_id,
			'body' => $body_of_text,
			'status' => 1,
		);

		$this->db->insert('comments', $data);
	}
}

?>
