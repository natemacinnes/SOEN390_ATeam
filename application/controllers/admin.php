<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends MY_Controller {
  /**
   * Constructor: initialize required libraries.
   */
  public function __construct() {
    parent::__construct();
    $this->load->model('upload_model');
    $this->load->model('narrative_model');
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

  public function processUpload() {
    //Creating unique folder name
    $folder_name = time();
    $path = './uploads/tmp/'.$folder_name.'/';
    if(!is_dir($path))
    {
      mkdir($path, 0775, TRUE);
    }

    //Setting constraints on the file uploaded
    $config['upload_path'] = $path;
    $config['allowed_types'] = 'zip';
    $config['overwrite'] = FALSE;
    $this->load->library('upload', $config);

      //Executing upload, display errors if any
    if (!$this->upload->do_upload()) {
      $data['error_message'] = $this->upload->display_errors();
      $this->view_wrapper('admin/upload', $data);
      return;
    }

    // Getting data from upload, filename of uploaded file is available at $upload_data['file_name']
    $upload_data = $this->upload->data();
    $zipFileName = $upload_data['file_name'];

    // Calling upload_model to unzip, handling error
    $data = $this->upload_model->unzip($path, $zipFileName);
    if($data['error'] === 1)
    {
      $this->view_wrapper('admin/upload', $data);
      return;
    }

    //Calling arrative_model for processing
    $data = $this->narrative_model->process_narrative($data['narrative_path']);
    if($data['error'] === 1)
    {
      $this->view_wrapper('admin/upload', $data);
      return;
    }

    //Output success
    $this->view_wrapper('admin/upload-success', $data);
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
