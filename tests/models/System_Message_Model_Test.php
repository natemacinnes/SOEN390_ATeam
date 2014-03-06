<?php
/**
 * @group Model
 */
class System_Message_Model_Test extends CIUnit_TestCase
{
	private $messages = array(
		MESSAGE_NOTICE => array('notice message 1', 'notice message 2'),
		MESSAGE_WARNING => array('warning message 1', 'warning message 2'),
		MESSAGE_ERROR => array('error message 1', 'error message 2'),
	);

	private function clear_messages()
	{
		$this->CI->session->set_userdata('system_messages', array());
	}

	private function set_messages()
	{
		foreach ($this->messages as $level => $messages) {
			foreach ($messages as $message) {
				$this->CI->system_message_model->set_message($message, $level);
			}
		}
	}

	public function __contruct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}

	/**
	 * Setup PHPUnit & load any required dependencies
	 * @covers System_Message_Model::__construct
	 */
	public function setUp()
	{
		parent::tearDown();
		parent::setUp();

		$this->CI->load->model('system_message_model', TRUE);
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0021
	 * @covers System_Message_Model::set_message
	 */
	public function test__set_message()
	{
		$this->clear_messages();
		$this->set_messages();

		$system_messages = $this->CI->session->userdata('system_messages');

		$this->assertEquals($system_messages, $this->messages);
	}

	/**
	 * UT-0022
	 * @covers System_Message_Model::get_messages
	 */
	public function test__get_message__equal()
	{
		$this->clear_messages();
		$this->set_messages();

		$system_messages = $this->CI->system_message_model->get_messages();

		$this->assertEquals($system_messages, $this->messages);
	}

	/**
	 * UT-0023
	 * @covers System_Message_Model::get_messages
	 */
	public function test__get_message__no_clear()
	{
		$this->clear_messages();
		$this->set_messages();

		$system_messages = $this->CI->system_message_model->get_messages(FALSE);
		$system_messages_again = $this->CI->system_message_model->get_messages();

		$this->assertEquals(TRUE, count($system_messages) > 0);
		$this->assertEquals($system_messages, $system_messages_again);
	}

	/**
	 * UT-0024
	 * @covers System_Message_Model::get_messages
	 */
	public function test__get_message__clear()
	{
		$this->clear_messages();
		$this->set_messages();

		$system_messages = $this->CI->system_message_model->get_messages();
		$system_messages_again = $this->CI->system_message_model->get_messages();

		$this->assertEquals(array(), $system_messages_again);
	}
}
