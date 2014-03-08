<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Defines admin operations to be performed on a narrative.
 */
class history extends YD_Controller
{
	public function add_to_history()
	{
		//Loading CI session library
		$this->load->library('session');

		//Getting the currently requested narrative id
		$id = $_REQUEST['id'];

		//Modifying session data to add the currently requested session id
		$history = $this->session->userdata('history');
		if($history == null)
			$history = array($id);
		else
		{
			//Handling case where the same narrative is replayed, to avoid duplicates in the history
			$key = array_search($id, $history);
			//PHP misbehavior: Second part of the predicate handles the case when 'null' and '0' are confused and cause failure of algorythm
			if($key != null || (isset($history[0]) && $id == $history[0])) unset($history[$key]);

			$history[] = $id;
		}
		$this->session->set_userdata('history', $history);

		$previous = '';
		foreach($history as $narrative_id)
		{
			$previous = $previous . $narrative_id . ' ';
		}

		echo $previous;
	}
}

?>
