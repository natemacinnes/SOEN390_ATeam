<?php
/**
 * @group Model
 */
class Topic_Model_Test extends CIUnit_TestCase
{
  /**
   * Loads sample data into test tables.
   * Key = table, value = fixture filename prefix.
   */
  protected $tables = array(
    'variables' => 'variables',
  );

  public function __contruct($name = NULL, array $data = array(), $dataName = '')
  {
    parent::__construct($name, $data, $dataName);
  }

  /**
   * Setup PHPUnit & load any required dependencies
   * @covers Topic_Model::__construct
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
    $this->CI->topic_model->change_topic('Tim');
    $topic1 = $this->CI->topic_model->get_topic();
    $this->assertEquals('Tim', $topic1);
    $this->CI->topic_model->change_topic('Stew');
    $topic2 = $this->CI->topic_model->get_topic();
    $this->assertEquals('Stew', $topic2);
    $this->assertNotEquals($topic2, $topic1);
  }
}
