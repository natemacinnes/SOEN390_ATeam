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
      // +1 to ensure that 0 doesn't give us NaN
      $pie_data = array(
        array("label" => "agrees", "value" => $narrative['agrees']+1),
        array("label" => "disagrees", "value" => $narrative['disagrees']+1),
      );
      $narrative['pie_data'] = $pie_data;
      //$narrative['created'] = strtotime($narrative['created']);
      $nodes[] = $narrative;
    }

    $clusters[] = array(
      'name' => 'cluser1',
      'children' => $nodes,
    );


    $groupings[] = array(
      'name' => 'grouping1',
      'children' => $clusters,
    );

    $data['children'] = $groupings;

    print json_encode($data);
  }

  public function audioImage($narrative_id, $time) {
    $current_time = floatval($time);
    $path = "./uploads/$narrative_id/AudioTimes.xml";
    $return = "";
    if (file_exists($path) && $xml = simplexml_load_file($path)) {
      $timeNarrative =0;
      if ($current_time <= floatval($xml->Narrative[$timeNarrative]->End)) {
        print base_url() . 'uploads/' . $narrative_id . '/' . $xml->Narrative[$timeNarrative]->Image;
      }
      else {
        while($current_time > floatval($xml->Narrative[$timeNarrative]->End)){
          $timeNarrative +=1;
        }
        print base_url() . 'uploads/' . $narrative_id . '/' .  $xml->Narrative[$timeNarrative]->Image;
      }
    }
    else {
      print $return;
    }
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
