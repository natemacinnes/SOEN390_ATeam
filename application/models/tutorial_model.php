<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tutorial_Model extends CI_Model {
	private $table = 'tutorials';

	public function __construct() {
		parent::__construct();
	}

	public function get_by_language($language)
	{
		// get the language you want!
		$query = $this->db->get_where($this->table, array('language' => $language));
		$tutorial = $query->row_array();
		return $tutorial;
	}

	public function get_all()
	{
		// get all of them! currently in use
		$query = $this->db->get_where($this->table);
		$tutorial = $query->result_array();
		return $tutorial;
	}
}
