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

  public function uploader() {
    // Render the views/pages/uploader.php file using including the header/footer
    $this->view_wrapper('pages/uploader');
  }
  
  public function upload()
  {
	$config['upload_path'] = './uploads/';
    $config['allowed_types'] = 'zip';
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload()) {
      $upload_error = $this->upload->display_errors();
    }
    $upload_data = $this->upload->data();
    // Filename of uploaded file is available at $upload_data['file_name']
    
    // Save the file to the database using a model
    //redirect("redirect/path", 'location');
	
    $data = array("upload_data" => $upload_data, 'error' => $upload_error);
	$this->view_wrapper('pages/success', $data);
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
