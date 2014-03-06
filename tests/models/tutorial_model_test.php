<?php
/**
 * @group Model
 */
class Tutorial_Model_Test extends CIUnit_TestCase
{
  /**
   * Loads sample data into test tables.
   * Key = table, value = fixture filename prefix.
   */
  protected $tables = array(
    'tutorials' => 'tutorials',
  );

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

    $this->CI->load->model('tutorial_model');
  }

  /**
   * Call native CI unit tests here.
   */
  public function index() {
  }

  /**
   * UT-0030
   * @covers tutorial_model::get_by_language
   */
  public function test__get_by_language__invalid_language()
  {

      $this->assertEquals(array(), $this->CI->tutorial_model->get_by_language('German'));

  }

  /**
   * UT-0031
   * @covers narrative_flag_model::get_by_narrative_id
   */
  public function test__get_by_language__valid_language()
  {

     // $Expected = $this->CI->tutorial_model->get_by_language("English");
      //die(print_r($Expected, true));
      $expected = array(
        'id' => 1,
        'language'=> "english",
        'url' => "http://www.youtube.com/embed/WjeskPsP7qY?rel=0&autoplay=1"
      );

      $actual = $this->CI->tutorial_model->get_by_language('English');
      $this->assertEquals($expected, $actual);

  }

/**
   * UT-0032
   * @covers tutorial_model::get_all()
   */
  public function test__get_all__valid_inputs()
  {
    $actual = $this->CI->tutorial_model->get_all();
    $this->assertEquals(2, count($actual));
    $this->assertEquals("english", $actual[0]['language']);
    $this->assertEquals("french", $actual[1]['language']);
  }

}
