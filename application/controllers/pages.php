<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Used for providing static/view-only pages.
 */
class Pages extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
   * The default method called, if none is provided.
   */
	public function index()
	{
		$this->view_wrapper('pages/home');
	}
}
