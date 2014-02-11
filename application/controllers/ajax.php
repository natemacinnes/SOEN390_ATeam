<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('narrative_model');
	}

	public function bubbles($position = NULL)
	{
		$data = array();
		$data['name'] = 'flare';
		// Clusters are groups of nodes
		$nodes = array();

		// Load nodes into the active cluster
		$result = $this->narrative_model->get_all('id', $position);
		foreach ($result as $narrative)
		{
			// +1 to ensure that 0 doesn't give us NaN
			$pie_data = array(
				array("label" => "agrees", "value" => $narrative['agrees']+1),
				array("label" => "disagrees", "value" => $narrative['disagrees']+1),
			);
			$narrative['pie_data'] = $pie_data;
			//$narrative['created'] = strtotime($narrative['created']);
			$nodes[] = $narrative;
		}

		$data['children'] = $nodes;

		print json_encode($data);
	}

	public function audioImage($narrative_id, $time)
	{
		$narrative = $this->narrative_model->get($narrative_id);
		if (!$narrative)
		{
			return;
		}
		$current_time = floatval($time);
		$path = "./uploads/$narrative_id/AudioTimes.xml";
		$return = "";
		if (file_exists($path) && $xml = simplexml_load_file($path))
		{
			foreach($xml->children() as $element)
			{
				if ($current_time >= $element->Start && $current_time < $element->End)
				{
					print base_url() . 'uploads/' . $narrative_id . '/' .  $element->Image;
					break;
				}
			}
		}
		else
		{
			print $return;
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
