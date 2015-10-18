<?php
/*
 * Sign_out Controller
 */
class Sign_out extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url'));
		$this->load->config('account/account');
		$this->load->language(array('general', 'account/sign_out'));
		$this->load->library(array('account/authentication', 'account/authorization'));
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
		$this->lang->load('general', 'english');
		$this->lang->load('mainmenu', 'english');
		}
		else
		{
		$this->lang->load('general', $language);
		$this->lang->load('mainmenu', $language);		
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Account sign out
	 *
	 * @access public
	 * @return void
	 */
	function index()
	{
		// Run sign out routine
		$this->authentication->sign_out();

		$response["success"] = 1;
		$response["message"] = "You have sign out";
		echo json_encode($response);
	}

}


/* End of file sign_out.php */
/* Location: ./application/account/controllers/sign_out.php */