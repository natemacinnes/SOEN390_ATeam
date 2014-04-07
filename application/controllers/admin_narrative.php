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
		$this->load->model('admin_model');
		$this->load->model('comment_model');
		$this->load->helper('narrative_editing');
		// Used to pass admin ID between methods during validation
		$admin_id = null;
	}

	/**
	 * The default method called, if none is provided.
	 */
	public function index($id = NULL)
	{
		// Alias for when /show isn't explicitly in the SQL.
		$this->edit($id);
	}

	/**
	 * Displays the edit page for a narrative.
	 */
	public function edit($narrative_id = NULL)
	{
		$this->require_login();
		$data = array();

		// Getting info on the narrative
		$data['paths'] = narrative_track_data($narrative_id);
		$data['narrative'] = $this->narrative_model->get($narrative_id);
		$data['admin'] = $this->admin_model->get($data['narrative']['uploaded_by']);
		if (!$data['paths'])
		{
			$this->system_message_model->set_message('Narrative #' . $narrative_id . ' could not be found.', MESSAGE_ERROR);
			redirect('admin/narratives');
		}

		$data['comments'] = $this->comment_model->get_all($narrative_id);

		// Getting deleted items
		$data['deleted'] = narrative_deleted_track_data($narrative_id);

		// Loading the page
		$this->view_wrapper('admin/narratives/edit', $data);
	}

	/**
	 * Process the submission of the edit narrative form.
	 */
	public function process($id)
	{
		$this->require_login();

		// Getting info on the narrative to edit the narrative
		$paths = narrative_track_data($id);

		// Handle exception when narrative has been modified by another admin while the current admin was on the page. Else update the last modification date and time in database
 		$narrative = $this->narrative_model->get($id);
		if ($narrative['modified'] !== $this->input->post('modified'))
		{
	 		$this->system_message_model->set_message('Narrative #' . $id . ' has been modified since you loaded this page. Changes are discarded and the page is refreshed so you can see the latest version of the narrative before editing.', MESSAGE_NOTICE);
			redirect('admin/narratives/' . $id . '/edit');
		}
		else
		{
			$this->narrative_model->update($narrative);
		}

		// Unpublishing the narrative before reprocessing
		$this->narrative_model->unpublish($id);

		// Creating a new folder to move for processing
		$old_narrative_dir = $this->config->item('site_data_dir') . '/' . $id . '/';
		$new_narrative_dir = $old_narrative_dir . $id . '/';
		mkdir($new_narrative_dir, 0755);

		// Creating a new folder to store deleted files
		$new_deleted_dir = $new_narrative_dir . 'deleted/';
		mkdir($new_deleted_dir, 0755);

		//Archiving desired tracks and moving the rest to the new folder
		$tracks_for_deletion = $this->input->post('tracks');
		if ($tracks_for_deletion)
		{
			narrative_delete_tracks($paths['tracks'], $new_narrative_dir, $tracks_for_deletion);
		}

		//Archiving desired images and moving the rest to the new folder
		$pictures_for_deletion = $this->input->post('pics');
		if ($pictures_for_deletion)
		{
			narrative_delete_pictures($paths['pictures'], $new_narrative_dir, $pictures_for_deletion);
		}

		// Moving XML file to the new folder
		narrative_move_files($old_narrative_dir, $new_narrative_dir);
		narrative_move_xml($id, $new_narrative_dir);

		// Moving files from the old to the new deleted folder
		$old_deleted_dir = $old_narrative_dir . 'deleted/';
		if (is_dir($old_deleted_dir))
		{
			narrative_move_files($old_deleted_dir, $new_deleted_dir);
		}

		// Creating new folder in tmp directory to hold the edited narrative and moving edited narrative to it
		$tmp_path = move_dir_to_tmp($new_narrative_dir, $id);

		// Deleting old narrative folder
		delete_dir($old_narrative_dir);

		// Calling processing on the new folder
		$this->narrative_model->process_narrative($tmp_path, $id);

		// Republishing the narrative before announcing success
		if ($narrative['status'])
		{
			$this->narrative_model->publish($id);
		}

		//Output success
		$this->system_message_model->set_message('Narrative #' . $id . ' was edited successfully.', MESSAGE_NOTICE);
		redirect('admin/narratives/' . $id . '/edit');
	}

	public function restore($id)
	{
		$this->require_login();

		// Getting info on the narrative to edit the narrative
		$paths = narrative_track_data($id);

		// Handle exception when narrative has been modified by another admin while the current admin was on the page. Else update the last modification date and time in database
		$narrative = $this->narrative_model->get($id);
		if (FALSE && $narrative['modified'] !== $this->input->post('modified'))
		{
			$this->system_message_model->set_message('Narrative #' . $id . ' has been modified since you loaded this page. Changes are discarded and the page is refreshed so you can see the latest version of the narrative before editing.', MESSAGE_NOTICE);
			redirect('admin/narratives/' . $id . '/edit');
		}
		else
		{
			$this->narrative_model->update($narrative);
		}

		// Getting files previously removed
		$deleted_paths = narrative_deleted_track_data($id);

		// Unpublishing the narrative before reprocessing
		$previousStatus = $this->narrative_model->unpublish($id);

		// Creating a new folder to move for processing
		$old_narrative_dir = $this->config->item('site_data_dir') . '/' . $id . '/';
		$new_narrative_dir = $old_narrative_dir . $id . '/';
		mkdir($new_narrative_dir, 0755);

		// Creating a new folder to store deleted files
		$new_deleted_dir = $new_narrative_dir . '/deleted/';
		mkdir($new_deleted_dir, 0755);

		$tracks_for_restoration = $this->input->post('tracks');
		if ($tracks_for_restoration)
		{
				narrative_restore_tracks($deleted_paths['tracks'], $new_narrative_dir, $tracks_for_restoration);
		}

		// Restoring desired images and moving the rest to the new deleted folder
		$pictures_for_restoration = $this->input->post('pics');
		if ($pictures_for_restoration)
		{
			narrative_restore_pictures($deleted_paths['pictures'], $new_narrative_dir, $pictures_for_restoration);
		}

		//Moving XML file to the new folder
		narrative_move_xml($id, $new_narrative_dir);

		//Moving files from the old to the new deleted folder
		$old_deleted_dir = $old_narrative_dir . 'deleted/';
		narrative_move_files($old_deleted_dir, $new_deleted_dir);
		narrative_move_files($old_narrative_dir, $new_narrative_dir);

		//Purging files from the uploads folder to the tmp folder to handle error of disappearing jpg
		narrative_purge_files($old_narrative_dir, $new_narrative_dir);

		//Creating new folder in tmp directory to hold the edited narrative and moving edited narrative to it
		$tmp_path = move_dir_to_tmp($new_narrative_dir, $id);

		//Deleting old narrative folder
		delete_dir($old_narrative_dir);

		//Calling processing on the new folder
		$this->narrative_model->process_narrative($tmp_path, $id);

		//Republishing the narrative before announcing success
		if ($previousStatus == 1)
		{
			$this->narrative_model->publish($id);
		}

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

		$data['narratives'][0] = $id;
		$this->view_wrapper('admin/narratives/delete', $data);
	}

	public function processDelete($id)
	{
		$this->require_login();

		delete_dir($this->config->item('site_data_dir') . '/' . $id . '/');
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

	/**
	 * Removes all flags on a comment.
	 */
	public function dismiss_flags($narrative_id)
	{
		$this->narrative_model->dismiss_flags($narrative_id);
		$this->system_message_model->set_message("All flags on this narrative were dismissed.");
		redirect('admin/narratives');
	}
}
