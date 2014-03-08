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
	);
	
	public function __contruct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}
	
	/**
	 * Setup PHPUnit & load any required dependencies
	 * @covers Editing_Model::__construct
	 */
	 
	public function setUp()
	{
		parent::tearDown();
		parent::setUp();

		$this->CI->load->model('editing_model', TRUE);
	}
	/**
   * UT-0001
   * @covers editing_model::gatherInfo()
   */
	public test__unexpected__database__error__gatherInfo()
	{
		$exception_caught = false;
		try
		{
			$data = $this->CI->editing_model->gatherInfo(-1);
		}
		catch (Exception $e) 
		{
			$excepion_caught = true;
		}
		
		$this->assertEquals(true, $exception_caught);
	}
	/**
   * UT-0002
   * @covers editing_model::gatherInfo()
   */
	public test__no__unexpected__database__error__gatherInfo()
	{
		$exception_caught = true;
		try
		{
			$data = $this->CI->editing_model->gatherInfo(50);
		}
		catch (Exception $e) 
		{
			$excepion_caught = false;
		}
		
		$this->assertEquals(true, $exception_caught);
	}
	
	/**
   * UT-0003
   * @covers editing_model::gatherInfo()
   */
	public test__valid__narrative__data__gatherInfo()
	{
		$data['narrative_id'] = $row->narrative_id;
		$data['created'] = $row->created;
		$data['uploaded'] = $row->uploaded;
		$data['length'] = $row->audio_length;
		$data['language'] = $row->language;
		$data['views'] = $row->views;
		$data['agrees'] = $row->agrees;
		$data['disagrees'] = $row->disagrees;
		$data['shares'] = $row->shares;
		$data['flags'] = $row->flags;
		$data['status'] = $row->status;
	}
	
	/**
   * UT-0004
   * @covers editing_model::gatherInfo()
   */
	public test__invalid__data__gatherInfo()
	{
		$exception_caught = true;
		try
		{
			$data = $this->CI->editing_model->gatherInfo(50);
		}
		catch (Exception $e) 
		{
			$excepion_caught = false;
		}
		
		$this->assertEquals(true, $exception_caught);
	}
}
?>