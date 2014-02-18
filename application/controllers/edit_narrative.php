<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edit_narrative extends YD_Controller {
  /**
   * Constructor: initialize required libraries.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -
   *    http://example.com/index.php/welcome/index
   *  - or -
   * Since this controller is set as the default controller in
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see http://codeigniter.com/user_guide/general/urls.html
   */
  public function index() {
    $this->edit(1);
  }

  public function edit($narrative_id = 1) {
    $data = array();

    $this->load->model('narrative_model');
    $narrative = $this->narrative_model->get($narrative_id);
    $this->load->model('flag_model');
    $flags = $this->flag_model->get_by_narrative($narrative_id);

    $data['narrative_id'] = $narrative_id;
    $data['narrative'] = $narrative;
    $data['flags'] = $flags;
    $this->view_wrapper('admin/editnarrative', $data);
  }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
