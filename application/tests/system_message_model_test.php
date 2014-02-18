<?php
$this->load->model('system_message_model');
$message = "Hello";

// testing set_messages()
$system_message_set = $this->system_message_model->set_message($message);
$this->unit->run($_SESSION['system_messages'][MESSAGE_NOTICE][0], $message, "System Message Model Set Test", "Tests system_message_model::set_message(). Passes if message was successfully stored in $_SESSION.");

// testing get_messages()
$system_message_get = $this->system_message_model->get_messages(FALSE);
$this->unit->run($system_message_get[MESSAGE_NOTICE][0], $message, "System Message Model Get Test", "Tests system_message_model::get_messages() without clearing messages. Passes if message stored earlier was retrieved verbatim.");

$system_message_get = $this->system_message_model->get_messages(TRUE);
$this->unit->run($system_message_get[MESSAGE_NOTICE][0], $message, "System Message Model Get Test", "Tests system_message_model::get_messages(). Passes if message message stored earlier was not cleared from previous test");

$system_message_get = $this->system_message_model->get_messages();
$this->unit->run($system_message_get, array(), "System Message Model Get Test", "Tests system_message_model::get_messages() after having cleared existing messages. Passes if an empty array is returned.");
