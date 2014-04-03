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

		$this->CI->load->model('narrative_model');
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0025
	 * @covers Ajax::audio_image
	 */
	public function test__audio_image__valid_folder()
	{
		$narrative_id = 5;
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
	 * @covers Ajax::process_narrative_bubble
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
	 * @covers Ajax::process_narrative_bubble
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

	/**
	 * UT-0053
	 * @covers Ajax::increment_views
	 */

	 public function test__increment_views__valid_id()
	 {
		$current_views = $this->CI->narrative_model->get(1);
		ob_start();
		$this->CI->increment_views(1);
		ob_end_clean();
		$after_inc = $this->CI->narrative_model->get(1);

		$this->assertGreaterThan($current_views, $after_inc);
	 }

	/**
	 * UT-0054
	 * @covers Ajax::increment_agrees_disagrees
	 */
	 public function test__increment_agrees_disagrees__increment_agree()
	 {
		$current_agrees = $this->CI->narrative_model->get(1);
		ob_start();
		$this->CI->increment_agrees_disagrees(1, "agree");
		ob_end_clean();
		$after_agrees = $this->CI->narrative_model->get(1);

		$this->assertGreaterThan($current_agrees, $after_agrees);
	 }

	 /**
	 * UT-0055
	 * @covers Ajax::increment_agrees_disagrees
	 */

	 public function test__increment_agrees_disagrees__increment_disagree()
	 {
		$current_disagrees = $this->CI->narrative_model->get(1);
		ob_start();
		$this->CI->increment_agrees_disagrees(1, "disagree");
		ob_end_clean();
		$after_disagrees = $this->CI->narrative_model->get(1);

		$this->assertGreaterThan($current_disagrees, $after_disagrees);
	 }


	 /**
	 * UT-0056
	 * @covers Ajax::increment_agrees_disagrees
	 */

	 public function test__increment_agrees_disagrees__increment_other()
	 {
		$current = $this->CI->narrative_model->get(1);
		ob_start();
		$this->CI->increment_agrees_disagrees(1, "else");
		ob_end_clean();
		$after = $this->CI->narrative_model->get(1);

		$this->assertEquals($current, $after);
	 }

	/**
	 * UT-0057
	 * @covers Ajax::decrement_agrees_disagrees
	 */
	 public function test__decrement_agrees_disagrees__increment_agree()
	 {
		$current_agrees = $this->CI->narrative_model->get(1);
		ob_start();
		$this->CI->decrement_agrees_disagrees(1, "agree");
		ob_end_clean();
		$after_agrees = $this->CI->narrative_model->get(1);

		$this->assertLessThan($current_agrees, $after_agrees);
	 }

	 /**
	 * UT-0058
	 * @covers Ajax::decrement_agrees_disagrees
	 */

	 public function test__decrement_agrees_disagrees__increment_disagree()
	 {
		$current_disagrees = $this->CI->narrative_model->get(1);
		ob_start();
		$this->CI->decrement_agrees_disagrees(1, "disagree");
		ob_end_clean();
		$after_disagrees = $this->CI->narrative_model->get(1);

		$this->assertLessThan($current_disagrees, $after_disagrees);
	 }


	 /**
	 * UT-0059
	 * @covers Ajax::decrement_agrees_disagrees
	 */

	 public function test__decrement_agrees_disagrees__increment_other()
	 {
		$current = $this->CI->narrative_model->get(1);
		ob_start();
		$this->CI->decrement_agrees_disagrees(1, "else");
		ob_end_clean();
		$after = $this->CI->narrative_model->get(1);

		$this->assertEquals($current, $after);
	 }

	 /**
	 * UT-0060
	 * @covers Ajax::toggle_agree_to_disagree
	 */
	 public function test__toggle_agree_to_disagree()
	 {
	 	$narrative_id = 1;
	 	$narr_previous_data = $this->CI->narrative_model->get($narrative_id);

	 	ob_start();
		$this->CI->toggle_agree_to_disagree($narrative_id);
	 	ob_end_clean();

	 	$narr_new_data = $this->CI->narrative_model->get($narrative_id);
	 	$this->assertEquals($narr_previous_data['agrees'], $narr_new_data['agrees']+1);
		$this->assertEquals($narr_previous_data['disagrees'], $narr_new_data['disagrees']-1);
	 }

	 /**
	 * UT-0061
	 * @covers Ajax::toggle_disagree_to_agree
	 */
	 public function test__toggle_disagree_to_agree()
	 {
	 	$narrative_id = 1;
	 	$narr_previous_data = $this->CI->narrative_model->get($narrative_id);

	 	ob_start();
		$this->CI->toggle_disagree_to_agree($narrative_id);
	 	ob_end_clean();

	 	$narr_new_data = $this->CI->narrative_model->get($narrative_id);
	 	$this->assertEquals($narr_previous_data['agrees'], $narr_new_data['agrees']-1);
		$this->assertEquals($narr_previous_data['disagrees'], $narr_new_data['disagrees']+1);
	 }



	 /**
	 * UT-0062
	 * @covers Ajax::get_history
	 * @covers Ajax::clear_history
	 */
	 public function test__get_history__false_history()
	 {
	 	$this->CI->clear_history();

	 	ob_start();
		$this->CI->get_history();
		$output = ob_get_contents();
		ob_end_clean();

		$history = json_decode(trim($output));

		$this->assertEquals(0, count($history));
	 }

	 /**
	 * UT-0063
	 * @covers Ajax::get_history
	 */

	 public function test__get_history__contains_history()
	 {
	 	$this->CI->clear_history();

	 	ob_start();
		$this->CI->add_history(1);
		$output = ob_get_contents();
		ob_end_clean();

		$history = json_decode(trim($output));
		$this->assertGreaterThan(0, count($history));
	 }

	 /**
	 * UT-0061
	 * @covers Ajax::add_history
	 * @covers Ajax::clear_history
	 */
	 public function test__add_history__valid_id()
	 {
	 	$this->CI->clear_history();

	 	ob_start();
		// doing this a second time to make sure it passes through the duplicate check if statement
		$this->CI->add_history(1);
		$this->CI->add_history(2);
		ob_clean();

		$this->CI->add_history(1);
		$output = ob_get_contents();
		ob_end_clean();

		$history = json_decode(trim($output));
		$this->CI->clear_history();
		
		$this->assertGreaterThan(0, count($history));
	 }

}
