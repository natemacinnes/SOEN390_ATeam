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
	
	/**
	 * UT-0053
	 * @covers Ajax::increment_views
	 */
	 
	 public function test__increment_views__valid_id()
	 {
		$current_views = $this->CI->narrative_model->get(1);
		$this->CI->increment_views(1);
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
		$this->CI->increment_agrees_disagrees(1, "Agree");
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
		$this->CI->increment_agrees_disagrees(1, "Disagree");
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
		$this->CI->increment_agrees_disagrees(1, "else");
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
		$this->CI->decrement_agrees_disagrees(1, "Agree");
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
		$this->CI->decrement_agrees_disagrees(1, "Disagree");
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
		$this->CI->decrement_agrees_disagrees(1, "else");
		$after = $this->CI->narrative_model->get(1);
		
		$this->assertEquals($current, $after);
	 }
	 
	 /**
	 * UT-0060
	 * @covers Ajax::toggle_concensus
	 */
	 
	 /*public function test__toggle_concensus__incremental()
	 {
		$this->assertEquals(true,false);
	 }*/

	 
	 /**
	 * UT-0062
	 * @covers Ajax::get_history
	 */
	 
	 public function test__get_history__false_history()
	 {
		$this->assertNotNull($this->CI->get_history());
	 }
	 
	 /**
	 * UT-0061
	 * @covers Ajax::add_history
	 */
	 
	 public function test__add_history__valid_id()
	 {
		$this->CI->add_history(1);
		$this->CI->add_history(1); //doing this a second time to make sure it passes through the duplicate check if statement
		$this->assertNotNull($this->CI->get_history());
	 }
	 
	 /**
	 * UT-0063
	 * @covers Ajax::get_history
	 */
	 
	 public function test__get_history__contains_history()
	 {
		$this->assertNotNull($this->CI->get_history());
	 }

}
