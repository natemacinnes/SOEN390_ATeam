<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Defines top-level administrative operations.
 */
class Admin extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('upload_model');
		$this->load->model('narrative_model');
		$this->load->model('editing_model');
		$this->load->model('admin_model');
		// Used to pass admin ID between methods during validation
		$admin_id = null;
	}

	/**
   * The default method called, if none is provided.
   */
	public function index() {
		// FIXME change this when we have dashboard
		$this->narratives();
	}

	/**
   * Display login form.
   */
	public function login() {
		if ($this->get_logged_in_user())
		{
			redirect('admin');
		}
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean|callback_validate_authenticate_user');

		if ($this->form_validation->run() == FALSE)
		{
			$this->view_wrapper('admin/login');
		}
		else
		{
			// set session
			$this->set_logged_in_user($this->admin_id);
			redirect('admin');
		}
	}

	/**
   * Terminates a user session.
   */
	public function logout() {
		$this->require_login();
		$this->set_logged_in_user(NULL);
		$this->system_message_model->set_message('You have been logged out.', MESSAGE_NOTICE);
		redirect('admin/login');
	}

	/**
   * Validation callback that attempts to authenticate the user, returning a
   * form error if not.
   *
   * TODO: figure out how to hide this from being accessed via URLs
   */
	public function validate_authenticate_user($password)
	{
		$email = $this->input->post("email");
		if ($this->admin_id = $this->admin_model->valid_admin($email, $password))
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('validate_authenticate_user', 'Your email or password is incorrect.');
			return false;
		}
	}

	/**
	 * Displays a list of all narratives on the portal with management links.
	 */
	public function narratives()
	{
		$this->require_login();
		$this->load->model('narrative_model'); //Might need to be removed
		$narratives = $this->narrative_model->get_all();

		$data = array('narratives' => $narratives);
		$this->view_wrapper('admin/narratives/list', $data);
	}

	/**
 	 * Display narrative upload form.
	 */
	public function upload()
	{
		$this->require_login();
		$this->view_wrapper('admin/upload');
	}

	/**
	 * Process the upload of a new narrative, unpacking it and processing its XML.
	 */
	public function processUpload()
	{
		$this->require_login();
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
		if (!$this->upload->do_upload())
		{
			$this->system_message_model->set_message($this->upload->display_errors(), MESSAGE_ERROR);
			redirect('admin/upload');
		}

		// Getting data from upload, filename of uploaded file is available at $upload_data['file_name']
		$upload_data = $this->upload->data();
		$zipFileName = $upload_data['file_name'];

		// Calling upload_model to unzip, handling error
		$data = $this->upload_model->unzip($path, $zipFileName);
		if ($data['error'] === 1)
		{
			$this->view_wrapper('admin/upload', $data);
			return;
		}

		//Calling arrative_model for processing
		$data = $this->narrative_model->process_narrative($data['narrative_path']);
		if ($data['error'] === 1)
		{
			$this->view_wrapper('admin/upload', $data);
			return;
		}

		//Output success
		$this->view_wrapper('admin/upload-success', $data);
	}
	
	public function batchAction()
	{
		 $this->require_login();
		
		//Checking if any narratives have been checked
		if(isset($_POST['narratives'])) $narratives = $_POST['narratives'];
		else redirect('admin');
		
		//Perform action depending on clicked button
		if(isset($_POST['delete']))
		{
			$message = 'Narratives';
			foreach($narratives as $id)
			{
				$this->editing_model->deleteDir('./uploads/' . $id . '/');
				$this->narrative_model->delete(array('narrative_id' => $id));
				$message = $message.' #'.$id.', ';
			}
			$message = $message.'have all been deleted successfully.';
			$this->system_message_model->set_message($message);
			redirect('admin/narratives');
			
		}
		else if(isset($_POST['publish']))
		{
			
		}
		else if(isset($_POST['unpublish']))
		{
			
		}
	}
}
