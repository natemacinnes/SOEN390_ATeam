<?php
/**
 * @group Model
 */
class Comment_Model_Test extends CIUnit_TestCase
{
	/**
	 * Loads sample data into test tables.
	 * Key = table, value = fixture filename prefix.
	 */
	protected $tables = array(
		'admins' => 'admins',
		'narratives' => 'narratives',
		'comments' => 'comments',
  );

	public function __contruct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}

	/**
	 * Setup PHPUnit & load any required dependencies
	 * @covers Comment_Model::__construct
	 */
	public function setUp()
	{
		parent::tearDown();
		parent::setUp();

		$this->CI->load->model('comment_model');
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0027
	 * @covers Comment_Model::get_all
	 */
	public function test__get_all__multiple_returned()
	{
		$comments = $this->CI->comment_model->get_all();

		$count = 0;
		$array_only = TRUE;
		foreach($comments as $comment) {
			// Ensure we have an array of arrays with at least 2 items.
			if (is_array($comment) && isset($comment['comment_id'])) {
				$count++;
			}
			else {
				$array_only = FALSE;
				break;
			}
		}

		$this->assertEquals(TRUE, $count > 1 && $array_only);
	}


	/**
	 * UT-0045
	 * @covers Comment_Model::get
	 */

	 public function test__get__valid_id()
	 {
	 	$comment_id = 1;
		$comment = $this->CI->comment_model->get($comment_id);
		$this->assertTrue(is_array($comment) && isset($comment['comment_id']));
		$this->assertEquals($comment_id, $comment['comment_id']);
	 }

	/**
	 * UT-0046
	 * @covers Comment_Model::get
	 */

	 public function test__get__invalid_id()
	 {
		$comment = $this->CI->comment_model->get(-1);

		$this->assertEquals($comment, array());
	 }

	 /**
	 * UT-0047
	 * @covers Comment_Model::get_by_narrative_id
	 */

	 public function test__get_by_narrative_id__valid_id()
	 {
		$comments = $this->CI->comment_model->get_by_narrative_id(1);
		$this->assertEquals(2, count($comments));
		$this->assertEquals(1, $comments[1]['comment_id']);
		$this->assertEquals(2, $comments[2]['comment_id']);
	 }

	 /**
	 * UT-0048
	 * @covers Comment_Model::get_by_narrative_id
	 */

	 public function test__get_by_narrative_id__invalid_id()
	 {
		$retrieved_comments = $this->CI->comment_model->get_by_narrative_id(-1);

		$this->assertEquals($retrieved_comments, array());
	 }
}
