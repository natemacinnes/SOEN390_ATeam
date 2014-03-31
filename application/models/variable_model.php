<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Variable_Model extends CI_Model
{
	private $table = 'variables';
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get a variable value from the database by its key name.
	 */
	public function get($key)
	{
		$query = $this->db->from($this->table)
			->where('key', $key)
			->get();
		$value = FALSE;
		foreach ($query->result_array() as $row)
		{
			$value = $row['value'];
		}
		return $value;
	}

	/**
	 * Set a variable in the database.
	 */
	public function set($key, $value)
	{
		if ($this->get($key) == false) {
			$data = array('key' => $key, 'value' => $value);
			$this->db->insert($this->table, $data);
		} else {
			$data = array('value' => $value);
			$this->db->where('key', $key);
			$this->db->update($this->table, $data);
		}
	}
}
