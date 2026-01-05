<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base controller with simple session-based authentication guard.
 */
#[AllowDynamicProperties]
class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		// Basic dependencies for auth checks and redirects
		$this->load->library('session');
		$this->load->helper('url');

		$this->_enforce_auth();
	}

	/**
	 * Redirects unauthenticated users to the login page.
	 */
	protected function _enforce_auth()
	{
		$allowed_controllers = array('auth', 'api');
		$current = strtolower($this->router->class);

		if (in_array($current, $allowed_controllers, TRUE))
		{
			return;
		}

		if ( ! $this->session->userdata('user_id'))
		{
			redirect('auth/login');
		}
	}
}
