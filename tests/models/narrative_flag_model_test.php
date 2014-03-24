<?php
/**
 * @group Model
 */
class Narrative_Flag_Model_Test extends CIUnit_TestCase
{
	/**
	 * Loads sample data into test tables.
	 * Key = table, value = fixture filename prefix.
	 */
	protected $tables = array(
		'admins' => 'admins',
		'narratives' => 'narratives',
		'narrative_flags' => 'narrative_flags',
  );

	public function __contruct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}

	/**
	 * Setup PHPUnit & load any required dependencies
	 * @covers Narrative_Flag_Model::__construct
	 */
	public function setUp()
	{
		parent::tearDown();
		parent::setUp();

		$this->CI->load->model('narrative_flag_model');
		$this->CI->load->model('narrative_model');
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0001
	 * @covers narrative_flag_model::get_by_narrative_id
	 */
	public function test__get_by_narrative_id__non_existant_id()
	{
		$this->assertEquals(array(), $this->CI->narrative_flag_model->get_by_narrative_id(-1));
	}

	/**
	 * UT-0002
	 * @covers narrative_flag_model::get_by_narrative_id
	 */
	public function test__get_by_narrative_id__valid_id()
	{
		$actual = $this->CI->narrative_flag_model->get_by_narrative_id(1);
		$this->assertEquals(2, count($actual));
		$this->assertEquals(1, $actual[0]['nflag_id']);
		$this->assertEquals(2, $actual[1]['nflag_id']);
	}

	/**
	 * UT-0002
	 * @covers narrative_flag_model::get_by_narrative_id
	 */
	public function test__insert()
	{
		$flag_before = $this->CI->narrative_model->get(1);
		$this->CI->narrative_flag_model->insert(1);
		$flag_after = $this->CI->narrative_model->get(1);
		$this->assertEquals($flag_before['flags'], $flag_after['flags'] - 1);
	}
}
