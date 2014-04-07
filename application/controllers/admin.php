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
		$this->load->model('comment_model');
		$this->load->model('admin_model');
		$this->load->model('variable_model');
		$this->load->helper('narrative_editing');
		// Used to pass admin ID between methods during validation
		$admin_id = null;
	}

	/**
	 * The default method called, if none is provided.
	 */
	public function index()
	{
		redirect('admin/narratives');
	}

	/**
	 * Display login form.
	 * @ingroup G-0006
	 */
	public function login()
	{
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
	 * @ingroup G-0006
	 */
	public function logout()
	{
		$this->require_login();
		session_destroy();
		$this->set_logged_in_user(NULL);
		$this->system_message_model->set_message('You have been logged out.', MESSAGE_NOTICE);
		redirect('admin/login');
	}

	/**
	 * Validation callback that attempts to authenticate the user, returning a
	 * form error if not.
	 * @ingroup G-0006
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
	 * @ingroup G-0008
	 */
	public function narratives($sort_by = "id", $sort_order = "desc", $offset = 0)
	{
		$this->require_login();

		// Pagination initialization
		$this->load->library('pagination');

		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;
		$data['offset'] = $offset;
		$data['limit'] = 20;

		$narratives = $this->narrative_model->get_all($sort_by, NULL, $sort_order, $offset, $data['limit']);

		$config['base_url'] = site_url("admin/narratives/$sort_by/$sort_order");
		$config['total_rows'] = $this->narrative_model->get_total_count();
		$config['per_page'] = $data['limit'];
		$config['uri_segment'] = 5;
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '&gt;';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_close'] = '</li>';
		$config['full_tag_open'] = '<ul class="pagination float-right">';
		$config['full_tag_close'] = '</ul>';

		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();

		$data['narratives'] = $narratives;
		$this->view_wrapper('admin/narratives/list', $data);
	}

	/**
	 * The default method called, if none is provided.
	 * @ingroup G-0009
	 */
	public function comments($sort_by = "id", $sort_order = "desc", $offset = 0, $narrative_id = NULL)
	{
		$this->require_login();

		// Pagination initialization
		$this->load->library('pagination');

		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;
		$data['offset'] = $offset;
		$data['limit'] = 20;

		$comments = $this->comment_model->get_all(NULL, $sort_by, $sort_order, $offset, $data['limit']);

		$config['base_url'] = site_url("admin/comments/$sort_by/$sort_order");
		$config['total_rows'] = $this->comment_model->get_total_count();
		$config['per_page'] = $data['limit'];
		$config['uri_segment'] = 5;
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '&gt;';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_close'] = '</li>';
		$config['full_tag_open'] = '<ul class="pagination float-right">';
		$config['full_tag_close'] = '</ul>';

		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();

		$data['comments'] = $comments;

		$this->view_wrapper('admin/comments/list', $data);
	}

	/**
	 * Display the settings form
	 * @ingroup G-0007
	 */
	public function settings()
	{
		$this->require_login();
		$data = array(
			'portal_topic' => $this->variable_model->get('portal_topic'),
			'email_address' => $this->variable_model->get('email_address'),
		);

		$this->view_wrapper('admin/settings', $data);
	}

	/**
	 * Update the site settings
	 * @ingroup G-0007
	 */
	public function update_settings()
	{
		$this->require_login();
		$this->form_validation->set_rules('portal_topic', 'Portal topic', 'required');
		$this->form_validation->set_rules('email_address', 'Contact', 'valid_email|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->view_wrapper('admin/settings', array('portal_topic' => '', 'email_address' => ''));
		}
		else
		{
			$new_topic = $this->input->post('portal_topic');
			$new_email = $this->input->post('email_address');

			$this->variable_model->set('portal_topic', $new_topic);
			$this->variable_model->set('email_address', $new_email);
			$this->system_message_model->set_message('Settings updated successfully.', MESSAGE_NOTICE);
			redirect('admin/settings');
		}
	}

	/**
	 * Display narrative upload form.
	 * @ingroup G-0001
	 */
	public function upload()
	{
		$this->require_login();
		$this->view_wrapper('admin/upload');
	}

	/**
	 * Process the upload of a new narrative, unpacking it and processing its XML.
	 * @ingroup G-0001
	 */
	public function processUpload()
	{
		$this->require_login();
		//Creating unique folder name
		$folder_name = time();
		$path = $this->config->item('site_data_dir') . '/tmp/' . $folder_name . '/';
		if (!is_dir($path))
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

		//Calling narrative_model for processing
		$data = $this->narrative_model->process_narrative($data['narrative_path']);
		if ($data['error'] === 1)
		{
			$this->view_wrapper('admin/upload', $data);
			return;
		}

		//Output success
		$this->system_message_model->set_message('Narrative(s) uploaded successfully.', MESSAGE_NOTICE);
		redirect('admin/narratives');
	}

	/**
	 * Performs a bulk action on narratives based on the action passed through in
	 * POST data.
	 * @ingroup G-0005
	 */
	public function bulk()
	{
		$this->require_login();

		// Checking if any narratives have been checked
		$narratives = $this->input->post('narratives');
		if (!$narratives)
		{
			$this->system_message_model->set_message("Please select at least one narrative.", MESSAGE_ERROR);
			redirect('admin/narratives');
			return;
		}

		$action = $this->input->post('action');
		$message = count($narratives) > 1 ? 'Narratives' : 'Narrative';

		// Perform action depending on clicked button
		if ($action == 'delete')
		{
			// Displaying deletion confirmation and downloads page
			$data['narratives'] = $narratives;
			$this->view_wrapper('admin/narratives/delete', $data);
		}
		else if ($action == 'publish')
		{
			// Publish selected narratives
			foreach ($narratives as $id)
			{
				$this->narrative_model->publish($id);
				$message = $message . ' #' . $id . ', ';
			}
			if (count($narratives) == 1)
			{
				$message .= 'has been published successfully.';
			}
			else
			{
				$message .= 'have all been published successfully.';
			}
			$this->system_message_model->set_message($message);
			redirect('admin/narratives');
		}
		else if ($action == 'unpublish')
		{
			// Unpublish selected narratives
			foreach ($narratives as $id)
			{
				$this->narrative_model->unpublish($id);
				$message = $message.' #'.$id.', ';
			}
			if (count($narratives) == 1)
			{
				$message .= 'has been unpublished successfully.';
			}
			else
			{
				$message .= 'have all been unpublished successfully.';
			}
			$this->system_message_model->set_message($message);
			redirect('admin/narratives');
		}
		else if ($action == 'download')
		{
			// ZIP & download selected narratives
			$this->bulk_download($narratives);

			if (count($narratives) == 1)
			{
				$message .= 'has been unpublished successfully.';
			}
			else
			{
				$message .= 'have all been unpublished successfully.';
			}

			$this->system_message_model->set_message($message);
			redirect('admin/narratives');
		}
		else if ($action == 'markFor')
		{
			// Mark narratives as "For" position
			foreach ($narratives as $id)
			{
				$this->narrative_model->set_position($id, 1);
				$message = $message . ' #' . $id . ', ';
			}
			if (count($narratives) == 1)
			{
				$message .= 'has been marked as For.';
			}
			else
			{
				$message .= 'have all been marked as For.';
			}
			$this->system_message_model->set_message($message);
			redirect('admin/narratives');
		}
		else if ($action == 'markNeutral')
		{
			// Mark narratives as "Ambivalent" position
			foreach ($narratives as $id)
			{
				$this->narrative_model->set_position($id, 0);
				$message = $message . ' #' . $id . ', ';
			}
			if (count($narratives) == 1)
			{
				$message .= 'has been marked as Ambivalent.';
			}
			else
			{
				$message .= 'have all been marked as Ambivalent.';
			}
			$this->system_message_model->set_message($message);
			redirect('admin/narratives');
		}
		else if ($action == 'markAgainst')
		{
			// Mark narratives as "Against" position
			foreach ($narratives as $id)
			{
				$this->narrative_model->set_position($id, 2);
				$message = $message . ' #' . $id . ', ';
			}
			if (count($narratives) == 1)
			{
				$message .= 'has been marked as Against.';
			}
			else
			{
				$message .= 'have all been marked as Against.';
			}
			$this->system_message_model->set_message($message);
			redirect('admin/narratives');
		}
	}

	/**
	 * Helper function for the bulk processing to ZIP one or more narratives
	 * @ingroup G-0005
	 */
	public function bulk_download($narratives = NULL)
	{
		$this->require_login();
		if ($narratives === NULL)
		{
			$narratives = unserialize($this->input->post('narratives'));
		}

		$this->load->library('zip');

		foreach ($narratives as $id)
		{
			// Zip narrative directories
			$path = $this->config->item('site_data_dir') . '/' . $id . '/';
			$this->zip->read_dir($path, FALSE);
		}

		// Download the zip file to the administrators desktop
		$this->zip->download('narratives-' . strftime('%Y%m%d-%H%M%S') . '.zip');
	}

	/**
	 * Helper function for the bulk processing to delete one or more narratives
	 * @ingroup G-0005
	 */
	public function bulk_delete()
	{
		$this->require_login();
		if ($narratives === NULL)
		{
			$narratives = unserialize($this->input->post('narratives'));
		}

		// Delete selected narratives and then remove them from the database
		$message = 'Narratives';
		foreach ($narratives as $id)
		{
			delete_dir($this->config->item('site_data_dir') . '/' . $id . '/');
			$this->narrative_model->delete(array('narrative_id' => $id));
			$message = $message . ' #' . $id . ', ';
		}
		$message = $message . 'have all been deleted successfully.';
		$this->system_message_model->set_message($message);
		redirect('admin/narratives');
	}
}
