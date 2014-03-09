<?php
/**
 * @group Model
 */
class Narrative_Model_Test extends CIUnit_TestCase
{
	/**
	 * Loads sample data into test tables.
	 * Key = table, value = fixture filename prefix.
	 */
	protected $tables = array(
		'admins' => 'admins',
		'narratives' => 'narratives',
  );
	protected $sampleNarrativeXml;
	protected $sampleNarrativeXmlFR;

	public function __contruct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}

	/**
	 * Setup PHPUnit & load any required dependencies
	 * @covers Narrative_Model::__construct
	 * @covers Upload_Model::__construct
	 */
	public function setUp()
	{
		parent::tearDown();
		parent::setUp();

		$this->sampleNarrativeXml = '<?xml version="1.0" encoding="UTF-8"?><narrative><narrativeName>2</narrativeName><language>English</language><submitDate>2013-07-11</submitDate><time>11-22-31</time></narrative>';
		$this->sampleNarrativeXmlFR = '<?xml version="1.0" encoding="UTF-8"?><narrative><narrativeName>2</narrativeName><language>French</language><submitDate>2013-07-11</submitDate><time>11-22-31</time></narrative>';

		$this->CI->load->model('narrative_model');
		$this->CI->load->model('upload_model');
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0001
	 * @covers Upload_Model::unzip
	 */
	public function test__unpack__non_existant_source() {
		$folder_name = time();
		$path = $this->CI->config->item('site_data_dir') . '/tmp/' . $folder_name . '/';

		if(!is_dir($path))
		{
			mkdir($path, 0775, TRUE);
		}

		$zipFileName = 'sample_narratives.zip';

		$data = $this->CI->upload_model->unzip($path, $zipFileName);
		$this->assertEquals(1, $data['error']);
	}

	/**
	 * UT-0002
	 * @covers Upload_Model::unzip
	 */
	public function test__unpack__non_existant_destination() {
		$folder_name = time();
		$path = 'non-existent';

		$zipFileName = 'sample_narratives.zip';

		$data = $this->CI->upload_model->unzip($path, $zipFileName);
		$this->assertEquals(1, $data['error']);
	}

	/**
	 * UT-0003
	 * @covers Upload_Model::unzip
	 */
	public function test__unpack__valid_source() {
		$folder_name = time();
		$path = $this->CI->config->item('site_data_dir') . '/tmp/' . $folder_name . '/';

		if(!is_dir($path))
		{
			mkdir($path, 0775, TRUE);
		}

		$zipFileName = 'sample_narratives.zip';
		$src = 'docs/' . $zipFileName;
		copy($src, $path . '/' . $zipFileName);
		$data = $this->CI->upload_model->unzip($path, $zipFileName);

		$this->assertEquals($data['error'], 0);

		$this->assertEquals(0, strpos($data['narrative_path'], $this->CI->config->item('site_data_dir')));

		return $data;
	}

	/**
	 * UT-0004
	 * @covers Narrative_Model::process_narrative
	 */
	public function test__processing__non_existant_source() {
		$zipFileName = 'sample_narratives.zip';
		$path = $this->CI->config->item('site_data_dir') . '/non-existent/';
		$data = $this->CI->narrative_model->process_narrative($path);
		$this->assertEquals(1, $data['error']);
	}

	/**
	 * UT-0005
	 * @depends test__unpack__valid_source
	 * @covers Narrative_Model::process_narrative
	 */
	function test__processing__valid_source(array $data) {
		$process_data = $this->CI->narrative_model->process_narrative($data['narrative_path']);
		$this->assertEquals(0, $process_data['error']);
	}

	/**
	 * UT-0006
	 * @covers Narrative_Model::get_all
	 */
	function test__get_all__sorting_invalid() {
		$narratives = $this->CI->narrative_model->get_all("non-existent");
		$this->assertEquals(array(), $narratives);
	}

	/**
	 * UT-0007
	 * @covers Narrative_Model::get_all
	 */
	function test__get_all__sorting_id() {
		$narratives = $this->CI->narrative_model->get_all("id");
		$ordered = TRUE;
		// Get the first narrative ID
		$narrative = array_shift($narratives);
		$last_value = $narrative['narrative_id'];
		// Make sure each following one is smaller
		foreach ($narratives as $narrative) {
			if ($last_value <= $narrative['narrative_id']) {
				$last_value = $narrative['narrative_id'];
			}
			else {
				$ordered = FALSE;
				break;
			}
		}

		$this->assertTrue($ordered);
	}

	/**
	 * UT-0008
	 * @covers Narrative_Model::get_all
	 */
	function test__get_all__sorting_agrees() {
		$narratives = $this->CI->narrative_model->get_all("agrees");
		$ordered = TRUE;
		// Get the first narrative ID
		$narrative = array_shift($narratives);
		$last_value = $narrative['agrees'];
		// Make sure each following one is smaller
		foreach ($narratives as $narrative) {
			if ($last_value <= $narrative['agrees']) {
				$last_value = $narrative['agrees'];
			}
			else {
				$ordered = FALSE;
				break;
			}
		}

		$this->assertTrue($ordered);
	}

	/**
	 * UT-0075
	 * @covers Narrative_Model::get_all
	 */
	function test__get_all__limit() {
		$narratives = $this->CI->narrative_model->get_all("id", NULL, 'asc', 0, 2);
		$this->assertEquals(2, count($narratives));
	}

	/**
	 * UT-0009
	 * @covers Narrative_Model::get_all
	 */
	function test__get_all__position_agree() {
		$narratives = $this->CI->narrative_model->get_all("id", NARRATIVE_POSITION_AGREE);
		$agree_only = TRUE;
		foreach ($narratives as $narrative) {
			if ($narrative['position'] != NARRATIVE_POSITION_AGREE) {
				$agree_only = FALSE;
				break;
			}
		}

		$this->assertTrue($agree_only);
	}

	/**
	 * UT-0010
	 * @covers Narrative_Model::get_all
	 */
	public function test__get_all__multiple_returned() {
		$narratives = $this->CI->narrative_model->get_all();

		$count = 0;
		$array_only = TRUE;
		foreach($narratives as $narrative) {
			// Ensure we have an array of arrays with at least 2 items.
			if (is_array($narrative) && isset($narrative['narrative_id'])) {
				$count++;
			}
			else {
				$array_only = FALSE;
				break;
			}
		}
		$this->assertTrue($count > 1 && $array_only);
	}

	/**
	 * UT-0011
	 * @covers Narrative_Model::get
	 */
	public function test__get__valid_id()
	{
		// Get the first narrative ID
		// FIXME prefill a new database with known contents for testing
		$narratives = $this->CI->narrative_model->get_all();
		$first_id = $narratives[0]['narrative_id'];

		$narrative = $this->CI->narrative_model->get($first_id);
		$properties_only = TRUE;
		foreach($narrative as $property) {
			// Ensure the returned data contains only a single narrative.
			if (is_array($property) && isset($property['narrative_id'])) {
				$properties_only = FALSE;
			}
		}

		$this->assertTrue(count($narrative) > 0 && $properties_only);
	}

	/**
	 * UT-0012
	 * @covers Narrative_Model::get
	 */
	public function test__get__invalid_id()
	{
		$narrative = $this->CI->narrative_model->get(-234);
		$this->assertEquals(array(), $narrative);
	}

	/**
	 * UT-0013
	 * @covers Narrative_Model::insert
	 */
	public function test__insert()
	{
		$narrative = array(
			'position' => '0',
			'audio_length' => '60',
			'created' => "2014-01-28 01:19:27",
			'uploaded' => "2014-01-28 01:19:28",
			'uploaded_by' => '1',
			'modified' => '0000-00-00 00:00:00',
			'language' => "en",
			'views' => '1',
			'agrees' => '2',
			'disagrees' => '3',
			'shares' => '4',
			'flags' => '5',
			"status" => '1',
		);
		// MySQL returns all fields as strings -_-
		$narrative['narrative_id'] = (string)$this->CI->narrative_model->insert($narrative);

		$narrative_inserted = $this->CI->narrative_model->get($narrative['narrative_id']);
		$this->assertEquals($narrative, $narrative_inserted);
	}

	/**
	 * UT-0014
	 * @covers Narrative_Model::delete
	 */
	public function test__delete()
	{
		$narrative_id = 2;
		$this->CI->narrative_model->delete(array('narrative_id' => $narrative_id));
		$narrative = $this->CI->narrative_model->get($narrative_id);
		$this->assertEquals(array(), $narrative);
	}

	/**
	 * UT-0015
	 * @covers Narrative_Model::get_XML_narrative_name
	 */
	function test__xml_parse__get_XML_narrative_name() {
		$xmlRoot = new SimpleXMLElement($this->sampleNarrativeXml);
		$xml_retval = $this->CI->narrative_model->get_XML_narrative_name($xmlRoot);
		$this->assertEquals(2, intval($xml_retval));
	}

	/**
	 * UT-0016
	 * @covers Narrative_Model::get_XML_narrative_language
	 */
	function test__xml_parse__get_XML_narrative_language() {
		$xmlRoot = new SimpleXMLElement($this->sampleNarrativeXml);
		$xml_retval = $this->CI->narrative_model->get_XML_narrative_language($xmlRoot);
		$this->assertEquals("EN", $xml_retval);
	}

	/**
	 * UT-0017
	 * @covers Narrative_Model::get_XML_narrative_submitDate
	 */
	function test__xml_parse__get_XML_narrative_submitDate() {
		$xmlRoot = new SimpleXMLElement($this->sampleNarrativeXml);
		$xml_retval = $this->CI->narrative_model->get_XML_narrative_submitDate($xmlRoot);
		$this->assertEquals("2013-07-11", $xml_retval);
	}

	/**
	 * UT-0018
	 * @covers Narrative_Model::get_XML_narrative_submitTime
	 */
	function test__xml_parse__get_XML_narrative_submitTime() {
		$xmlRoot = new SimpleXMLElement($this->sampleNarrativeXml);
		$xml_retval = $this->CI->narrative_model->get_XML_narrative_submitTime($xmlRoot);
		$this->assertEquals("11-22-31", $xml_retval);
	}

	/**
	 * UT-00019
	 * @covers Narrative_Model::is_audio
	 */
	function test__is_audio() {
		$formats = array(
			// Make sure we accept valid formats
			'mp3' => TRUE,
			'wav' => TRUE,
			'mp4' => TRUE,
			'm4a' => TRUE,
			'aac' => TRUE,
			'avi' => TRUE,
			'3gp' => TRUE,
			'ogg' => TRUE,
			'mp2' => TRUE,
			'ac3' => TRUE,

			// Make sure we reject invalid ones
			'zip' => FALSE,
			'doc' => FALSE,
			'pdf' => FALSE,
			'pdf' => FALSE,
		);
		$detection = TRUE;
		foreach ($formats as $extension => $expected) {
			if ($this->CI->narrative_model->is_audio($extension) !== $expected) {
				$detection = FALSE;
				break;
			}
		}

		$this->assertTrue($detection);
	}

	/**
	 * UT-00020
	 * @covers Narrative_Model::is_image
	 */
	function test__is_image() {
		$formats = array(
			// Make sure we accept valid formats
			"jpg" => TRUE,
			"jpeg" => TRUE,
			"gif" => TRUE,
			"bmp" => TRUE,
			"png" => TRUE,
			"tif" => TRUE,

			// Make sure we reject invalid ones
			'zip' => FALSE,
			'doc' => FALSE,
			'pdf' => FALSE,
			'pdf' => FALSE,
		);
		$detection = TRUE;
		foreach ($formats as $extension => $expected) {
			if ($this->CI->narrative_model->is_image($extension) !== $expected) {
				$detection = FALSE;
				break;
			}
		}

		$this->assertTrue($detection);
	}

	/**
	 * UT-0064
	 * @covers Narrative_Model::get_total_count
	 */
	function test__get_total_count()
	{
		$count = $this->CI->narrative_model->get_total_count();
		$count_arr = array('count' => 4);

		$this->assertEquals($count, $count_arr);
	}

	/**
	 * UT-0065
	 * @covers Narrative_Model::get_XML_narrative_language
	 */
	function test__xml_parse__get_XML_narrative_language__FR() {
		$xmlRoot = new SimpleXMLElement($this->sampleNarrativeXmlFR);
		$xml_retval = $this->CI->narrative_model->get_XML_narrative_language($xmlRoot);
		$this->assertEquals("FR", $xml_retval);
	}

	/**
	 * UT-0066
	 * @covers Narrative_Model::publish
	 * @covers Narrative_Model::set_modified_date
	 */
	function test__publish()
	{
		$this->CI->narrative_model->publish(1);
		$narr_array = $this->CI->narrative_model->get(1);
		$this->assertEquals($narr_array["status"], 1);
	}

	/**
	 * UT-0067
	 * @covers Narrative_Model::unpublish
	 * @covers Narrative_Model::set_modified_date
	 */
	function test__unpublish()
	{
		$this->CI->narrative_model->unpublish(1);
		$narr_array = $this->CI->narrative_model->get(1);
		$this->assertEquals($narr_array["status"], 0);
	}

	/**
	 * UT-0068
	 * @covers Narrative_Model::increment_views
	 */
	function test__increment_views()
	{
		$narr_array_before = $this->CI->narrative_model->get(1);
		$this->CI->narrative_model->increment_views(1);
		$narr_array_after = $this->CI->narrative_model->get(1);
		$this->assertEquals($narr_array_before["views"], $narr_array_after["views"]-1);
	}

	/**
	 * UT-0069
	 * @covers Narrative_Model::toggle_agrees
	 */
	function test__toggle_agrees()
	{
		$narr_array_before = $this->CI->narrative_model->get(1);
		$this->CI->narrative_model->toggle_agrees(1, "+");
		$narr_array_after = $this->CI->narrative_model->get(1);
		$this->assertEquals($narr_array_before["agrees"], $narr_array_after["agrees"]-1);
	}

	/**
	 * UT-0070
	 * @covers Narrative_Model::toggle_disagrees
	 */
	function test__toggle_disagrees()
	{
		$narr_array_before = $this->CI->narrative_model->get(1);
		$this->CI->narrative_model->toggle_disagrees(1, "+");
		$narr_array_after = $this->CI->narrative_model->get(1);
		$this->assertEquals($narr_array_before["disagrees"], $narr_array_after["disagrees"]-1);
	}

	/**
	 * UT-0076
	 * @covers Narrative_Model::process_image
	 */
	function test__process_image() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * UT-0077
	 * @covers Narrative_Model::toggle
	 */
	function test__toggle() {
		$narrative_id = 1;
	 	$old = $this->CI->narrative_model->get($narrative_id);
		$this->CI->narrative_model->toggle('agrees', 'disagrees', $narrative_id);
		$new = $this->CI->narrative_model->get($narrative_id);

		$this->assertEquals($new['agrees'], $old['agrees']+1);
		$this->assertEquals($new['disagrees'], $old['disagrees']-1);
	}

	/**
	 * UT-0078
	 * @covers Narrative_Model::update
	 * @covers Narrative_Model::set_modified_date
	 */
	function test__update() {
		$narrative = $this->CI->narrative_model->get(1);
		$narrative['audio_length'] = 5;
		$this->CI->narrative_model->update($narrative);

		$updated_narrative = $this->CI->narrative_model->get(1);
		$this->assertEquals(5, $updated_narrative['audio_length']);
		$this->assertNotEquals($narrative['modified'], $updated_narrative['modified']);
	}
}
