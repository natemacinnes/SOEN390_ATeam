<?php
/**
 * @group Model
 */
class Admin_Model_Test extends CIUnit_TestCase
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

    $this->CI->load->model('admin_model');
  }

  /**
   * Call native CI unit tests here.
   */
  public function index() {
  }

  /**
   * UT-0033
   * @covers admin_model::get
   */
  public function test__get__invalid_admin_id()
  {

      $this->assertEquals(array(), $this->CI->admin_model->get(-1));

  }
  /**
   * UT-0034
   * @covers admin_model::get
   */
  public function test__get__valid_admin_id()
  {

     // $Expected = $this->CI->tutorial_model->get_by_language("English");
      //die(print_r($Expected, true));
      $expected = array(
        'admin_id' => 1,
        'login'=> "admin",
        'password' => "admin",
        'created' => "2014-01-28 01:20:51"
      );

      $actual = $this->CI->admin_model->get(1);
      $this->assertEquals($expected, $actual);

  }

  /**
   * UT-0035
   * @covers admin_model::valid_admin()
   */
  public function test__valid_admin__invalid_email()
  {
    

    $actual = $this->CI->admin_model->valid_admin(gawd, admin);

    $this->assertEquals(False, $actual);
    

  }

  /**
   * UT-0036
   * @covers admin_model::valid_admin()
   */
  function test__valid_admin__invalid_password()
  {
    
    $actual = $this->CI->admin_model->valid_admin(admin, gawd);

    $this->assertEquals(False, $actual);
    

  }

  /**
   * UT-0037
   * @covers admin_model::valid_admin()
   */
  public function test__valid_admin__valid_credentials()
  {

     // $Expected = $this->CI->tutorial_model->get_by_language("English");
      //die(print_r($Expected, true));

      $actual = $this->CI->admin_model->valid_admin(admin, admin);
      $this->assertEquals(1, $actual);

  }
}
