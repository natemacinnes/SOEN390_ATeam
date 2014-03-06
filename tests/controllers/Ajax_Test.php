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
		'admins' => 'admins',
    'narratives' => 'narratives',
  );

	/**
	 * Setup PHPUnit & load any required dependencies
	 * @covers Ajax::__construct
	 */
	public function setUp()
	{
		// Set the tested controller
		$this->CI = set_controller('ajax');

		parent::tearDown();
		parent::setUp();
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
	 * UT-FIXME
	 * @covers Ajax::audio_image
	 *
	 */
	public function test__bubbles__invalid_position()
	{
		ob_start();
		$this->CI->bubbles(-1);
		$output = ob_get_contents();
		ob_end_clean();

		$bubbles = json_decode(trim($output));

		$this->assertEquals(0, count($bubbles->children));
	}

	/**
	 * UT-FIXME
	 * @covers Ajax::bubbles
	 */
	public function test__bubbles__valid_position()
	{
		ob_start();
		$this->CI->bubbles(NARRATIVE_POSITION_NEUTRAL);
		$output = ob_get_contents();
		ob_end_clean();

		$bubbles = json_decode(trim($output));

		$this->assertGreaterThan(0, count($bubbles->children));
	}

	/**
	 * UT-FIXME
	 * @covers Ajax::bubbles
	 */
	public function test__bubbles__no_position()
	{
		ob_start();
		$this->CI->bubbles();
		$output = ob_get_contents();
		ob_end_clean();

		$bubbles = json_decode(trim($output));

		$this->assertGreaterThan(0, count($bubbles->children));
	}


}
