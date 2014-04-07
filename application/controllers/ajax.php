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
	 * @ingroup G-0015
	 */
	public function bubbles($position = NULL)
	{
		$data = array();
		if ($position == 0)
			$data['name'] = 'Neutral / Ambivalent';
		else if ($position == 1)
			$data['name'] = 'For / Pour';
		else if ($position == 2)
			$data['name'] = 'Against / Contre';
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
			$this->process_narrative_bubble($narrative);
			$nodes[] = $narrative;
		}

		$data['children'] = $nodes;

		print json_encode($data);
	}

	/**
	 * Accepts a narrative array and processes some options in preparation for use
	 * with D3.
	 * @ingroup G-0015
	 */
	private function process_narrative_bubble(&$narrative)
	{
		if (!isset($_SESSION['history']))
		{
			$_SESSION['history'] = array();
		}

		$narrative['viewed'] = in_array($narrative['narrative_id'], $_SESSION['history']);

		// +1 to ensure that 0 doesn't give us NaN
		$pie_data = array(
			array("label" => "agrees", "value" => $narrative['agrees']+1),
			array("label" => "disagrees", "value" => $narrative['disagrees']+1),
		);
		$narrative['pie_data'] = $pie_data;
	}

	/**
	 * Return the image URL given a narrative & timecode.
	 * @ingroup G-0014
	 */
	public function audio_image($narrative_id, $time)
	{
		$current_time = floatval($time);
		$path =  $this->config->item('site_data_dir') . "/$narrative_id/AudioTimes.xml";
		$return = "";
		if (file_exists($path) && $xml = simplexml_load_file($path))
		{
			foreach($xml->children() as $element)
			{
				if ($current_time >= $element->Start && $current_time < $element->End)
				{
					$return = base_url() . $this->config->item('site_data_dir') . '/' . $narrative_id . '/' .  $element->Image;
					break;
				}
			}
		}
		print $return;
	}

	/**
	 * Increment the amount of views on a narrative.
	 * @ingroup G-0017
	 */
	public function increment_views($narrative_id)
	{
		echo $this->narrative_model->increment_views($narrative_id);
	}

	/**
	 * Increment the agree/disagree of a narrative.
	 * @ingroup G-0017
	 */
	public function increment_agrees_disagrees($narrative_id, $decision)
	{
		if ($decision == "agree")
		{
			$this->narrative_model->toggle_agrees($narrative_id, "+");
			echo "green";
		}
		else if ($decision == "disagree")
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
	 * Decrement the agree/disagree of a narrative.
	 * @ingroup G-0017
	 */
	public function decrement_agrees_disagrees($narrative_id, $decision)
	{
		if ($decision == "agree")
		{
			$this->narrative_model->toggle_agrees($narrative_id, "-");
			echo "green";
		}
		else if ($decision == "disagree")
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
	 * Increment the agree, decrement the disagree of a narrative.
	 * @ingroup G-0017
	 */
	 public function toggle_agree_to_disagree($narrative_id)
	 {
		$this->narrative_model->increment_disagree_decrement_agree($narrative_id);
	 }

	/**
	 * Increment the disagree, decrement the agree of a narrative.
	 * @ingroup G-0017
	 */
	 public function toggle_disagree_to_agree($narrative_id)
	 {
		$this->narrative_model->increment_agree_decrement_disagree($narrative_id);
	 }

	/**
	 * Outputs JSON for the history bar without modifying it.
	 * @ingroup G-0018
	 */
	public function get_history()
	{
		if (!isset($_SESSION['history']))
		{
			$_SESSION['history'] = array();
		}
		$display_history = array_slice($_SESSION['history'], 0, NARRATIVE_HISTORY_LIMIT);
		$narratives = array();
		foreach ($display_history as $narrative_id)
		{
			if ($narrative = $this->narrative_model->get($narrative_id))
			{
				$this->process_narrative_bubble($narrative);
				$narratives[] = $narrative;
			}
		}
		print json_encode($narratives);
	}

	/**
	 * Adds a narrative to the history, then outputs get_history().
	 * @ingroup G-0018
	 */
	public function add_history($narrative_id)
	{
		// Modifying session data to add the currently requested session id
		if (!isset($_SESSION['history']))
		{
			$_SESSION['history'] = array();
		}
		// Handling case where the same narrative is replayed, to avoid duplicates in the history
		$key = array_search($narrative_id, $_SESSION['history']);
		if ($key !== FALSE)
		{
			unset($_SESSION['history'][$key]);
		}
		array_unshift($_SESSION['history'], $narrative_id);

		$this->get_history();
	}

	/**
	 * Clears history. Debugging method.
	 * @ingroup G-0018
	 */
	public function clear_history()
	{
		if (isset($_SESSION['history']))
		{
			unset($_SESSION['history']);
		}
	}
}
