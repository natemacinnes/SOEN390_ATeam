<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Test the Narrative_Model model.
 */
class Narrative_Model_Test extends YD_Controller
{
	private $data;
	private $sampleNarrativeXml = '<?xml version="1.0" encoding="UTF-8"?><narrative><narrativeName>2</narrativeName><language>English</language><submitDate>2013-07-11</submitDate><time>11-22-31</time></narrative>';
	private $insert_id;

	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
		$this->load->model('narrative_model');
		$this->load->model('upload_model');
	}

	public function test__unpack__non_existant_source() {
		$folder_name = time();
		$path = $this->config->item('site_data_dir') . '/tmp/' . $folder_name . '/';

		if(!is_dir($path))
		{
			mkdir($path, 0775, TRUE);
		}

		$zipFileName = 'sample_narratives.zip';

		$data = $this->upload_model->unzip($path, $zipFileName);
		$this->unit->run(
			$data['error'],
			1,
			"Narrative unpacking: non-existent source",
			"An error flag should be present in the returned data."
		);
	}

	public function test__unpack__non_existant_destination() {
		$folder_name = time();
		$path = 'non-existent';

		$zipFileName = 'sample_narratives.zip';

		$data = $this->upload_model->unzip($path, $zipFileName);
		$this->unit->run(
			$data['error'],
			1,
			"Narrative unpacking: non-existant destination",
			"An error flag should be present in the returned data."
		);
	}

	public function test__unpack__valid_source() {
		$folder_name = time();
		$path = $this->config->item('site_data_dir') . '/tmp/' . $folder_name . '/';

		if(!is_dir($path))
		{
			mkdir($path, 0775, TRUE);
		}

		$zipFileName = 'sample_narratives.zip';
		$src = 'docs/' . $zipFileName;
		copy($src, $path . '/' . $zipFileName);
		$data = $this->upload_model->unzip($path, $zipFileName);

		// Store the "good" results future tests.
		// FIXME: This is ugly but will do for now.
		$this->data = $data;

		$this->unit->run(
			$data['error'],
			0,
			"Narrative unpacking: valid source",
			"No error flag should be present in the returned data."
		);

		$this->unit->run(
			strstr($data['narrative_path'], $this->config->item('site_data_dir')),
			0,
			"Narrative unpacking: valid source",
			"A narrative path should be returned."
		);

		return $this->unit->result();
	}

	/**
	 * UT-0007.1
	 */
	public function test__processing__non_existant_source() {
		$zipFileName = 'sample_narratives.zip';
		$path = $this->config->item('site_data_dir') . '/';
		$data = $this->narrative_model->process_narrative('non-existent/');
		$this->unit->run(
			$data['error'],
			1,
			"Narrative processing: non-existent source",
			"We expect and error flag in the returned data."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0007.2
	 */
	function test__processing__valid_source() {
		$data = $this->narrative_model->process_narrative($this->data['narrative_path']);
		$this->unit->run(
			$data['error'],
			0,
			"Narrative processing: valid source",
			"No error flag should be present in the returned data."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0006.1
	 */
	function test__get_all_sorting__invalid() {
		$narratives = $this->narrative_model->get_all("non-existent");
		$this->unit->run(
			$narratives,
			array(),
			"Narrative retrieval: sort by invalid index",
			"No error flag should be present in the returned data."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0006
	 */
	function test__get_all_sorting__id() {
		$narratives = $this->narrative_model->get_all("id");
		$ordered = TRUE;
		// Get the first narrative ID
		$narrative = array_shift($narratives);
		$last_value = $narrative['narrative_id'];
		// Make sure each following one is smaller
		foreach ($narratives as $narrative) {
			if ($last_value > $narrative['narrative_id']) {
				$last_value = $narrative['narrative_id'];
			}
			else {
				$ordered = FALSE;
				break;
			}
		}

		$this->unit->run(
			$ordered,
			TRUE,
			"Narrative retrieval: sort by id index",
			"Every narrative should have a smaller ID than the following one."
		);
		return $this->unit->result();
	}

	function test__get_all_sorting__agrees() {
		$narratives = $this->narrative_model->get_all("agrees");
		$ordered = TRUE;
		// Get the first narrative ID
		$narrative = array_shift($narratives);
		$last_value = $narrative['agrees'];
		// Make sure each following one is smaller
		foreach ($narratives as $narrative) {
			if ($last_value >= $narrative['agrees']) {
				$last_value = $narrative['agrees'];
			}
			else {
				$ordered = FALSE;
				break;
			}
		}

		$this->unit->run(
			$ordered,
			TRUE,
			"Narrative retrieval: sort by id index",
			"Every narrative should have a smaller or equal agree count than the following one."
		);
		return $this->unit->result();
	}

	function test__get_all_position__agree() {
		$narratives = $this->narrative_model->get_all("id", NARRATIVE_POSITION_AGREE);
		$agree_only = TRUE;
		foreach ($narratives as $narrative) {
			if ($narrative['position'] != NARRATIVE_POSITION_AGREE) {
				$agree_only = FALSE;
				break;
			}
		}

		$this->unit->run(
			$agree_only,
			TRUE,
			"Narrative retrieval: filter by 'agree' position",
			"Retrieved narratives should only have an agree position."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0003
	 */
	public function test__get_all__multiple_returned() {
		$narratives = $this->narrative_model->get_all();

		$count = 0;
		$array_only = TRUE;
		foreach($narratives as $narrative) {
			// Ensure we have an array of arrays with at least 2 items.
			if (is_array($narrative)) {
				$count++;
			}
			else {
				$array_only = FALSE;
				break;
			}
		}

		$this->unit->run(
			$count > 1 && $array_only,
			TRUE,
			"Narrative retrieval: get all",
			"Retrieved data should be >= 2 narratives."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0001
	 */
	public function test__get__valid_id()
	{
		// Get the first narrative ID
		// FIXME prefill a new database with known contents for testing
		$narratives = $this->narrative_model->get_all();
		$first_id = $narratives[0]['narrative_id'];

		$narrative = $this->narrative_model->get($first_id);
		$properties_only = TRUE;
		foreach($narrative as $property) {
			// Ensure the returned data contains only a single narrative.
			if (is_array($property) && isset($property['narrative_id'])) {
				$properties_only = FALSE;
			}
		}

		$this->unit->run(
			count($narrative) && $properties_only,
			TRUE,
			"Narrative retrieval: get valid ID",
			"Retrieved data should be a single narrative."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0002
	 */
	public function test__get__invalid_id()
	{
		$narrative = $this->narrative_model->get(-234);
		$this->unit->run(
			$narrative,
			array(),
			"Narrative retrieval: get invalid ID",
			"Retrieved data should be an empty array."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0004
	 */
	public function test__insert()
	{
		$narrative = array(
			'position' => 0,
			'audio_length' => 60,
			'created' => "2014-01-28 01:19:27",
			'uploaded' => "2014-01-28 01:19:28",
			'uploaded_by' => 1,
			'language' => "en",
			'views' => 1,
			'agrees' => 2,
			'disagrees' => 3,
			'shares' => 4,
			'flags' => 5,
			"status" => 1,
		);
		$this->insert_id = $this->narrative_model->insert($narrative);
		$narrative['narrative_id'] = $this->insert_id;

		$narrative_inserted = $this->narrative_model->get($this->insert_id);
		$this->unit->run(
			$narrative,
			$narrative_inserted,
			"Narrative insertion",
			"Retrieved data should be an equal to stored data."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0005
	 */
	public function test__delete()
	{
		$this->narrative_model->delete(array('narrative_id' => $this->insert_id));
		$narrative = $this->narrative_model->get($this->insert_id);
		$this->unit->run(
			$narrative,
			array(),
			"Narrative deletion",
			"Retrieved data should be an empty array."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0017
	 */
	function test__xml_parse__get_XML_narrative_name() {
		$xmlRoot = new SimpleXMLElement($this->sampleNarrativeXml);
		$xml_retval = $this->narrative_model->get_XML_narrative_name($xmlRoot);
		$this->unit->run(
			$xml_retval,
			2,
			"Narrative XML processing: retrieve narrative name",
			"Using mock XML data."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0018
	 */
	function test__xml_parse__get_XML_narrative_language() {
		$xmlRoot = new SimpleXMLElement($this->sampleNarrativeXml);
		$xml_retval = $this->narrative_model->get_XML_narrative_language($xmlRoot);
		$this->unit->run(
			$xml_retval,
			"EN",
			"Narrative XML processing: retrieve narrative language",
			"Using mock XML data."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0019
	 */
	function test__xml_parse__get_XML_narrative_submitDate() {
		$xmlRoot = new SimpleXMLElement($this->sampleNarrativeXml);
		$xml_retval = $this->narrative_model->get_XML_narrative_submitDate($xmlRoot);
		$this->unit->run(
			$xml_retval,
			"2013-07-11",
			"Narrative XML processing: retrieve narrative submit date",
			"Using mock XML data."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0020
	 */
	function test__xml_parse__get_XML_narrative_submitTime() {
		$xmlRoot = new SimpleXMLElement($this->sampleNarrativeXml);
		$xml_retval = $this->narrative_model->get_XML_narrative_submitTime($xmlRoot);
		$this->unit->run(
			$xml_retval,
			"11-22-31",
			"Narrative XML processing: retrieve narrative submit time",
			"Using mock XML data."
		);
		return $this->unit->result();
	}

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
			if ($this->narrative_model->is_audio($extension) !== $expected) {
				$detection = FALSE;
				break;
			}
		}

		$this->unit->run(
			$detection,
			TRUE,
			"Narrative audio processing: format detection",
		  "Should accept valid audio (mp3, wav, mp4, m4a, aac, avi, 3gp, ogg, mp2, ac3) extensions and reject others."
		);
		return $this->unit->result();
	}

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
			if ($this->narrative_model->is_image($extension) !== $expected) {
				$detection = FALSE;
				break;
			}
		}

		$this->unit->run(
			$detection,
			TRUE,
			"Narrative audio processing: format detection",
			"Should accept valid image extensions (jpg, jpeg, gif, bmp, png, tif) and reject others."
		);
		return $this->unit->result();
	}
}
