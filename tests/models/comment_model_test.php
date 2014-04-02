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
		$comments = $this->CI->comment_model->get_all(1);

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

	/**
	 * UT-0071
	 * @covers Comment_Model::insert
	 */
	public function test__insert()
	{
		$comment = array(
			'narrative_id' => 1,
			'body' => "some comment text",
			'status' => 1,
		);
		$last_id = $this->CI->comment_model->insert($comment);

		$this->assertGreaterThan(0, $last_id);
	}

	/**
	 * UT-0079
	 * @covers Comment_Model::get_total_count
	 */
	 public function test__get_total_count()
	 {
		$this->assertEquals(3, $this->CI->comment_model->get_total_count());
	 }

	/**
	 * UT-0080
	 * @covers Comment_Model::delete
	 */
	 public function test__delete__base_comment()
	 {
		$count_before = $this->CI->comment_model->get_total_count();
		$this->CI->comment_model->delete(array('comment_id' => 3));
		$count_after = $this->CI->comment_model->get_total_count();
		$this->assertEquals($count_before-1, $count_after);
	 }

	 /**
	 * UT-0040
	 * @covers Comment_Model::delete
	 */
	 public function test__delete__parent_to_other_comment()
	 {
		$count_before = $this->CI->comment_model->get_total_count();
		$this->CI->comment_model->delete(array('comment_id' => 1));
		$count_after = $this->CI->comment_model->get_total_count();
		$this->assertEquals($count_before-2, $count_after);
	 }

	/**
	 * UT-0038
	 * @covers Comment_Model::dismiss_flags
	 */
	public function test__dismiss_flags()
	{
		$flags = $this->CI->comment_model->dismiss_flags(3);
		$comment = $this->CI->comment_model->get(3);
		$this->assertEquals(0, $comment['flags']);
	}

	/**
	 * UT-0039
	 * @covers Comment_Model::flag
	 */
	public function test__flag()
	{
		$comment = $this->CI->comment_model->get(2);
		$this->CI->comment_model->flag(2);
		$comment_after = $this->CI->comment_model->get(2);
		$this->assertEquals($comment['flags']+1, $comment_after['flags']);
	}
}
