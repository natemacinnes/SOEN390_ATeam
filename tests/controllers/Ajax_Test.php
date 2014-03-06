<?php

/**
 * @group Controller
 */

class Ajax_Test extends CIUnit_TestCase
{
	/**
	 * Loads sample data into test tables.
	 * Key = table, value = fixture filename prefix.
	 */
	protected $tables = array(
    'narratives' => 'narratives',
  );

	/**
	 * Setup PHPUnit & load any required dependencies
	 */
	public function setUp()
	{
		// Set the tested controller
		$this->CI = set_controller('ajax');
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0025
	 * @covers Ajax::audio_image
	 *
	 */
	public function test__audio_image__valid_folder()
	{
		$narrative_id = 1;
		$this->CI->audio_image($narrative_id, 1);

		$prefix = base_url() . $this->CI->config->item('site_data_dir') . '/' . $narrative_id . '/';
		$this->expectOutputRegex("|^(" . $prefix . ")(\d+).jpg$|");
	}

	/**
	 * UT-0026
	 * @covers Ajax::audio_image
	 */
	public function test__audio_image__invalid_folder()
	{
		$this->CI->audio_image(-14, 450);
		$this->expectOutputString("");
	}
}
