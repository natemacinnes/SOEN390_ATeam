<?php

class Unit_Test extends YD_Controller {

  /**
   * Constructor: initialize required libraries.
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->library('unit_test');

    // Change directory for all site data to test folder
    $this->config->set_item('site_data_dir', 'test');
  }


  private function getClassDefinedMethods($object) {
    $methods = array();
    $reflection = new ReflectionClass($object);
    foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
      if ($method->class == $reflection->getName()) {
        $methods[] = $method->name;
      }
    }
    return $methods;
  }

  private function loadUnitTests($controller_name, &$data) {
    try {
      // If $controller_name = "ajax_test", this creates a variable "$ajax_test"
      // that holds the a new instance of ajax_test() [e.g., a controller]
      $$controller_name = $this->load->controller($controller_name);
      foreach ($this->getClassDefinedMethods($$controller_name) as $method) {
        // We don't want to call constructors.
        if (strpos($method, "test") !== 0) {
          continue;
        }

        // Call each method and merge it's test data back into our resultset
        // Requires that each test method implemented in other classes returns
        // $this->unit->result().
        $data['test_controllers'][$controller_name] = $$controller_name->$method();
      }
    }
    catch(Exception $e) {
      die("Could not load controller $controller_name: <pre>" . $e . "</pre>");
    }
  }

  public function index() {
    $data['test_controllers'] = array();

    // Load external classes - see note below about set_instance(). Merge test
    // result data into $data['tests'].
    $this->loadUnitTests('narrative_model_test', $data);
    $this->loadUnitTests('comment_model_test', $data);
    $this->loadUnitTests('system_message_model_test', $data);
    $this->loadUnitTests('ajax_test', $data);

    // Loading unit tests from a controller resets the global $CI object so that
    // each controller can load its own models, libraries and classes. Set it
    // back so we regain control over class loading now that we have finished
    // processing external classes.
    YD_Controller::set_instance($this);

    $data['count'] = 0;
    $data['failed'] = 0;
    foreach($data['test_controllers'] as $controller => $tests) {
      $data['count'] += count($tests);
      foreach ($tests as $test) {
        if ($test['Result'] == 'Failed') {
          $data['failed']++;
        }
      }
    }

    $this->view_wrapper('pages/unit', $data);
  }
}
