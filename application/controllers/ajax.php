<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller {
  /**
   * Constructor: initialize required libraries.
   */
  public function __construct() {
    parent::__construct();
    $this->load->model('narrative_model');
  }

  public function bubbles() {
    $data = array();
    $data['name'] = 'flare';

    // A flare can have on or more groupings
    $groupings = array();

    // A flare can have on or more clusters
    $clusters = array();

    // Clusters are groups of nodes
    $nodes = array();

    // Load nodes into the active cluster
    $result = $this->narrative_model->get_all();
    foreach ($result as $narrative) {
      $nodes[] = $narrative;
    }

    $clusters[] = array(
      'name' => 'cluster1',
      'children' => $nodes,
    );

    $groupings[] = array(
      'name' => 'grouping1',
      'children' => $clusters,
    );

    $data['children'] = $groupings;

    print json_encode($data);
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
