<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Model extends CI_Model {
	private $table = 'admins';
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Retrieve a admin user data structure by ID, or FALSE upon failure.
	 */
	public function get($admin_id)
	{
		$query = $this->db->get_where($this->table, array('admin_id' => $admin_id));
		$admin = $query->row_array();

		// Security: don't let controllers or views grab the password
		unset($admin['password']);

		return $admin;
	}

	/**
	 * Retrieve admin ID for a given admin if authenticated, or FALSE if not
	 * authenticated
	 * @ingroup G-0006
	 */
	public function valid_admin($email, $password)
	{
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
