<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Provides callbacks for any AJAX calls in the system.
 */
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

	/**
	 * Callback for d3 upon page load.
	 *
	 * Returns matching narrative information in JSON format.
	 */
	public function bubbles($position = NULL)
	{
		$data = array();
		$data['name'] = 'flare';

		// Clusters are groups of nodes
		$clusters = array();

		$positions = array(NARRATIVE_POSITION_NEUTRAL, NARRATIVE_POSITION_AGREE, NARRATIVE_POSITION_DISAGREE);
		foreach ($positions as $position) {
			$cluster = array();
			$nodes = array();

			$cluster['name'] = 'position-' . $position;

			// Load nodes into the active cluster
			$result = $this->narrative_model->get_all('id', $position);
			foreach ($result as $narrative)
			{
				if (!$narrative['status'])
				{
					continue;
				}
				// +1 to ensure that 0 doesn't give us NaN
				$pie_data = array(
					array("label" => "agrees", "value" => $narrative['agrees']+1),
					array("label" => "disagrees", "value" => $narrative['disagrees']+1),
				);
				$narrative['pie_data'] = $pie_data;
				//$narrative['created'] = strtotime($narrative['created']);
				$nodes[] = $narrative;
			}
			$cluster['children'] = $nodes;
			$clusters[] = $cluster;
		}

		$data['children'] = $clusters;

		print json_encode($data);
	}

	/**
	 * Return the image URL given a narrative & timecode.
	 */
	public function audio_image($narrative_id, $time)
	{
		$narrative = $this->narrative_model->get($narrative_id);
		if (!$narrative)
		{
			return;
		}
		$current_time = floatval($time);
		$path =  $this->config->item('site_data_dir') . "/$narrative_id/AudioTimes.xml";
		$return = "";
		if (file_exists($path) && $xml = simplexml_load_file($path))
		{
			foreach($xml->children() as $element)
			{
				if ($current_time >= $element->Start && $current_time < $element->End)
				{
					print base_url() . $this->config->item('site_data_dir') . '/' . $narrative_id . '/' .  $element->Image;
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
