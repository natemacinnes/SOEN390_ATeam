<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Model extends CI_Model {
	private $table = 'admins';
	public function __construct() {
		parent::__construct();
	}


	/**
	 * Retrieve a admin user data structure by ID, or FALSE upon failure.
	 */
	public function get($admin_id)
	{
		$query = $this->db->get_where($this->table, array('admin_id' => $admin_id));
		$admin = $query->row_array();
		return $admin;
	}

	/**
	 * Retrieve flags for a given admin
	 */
	public function valid_admin($email, $password) {
		$this->db->select('admin_id');
		$this->db->where('login', $email);
		$this->db->where('password', $password);

		$query = $this->db->get($this->table);
		if($query->result())
		{
			$result = $query->row_array();
			return $result['admin_id'];
		}
		return False;
	}
}
