<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Defines admin operations to be performed on a narrative.
 */
class Admin_Narrative extends YD_Controller
{
  /**
   * Constructor: initialize required libraries.
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('narrative_model');
    $this->load->model('editing_model');
    $this->load->model('admin_model');
    $this->load->model('narrative_flag_model');
    // Used to pass admin ID between methods during validation
    $admin_id = null;
  }

  /**
   * The default method called, if none is provided.
   */
  public function index($id = NULL) {
    // Alias for when /show isn't explicitly in the SQL.
    $this->edit($id);
  }

  /**
   * Displays the edit page for a narrative.
   */
  public function edit($id = NULL)
  {
    $this->require_login();

    //Handling error when method gets called by it's URL without an input
    if($id == NULL) {
      redirect('admin/narratives');
    }
	
    //Getting info on the narrative
    $data = $this->editing_model->gatherInfo($id);
	
	//Getting deleted items
	$path = $this->config->item('site_data_dir') . '/' . $id . '/deleted/';
	if(is_dir($path)) $data['deleted'] = $this->editing_model->gatherDeleted($path);
	
    //Handling error when input id doesn't exist
    if($data == null) {
      $data['error'] = 1;
    }
	
	//Loading the page
    $this->view_wrapper('admin/narratives/edit', $data);
  }

  /**
   * Review narrative including player, flag and change publish status.
   */
  public function review($narrative_id = 0)
  {
    $this->require_login();

    $data = array();

    //get selected narrative
    $narrative = $this->narrative_model->get($narrative_id);

    //get flag for given narrative
    $flags = $this->narrative_flag_model->get_by_narrative_id($narrative_id);

    $data['narrative_id'] = $narrative_id;
    $data['narrative'] = $narrative;
    $data['flags'] = $flags;
    $this->view_wrapper('admin/narratives/review', $data);
  }

  /**
   * Process the submission of the edit narrative form.
   */
  public function process($id)
  {
    $this->require_login();

    //Getting info on the narrative to edit the narrative
    $info = $this->editing_model->gatherInfo($id);

    //Unpublishing the narrative before reprocessing
    $this->narrative_model->unpublish($id);

    //Creating a new folder to move for processing
    $newDir = $this->config->item('site_data_dir') . '/' . $id . '/' . $id . '/';
    mkdir($newDir, 0755);
	
	//Creating a new folder to store deleted files
	$delDir = $this->config->item('site_data_dir') . '/' . $id . '/' . $id . '/deleted/';
    mkdir($delDir, 0755);

    //Archiving desired tracks and moving the rest to the new folder
    $trackName = $info['trackName'];
    $trackPath = $info['trackPath'];
    if(isset($_POST['tracks']))
    {
      $tracksToDelete = $_POST['tracks'];
      if($this->editing_model->deleteTracks($trackName, $trackPath, $newDir, $tracksToDelete) == 0) redirect('admin/narratives/'.$id.'/delete');
    }
    else
    {
      $this->editing_model->deleteTracks($trackName, $trackPath, $newDir);
    }

    //Archiving desired images and moving the rest to the new folder
    $picName = $info['picName'];
    $picPath = $info['picPath'];
    if(isset($_POST['pics']))
    {
      $picsToDelete = $_POST['pics'];
      $this->editing_model->deletePics($picName, $picPath, $newDir, $picsToDelete);
    }
    else
    {
      $this->editing_model->deletePics($picName, $picPath, $newDir);
    }
	
    //Moving XML file to the new folder
    $this->editing_model->moveXML($id, $newDir);
	
	//Moving files from the old to the new deleted folder
	$this->editing_model->moveFiles($this->config->item('site_data_dir') . '/' . $id . '/deleted/', $delDir);

    //Creating new folder in tmp directory to hold the edited narrative and moving edited narrative to it
    $tmpPath = $this->editing_model->moveDir($newDir, $id);
	
    //Deleting old narrative folder
    $this->editing_model->deleteDir($this->config->item('site_data_dir') . '/' . $id . '/');

    //Calling processing on the new folder
    $this->narrative_model->process_narrative($tmpPath, $id);

    //Republishing the narrative before announcing success
    $this->narrative_model->publish($id);

    //Output success
    $this->system_message_model->set_message('Narrative #' . $id . ' was edited successfully.', MESSAGE_NOTICE);
    redirect('admin/narratives/' . $id . '/edit');
  }
  
  public function restore($id)
  {
	$this->require_login();
	
	//Getting info on the narrative to edit the narrative
    $info = $this->editing_model->gatherInfo($id);
	
	//Getting files previously removed
	$path = $this->config->item('site_data_dir') . '/' . $id . '/deleted/';
	$deleted = $this->editing_model->gatherDeleted($path);

    //Unpublishing the narrative before reprocessing
    $this->narrative_model->unpublish($id);

    //Creating a new folder to move for processing
    $newDir = $this->config->item('site_data_dir') . '/' . $id . '/' . $id . '/';
    mkdir($newDir, 0755);
	
	//Creating a new folder to store deleted files
	$delDir = $this->config->item('site_data_dir') . '/' . $id . '/' . $id . '/deleted/';
    mkdir($delDir, 0755);

    //Keeping files already in the narrative
    $trackName = $info['trackName'];
    $trackPath = $info['trackPath'];
    $this->editing_model->deleteTracks($trackName, $trackPath, $newDir);
    $picName = $info['picName'];
    $picPath = $info['picPath'];
    $this->editing_model->deletePics($picName, $picPath, $newDir);
	
	//Restoring desired tracks and moving the rest to the new deleted folder
    if(isset($deleted['deletedAudio']))
	{
		$trackName = $deleted['deletedAudio'];
		$trackPath = $deleted['deletedAudioPath'];
	}
    if(isset($_POST['tracks']))
    {
      $tracksToRestore = $_POST['tracks'];
      $this->editing_model->restoreTracks($trackName, $trackPath, $newDir, $tracksToRestore);
    }
    else
    {
      $this->editing_model->restoreTracks($trackName, $trackPath, $newDir);
    }

    //Restoring desired images and moving the rest to the new deleted folder
    if(isset($deleted['deletedImage']))
	{
		$picName = $deleted['deletedImage'];
		$picPath = $deleted['deletedImagePath'];
	}
    if(isset($_POST['pics']))
    {
      $picsToRestore = $_POST['pics'];
      $this->editing_model->restorePics($picName, $picPath, $newDir, $picsToRestore);
    }
    else
    {
      $this->editing_model->restorePics($picName, $picPath, $newDir);
    }
	
    //Moving XML file to the new folder
    $this->editing_model->moveXML($id, $newDir);
	
	//Moving files from the old to the new deleted folder
	$this->editing_model->moveFiles($this->config->item('site_data_dir') . '/' . $id . '/deleted/', $delDir);
	
	//Purging files from the uploads folder to the tmp folder to handle error of disappearing jpg
	$this->editing_model->purge($this->config->item('site_data_dir') . '/' . $id . '/', $newDir);

    //Creating new folder in tmp directory to hold the edited narrative and moving edited narrative to it
    $tmpPath = $this->editing_model->moveDir($newDir, $id);
	
    //Deleting old narrative folder
    $this->editing_model->deleteDir($this->config->item('site_data_dir') . '/' . $id . '/');

    //Calling processing on the new folder
    $this->narrative_model->process_narrative($tmpPath, $id);

    //Republishing the narrative before announcing success
    $this->narrative_model->publish($id);
	
    //Output success
    $this->system_message_model->set_message('Narrative #' . $id . ' was edited successfully.', MESSAGE_NOTICE);
    redirect('admin/narratives/' . $id . '/edit');
  }
  
  public function download($id)
  {
	$this->require_login();
	
	$this->load->library('zip');
	
	//Zip narrative directory
	$path = $this->config->item('site_data_dir') . '/' . $id . '/';
	$this->zip->read_dir($path, FALSE);

	// Download the zip file to the administrators desktop
	$this->zip->download($id . '.zip');
  }

  public function delete($id)
  {
	$this->require_login();
	
    $data['narrative_id'] = $id;
	$this->view_wrapper('admin/narratives/delete', $data);
  }
  
  public function processDelete($id)
  {
    $this->require_login();
	
    $this->editing_model->deleteDir($this->config->item('site_data_dir') . '/' . $id . '/');
    $this->narrative_model->delete(array('narrative_id' => $id));
	
    $this->system_message_model->set_message('Narrative #' . $id . ' was deleted successfully.');
    redirect('admin/narratives');
  }

  public function publish($id)
  {
	$this->narrative_model->publish($id);
	$this->system_message_model->set_message('Narrative #' . $id . ' has been published successfully.');
	redirect('admin/narratives/'.$id);
  }

  public function unpublish($id)
  {
	$this->narrative_model->unpublish($id);
	$this->system_message_model->set_message('Narrative #' . $id . ' has been unpublished successfully.');
	redirect('admin/narratives/'.$id);
  }
}
