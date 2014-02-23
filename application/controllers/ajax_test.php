<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Test the Ajax controller.
 *
 * Note that controllers render page output; we'll use ob_start() to buffer
 * output and capture printed data.
 */
class Ajax_Test extends YD_Controller
{
	// Stores a reference to the Ajax controller
	private $ajax;

	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
		$this->load->model('narrative_model');
		$this->ajax = $this->load->controller('ajax');
	}

	/**
	 * UT-0015
	 */
	public function test__audio_image__valid_folder()
	{
		// Get the first narrative ID
		// FIXME prefill a new database with known contents for testing
		$narratives = $this->narrative_model->get_all();
		$first_id = $narratives[0]['narrative_id'];

		ob_start();
		$this->ajax->audio_image($first_id, 1);
		$ajax_audio_image = ob_get_contents();
		ob_end_clean();

		$prefix = base_url() . $this->config->item('site_data_dir') . '/' . $first_id . '/';
		$matches = array();
		preg_match("|^(" . $prefix . ")(\d+).jpg$|", $ajax_audio_image, $matches);

		// Expecting 3 because an array of (full match, prefix match, jpg numer match)
		$this->unit->run(
			count($matches),
			3,
			"audio_image retrieval: valid folder",
			"Should return the URL to a JPG file."
		);

		return $this->unit->result();
	}

	/**
	 * UT-0015
	 */
	public function test__audio_image__invalid_folder()
	{
		ob_start();
		$this->ajax->audio_image(-14, 450);
		$ajax_audio_image = ob_get_contents();
		ob_end_clean();

		$this->unit->run(
			$ajax_audio_image,
			"",
			"audio_image retrieval: invalid folder",
			"Should return an empty string."
		);

		return $this->unit->result();
	}
}
