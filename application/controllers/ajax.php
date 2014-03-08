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
		$nodes = array();

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

		$data['children'] = $nodes;

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
	
	/**
	 * Increment the amount of views on a narrative.
	 */
	public function increment_views($narrative_id)
	{
		$this->narrative_model->increment_views($narrative_id);
	}
	
	/**
	 * Increment the agree/disagree of a narrative.
	 */
	public function increment_agrees_disagrees($narrative_id, $decision)
	{
		if($decision == "Agree")
		{
			$this->narrative_model->toggle_agrees($narrative_id, "+");
			echo "green";
		}
		else if($decision == "Disagree")
		{
			$this->narrative_model->toggle_disagrees($narrative_id, "+");
			echo "red";
		}
		else
		{
			echo "";
		}
	}
	
	/**
	 * decrement the agree/disagree of a narrative.
	 */
	public function decrement_agrees_disagrees($narrative_id, $decision)
	{
		if($decision == "Agree")
		{
			$this->narrative_model->toggle_agrees($narrative_id, "-");
			echo "green";
		}
		else if($decision == "Disagree")
		{
			$this->narrative_model->toggle_disagrees($narrative_id, "-");
			echo "red";
		}
		else
		{
			echo "";
		}
	}
	
	/**
	 * Increment the agree/disagree of a narrative.
	 */
	public function toggle_concensus($incrementing, $decrementing, $narrative_id)
	{
		$this->narrative_model->toggle($incrementing, $decrementing, $narrative_id);
	}
}
