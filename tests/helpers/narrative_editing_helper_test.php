<?php
/**
 * @group Model
 */
class Narrative_Editing_Helper_Test extends CIUnit_TestCase
{
	/**
	 * Loads sample data into test tables.
	 * Key = table, value = fixture filename prefix.
	 */
	protected $tables = array(
		'admins' => 'admins',
		'narratives' => 'narratives',
		'comments' => 'comments',
  );

	public function __contruct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}

	/**
	 * Setup PHPUnit & load any required dependencies
	 * @covers Comment_Model::__construct
	 */
	public function setUp()
	{
		parent::tearDown();
		parent::setUp();

		$this->CI->load->helper('narrative_editing');
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0049
	 * @covers narrative_track_data
	 */
	public function test__narrative_track_data__valid_id()
	{
		$info = narrative_track_data(1);

		$check = false;
		if(isset($info['narrative_id']))
		{
			$check = true;
		}

		$this->assertEquals(true, $check);
	}

	/**
	 * UT-0050
	 * @covers Editing_Model::narrative_track_data
	 */
	public function test__narrative_track_data__invalid_id()
	{
		$info = narrative_track_data(-1);
		$this->assertEquals($info, null);
	}

}
