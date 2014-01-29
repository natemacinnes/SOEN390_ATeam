<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class upload_model extends CI_Model {
  public function __construct() {
    parent::__construct();
	$this->load->model('narrative_model');
  }

  /**
   * Pre-processing: unzipping folder and moving it to tmp folder
   */
  public function preprocessing($path, $folder_name, $zipFileName) {
	$zipFile = $path . $zipFileName;
	//Unzipping
	$zip = new ZipArchive;
	if($zip->open($zipFile) === TRUE)
	{
		$zip->extractTo($path);
		$zip->close();
	}
	else
	{
		$error['error'] = 'Unzipping failed. Please attempt the upload again.';
		$this->view_wrapper('admin/upload', $error);
	}
	
	//Call narrative_model for processing
	$narrative_path = $path.substr($zipFileName, 0, -4);
	$this->narrative_model->process_narrative($narrative_path);
	$data['path'] = $narrative_path;
	
	return $data;
  }
}
?>
