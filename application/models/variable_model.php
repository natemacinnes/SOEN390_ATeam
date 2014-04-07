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
	 * @ingroup G-0007
	 */
	public function get($key, $default=FALSE)
	{
		$query = $this->db->from($this->table)
			->where('key', $key)
			->get();
		$value = $default;
		foreach ($query->result_array() as $row)
		{
			$value = $row['value'];
		}
		return $value;
	}

	/**
	 * Set a variable in the database.
	 * @ingroup G-0007
	 */
	public function set($key, $value)
	{
		if ($this->get($key) == false)
		{
			$data = array('key' => $key, 'value' => $value);
			$this->db->insert($this->table, $data);
		} else {
			$data = array('value' => $value);
			$this->db->where('key', $key);
			$this->db->update($this->table, $data);
		}
	}
}
