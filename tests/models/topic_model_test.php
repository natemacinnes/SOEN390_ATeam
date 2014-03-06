<?php
/**
 * @group Model
 */
class Topic_Model_Test extends CIUnit_TestCase
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

		$this->CI->load->model('topic_model');
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0001
	 * @covers topic_model::change_topic
	 */
	public function test__change_topic__valid_data()
	{

		  $this->CI->topic_model->change_topic(Tim);
      $topic1 = $this->CI->topic_model->get_topic();
      $this->assertEquals(Tim, $topic1);
      $this->CI->topic_model->change_topic(Stew);
      $topic2 = $this->CI->topic_model->get_topic();
      $this->assertEquals(Stew, $topic2);
      $this->assertNotEquals($topic2, $topic1);

      

	}

	
}
