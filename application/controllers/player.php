<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Player extends MY_Controller {
  /**
   * Constructor: initialize required libraries.
   */
  public function __construct() {
    parent::__construct();
    $this->load->model('narrative_model');
  }

  /**
   * Index Page for this controller.
   */
  public function index($narrative_id) {
    $narrative = $this->narrative_model->get($narrative_id);
    $data = array('narrative_id' => $narrative_id, 'narrative' => $narrative);
    $this->load->view('player/index', $data);
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
