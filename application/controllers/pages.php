<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Used for providing static/view-only pages.
 */
class Pages extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('tutorial_model');
		$this->load->model('variable_model');
	}

	/**
   * The default method called, if none is provided.
   */
	public function index()
	{
		$topic = $this->variable_model->get('portal_topic', '');
		$contact = $this->variable_model->get('email_address', '');
		$data = array('topic' => $topic ,
			'contact' => $contact,
		);
		$this->view_wrapper('pages/home', $data);
	}

	public function narratives($id)
	{
		$topic = $this->variable_model->get('portal_topic', '');
		$contact = $this->variable_model->get('email_address', '');
		$data = array(
			'topic' => $topic,
			'contact' => $contact,
		);
		$data['narrative'] = $id;
		$this->view_wrapper('pages/home', $data);
	}

	public function tutorial($language)
	{
		$row = $this->tutorial_model->get_all();

		$data['enurl'] = $row[0]['url'];//direct mappings are bad kids
		$data['frurl'] = $row[1]['url'];//TODO:Make a bit more generic in sprint 3

		$this->load->view('embedded/tutorial', $data);
	}
}
