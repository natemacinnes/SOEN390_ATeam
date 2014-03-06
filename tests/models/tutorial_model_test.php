<?php
/**
 * @group Model
 */
class Tutorial_Model_Test extends CIUnit_TestCase
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

      $actual = $this->CI->tutorial_model->get_by_language(English);
      $this->assertEquals($expected, $actual);

  }

/**
   * UT-0032
   * @covers tutorial_model::get_all()
   */
  public function test__get_all__valid_inputs()
  {

    //$Expected = $this->CI->tutorial_model->get_all();
    //die(print_r($Expected, true));
      $expected = Array
        (
        (0) => Array
        (
            'id' => 1,
            'language' => "english",
            'url' => "http://www.youtube.com/embed/WjeskPsP7qY?rel=0&autoplay=1"
        ), 

        (1) => Array
        (
            'id' => 2,
            'language' => "french",
            'url' => "http://www.youtube.com/embed/vPcfXD_GAmo?rel=0&autoplay=1"
        )

      );

      $actual = $this->CI->tutorial_model->get_all();
      $this->assertEquals($expected, $actual);

  }

}
