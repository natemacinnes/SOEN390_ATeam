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
    //Getting info on the narrative and opening the page
    $data = $this->editing_model->gatherInfo($id);
    //Handling error when input id doesn't exist
    if($data == null) {
      $data['error'] = 1;
    }
    $this->view_wrapper('admin/narratives/edit', $data);
  }

  /**
   * Process the submission of the edit narrative form.
   */
  public function process($id = NULL)
  {
    $this->require_login();

    //Getting info on the narrative to edit the narrative
    $info = $this->editing_model->gatherInfo($id);

    //Unpublishing the narrative before reprocessing
    $this->narrative_model->unpublish($id);

    //Creating a new folder to move for processing
    $newDir = './uploads/'.$id.'/'.$id.'/';
    mkdir($newDir, 0755);

    //Removing desired tracks and moving the rest to the new folder
    $trackName = $info['trackName'];
    $trackPath = $info['trackPath'];
    if(isset($_POST['tracks']))
    {
      $tracksToDelete = $_POST['tracks'];
      if($this->editing_model->deleteTracks($trackName, $trackPath, $newDir, $tracksToDelete) == 0) redirect('admin/deleteNarrative/'.$id);
    }
    else
    {
      $this->editing_model->deleteTracks($trackName, $trackPath, $newDir);
    }

    //Removing desired images and moving the rest to the new folder
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

    //Creating new folder in tmp directory to hold the edited narrative and moving edited narrative to it
    $tmpPath = $this->editing_model->moveDir($newDir, $id);

    //Deleting old narrative folder
    $this->editing_model->deleteDir('./uploads/'.$id.'/');

    //Calling processing on the new folder
    $this->narrative_model->process_narrative($tmpPath, $id);

    //Republishing the narrative before announcing success
    $this->narrative_model->publish($id);

    //Output success
    $this->system_message_model->set_message('The narrative was edited successfully.', MESSAGE_NOTICE);
    redirect('admin/narratives/' . $id . '/edit');
  }

  public function delete($id = NULL)
  {
    // FIXME maybe we should confirm?
    $this->require_login();

    $this->editing_model->deleteDir('./uploads/' . $id . '/');
    $this->narrative_model->delete(array('narrative_id' => $id));
    $this->system_message_model->set_message('Narrative #' . $id . ' was deleted successfully.');
    redirect('admin/narratives');
  }

  public function publish($id = NULL)
  {

  }

  public function unpublish($id = NULL)
  {

  }
}