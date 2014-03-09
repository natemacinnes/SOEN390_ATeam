<?php
/**
 * @group Model
 */
class Admin_Model_Test extends CIUnit_TestCase
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
   * @covers Admin_Model::__construct
   */
  public function setUp()
  {
    parent::tearDown();
    parent::setUp();

    $this->CI->load->model('admin_model', TRUE);
  }

  /**
   * Call native CI unit tests here.
   */
  public function index() {
  }

  /**
   * UT-0033
   * @covers Admin_Model::get
   */
  public function test__get__invalid_admin_id()
  {

      $this->assertEquals(array(), $this->CI->admin_model->get(-1));

  }
  /**
   * UT-0034
   * @covers Admin_Model::get
   */
  public function test__get__valid_admin_id()
  {
      $expected = array(
        'admin_id' => '1',
        'login'=> "admin",
        'created' => "2014-01-28 01:20:51"
      );

      $actual = $this->CI->admin_model->get(1);
      $this->assertEquals($expected, $actual);

  }

  /**
   * UT-0035
   * @covers Admin_Model::valid_admin()
   */
  public function test__valid_admin__invalid_email()
  {
    $actual = $this->CI->admin_model->valid_admin('invalid', 'admin');
    $this->assertFalse($actual);
  }

  /**
   * UT-0036
   * @covers Admin_Model::valid_admin()
   */
  function test__valid_admin__invalid_password()
  {
    $actual = $this->CI->admin_model->valid_admin('admin', 'invalid');
    $this->assertFalse($actual);
  }

  /**
   * UT-0037
   * @covers Admin_Model::valid_admin()
   */
  public function test__valid_admin__valid_credentials()
  {
      $actual = $this->CI->admin_model->valid_admin('admin@youdeliberate.com', 'Moderator');
      // This is the admin_id from sample data
      $this->assertEquals(2, $actual);
  }
}
