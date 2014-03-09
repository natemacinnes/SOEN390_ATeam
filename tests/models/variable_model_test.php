<?php
/**
 * @group Model
 */
class Variable_Model_Test extends CIUnit_TestCase
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
   * @covers variable_model::__construct
   */
  public function setUp()
  {
    parent::tearDown();
    parent::setUp();

    $this->CI->load->model('variable_model');
  }

  /**
   * Call native CI unit tests here.
   */
  public function index() {
  }

  /**
   * UT-0072
   * @covers variable_model::set
   */
  public function test__set()
  {
    // Set it to a known value, and verify it worked.
    $key = 'portal_topic';
    $this->CI->variable_model->set($key, 'Tim');
    $topic1 = $this->CI->variable_model->get($key);
    $this->assertEquals('Tim', $topic1);

    // Set it to a different value, and verify it worked.
    $this->CI->variable_model->set($key, 'Stew');
    $topic2 = $this->CI->variable_model->get($key);
    $this->assertEquals('Stew', $topic2);

    // Now ensure that the two set() operations resulted in something different
    $this->assertNotEquals($topic2, $topic1);
  }

  /**
   * UT-0073
   * @covers variable_model::get
   */
  public function test__get__not_exists()
  {
    $key = 'foobar';
    $topic = $this->CI->variable_model->get($key);
    $this->assertFalse($topic);
  }

  /**
   * UT-0074
   * @covers variable_model::get
   */
  public function test__get__exists()
  {
    $key = 'portal_topic';
    $topic = $this->CI->variable_model->get($key);
    $this->assertEquals("GMO labeling", $topic);
  }
}
