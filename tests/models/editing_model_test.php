<?php
/**
 * @group Model
 */
class Editing_Model_Test extends CIUnit_TestCase
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

		$this->CI->load->model('editing_model');
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0049
	 * @covers Editing_Model::gatherInfo
	 */
	public function test__gatherInfo__valid_id()
	{
		$info = $this->CI->editing_model->gatherInfo(1);

		$check = false;
		if(isset($info['narrative_id']))
		{
			$check = true;
		}

		$this->assertEquals(true, $check);
	}

	/**
	 * UT-0050
	 * @covers Editing_Model::gatherInfo
	 */
	public function test__gatherInfo__invalid_id()
	{
		$info = $this->CI->editing_model->gatherInfo(-1);
		$this->assertEquals($info, null);
	}
	
	/**
	 * UT-0051
	 * @covers Editing_Model::gatherDeleted
	 */
	 
/*	 public function test__gatherDeleted__path()
	 {
		$deleted = $this->editing_model->gatherDeleted("../../uploads/5");
		die(print_r($deleted, true));
	 }
*/
}
