<?php
/**
 * @group Model
 */
class Narrative_Flag_Model_Test extends CIUnit_TestCase
{
	private $sampleNarrativeXml = '<?xml version="1.0" encoding="UTF-8"?><narrative><narrativeName>2</narrativeName><language>English</language><submitDate>2013-07-11</submitDate><time>11-22-31</time></narrative>';
	private $insert_id;

	public function __contruct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}

	/**
	 * Setup PHPUnit & load any required dependencies
	 */
	public function setUp()
	{
		parent::tearDown();
		parent::setUp();

		$this->CI->load->model('narrative_flag_model');
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

			//$Expected = $this->CI->narrative_flag_model->get_by_narrative_id(3);
			//die(print_r($Expected, true));
			$expected = array(
				'nflag_id' => 1,
				'narrative_id'=> 3,
				'description' => "inappropriate sexual sounds",
				'date_created' => "2014-03-05 14:30:15"
			);

			$actual = $this->CI->narrative_flag_model->get_by_narrative_id(3);
			$this->assertEquals($expected, $actual[0]);

	}

}
