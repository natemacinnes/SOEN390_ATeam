<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topic extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('topic_model');
	}
	
	/**
   * The default method called, if none is provided.
   */
	public function index()
	{
		return;
	}

	/**
   * Change the Topic of the Website.
   */
	public function change($new_topic)
	{
		if (strlen($new_topic)) 
		{
			$this->topic_model->change_topic($new_topic);
		}
		else
		{
			// Set header: 400 Bad response
			$this->output->set_status_header('400');
		}
	}
?>