<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {
  private $table = 'admins';
  public function __construct() {
    parent::__construct();
  }


  /**
   * Retrieve flags for a given narrative
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
    else
    {
      return False;
    }


  }
}
