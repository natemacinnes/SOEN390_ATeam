<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends MY_Controller {
  /**
   * Constructor: initialize required libraries.
   */
  public function __construct() {
    parent::__construct();
    $this->load->model('upload_model');
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

  public function upload() {
    // Render the views/pages/uploader.php file using including the header/footer
    $this->view_wrapper('admin/upload');
  }

  public function unpack() {
    $config['upload_path'] = './uploads/';
    $config['allowed_types'] = 'zip';
    $config['overwrite'] = FALSE;

    $this->load->library('upload', $config);

    $upload_error = FALSE;
    if (!$this->upload->do_upload()) {
      $upload_error = $this->upload->display_errors();
    }
    $upload_data = $this->upload->data();
    // Filename of uploaded file is available at $upload_data['file_name']

    // Save the file to the database using a model
    //redirect("redirect/path", 'location');

    $this->load->model('narrative_model');
    $path = $config['upload_path'] . $upload_Data['file_name'];

    //$narrative = $this->narrative_model->unpack($path);
    //$this->narrative_model->insert($narrative);

    $data = array("upload_data" => $upload_data, 'error' => $upload_error);
    $this->view_wrapper('admin/upload-success', $data);
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
