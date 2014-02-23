<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Test the Narrative_Model model.
 */
class Narrative_Model_Test extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 *    http://example.com/index.php/welcome
	 *  - or -
	 *    http://example.com/index.php/welcome/index
	 *  - or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */


	public function index()
	{
		$data['title'] = "Unit Tests";


		//system message model
		//testing set function
		$this->load->model('narrative_model');



		//testing narrative process with a path to a directory that does not exist
		$nar_model_nopath = $this->narrative_model->process_narrative("./uploads/tmp/1391131894");

		$data['narrativeModelMessage1'] = $this->unit->run($nar_model_nopath['error_message'], '', "Narrative Model Process Narrative test","Tests process_narrative using a path to a tmp folder which does not exist, test to see if we get the desired error msg, test fails if error msg is not right (This test case is expected to fail");

		$data['narrativeModelError1'] = $this->unit->run($nar_model_nopath['error'], 0, "Narrative Model Process Narrative test","Tests process narrative using a non existent tmp path, check to see if we get error = 1, fails if error != 1  (this test case is expected to fail");


		$data['narrativeModelMessage'] = $this->unit->run($nar_model_nopath['error_message'], 'Processing failed. Please attempt the upload again.', "Narrative Model Process Narrative test","Tests process_narrative using a path to a tmp folder which  exists, test to see if we get no error msg, test fails if error msg is present (this test case is expected to pass");

		$data['narrativeModelError'] = $this->unit->run($nar_model_nopath['error'], 1, "Narrative Model Process Narrative test","Tests process narrative using a an existing tmp path, check to see if we get error = 0, fails if error != 0 (This test case is expected to pass");

		//testing narrative process with a path that points to an existing directory

		$nar_model_goodpath = $this->narrative_model->process_narrative("./uploads/tmp/1391140978");

		$data['narrativeModelNoError1'] = $this->unit->run($nar_model_goodpath['error'], 1, "Narrative Model Process Narrative test","Tests process_narrative using a path to a tmp folder which  exists, test to see if we get no error msg, test fails if error msg is present (this test case is expected to fail");

		$data['narrativeModelNoMessage1'] = $this->unit->run($nar_model_goodpath['error_message'], 'Processing failed. Please attempt the upload again.', "Narrative Model Process Narrative test","Tests process narrative using a an existing tmp path, check to see if we get error = 0, fails if error != 0 (This test case is expected to fail");

		$data['narrativeModelNoError'] = $this->unit->run($nar_model_goodpath['error'], 0, "Narrative Model Process Narrative test","Tests process_narrative using a path to a tmp folder which  exists, test to see if we get no error msg, test fails if error msg is present (this test case is expected to pass");

		$data['narrativeModelNoMessage'] = $this->unit->run($nar_model_goodpath['error_message'], "", "Narrative Model Process Narrative test","Tests process narrative using a an existing tmp path, check to see if we get error = 0, fails if error != 0 (This test case is expected to pass");



		//testing the get_all function which helps with sorting
		$getallTest = $this->narrative_model->get_All("id");

		$data['sorting_id_fail'] = $this->unit->run($getallTest[0]['narrative_id'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  In the case of this test highest is 16, lowest is 0, Testing narrative idTest expected to fail");
		$data['sorting_path_fail'] = $this->unit->run($getallTest[0]['position'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing position, In the case of this test it is null,  Test expected to fail");
		$data['sorting_length_fail'] = $this->unit->run($getallTest[0]['audio_length'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing audio length value, In the case of this test it should be 0 (placeholder file), Test expected to fail");
		$data['sorting_created_fail'] = $this->unit->run($getallTest[0]['created'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing created value, In the case of this test it should be 0 (placeholder file) Test expected to fail");
		$data['sorting_uploaded_fail'] = $this->unit->run($getallTest[0]['uploaded'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing uploaded value, In the case of this test it should be 0 (placeholder file), Test expected to fail");
		$data['sorting_uploadby_fail'] = $this->unit->run($getallTest[0]['uploaded_by'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing uploaded by value,  In the case of this test it should be 1, Test expected to fail");
		$data['sorting_lang_fail'] = $this->unit->run($getallTest[0]['language'], "fr", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing language value, In the case of this test it should be en,  Test expected to fail");
		$data['sorting_view_fail'] = $this->unit->run($getallTest[0]['views'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing views value, In the case of this test it should be 0, Test expected to fail");
		$data['sorting_agree_fail'] = $this->unit->run($getallTest[0]['agrees'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing agrees value, In the case of this test it should be 0, Test expected to fail");
		$data['sorting_disagree_fail'] = $this->unit->run($getallTest[0]['disagrees'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing disagrees value, In the case of this test it should be 0, Test expected to fail");
		$data['sorting_shares_fail'] = $this->unit->run($getallTest[0]['shares'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing shares value, In the case of this test it should be 0, Test expected to fail");
		$data['sorting_flags_fail'] = $this->unit->run($getallTest[0]['flags'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing flags value, In the case of this test it should be 0, Test expected to fail");

		$data['sorting_id_pass'] = $this->unit->run($getallTest[0]['narrative_id'], "16", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  In the case of this test highest is 16, lowest is 0, Test expected to pass");
		$data['sorting_path_pass'] = $this->unit->run($getallTest[0]['position'], null, "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing position, In the case of this test it is null,  Test expected to pass");
		$data['sorting_length_pass'] = $this->unit->run($getallTest[0]['audio_length'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing audio length value, In the case of this test it should be 0 (placeholder file), Test expected to pass");
		$data['sorting_created_pass'] = $this->unit->run($getallTest[0]['created'], "0000-00-00 00:00:00", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing created value, In the case of this test it should be 0 (placeholder file) Test expected to pass");
		$data['sorting_uploaded_pass'] = $this->unit->run($getallTest[0]['uploaded'], "0000-00-00 00:00:00", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing uploaded value, In the case of this test it should be 0 (placeholder file), Test expected to pass");
		$data['sorting_uploadby_pass'] = $this->unit->run($getallTest[0]['uploaded_by'], "1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing uploaded by value,  In the case of this test it should be 1, Test expected to pass");
		$data['sorting_lang_pass'] = $this->unit->run($getallTest[0]['language'], "en", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing language value, In the case of this test it should be en,  Test expected to pass");
		$data['sorting_view_pass'] = $this->unit->run($getallTest[0]['views'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing views value, In the case of this test it should be 0, Test expected to pass");
		$data['sorting_agree_pass'] = $this->unit->run($getallTest[0]['agrees'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing agrees value, In the case of this test it should be 0, Test expected to pass");
		$data['sorting_disagree_pass'] = $this->unit->run($getallTest[0]['disagrees'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing disagrees value, In the case of this test it should be 0, Test expected to pass");
		$data['sorting_shares_pass'] = $this->unit->run($getallTest[0]['shares'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing shares value, In the case of this test it should be 0, Test expected to pass");
		$data['sorting_flags_pass'] = $this->unit->run($getallTest[0]['flags'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing flags value, In the case of this test it should be 0, Test expected to pass");


		$data['sorting_last'] = $this->unit->run($getallTest[15]['narrative_id'], "1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing that the last file sorted has narrative_id of 1 (being the lowest value in database), Test expected to pass");


		//testing the xml file parsing functions in narrative_model
		$xml_parse_name = $this->narrative_model->get_XML_narrative_name(simplexml_load_file("./uploads/tmp/1391652225/1.xml"));
		$xml_parse_lang = $this->narrative_model->get_XML_narrative_language(simplexml_load_file("./uploads/tmp/1391652225/1.xml"));
		$xml_parse_date = $this->narrative_model->get_XML_narrative_submitDate(simplexml_load_file("./uploads/tmp/1391652225/1.xml"));
		$xml_parse_time = $this->narrative_model->get_XML_narrative_submitTime(simplexml_load_file("./uploads/tmp/1391652225/1.xml"));


		$data['xmlParseNameFail'] = $this->unit->run($xml_parse_name, "Hello", "Narrative Model Parse Name Test","Test run locally, uses the xml file provided by the creator when uploaded. Runs using the temporary folder path, Expected to fail by passing string");
		$data['xmlParseLanguageFail'] = $this->unit->run($xml_parse_lang, "1", "Narrative Model Parse Language Test","Test run locally, uses the xml file provided by the creator when uploaded. Runs using the temporary folder path, Expected to fail by passing integer");
		$data['xmlParseDateFail'] = $this->unit->run($xml_parse_date, "Date", "Narrative Model Parse Date Test","Test run locally, uses the xml file provided by the creator when uploaded. Runs using the temporary folder path, Expected to fail by passing string");
		$data['xmlParseTimeFail'] = $this->unit->run($xml_parse_time, "Time", "Narrative Model Parse Time Test", "Test run locally, uses the xml file provided by the creator when uploaded. Runs using the temporary folder path, Expected to fail by passing string");




		$data['xmlParseNamePass'] = $this->unit->run($xml_parse_name, 1, "Narrative Model Parse Name Test","Test run locally, uses the xml file provided by the creator when uploaded. Runs using the temporary folder path, Expected to pass");
		$data['xmlParseLanguagePass'] = $this->unit->run($xml_parse_lang, "English", "Narrative Model Parse Language Test","Test run locally, uses the xml file provided by the creator when uploaded. Runs using the temporary folder path, Expected to pass");
		$data['xmlParseDatePass'] = $this->unit->run($xml_parse_date, "2013-05-30", "Narrative Model Parse Date Test","Test run locally, uses the xml file provided by the creator when uploaded. Runs using the temporary folder path, Expected to pass");
		$data['xmlParseTimePass'] = $this->unit->run($xml_parse_time, "20-19-33", "Narrative Model Parse Time Test", "Test run locally, uses the xml file provided by the creator when uploaded. Runs using the temporary folder path, Expected to pass");


		//testing audio detection functions in narrative model

		$audio_mp3 = $this->narrative_model->is_audio("mp3");
		$audio_wav = $this->narrative_model->is_audio("wav");
		$audio_mp4 = $this->narrative_model->is_audio("mp4");
		$audio_m4a = $this->narrative_model->is_audio("m4a");
		$audio_aac = $this->narrative_model->is_audio("aac");
		$audio_avi = $this->narrative_model->is_audio("avi");
		$audio_3gp = $this->narrative_model->is_audio("3gp");
		$audio_ogg = $this->narrative_model->is_audio("ogg");
		$audio_mp2 = $this->narrative_model->is_audio("mp2");
		$audio_ac3 = $this->narrative_model->is_audio("ac3");

		$data['audioMp3DetectFail'] = $this->unit->run($audio_mp3, false, "Mp3 Audio File Detection Test", "Expected to Fail");
		$data['audioWavDetectFail'] = $this->unit->run($audio_wav, false, "Wav Audio File Detection Test", "Expected to Fail");
		$data['audioMp4DetectFail'] = $this->unit->run($audio_mp4, false, "Mp4 Audio File Detection Test", "Expected to Fail");
		$data['audioM4aDetectFail'] = $this->unit->run($audio_m4a, false, "M4a Audio File Detection Test", "Expected to Fail");
		$data['audioAacDetectFail'] = $this->unit->run($audio_aac, false, "Aac Audio File Detection Test", "Expected to Fail");
		$data['audioAviDetectFail'] = $this->unit->run($audio_avi, false, "Avi Audio File Detection Test", "Expected to Fail");
		$data['audio3gpDetectFail'] = $this->unit->run($audio_3gp, false, "3gp Audio File Detection Test", "Expected to Fail");
		$data['audioOggDetectFail'] = $this->unit->run($audio_ogg, false, "Ogg Audio File Detection Test", "Expected to Fail");
		$data['audioMp2DetectFail'] = $this->unit->run($audio_mp2, false, "Mp2 Audio File Detection Test", "Expected to Fail");
		$data['audioAc3DetectFail'] = $this->unit->run($audio_ac3, false, "Ac3 Audio File Detection Test", "Expected to Fail");

		$data['audioMp3DetectPass'] = $this->unit->run($audio_mp3, true, "Mp3 Audio File Detection Test", "Expected to Pass");
		$data['audioWavDetectPass'] = $this->unit->run($audio_wav, true, "Wav Audio File Detection Test", "Expected to Pass");
		$data['audioMp4DetectPass'] = $this->unit->run($audio_mp4, true, "Mp4 Audio File Detection Test", "Expected to Pass");
		$data['audioM4aDetectPass'] = $this->unit->run($audio_m4a, true, "M4a Audio File Detection Test", "Expected to Pass");
		$data['audioAacDetectPass'] = $this->unit->run($audio_aac, true, "Aac Audio File Detection Test", "Expected to Pass");
		$data['audioAviDetectPass'] = $this->unit->run($audio_avi, true, "Avi Audio File Detection Test", "Expected to Pass");
		$data['audio3gpDetectPass'] = $this->unit->run($audio_3gp, true, "3gp Audio File Detection Test", "Expected to Pass");
		$data['audioOggDetectPass'] = $this->unit->run($audio_ogg, true, "Ogg Audio File Detection Test", "Expected to Pass");
		$data['audioMp2DetectPass'] = $this->unit->run($audio_mp2, true, "Mp2 Audio File Detection Test", "Expected to Pass");
		$data['audioAc3DetectPass'] = $this->unit->run($audio_ac3, true, "Ac3 Audio File Detection Test", "Expected to Pass");

		$image_jpg = $this->narrative_model->is_image("jpg");
		$image_jpeg = $this->narrative_model->is_image("jpeg");
		$image_gif = $this->narrative_model->is_image("gif");
		$image_bmp = $this->narrative_model->is_image("bmp");
		$image_png = $this->narrative_model->is_image("png");
		$image_tif = $this->narrative_model->is_image("tif");

		$data['imageJpgDetectFail'] = $this->unit->run($image_jpg, false, "Jpg Image File Detection Test", "Expected to Fail");
		$data['imageJpegDetectFail'] = $this->unit->run($image_jpeg, false, "Jpeg Image File Detection Test", "Expected to Fail");
		$data['imageGifDetectFail'] = $this->unit->run($image_gif, false, "Gif Image File Detection Test", "Expected to Fail");
		$data['imageBmpDetectFail'] = $this->unit->run($image_bmp, false, "Bmp Image File Detection Test", "Expected to Fail");
		$data['imagePngDetectFail'] = $this->unit->run($image_png, false, "Png Image File Detection Test", "Expected to Fail");
		$data['imageTifDetectFail'] = $this->unit->run($image_tif, false, "Tif Image File Detection Test", "Expected to Fail");

		$data['imageJpgDetectPass'] = $this->unit->run($image_jpg, true, "Jpg Image File Detection Test", "Expected to Pass");
		$data['imageJpegDetectPass'] = $this->unit->run($image_jpeg, true, "Jpeg Image File Detection Test", "Expected to Pass");
		$data['imageGifDetectPass'] = $this->unit->run($image_gif, true, "Gif Image File Detection Test", "Expected to Pass");
		$data['imageBmpDetectPass'] = $this->unit->run($image_bmp, true, "Bmp Image File Detection Test", "Expected to Pass");
		$data['imagePngDetectPass'] = $this->unit->run($image_png, true, "Png Image File Detection Test", "Expected to Pass");
		$data['imageTifDetectPass'] = $this->unit->run($image_tif, true, "Tif Image File Detection Test", "Expected to Pass");

		return $this->unit->result();
	}
}
