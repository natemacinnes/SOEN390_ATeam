<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Test the System_Message_Model model.
 */
class System_Message_Model_Test extends YD_Controller
{
	private $messages = array(
		MESSAGE_NOTICE => array('notice message 1', 'notice message 2'),
		MESSAGE_WARNING => array('warning message 1', 'warning message 2'),
		MESSAGE_ERROR => array('error message 1', 'error message 2'),
	);

	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
		$this->load->model('system_message_model');
	}

	private function clear_messages()
	{
		$this->session->set_userdata('system_messages', array());
	}

	
	private function set_messages()
	{
		foreach ($this->messages as $level => $messages) {
			foreach ($messages as $message) {
				$this->system_message_model->set_message($message, $level);
			}
		}
	}

	/**
	 * UT-0021
	 */
	public function test__set_message()
	{
		$this->clear_messages();
		$this->set_messages();

		$system_messages = $this->session->userdata('system_messages');

		$this->unit->run(
			$system_messages,
			$this->messages,
			"System messages: set messages",
			"Ensures messages are stored correctly."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0022
	 */
	public function test__get_message__equal()
	{
		$this->clear_messages();
		$this->set_messages();

		$system_messages = $this->system_message_model->get_messages();

		$this->unit->run(
			$system_messages,
			$this->messages,
			"System messages: get messages",
			"Ensures the messages retrieved are equal to the messages set."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0023
	 */
	public function test__get_message__no_clear()
	{
		$this->clear_messages();
		$this->set_messages();

		$system_messages = $this->system_message_model->get_messages(FALSE);
		$system_messages_again = $this->system_message_model->get_messages();

		$this->unit->run(
			count($system_messages),
			TRUE,
			"System messages: get messages (preserve existing)",
			"Ensures the retrieved messages are non-empty."
		);
		$this->unit->run(
			$system_messages,
			$system_messages_again,
			"System messages: get messages (preserve existing)",
			"Ensures messages can be retrieved again when the clear messages flag is not set."
		);
		return $this->unit->result();
	}

	/**
	 * UT-0024
	 */
	public function test__get_message__clear()
	{
		$this->clear_messages();
		$this->set_messages();

		$system_messages = $this->system_message_model->get_messages();
		$system_messages_again = $this->system_message_model->get_messages();

		$this->unit->run(
			$system_messages_again,
			array(),
			"System messages: get messages (clear existing)",
			"Ensures the messages retrieved for a second time is empty."
		);
		return $this->unit->result();
	}
}
